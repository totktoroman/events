<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\School;
use app\models\District;
use yii\jui\AutoComplete;
use kartik\select2\Select2;
$this->title = 'Редактирование информации';
$this->params['breadcrumbs'][] = ['label' => 'Анкета', 'url' => ['users']];
$this->params['breadcrumbs'][] = $this->title;
// Normal select with ActiveForm & model

?>
<?php $form = ActiveForm::begin(['action'=>'index.php?r=info%2Fform-process']); ?>
    <?= $form->field($model, 'user_family')->label("Фамилия")?>

    <?= $form->field($model, 'user_name')->label("Имя") ?>

		<?= $form->field($model, 'user_patronymic')->label("Отчество") ?>

		<?php 
		$school = School::find()->all();
		$items = ArrayHelper::map($school,'school_id','school_name');?>
    <?= $form->field($model, 'user_school')->label("Школа")->widget(Select2::class, [
    'data' => $items,
    'language' => 'ru',
    'options' => ['placeholder' => 'Укажите школу ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?> 

	
		<?= $form->field($model, 'user_class')->dropDownList([
    '1' => '1 класс',
    '2' => '2 класс',
		'3' => '3 класс',
		'4' => '4 класс',
    '5' => '5 класс',
		'6' => '6 класс',
		'7' => '7 класс',
    '8' => '8 класс',
		'9' => '9 класс',
		'10'=> '10 класс',
    '11'=> '11 класс',
		])->label("Класс")?>

		<?= $form->field($model, 'user_phonenumber') ->label("Номер телефона")?>	

		<?= $form->field($model, 'user_email') ->input('email')->label("Электронная почта")?>	
  
    <?= $form->field($model, 'soglasie')->radioList([1 => 'Я согласен(-сна) на обработку моих персональных данных (для участников 18 лет и старше)', 
                                                     2 => 'Я согласен(-сна) на обработку персональных данных несовершеннолетнего (для участников младше 18 лет)'])->label('Согласие'); ?>
    
		<input type="hidden" name="item_id" value="<?=$model->user_id?>">
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>