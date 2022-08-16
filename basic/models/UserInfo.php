<?php

namespace app\models;
use yii\db\ActiveRecord;
use app\models\Position;

class UserInfo extends ActiveRecord 
{

	public function rules()
	{
		return [ 
				[['user_family','user_name','user_school','user_phonenumber','user_class','user_email','user_id','soglasie'], 'required'],
				['user_email', 'email'],
				[['user_patronymic'], 'default','value' => null]
				
		];
	}

	public function getInfo(){
		return $this->user_family." ".$this->user_name." ".$this->user_patronymic;
	}

	public function getMail(){
		return $this->user_email;
	}
	
	
}
?>