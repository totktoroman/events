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
use app\models\Attribute;
use app\models\EventAttribute;
use app\models\UserAttribute;
use app\models\Time;
use app\models\Log;
use app\models\Mailer;

class EventMemberController extends Controller 
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
	
	function actionEventMember($event){
			$query="SELECT B.* FROM register_event AS A,user_info AS B WHERE A.user_id=B.user_id  AND event_id=".$event." ORDER BY register_date DESC";
			$members=Yii::$app->db->createCommand($query)->queryAll();
			$attribute = EventAttribute::find()->where(['event_id' => $event])->all();
            return $this->render("eventmember",["members"=>$members,"event"=>$event, "attribute"=>$attribute]);
	}

	// function actionEventMembersForm($event=0){
	// 	$model=Event::find()->where("Event_id=".$event)->one();
	// 	if(!is_object($model)){
	// 		$model=new Event();
	// 	}		
	// 	return $this->render("event-form",["model"=>$model]);
	// }
	
						
	
	function actionCancelRegistr($event,$user){
		$model=RegistrEvent::find()->where(['user_id' => $user, 'event_id' => $event])->one();
		$model->register_status=5;

		if($model->save() ){
			$log = new Log();
			$log->user_id = Yii::$app->user->identity->user_id;
			$log->log_action = Yii::$app->user->identity->user_login." отклонил заявку ".User::findOne($user)->getLogin()." на мероприятие ".Event::findOne($event)->getTitle();
			$date = new Time();
			$log->log_date = $date->getDatetimeNow();
			$log->save();

			$email = User::getMail($user);
			$params['model'] = Event::find()->where(['event_id' => $event])->one();
			Mailer::sendRegisterDecline($email,$params);
		}
		

		return $this->redirect("index.php?r=event-member%2Fevent-member&event=$event");
	}

	function actionAcceptRegistr($event,$user){
		$model=RegistrEvent::find()->where(['user_id' => $user, 'event_id' => $event])->one();
		$model->register_status=1;

		if($model->save() ){
			$log = new Log();
			$log->user_id = Yii::$app->user->identity->user_id;
			$log->log_action = Yii::$app->user->identity->user_login." принял заявку ".User::findOne($user)->getLogin()." на мероприятие ".Event::findOne($event)->getTitle();
			$date = new Time();
			$log->log_date = $date->getDatetimeNow();
			$log->save();

			$email = User::getMail($user);
			$params['model'] = Event::find()->where(['event_id' => $event])->one();
			Mailer::sendRegisterAccept($email,$params);
		}
		return $this->redirect("index.php?r=event-member%2Fevent-member&event=$event");
	}


	

}
