<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "aud".
 *
 * @property int $aud_id
 * @property string $aud_title
 * @property int $aud_count
 */
class Aud extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'aud';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aud_title', 'aud_count'], 'required'],
            [['aud_count'], 'integer'],
            [['aud_title'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'aud_id' => 'Aud ID',
            'aud_title' => 'Aud Title',
            'aud_count' => 'Aud Count',
        ];
    }
}
