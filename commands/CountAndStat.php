<?php


namespace app\commands;
use Yii;

class CountAndStat
{
    public static function counterParam($paramArray, $arrayKey)
    {
        $datesArray = [];

        foreach ($paramArray as $key => $valueArray) {
            $datesArray[] = $valueArray[$arrayKey];
        }
        $counterDates = array_count_values($datesArray);

        return $counterDates;
    }

    public static function counterUniq($dataArray, $arrayKey)
    {
        $counterArray = [];

        foreach ($dataArray as $key => $value) {
            if ($counterArray[$value['date_create']]) {
                array_push($counterArray[$value['date_create']], $value[$arrayKey]);
            } else {
                $counterArray[$value['date_create']] = [$value[$arrayKey]];
            }
        }
        //Выбираем уникальные профили проектов и делаем count
        foreach ($counterArray as $date => $profile) {
            $counterArray[$date] = count(array_unique($profile));
        }

        return $counterArray;
    }

}