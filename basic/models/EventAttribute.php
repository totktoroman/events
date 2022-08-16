<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_attribute".
 *
 * @property int $event_attribute_id
 * @property int $event_id
 * @property int $attribute_id
 *
 * @property Attribute $attribute0
 * @property Event $event
 */
class EventAttribute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_attribute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'attribute_id'], 'required'],
            [['event_id', 'attribute_id'], 'integer'],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attribute::class, 'targetAttribute' => ['attribute_id' => 'attribute_id']],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::class, 'targetAttribute' => ['event_id' => 'event_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'event_attribute_id' => 'Event Attribute ID',
            'event_id' => 'Event ID',
            'attribute_id' => 'Attribute ID',
        ];
    }

    /**
     * Gets query for [[Attribute0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttribute0()
    {
        return $this->hasOne(Attribute::class, ['attribute_id' => 'attribute_id']);
    }

    /**
     * Gets query for [[Event]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::class, ['event_id' => 'event_id']);
    }
}
