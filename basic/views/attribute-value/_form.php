<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Attribute;

/* @var $this yii\web\View */
/* @var $model app\models\AttributeValue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="attribute-value-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
    $attr_value = Attribute::find()->all();
    $value = array();
    foreach($attr_value as $v){
        $value[$v->attribute_id] =  $v->attribute_name;
    }
    ?>
    <?= $form->field($model, 'attribute_id')->dropDownList($value) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
