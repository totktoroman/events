<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Attribute */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="attribute-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'attribute_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'attribute_type')->dropDownList([
        'DropdownList' => 'Выпадающий список',
        'String' => 'Строка'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
