<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Aud */

$this->title = 'Update Aud: ' . $model->aud_title;
$this->params['breadcrumbs'][] = ['label' => 'Auds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->aud_title, 'url' => ['view', 'id' => $model->aud_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="aud-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
