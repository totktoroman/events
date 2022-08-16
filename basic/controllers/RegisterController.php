<?php
namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;
use app\controllers\InfoController;
use app\models\Log;
use app\models\Time;

class RegisterController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                //'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

   
    
    function actionRegister(){
        $err=0; // условие сравнения паролей
        
        $model=new User();
        return $this->render("register",["model"=>$model, 'err'=>$err]);
    }
	function actionRegisterProcess(){
        $model=new User();
        $model->load(\Yii::$app->request->post());
        $item=User::find()->where(['user_login' => $model->user_login])->one();
        if(is_object($item)){
            $err=2; 
            return $this->render ("register",["model"=>$model,'err'=>$err]);

        }

        if ($model->user_password == $_POST['password_repeat']){

            
              
                if( $model->save() ){
                    $log = new Log();
                    $log->user_id = $model->user_id;
                    $log->log_action = $model->user_login." зарегистрировался в системе ";
                   
                    $date = new Time();
                    $log->log_date = $date->getDatetimeNow();
                    $log->save();
                }
                
            
            return $this->redirect('index.php?r=site%2Flogin');     
        }
        else {
            $err=1; 
            return $this->render ("register",["model"=>$model,'err'=>$err]);
        }
    }
	
	
}
?>