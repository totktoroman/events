<?php

namespace app\models;
use yii\db\ActiveRecord;

class District extends ActiveRecord 
{

	public function rules()
	{
		return [ 
				[['district_name',], 'required'],
				
		];
	}
	 public function findIDByName($name)
    {
		$district=District::find()->where("district_name='".$name."'")->one();
        return $district->district_id;
	}
	public function findByName($name)
    {
        return District::find()->where("district_name='".$name."'")->one();
	}
	
	
}
?>