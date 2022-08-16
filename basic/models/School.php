<?php

namespace app\models;
use yii\db\ActiveRecord;
use app\models\District;
class School extends ActiveRecord 
{

	public function rules()
	{
		return [ 
				[['school_name','school_district_id'], 'required'],
				
		];
	}
	public function getDistrict(){
		$district=District::find()->where(['district_id' => $this->school_district_id])->one();
		return $district->district_name;
	}
	public function getName(){
		return $this->school_name;
	}
	
	
	
}
?>