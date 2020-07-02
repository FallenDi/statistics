<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

/**
 * This is the model class for table "auth_stat".
 *
 * @property string|null $date_create
 * @property string|null $ip
 * @property string|null $username
 * @property int $correct_auth
 */
class AuthStat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_stat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_create'], 'safe'],
            [['date_time_create'], 'safe'],
            [['correct_auth'], 'required'],
            [['correct_auth'], 'integer'],
            [['ip'], 'string', 'max' => 15],
            [['user_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'date_create' => 'Date Create',
            'date_time_create' => 'Date Time Create',
            'ip' => 'Ip',
            'username' => 'Username',
            'correct_auth' => 'Correct Auth',
        ];
    }


    public static function authCorrect($correct, $start, $end)
    {
        return self::find()
            ->where(['>=', 'date_create', $start])
            ->andWhere(['<=', 'date_create', $end])
            ->andWhere(['correct_auth' => $correct])
            ->asArray()
            ->all();
    }

}
