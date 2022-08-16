<?php

namespace app\models;
use yii\db\ActiveRecord;


class Event extends ActiveRecord 
{

	public function rules()
	{
		return [ 
				[['event_title','event_info','event_start_date','event_register_end', 'is_active'], 'required'],
				['event_auto_acceptance','boolean']
				
		];
	 }
	 public function getAuto(){
	 		return $this->event_auto_acceptance;	
	}

	public function getTitle(){
		return $this->event_title;		
	}

	public function getStatus(){
		if($this->is_active)
			return "Активно";
		else 
			return "Неактивно";		
	}


		
	
	
}