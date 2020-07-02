<?php
/**
 * Created by PhpStorm.
 * User: ngalkin
 * Date: 22.06.2020
 * Time: 13:46
 */

namespace app\models;


use yii\db\ActiveRecord;

class DbResist extends ActiveRecord
{

    public static function getDb()
    {
        // использовать компонент приложения "db2"
        return \Yii::$app->dbResist;
    }

    public static function countRegistration($startDate, $endDate)
    {
        $sql = "SELECT * FROM user
                WHERE FROM_UNIXTIME(created_at, '%Y-%m-%d') >= '".  $startDate . "'
                AND FROM_UNIXTIME(created_at, '%Y-%m-%d') <= '" . $endDate . "'";

        return self::findBySql($sql)->asArray()->all();

    }

    public static function projectCount($startDate, $endDate)
    {

        $sql = "SELECT * FROM project
                WHERE date_create >= '".  $startDate . "'
                AND date_create <= '" . $endDate . "'";

        return  self::findBySql($sql)->asArray()->all();


    }

    public static function reportsCount($startDate, $endDate)
    {
        $sql = "SELECT * FROM project
                WHERE date_create >= '".  $startDate . "'
                AND date_create <= '" . $endDate . "'";

        return self::findBySql($sql)->asArray()->all();


    }

    public static function profilesCount($startDate, $endDate, $correct)
    {

        $sql = "SELECT * FROM project
                WHERE date_create >= '".  $startDate . "'
                AND date_create <= '" . $endDate . "'
                AND `status` !='" . $correct . "'";

        return self::findBySql($sql)->asArray()->all();

    }

    public static function pathogenCount($startDate, $endDate) {

        $sql = "SELECT *
                FROM project as A
                JOIN project_profile as B
                ON A.profile_id = B.id
                WHERE date_create >='" . $startDate . "'
                AND date_create <='" . $endDate . "'
                AND `status` != 1";

        return self::findBySql($sql)->asArray()->all();

    }

    public static function countUsers($startDate, $endDate) {

        $sql = "SELECT * FROM project
                WHERE date_create >= '".  $startDate . "'
                AND date_create <= '" . $endDate . "'";

        return self::findBySql($sql)->asArray()->all();
    }

}
