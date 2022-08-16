<?php

namespace app\models;
use yii\db\ActiveRecord;

class Sagaalgan extends ActiveRecord 
{

	public function rules()
	{
		return [ 
				[['surname','name','patronymic','school','phonenumber','class','id'], 'required'],
				
		];
	 }
	
	
}
?>