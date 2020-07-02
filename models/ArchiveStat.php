<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "archive_stat".
 *
 * @property int $id
 * @property int|null $project_id
 * @property int|null $user_id
 * @property string|null $date_time_create
 * @property string|null $date_create
 */
class ArchiveStat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'archive_stat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'user_id'], 'integer'],
            [['date_time_create', 'date_create'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'user_id' => 'User ID',
            'date_time_create' => 'Date Time Create',
            'date_create' => 'Date Create',
        ];
    }

    public static function archiveProject($startDate, $endDate)
    {
        return self::find()
            ->where(['>=', 'date_create', $startDate])
            ->andWhere(['<=', 'date_create', $endDate])
            ->asArray()
            ->all();
    }
}
