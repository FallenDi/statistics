<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resetpass_stat".
 *
 * @property string|null $date_create
 * @property string|null $ip
 * @property string|null $email
 */
class ResetpassStat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resetpass_stat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_create'], 'safe'],
            [['date_time_create'], 'safe'],
            [['ip'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 255],
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
            'email' => 'Email',
        ];
    }

    public static function countResets($startDate, $endDate)
    {
        return self::find()
            ->where(['>=', 'date_create', $startDate])
            ->andWhere(['<=', 'date_create', $endDate])
            ->asArray()
            ->all();
    }

}
