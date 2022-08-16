<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Aud */

$this->title = 'Create Aud';
$this->params['breadcrumbs'][] = ['label' => 'Auds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aud-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
