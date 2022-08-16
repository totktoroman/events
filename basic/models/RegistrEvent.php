<?php

namespace app\models;
use yii\db\ActiveRecord;


class RegistrEvent extends ActiveRecord 
{
	//обращение к таблице 
	public static function tableName(){
		return "register_event";
	} 

	public function rules()
	{
		return [ 
				[['event_id','user_id', 'register_date'], 'required'],
				[['register_status'], 'default'],
		];
	}
	
	public function getRegisterDate(){
		return $this->register_date;
	}
	 public function getStatus(){
		 if($this->register_status==2){
				return "Не рассмотрен"; 
		 }
		 if($this->register_status==1){
				return "Принят"; 
		 }
		 if($this->register_status==5){
				return "Отклонен"; 
		 }
	}
	public function getStatusForUser(){
		if($this->register_status==2){
			 return '<div style="font-size:1em;color:orange;">Заявка в рассмотрении</div>'; 
		}
		if($this->register_status==1){
			 return '<div style="font-size:1em;color:green;">Заявка одобрена </div>'; 
		}
		if($this->register_status==5){
			 return '<div style="font-size:1em;color:red;">Заявка отклонена </div>'; 
		}
 }
	
	
}
?>