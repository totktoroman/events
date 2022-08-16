<?php
namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Event;
use app\models\UserInfo;
use app\models\User;	
use app\models\RegistrEvent;
use app\models\UserAttribute;
use yii\base\Model;
use app\models\Attribute;
use app\models\Log;
use app\models\Time;
use app\models\EventAttribute;
use app\models\EventOrganizer;

class EventController extends Controller 
{

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
				'only' => ['form-process', 'event-form', 'delete-event'],
                'rules' => [
                    [
						'allow' => true,
                        'actions' => ['form-process'],
                        'matchCallback' => function($rule, $action){
                            $access = false;
                            if(!Yii::$app->user->isGuest){
                                $user = Yii::$app->user->identity;
                                $status = $user->getStatusTitle();
                                if($status == "ADMINISTRATOR"){
                                    $access = true;
                                }
                            }
                            return $access;
                        },
						'allow' => true,
						'actions' => ['form-process', 'event-form', 'delete-event'],
                        'matchCallback' => function($rule, $action){
                            $access = false;
							$status;
                            if(!Yii::$app->user->isGuest){
                                $user = Yii::$app->user->identity;
                                $status = $user->getStatusTitle();
                                if($status == "ADMINISTRATOR"){
                                    $access = true;
                                }
								else if($status == "ORGANIZATOR"){
									if(isset($_GET['event'])){
										$event = $_GET['event'];
										$item = EventOrganizer::find()->where(['user_id' => $user->user_id, 'event_id' => $event])->one();
										if(!is_null($item)){
											$access = true;
										}	
									}
									else{
										$access = true;
									}
								}
                            }
                            return $access;
                        }
                    ],
                ],
            ],
            
        ];
    }
	
	function actionEvents(){
			$btn=0;
			
			$event=Event::find()->where(['is_active' => true])->all();
			if(Yii::$app->user->isGuest==0){
				$user = Yii::$app->user->identity;
				$status = $user->getStatusTitle();
				if($status == "ADMINISTRATOR" || $status == "ORGANIZATOR"){
					$event=Event::find()->all();
				}
			}
			
			

			
			if(Yii::$app->user->isGuest==0){
				$item=User::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
				if($item->user_status_id == 1 || $item->user_status_id == 3 )
				{
					$btn=1;
				}
				else {
					$btn=2;
				}
			}
			
			return $this->render("events",["event"=>$event, "btn"=>$btn]);
	}

	function actionEventForm($event=0){
		$model=Event::find()->where("Event_id=".$event)->one();
		if(!is_object($model)){
			$model=new Event();
		}		
		$attr = Attribute::find()->all();
		$attr_checked = [];
		$i=0;
		foreach($attr as $a){
			if(EventAttribute::find()->where(['event_id' => $event, 'attribute_id' => $a->attribute_id])->exists()){
				$attr_checked[$i] = true;
			}
			else{
				$attr_checked[$i] = false;
			}
			$i++;
		}

		$org = User::find()->where(['user_status_id' => 3])->all();
		$org_checked = [];
		$i=0;
		foreach($org as $o){
			if(EventOrganizer::find()->where(['event_id' => $event, 'user_id' => $o->user_id])->exists()){
				$org_checked[$i] = true;
			}
			else{
				$org_checked[$i] = false;
			}
			$i++;
		}
		return $this->render("event-form",["model" => $model, "attr"=>$attr, "org" => $org, "org_checked" => $org_checked, "attr_checked" => $attr_checked]);
	}

	function actionFormProcess(){
		$model=new Event();
		$f=0;
		$attributes=[];

		if (is_numeric($_POST['item_id'])){
			//если передано значение прогружаем
			$model=Event::findOne($_POST['item_id']);
			$old_data = $model;
			$f=1;
		}
		
		if($model->load(\Yii::$app->request->post()) && $model->save() ){
			$attributes = [];
			$all_attr = EventAttribute::find()->where(['event_id' => $model->event_id])->all();
			if(isset($_POST['attr'])){
				$attributes = $_POST['attr'];
				foreach($all_attr as $attr){
					if(!in_array($attr->attribute_id, $attributes)){
						$attr->delete();
					}
				}
				foreach ($attributes as $attr_id){
					$ea = EventAttribute::findOne(['attribute_id' => $attr_id, 'event_id' => $model->event_id]);
					if(is_null($ea)){
						$ea=new EventAttribute();
						$ea->event_id = $model->event_id;
						$ea->attribute_id = $attr_id;
						$ea->save();
					}
				}
			}
			else{
				foreach($all_attr as $attr){
					$attr->delete();
				}

			}

				
			$org = [];
			if(Yii::$app->user->identity->getStatusTitle() == "ORGANIZATOR"){
				// $event_org = EventOrganizer::findOne(['user_id' => Yii::$app->user->identity->user_id, 'event_id' => $model->event_id]);
				array_push($org, Yii::$app->user->identity->user_id);
			}
			$all_orgs = EventOrganizer::find()->where(['event_id' => $model->event_id])->all();
			if(Yii::$app->user->identity->getStatusTitle() == "ORGANIZATOR" || Yii::$app->user->identity->getStatusTitle() == "ADMINISTRATOR" ){

				if(isset($_POST['orgs'])){
					$orgs = array_merge($org, $_POST['orgs']);
					foreach($all_orgs as $org){
						if(!in_array($org->user_id, $orgs)){
							$org->delete();
						}
					}
					foreach ($orgs as $org_id){
						$eo = EventOrganizer::findOne(['user_id' => $org_id, 'event_id' => $model->event_id]);
						if(is_null($eo)){
							$eo=new EventOrganizer();
							$eo->event_id = $model->event_id;
							$eo->user_id = $org_id;
							$eo->save();
						}
					}
				}
			}
			else{
				foreach($all_orgs as $org){
					$org->delete();
				}

			}
		
			$log = new Log();
			if(!$f)
				$log->log_action = Yii::$app->user->identity->user_login." добавил мероприятие ".$model->event_title;
			else
				$log->log_action = Yii::$app->user->identity->user_login." отредактировал мероприятие ".$model->event_title;	
			$log->user_id = Yii::$app->user->identity->user_id;
			$date = new Time();
			$log->log_date = $date->getDatetimeNow();
			$log->save();
		}




	
		return $this->redirect("index.php?r=event%2Fevents");
	}
	function actionDeleteEvent($event=0){
		$event=Event::findOne($event);
		if (is_object($event)){
			
			if($event->delete()){
				$log = new Log();
				$log->user_id = Yii::$app->user->identity->user_id;
				$log->log_action = Yii::$app->user->identity->user_login." удалил мероприятие ".$event->event_title;
				$date = new Time();
				$log->log_date = $date->getDatetimeNow();
				$log->save();
			}
			
		}
		return $this->redirect("index.php?r=event%2Fevents");
	}
	

	function actionCancelRegistr($event=0){
		$re=RegistrEvent::find()->where(['user_id' => Yii::$app->user->identity->user_id, 'event_id' => $event])->one();
		$re->register_status=5;
		
		if( $re->save() ){
			$log = new Log();
			$log->user_id = Yii::$app->user->identity->user_id;
			$log->log_action = Yii::$app->user->identity->user_login." отменил регистрацию на мероприятие ".Event::findOne($event)->event_title;
			$date = new Time();
			$log->log_date = $date->getDatetimeNow();
			$log->save();
		}
		return $this->redirect("index.php?r=event%2Fevents");
	}

	function actionRegistr($event){
		$model=RegistrEvent::find()->where(['user_id' => Yii::$app->user->identity->user_id, 'event_id' => $event])->one();
		

		if(!is_object($model)){
			$model=new RegistrEvent();
			$model->user_id = Yii::$app->user->identity->user_id;
			$model->event_id = $event;
			$date = new Time();
			$model->register_date = $date->getDatetimeNow();
		}
		if(Event::findone($event)->getAuto()){
			$model->register_status = 1;
           
		}
		else{
			$model->register_status = 2;

		}

		$item=EventAttribute::find()->where(['event_id' => $event])->all();
		$reg_event = RegistrEvent::find()->where(['event_id' => $event, 'user_id' => Yii::$app->user->identity->user_id])->one();
		if(!is_object($reg_event)){
			
			foreach($item as $ea){
				$ua = new UserAttribute();
				$ua->attribute_id = $ea->attribute_id;
				$user_attribute[] = $ua;
			}
		}
		else{

			$user_attribute = UserAttribute::find()->where(['register_event_id' => $reg_event->register_event_id])->indexBy('user_attribute_id')->all();
		}
		
			
		if($model->save()){
			if(Model::loadMultiple( $user_attribute, Yii::$app->request->post() ) ){

				$reg_event = RegistrEvent::find()->where(['event_id' => $event, 'user_id' => Yii::$app->user->identity->user_id])->one();
				
				foreach ($user_attribute as $setting) {
					$setting->register_event_id = $reg_event->register_event_id;
				}
				
				if (Model::validateMultiple($user_attribute)) {
					foreach ($user_attribute as $setting) {
						$setting->save();
					}
				}
			}

			$log = new Log();
			$log->user_id = Yii::$app->user->identity->user_id;
			$log->log_action = Yii::$app->user->identity->user_login." подал заявkу на регистрацию на мероприятие ".Event::findOne($event)->event_title;
			$date = new Time();
			$log->log_date = $date->getDatetimeNow();
			$log->save();
		}

		return $this->redirect("index.php?r=event%2Fevents");
	}

	function actionEventInfo($event){
		$btn=0;
		$event=Event::findOne($event);
		$users=UserInfo::find()->all();
		$item=EventAttribute::find()->where(['event_id' => $event->event_id])->all();
		$user_attribute=[];
		if(Yii::$app->user->isGuest==false){
			$reg_event = RegistrEvent::find()->where(['event_id' => $event->event_id, 'user_id' => Yii::$app->user->identity->user_id])->one();
			if(!is_object($reg_event)){
				
				foreach($item as $ea){
					$ua = new UserAttribute();
					$ua->attribute_id = $ea->attribute_id;
					$user_attribute[] = $ua;
				}
			}
			else{
			$user_attribute = UserAttribute::find()->where(['register_event_id' => $reg_event->register_event_id])->indexBy('user_attribute_id')->all();
			// if(!is_object($user_attribute)){
			// 	foreach($item as $ea){
			// 		$ua = new UserAttribute();
			// 		$ua->attribute_id = $ea->attribute_id;
			// 		$user_attribute[] = $ua;
			// 	}
			// }
			}
		}

		
		if(Yii::$app->user->isGuest==false){
			$item=User::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
			if($item->user_status_id == 1){
				$btn=1;
			}
		}

		
    return $this->render("event-info",["event"=>$event, "users" => $users, "user_attribute" => $user_attribute, "btn"=>$btn]);
		
	}

	function actionInstruction(){
		
		// header("Location:instruction.html");
		return $this->render("instruction");
	
	}

	
	function actionIndex(){
		$user=Yii::$app->user->identity->user_id;
	}
}
?>

		

			
			
