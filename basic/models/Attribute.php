<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attribute".
 *
 * @property int $attribute_id
 * @property string $attribute_name
 * @property int $attribute_type
 *
 * @property EventAttribute[] $eventAttributes
 * @property UserAttribute[] $userAttributes
 */
class Attribute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attribute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_name', 'attribute_type'], 'required'],
            [['attribute_type'], 'string'],
            [['attribute_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attribute_id' => 'Attribute ID',
            'attribute_name' => 'Attribute Name',
            'attribute_type' => 'Attribute Type',
        ];
    }

    /**
     * Gets query for [[EventAttributes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventAttributes()
    {
        return $this->hasMany(EventAttribute::className(), ['attribute_id' => 'attribute_id']);
    }

    /**
     * Gets query for [[UserAttributes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAttributes()
    {
        return $this->hasMany(UserAttribute::className(), ['attribute_id' => 'attribute_id']);
    }

    public function getAttributeValue()
    {
        return $this->hasMany(AttributeValue::className(), ['attribute_id' => 'attribute_id']);
    }
    
    public static function getName($id){
        return Attribute::findOne($id)->attribute_name;
    }


    


    
}
