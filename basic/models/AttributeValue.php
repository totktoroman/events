<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attribute_value".
 *
 * @property int $attribute_value_id
 * @property int|null $attribute_id
 * @property string $value
 *
 * @property Attribute $attribute0
 */
class AttributeValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attribute_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_id'], 'integer'],
            [['value'], 'required'],
            [['value'], 'string', 'max' => 255],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attribute::className(), 'targetAttribute' => ['attribute_id' => 'attribute_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attribute_value_id' => 'Attribute Value ID',
            'attribute_id' => 'Attribute ID',
            'value' => 'Value',
        ];
    }

    /**
     * Gets query for [[Attribute0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttribute0()
    {
        return $this->hasOne(Attribute::className(), ['attribute_id' => 'attribute_id']);
    }
}
