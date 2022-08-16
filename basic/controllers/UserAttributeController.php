<?php

namespace app\controllers;
use Yii;
use yii\base\Model;
use app\models\UserAttribute;

class UserAttributeController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
				'only' => ['form-process', 'event-form'],
                'rules' => [
                    [
						'allow' => true,
                        'actions' => ['form-process', 'event-form'],
                        'matchCallback' => function($rule, $action){
                            $access = false;
                            if(!Yii::$app->user->isGuest){
                                $user = Yii::$app->user->identity;
                                $status = $user->getStatusTitle();
                                if($status == "ADMINISTRATOR" || $status == "ORGANIZATOR"){
                                    $access = true;
                                }
                            }
                            return $access;
                        }
                    ],
                ],
            ],
            
        ];
    }
   
    function actionFormProcess(){
        
        $event_id = $_POST['event_id'];
        $item=UserAttribute::find()->where(['user_id' => $_POST['user_id'], 'attribute_id' => $_POST['attr_id']])->one();
        if(!is_object($item)){
            $item=new UserAttribute();
        }
        $item->load(\Yii::$app->request->post());
      
        $item->save();
        return $this->redirect("index.php?r=event%2Fevent-info&event=".$event_id);
        
    }
    public function actionUpdate()
    {
        $event_id = $_POST['event_id'];
        $settings = UserAttribute::find()->where(['register_event_id' => $event_id])->indexBy('id')->all();

        if (Model::loadMultiple($settings, Yii::$app->request->post()) && Model::validateMultiple($settings)) {
            foreach ($settings as $setting) {
                $setting->save();
            }
            
        }

        return $this->redirect("index.php?r=event%2Fevent-info&event=".$event_id);
    }
        
        
            

}
