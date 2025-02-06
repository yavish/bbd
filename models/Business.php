<?php

namespace lmy\humhub\modules\bbd\models;
use lmy\humhub\modules\bbd\models\Floor;
use Yii;

/**
 * This is the model class for table "{{%bbd_business}}".
 *
 * @property int $id
 * @property int $floor_id
 * @property string|null $room_number
 * @property string|null $title
 * @property string|null $description
 * @property string|null $href
 * @property int $sort_order
 */
class Business extends \humhub\modules\content\components\ContentActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bbd_business}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['floor_id','room_number', 'title'], 'required'],
            [['floor_id', 'sort_order'], 'integer'],
            [['title', 'description', 'href'], 'string'],
            [['room_number'], 'string', 'max' => 255],
        ];
    }
    public function getFloor()
    {
        return $this->hasOne(Floor::class, ['id' => 'floor_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'floor_id' => 'Floor ID',
            'room_number' => 'Room Number',
            'title' => 'Title',
            'description' => 'Description',
            'href' => 'Href',
            'sort_order' => 'Sort Order',
        ];
    }

     /**
     * approve the content object
     */
    public function approve()
    {
      
        $this->content->was_published =1;
        //This prevents the call of beforeSave, and the setting of update_at
        $this->content->updateAttributes(['was_published']);
    }

    /**
     * Unapprove the content object
     */
    public function unapprove()
    {
        $this->content->was_published =0;
        //This prevents the call of beforeSave, and the setting of update_at
        $this->content->updateAttributes(['was_published']);
    }
}
