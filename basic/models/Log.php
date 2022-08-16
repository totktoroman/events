<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $log_id
 * @property int $user_id
 * @property string $log_action
 * @property string $log_date
 *                                                                                                                          
 * @property User $user
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'log_action', 'log_date'], 'required'],
            [['user_id'], 'integer'],
            [['log_action'], 'string'],
            [['log_date'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'user_id' => 'User ID',
            'log_action' => 'Log Action',
            'log_date' => 'Log Date',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }
}
