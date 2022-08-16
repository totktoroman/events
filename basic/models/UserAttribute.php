<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_attribute".
 *
 * @property int $attribute_id
 * @property int $register_event_id
 * @property string $value
 */
class UserAttribute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_attribute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_id', 'register_event_id', 'value'], 'required'],
            [['attribute_id', 'register_event_id'], 'integer'],
            [['value'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attribute_id' => 'Attribute ID',
            'register_event_id' => 'register_event ID',
            'value' => 'Value',
        ];
    }
}
