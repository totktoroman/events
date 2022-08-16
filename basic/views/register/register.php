<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
 
$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(['action'=>'index.php?r=register%2Fregister-process']); ?>
    <?= $form->field($model, 'user_login')->label("Имя пользователя")?>
    <?php 
        if ($err==2){
            echo "<b style='color:red'>Имя пользователя уже используется</b>";
        }
    
    ?>
    <?= $form->field($model, 'user_password') ->label("Пароль")->passwordInput()?>
    <b>Повторите пароль</b>
    <input type="password" name="password_repeat" class="form-control" ><br>
    <?php 
        if ($err==1){
            echo "<b style='color:red'> Пароли не совпадают </b>";
        }
    
    ?>
   <input type="hidden" name="status_id" value="<?=2?>">
    
    
	<div class="form-group">
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>