<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "aud_student".
 *
 * @property int $aud_student_id
 * @property int $aud_student_aud_id
 * @property int $aud_student_student_id
 */
class AudStudent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'aud_student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aud_student_aud_id', 'aud_student_student_id','aud_student_event_id'], 'required'],
            [['aud_student_aud_id', 'aud_student_student_id','aud_student_event_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'aud_student_id' => 'Aud Student ID',
            'aud_student_aud_id' => 'Aud Student Aud ID',
			'aud_student_student_id' => 'Aud Student Student ID',
			'aud_student_event_id' => 'Aud Student Event ID',
        ];
    }
}
