<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "update_project".
 *
 * @property int $id
 * @property string|null $udate_create
 * @property int|null $project_id
 */
class UpdateProject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'update_project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['udate_create'], 'safe'],
            [['project_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'udate_create' => 'Udate Create',
            'project_id' => 'Project ID',
        ];
    }

    public static function projectUpdate($start, $end)
    {
        return self::find()
            ->where(['>=', 'udate_create',  $start])
            ->andWhere(['<=', 'udate_create', $end])
            ->asArray()
            ->all();
    }
}
