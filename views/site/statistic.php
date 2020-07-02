<?php
/* @var $this yii\web\View */
/* @var $fullStatistics */

use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\widgets\Pjax;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);

$this->title = 'Statistics';
$currentDate = date("Y-m-d");

$date = [];
$dateLabel = $currentDate;
if ($fullStatistics) {
    $date = array_keys($fullStatistics);
    $dateLabel = $date[0] . ' - ' . end($date);
}

Pjax::begin(); ?>
<?php
echo Html::beginForm(['/statistic', 'id' => 'dastaform'], 'POST');
?>

<?php
echo '<div class="calendar-submit">';
echo '<label class="control-label">Выберете интервал</label>';
echo '<div class="drp-container calendar">';
echo DateRangePicker::widget([
    'name'=>'dateRange',
    'presetDropdown'=>true,
    'convertFormat'=>true,
    'includeMonthsFilter'=>false,
    'pluginOptions' => [
        'locale' => [
            'format' => 'Y-m-d'
        ],
        'opens'=>'left',
        'autoApply'=>true,
    ],
    'options' => [
        'placeholder' => $dateLabel,
        'id' => 'date-interval'
    ],
    'pluginEvents'=>[
            ''
    ]
]);
echo '<div class="submit">';
echo Html::submitButton("Применить",  ["class" => "btn btn-primary"]);
echo '</div>';
echo '</div>';
echo '</div>';
?>
<ul class="nav nav-tabs">
    <li class="nav-item active"><?=Html::a('Общая статистика', '#test', ['data-toggle' => 'tab', 'class' => 'nav-link active']); ?></li>
    <li class="nav-item"><?=Html::a('Профили', '#profiles',  ['data-toggle' => 'tab', 'class' => 'nav-link']); ?></li>
</ul>
<?= Html::endForm(); ?>


<div class="tab-content">
    <div class="tab-pane fade active in" id="test">
        <div class="container">
            <div class="row">
                <div id="left-param" class="col-xs-12 col-lg-4">
                    <div id="myAffix" data-spy="affix">
                        <div class="table-responsive stat-table">
                            <table class="table table-bordered" >
                                <thead>
                                <tr>
                                    <th class="param">Параметр<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Диапазон выбранных дат" style="font-size: 14px; margin-top: 5px;"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="param">Успешных авторизаций<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Сумма за день входов на страницу создания проекта" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                <tr>
                                    <td class="param">Неудачных авторизаций<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Сумма за день возникновения ошибок о неверно введенном пароле" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                <tr>
                                    <td class="param">Регистраций<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Количество новых зарегестрированных пользователей за день" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                <tr>
                                    <td class="param">Восстановлено паролей<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Количество запросов на восстановление пароля от учетной записи" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                <tr>
                                    <td class="param">Использовано профилей за день<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Количество уникальных профилей" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                <tr>
                                    <td class="param">Пользователей за день<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Количество пользователей работавших над проектами" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-lg-8">
                    <div class="table-responsive stat-table">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<th class="text-center">' . $item . '</th>' ?>
                                <?php };?>
                            </tr>

                            </thead>
                            <tbody>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td align="center">' . $fullStatistics[$item]['CorrectAuth'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td align="center">' . $fullStatistics[$item]['UnCorrectAuth'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td align="center">' . $fullStatistics[$item]['Registration'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td align="center">' . $fullStatistics[$item]['ResetPass'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td>' .  $fullStatistics[$item]['ProfilesCorrect'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td align="center">' .  $fullStatistics[$item]['Users'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="profiles">
        <div class="container">
            <div class="row">
                <div id="left-param" class="col-xs-12 col-lg-4">
                    <div id="myAffix" data-spy="affix">
                        <div class="table-responsive stat-table">
                            <table class="table table-bordered" >
                                <thead>
                                <thead>
                                <tr>
                                    <th class="param">Параметр<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Диапазон выбранных дат" style="font-size: 14px; margin-top: 5px;"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="param">Создано проектов<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Количество созданных проектов (только корректные сборки)" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                <tr>
                                    <td class="param">Проектов изменено<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Количество редактированных проектов" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                <tr>
                                    <td class="param">Некорректных сборок<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Количество проектов с ошибками сборки" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                <tr>
                                    <td class="param">Собрано из архива<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Количество открытых проектов из архива пользователя" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                <tr>
                                    <td class="param">Использованы виды патогенов<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Количество патогенов за день" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                <tr>
                                    <td class="param">Среднее число проектов на пользователя<i class="icon-ico_alert pull-right" data-toggle="tooltip" data-placement="top" title="Количество проектов за день деленное на число пользователей за день, округленное до 2 цифр" style="font-size: 14px; margin-top: 5px;"></i></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-lg-8">
                    <div class="table-responsive stat-table">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<th class="text-center">' . $item . '</th>' ?>
                                <?php };?>
                            </tr>

                            </thead>
                            <tbody>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td>' .  $fullStatistics[$item]['ProjectDay'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td>' .  $fullStatistics[$item]['ProjectUpdate'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td>' .  $fullStatistics[$item]['ProfilesUnCorrect'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td>' .  $fullStatistics[$item]['Archive'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td>' .  $fullStatistics[$item]['Pathogen'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            <tr>
                                <?php foreach ($date as $item) { ?>
                                    <?= '<td>' .  $fullStatistics[$item]['Average'] . '</td>'; ?>
                                <?php };?>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
