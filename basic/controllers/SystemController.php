<?php
namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\UserInfo;
use app\models\Aud;
use app\models\AudStudent;
use app\models\Log;
use app\models\Time;
use app\models\Event;

class SystemController extends Controller 
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
	public function actionMakeSeat($class=10){
		
		//если данные из формы летят
		//$au[]=$_POST['auds'];
		$auds=[];
		foreach ($_POST['auds'] as $item){
				$rr=Aud::find()->where('aud_id='.$item)->one();
				array_push($auds,array("obj"=>$rr,"cou"=>$rr->aud_count));
		}
		$class=$_POST['class'];
		$event_id=$_POST['event_id'];
		//получили номера аудиторий в зависимости от класса
		/*if($class==10){
			$aud=[1205,1111];
		}
		else{
			$aud=[1208,1209,1309];	
		}*/


		//формируем массив объектов аудиторий
	/*	$auds=array();
		foreach($aud as $item){
			$obj=Aud::find()->where('aud_title='.$item)->one();
			if (is_object($obj)){
				array_push($auds,array("obj"=>$obj,"cou"=>$obj->aud_count));
			}
		}*/
		//$students_all=UserInfo::find()->where('user_class='.$class)->orderBy(['user_school'=>'ASC'])->all();
		$delete_query = "DELETE FROM aud_student  
						 WHERE aud_student_event_id =".$event_id." AND aud_student_student_id IN (SELECT user_id FROM user_info 
											WHERE user_class=".$class." ) "; 
		$STH=Yii::$app->db->createCommand($delete_query)->query();

		$query="SELECT A.user_id FROM register_event AS A,user_info AS B 
						WHERE user_class=".$class." AND A.user_id=B.user_id AND register_status=1 AND event_id=".$event_id." 
						ORDER BY user_school";
		$STH=Yii::$app->db->createCommand($query)->queryAll();
		$students=array();
		foreach($STH as $item){
			$obj=UserInfo::find()->where(['user_id' => $item, 'user_class' => $class])->one();
			if (is_object($obj)){
				array_push($students,$obj);
			}
		}
		/*foreach($students as $item){
		
			echo $item->user_id;
			echo "<br>";
		}*/
	
		$aud_num=0;
		$n=count($auds);
		$cou=0;
		foreach($students as $student){
			$as=new AudStudent();	
			$as->aud_student_event_id=$event_id;		
			$as->aud_student_student_id=$student->user_id;
			$obj=$auds[$aud_num]["obj"];
			$as->aud_student_aud_id=$obj->aud_id;
			$auds[$aud_num]["cou"]--;
			if ($auds[$aud_num]["cou"]==0){				
				for($i=$aud_num;$i<$n-1;$i++){
					$auds[$i]=$auds[$i+1];
				}
				$x=array_pop($auds);
				$n--;
			}			
			$as->save();
			$aud_num++;
			if ($aud_num>=$n) $aud_num=0;
			$cou++;
			if($n==0) break;
		}
		if ($cou<count($students)){

			
				$log = new Log();
				$log->user_id = Yii::$app->user->identity->user_id;
				$log->log_action = Yii::$app->user->identity->user_login." произвел неполную рассадку на мероприятие ".Event::findOne($event_id)->getTitle()." для ". $class." kласса";
			   
				$date = new Time();
				$log->log_date = $date->getDatetimeNow();
				$log->save();
			

			echo "Мест не хватило<br> Посадили: ".$cou." из:".count($students);
		}
		else{


			$log = new Log();
			$log->user_id = Yii::$app->user->identity->user_id;
			$log->log_action = Yii::$app->user->identity->user_login." произвел рассадку на мероприятие ".Event::findOne($event_id)->getTitle()." для ". $class." kласса";
			
			$date = new Time();
			$log->log_date = $date->getDatetimeNow();
			$log->save();

			$query="SELECT DISTINCT B.*,C.aud_title, A.aud_student_aud_id
							FROM aud_student AS A, user_info AS B,aud as C 
							WHERE user_class=".$class." AND B.user_id=A.aud_student_student_id AND C.aud_id=A.aud_student_aud_id  AND aud_student_event_id=".$event_id." ORDER BY aud_student_aud_id";
			$students_all=Yii::$app->db->createCommand($query)->queryAll();
			$auds=Aud::find()->all();
			return $this->render('seat-result', [
				'student' => $students_all,'event' => $event_id
				]);
			}
		}
	
	public function actionCheckSeat(){
		$auds=[];
		$class=$_POST['class'];
		$event_id=$_POST['event_id'];

		$query="SELECT DISTINCT B.*,C.aud_title, A.aud_student_aud_id
							FROM aud_student AS A, user_info AS B,aud as C 
							WHERE user_class=".$class." AND B.user_id=A.aud_student_student_id AND C.aud_id=A.aud_student_aud_id  AND aud_student_event_id=".$event_id." ORDER BY aud_student_aud_id";
			$students_all=Yii::$app->db->createCommand($query)->queryAll();
			$auds=Aud::find()->all();
			
			return $this->render('seat-result', [
				'student' => $students_all,'event' => $event_id
				]);
	}	


	public function actionSQL(){
		$class=10;
		$event_id=1;
		$query="SELECT A.user_id FROM register_event AS A,user_info AS B WHERE user_class=".$class." AND A.user_id=B.user_id AND register_status=1 AND event_id=".$event_id." ORDER BY user_school";
		$STH=Yii::$app->db->createCommand($query)->queryAll();
		if(count($STH)>0){
			foreach($STH as $item){
				print_r($item);
				echo $item['user_id'];
				echo "<br>";
			}
		}
	}

	public function actionSeatInterface($event=0){
		$auds=Aud::find()->all();
		return $this->render('seat-interface', [
            'auds' => $auds,'event' => $event
        ]);
	}

	public function actionSeatCheck($event=0){
		$auds=Aud::find()->all();
		return $this->render('seat-check', [
            'auds' => $auds,'event' => $event
        ]);
	}
	/*function actionImport(){
		set_time_limit(200);
	
		  $model=SchoolMain::find()->all();
		 	foreach($model as $item){
				$school=new School();
				$district=new District();
				$school->school_name=$item->school_main_name;
				$school->school_district=$district->findIDByName($item->school_main_district);
				$school->save();
			}
			echo 'Ok!';
		}*/
		
}
?>
			
			
			



