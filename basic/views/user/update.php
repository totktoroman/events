<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Update User: ' . $model->user_login;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_login, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
echo $model->user_id;
?>

<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>
  

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
