<?php

namespace app\controllers;

use Yii;
use app\models\Aud;
use app\models\AudSearch;
use app\models\Log;
use app\models\Time;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;





use yii\filters\AccessControl;


/**
 * AudController implements the CRUD actions for Aud model.
 */
class AudController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Aud models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Aud model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Aud model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Aud();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $log = new Log();
            $log->user_id = Yii::$app->user->identity->user_id;
            $log->log_action = Yii::$app->user->identity->user_login." добавил в таблицу аудиторию ".$model->aud_title;
            $date = new Time();
            $log->log_date = $date->getDatetimeNow();
            $log->save();
            

            return $this->redirect(['view', 'id' => $model->aud_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Aud model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $model = $this->findModel($id);
        $old_data = Aud::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $log = new Log();
            $log->user_id = Yii::$app->user->identity->user_id;
            $log->log_action = Yii::$app->user->identity->user_login." изменил данные аудитории ".$model->aud_title.".
                                Предыдущие данные: название ".$old_data->aud_title.", вместимость ".$old_data->aud_count;
                                
            $date = new Time();
            $log->log_date = $date->getDatetimeNow();
            $log->save();

            return $this->redirect(['view', 'id' => $model->aud_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Aud model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($this->findModel($id)->delete()){

            $log = new Log();
            $log->user_id = Yii::$app->user->identity->user_id;
            $log->log_action = Yii::$app->user->identity->user_login." удалил аудиторию ".$model->aud_title;
            $date = new Time();
            $log->log_date = $date->getDatetimeNow();
            $log->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Aud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Aud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Aud::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
