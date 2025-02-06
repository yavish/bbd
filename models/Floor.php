<?php

namespace lmy\humhub\modules\bbd\models;

use Yii;

/**
 * This is the model class for table "{{%bbd_floor}}".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $sort_order
 */
class Floor extends \humhub\modules\content\components\ContentActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bbd_floor}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'string'],
            [['sort_order'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'sort_order' => 'Sort Order',
        ];
    }
}
