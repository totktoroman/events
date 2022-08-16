<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Aud */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="aud-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'aud_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aud_count')->textInput() ?>

    <input type="hidden" name="item_id" value="<?=$model->aud_id?>">

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
