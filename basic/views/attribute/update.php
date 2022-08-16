<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Attribute */

$this->title = 'Update Attribute: ' . $model->attribute_id;
$this->params['breadcrumbs'][] = ['label' => 'Attributes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->attribute_id, 'url' => ['view', 'id' => $model->attribute_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="attribute-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
