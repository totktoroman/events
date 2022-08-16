<?php
namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\UserInfo;
use app\models\User;	
use app\models\Sagaalgan;
use app\models\Log;
use app\models\Time;
class InfoController extends Controller 
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
				'only' => ['admin-users'],
                'rules' => [
                    [
						'allow' => true,
                        'actions' => ['admin-users'],
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
                        }
                    ],
                ],
            ],
        ];
    }
	function actionUsers(){

			$item=UserInfo::find()->where("user_id=".Yii::$app->user->identity->user_id)->one();
			if(is_null($item))
			{
				$item=new UserInfo();
				return $this->render("user-form",["model"=>$item]);
			}

			else
			{
				return $this->render("users",["model"=>$item]);
			
			}

		}
	
		function actionAdminUsers($id=0){

			$item=UserInfo::find()->where("user_id=".$id)->one();
			if(is_null($item))
			{
				$item=new UserInfo();
				return $this->render("user-form",["model"=>$item]);
			}

			else
			{
				return $this->render("users",["model"=>$item]);
			
			}

		}

		function actionUserForm($user=0){
			$user1 = Yii::$app->user->identity;
            $status = $user1->getStatusTitle();
			if($status == "ADMINISTRATOR"){
				$item=UserInfo::find()->where("user_id=".$user)->one();
			}
			else{
				$item=UserInfo::find()->where("user_id=".$user1->user_id)->one();
			}
			if(!is_object($item)){
				$item=new UserInfo();
			}		
			return $this->render("user-form",["model"=>$item]);
		}

		function actionFormProcess(){
			$item=new UserInfo();
			$f = 0;
			if (is_numeric($_POST['item_id'])){
				//если передано значение прогружаем
				// $item=UserInfo::findOne($_POST['item_id']);
				// $item=UserInfo::find()->where("user_id=".$i)->all();
				$item=UserInfo::find()->where("user_id=".$_POST['item_id'])->one();
				$f=1;
				// $item->user_id=User::getId();	
			}
			else{
				$item->user_id=Yii::$app->user->identity->user_id;
			}
			

			if($item->load(\Yii::$app->request->post()) && $item->save() ){
				$log = new Log();
				$log->user_id = Yii::$app->user->identity->user_id;
				if($f)
					$log->log_action = Yii::$app->user->identity->user_login." изменил персональные данные ". User::findOne($_POST['item_id'])->getLogin();
				else
				$log->log_action = Yii::$app->user->identity->user_login." внес персональные данные ";
				$date = new Time();
				$log->log_date = $date->getDatetimeNow();
				$log->save();
			}

			return $this->render("users",["model"=>$item]);
		}

		function actionIndex(){
			$user=Yii::$app->user->identity->user_id;
			
	 }
	/* function actionImport(){
		set_time_limit(200);
		function translit($str) {
				$rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
				$lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
				return str_replace($rus, $lat, $str);
			}
		  $model=Sagaalgan::find()->all();
			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		 	foreach($model as $item){
				$user=new User();
				$user->user_login=translit($item->surname.' '.$item->name.' '.$item->patronymic);
				$user->user_password=substr(str_shuffle($permitted_chars), 0, 10);
				$user->save();
				
				$user2=new UserInfo();
				$user2->user_id=$user->user_id;
				$user2->user_family=$item->surname;
				$user2->user_name=$item->name;
				$user2->user_patronymic=$item->patronymic;
				$user2->user_school=$item->school;
				$user2->user_class=$item->class;
				$user2->user_phonenumber=228;
				$user2->save();	
			}
			echo 'Ok!';
		}*/
}
?>
			
			
			



