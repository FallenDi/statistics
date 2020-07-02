<?php

namespace app\controllers;

use app\models\AuthStat;
use app\models\ResetpassStat;
use app\models\dbResist;
use app\models\ArchiveStat;
use app\models\UpdateProject;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Html;
use app\commands\CountAndStat;
use DateTimeZone;



class SiteController extends Controller
{


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionStatistic()
    {
        $data = Yii::$app->request->post();
        $dateAll = explode(' - ', Html::encode($data['dateRange']));
        $dateStart = $dateAll[0];
        $dateEnd = $dateAll[1];

        if (!$data) {
            $dateStart = date('Y-m-d');
            $dateEnd = $dateStart;
        }
        if ($dateStart && $dateEnd) {

            date_default_timezone_set('Europe/Moscow');

            //Запросы к бд по необходимым таблицам
            $authDataCorrect = AuthStat::authCorrect('1', $dateStart, $dateEnd);
            $authDataUnCorrect = AuthStat::authCorrect('0', $dateStart, $dateEnd);
            $resetPass = ResetpassStat::countResets($dateStart, $dateEnd);
            $registration = DbResist::countRegistration($dateStart, $dateEnd);
            $projects = DbResist::projectCount($dateStart, $dateEnd);
            $profilesCorrect = DbResist::profilesCount($dateStart, $dateEnd, 1);
            $profilesUnCorrect = DbResist::profilesCount($dateStart, $dateEnd, 0);
            $archive = ArchiveStat::archiveProject($dateStart, $dateEnd);
            $pathogen = DbResist::pathogenCount($dateStart, $dateEnd);
            $users = DbResist::countUsers($dateStart, $dateEnd);

            //Перевод текущей даты в UNIX метку дла сбора диапазона дат интервала
            $dateStarted = \DateTime::createFromFormat("Y-m-d", $dateStart, new DateTimeZone(Yii::$app->timeZone))->format('U');
            $dateEnded = \DateTime::createFromFormat("Y-m-d", $dateEnd, new DateTimeZone(Yii::$app->timeZone))->format('U');
            $allDatesUnix = range($dateStarted, $dateEnded, (24 * 60 * 60));

            //Даты для сравнения с тем, что записано в бд
            $dateForUpdateStart = \DateTime::createFromFormat('Y-m-d', $dateStart)->format('Y-m-d 00:00:00');
            $dateForUpdateEnd = \DateTime::createFromFormat('Y-m-d', $dateEnd)->format('Y-m-d H:m:s');

            //Ищем измененные проекты за диапазон дат
            $projectUpdate = UpdateProject::projectUpdate($dateForUpdateStart, $dateForUpdateEnd);
            foreach ($projectUpdate as $key => $dateTimeArray) {
                $projectUpdate[$key] = [\DateTime::createFromFormat('Y-m-d H:m:s', ($dateTimeArray['udate_create']))->format('Y-m-d') => $dateTimeArray['project_id']];

            }

            //Собираем массив интервала дат
            $allDates = [];
            foreach ($allDatesUnix as $dates) {
                $allDates[] = \DateTime::createFromFormat('U', $dates)->format('Y-m-d');
            }

            //Собираем рыбу итогового массива вида Дата => [Параметр => [Значения]]
            $fullStatistics = array_fill_keys($allDates,
                [
                    'CorrectAuth' => 0,
                    'UnCorrectAuth' => 0,
                    'ProjectDay' => 0,
                    'Registration' => 0,
                    'ResetPass' => 0,
                    'ProjectUpdate' => 0,
                    'ProfilesCorrect' => 0,
                    'ProfilesUnCorrect' => 0,
                    'Archive' => 0,
                    'Pathogen' => 0,
                    'Users' => 0,
                    'Average' => 0
                ]);

            //Собираем массивы вида [ Дата => значение за день]

            //Авторизации успех и неудача
            $counterCorrectAuth = CountAndStat::counterParam($authDataCorrect, 'date_create');
            $counterUnCorrectAuth = CountAndStat::counterParam($authDataUnCorrect, 'date_create');

            //Восстановление пароля
            $counterResets = CountAndStat::counterParam($resetPass, 'date_create');

            //Для регистраций проводим доп преобразование из метки UNIX
            $countersRegistration = CountAndStat::counterParam($registration, 'created_at');
            $datesRegistration = [];
            foreach ($countersRegistration as $key => $dateRegistration) {
                $datesRegistration[\DateTime::createFromFormat('U', $key)->format('Y-m-d')] = $dateRegistration;
            }

            //Всего проектов за день
            $counterProjectsDay = CountAndStat::counterParam($projects, 'date_create');

            //Модернизированных проектов
            $projectsUpdate = [];
            foreach ($projectUpdate as $key => $projectUpd) {
                foreach ($projectUpd as $date => $pr_id) {
                    if (array_key_exists($date, $projectsUpdate)) {
                        array_push($projectsUpdate[$date], $pr_id);
                    } else {
                        $projectsUpdate[$date] = [$pr_id];
                    }
                }
            }

            //Считаем сколько их было за каждый день
            foreach ($projectsUpdate as $date => $counter) {
                $projectsUpdate[$date] = count(array_unique($counter));
            }

            $counterProfile = CountAndStat::counterUniq($profilesCorrect, 'profile_id');
            $counterUncorrect = CountAndStat::counterUniq($profilesUnCorrect, 'profile_id');
            $counterArchive = CountAndStat::counterUniq($archive, 'id');
            $counterPathogen = CountAndStat::counterUniq($pathogen, 'pathogen_id');
            $countUsers = CountAndStat::counterUniq($users, 'user_id');

            //Среднее число проектов на пользователя
            $projectAverage =[];
            foreach ($counterProjectsDay as $dateProject => $countProject) {
                foreach ($countUsers as $dateUser => $countUser) {
                    if ($dateProject == $dateUser) {
                        $projectAverage[$dateProject] = round($countProject/$countUser, 2);
                    }
                }
            }

            //Наполняем рыбу реальными значениями
            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($counterCorrectAuth as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['CorrectAuth'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($counterUnCorrectAuth as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['UnCorrectAuth'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($counterResets as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['ResetPass'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($datesRegistration as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['Registration'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($counterProjectsDay as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['ProjectDay'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            //Необходима доработка под консоль
            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($projectsUpdate as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['ProjectUpdate'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($counterProfile as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['ProfilesCorrect'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($counterUncorrect as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['ProfilesUnCorrect'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($counterArchive as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['Archive'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($counterPathogen as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['Pathogen'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($countUsers as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['Users'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

            foreach ($fullStatistics as $dateStat => $arrayStatValue) {
                foreach ($projectAverage as $dateKey => $dateValue) {
                    if ($dateKey == $dateStat) {
                        $arrayStatValue['Average'] = $dateValue;
                        $fullStatistics[$dateStat] = $arrayStatValue;
                    }
                }
            }

        }

        return $this->render('statistic', compact('fullStatistics'));
    }

    public function actionModify()
    {
        date_default_timezone_set('Europe/Moscow');
        //подставлять текущую дату
        $currentDate = date('Y-m-d');
        $projects = DbResist::projectCount($currentDate, $currentDate);
        Yii::warning(count($projects));
        $projectsUpdate = [];
        foreach ($projects as $key => $valueArray) {
            $humanDatetime = \DateTime::createFromFormat('U', $valueArray['last_update'])->format('Y-m-d H:m:s');
            $projectsUpdate[$humanDatetime] = $valueArray['id'];
        }

        foreach ($projectsUpdate as $udate => $proj_id) {
            $model = new UpdateProject();
            $isExists = $model::find()->where(['udate_create' => $udate])->andWhere(['project_id' => $proj_id])->exists();
            if (!$isExists) {
                $model->project_id = $proj_id;
                $model->udate_create = $udate;
                $model->save();
            }
        }

        return  $this->render('modify', compact('projectsUpdate'));

    }

}
