<?php
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\widgets\Pjax;
use kartik\icons\FontAwesomeAsset;


$this->title = 'Statistics Profiles';



Pjax::begin(); ?>
<?php
echo Html::beginForm(['/test', 'id' => 'dastaform'], 'POST');
?>

<?php
echo '<label class="control-label">Выберете интервал</label>';

echo '<div class="drp-container">';
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
        'placeholder' => $dateLabel
    ],
    'pluginEvents'=>[
        ''
    ]
]);
echo '</div>';

?>
<?= Html::submitButton('Применить', ['class' => 'btn btn-primary']); ?>
    <ul class="nav nav-tabs">
        <li class="nav-item"><?=Html::a('Общая статистика', '#test', ['data-toggle' => 'tab', 'class' => 'nav-link active']); ?></li>
        <li class="nav-item"><?=Html::a('Профили', '#profiles',  ['data-toggle' => 'tab', 'class' => 'nav-link']); ?></li>
    </ul>
<?= Html::endForm(); ?>

    <div class="site-about">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead >
                <tr>
                    <th > Параметр </th>
                    <?php foreach ($date as $item) { ?>
                        <?= '<th class="text-center">' . $item . '</th>' ?>
                    <?php };?>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Успешных авторизаций</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' . $fullStatistics[$item]['CorrectAuth'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Неудачных авторизаций</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' . $fullStatistics[$item]['UnCorrectAuth'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Регистраций</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' . $fullStatistics[$item]['Registration'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Восстановлено паролей</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' . $fullStatistics[$item]['ResetPass'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Создано проектов</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' .  $fullStatistics[$item]['ProjectDay'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Проектов изменено (пока неверный алгоритм)</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' .  $fullStatistics[$item]['ProjectUpdate'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Использовано профилей за день</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' .  $fullStatistics[$item]['ProfilesCorrect'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Некорректных сборок</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' .  $fullStatistics[$item]['ProfilesUnCorrect'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Собрано из архива</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' .  $fullStatistics[$item]['Archive'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Использованы виды патогенов</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' .  $fullStatistics[$item]['Pathogen'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Пользователей за день</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' .  $fullStatistics[$item]['Users'] . '</td>'; ?>
                    <?php };?>
                </tr>
                <tr>
                    <td>Среднее число проектов на пользователя</td>
                    <?php foreach ($date as $item) { ?>
                        <?= '<td align="center">' .  $fullStatistics[$item]['Average'] . '</td>'; ?>
                    <?php };?>
                </tr>

                </tbody>
            </table>
        </div>
    </div>

<?php Pjax::end(); ?>