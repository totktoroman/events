<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AttributeValue */

$this->title = 'Update Attribute Value: ' . $model->attribute_value_id;
$this->params['breadcrumbs'][] = ['label' => 'Attribute Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->attribute_value_id, 'url' => ['view', 'id' => $model->attribute_value_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="attribute-value-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
