<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Event;
use kartik\datetime\DateTimePicker;

// echo '<label>Start Date/Time</label>';
// echo DateTimePicker::widget([
	//     'name' => 'datetime_10',
	//     'options' => ['placeholder' => 'Select operating time ...'],
	//     'convertFormat' => true,
	//     'pluginOptions' => [
		//         'format' => 'd-M-Y g:i A',
		//         'startDate' => '01-Mar-2014 12:00 AM',
		//         'todayHighlight' => true
		//     ]
// ]);
// ?>

<?php $form = ActiveForm::begin(['action'=>'index.php?r=event%2Fform-process']); ?>
    <?= $form->field($model, 'event_title')->label("Название")?>
		
    <?= $form->field($model, 'event_info')->textarea(['collumns'=>5])->label("Информация") ?>
	
	<?= $form->field($model, 'event_start_date')->input("datetime")->label("Дата начала мероприятия")->widget(DateTimePicker::class, [
	'options' => ['placeholder' => 'Enter event time ...'],
	'pluginOptions' => [
		'autoclose' => true
	]
]);?>
    
	<?= $form->field($model, 'event_register_end')->input("datetime")->label("Дата завершения регистрации")->widget(DateTimePicker::class, [
	'options' => ['placeholder' => 'Enter register time end ...'],
	'pluginOptions' => [
		'autoclose' => true
	]
]); ?>
	
	<?= $form->field($model, 'event_auto_acceptance')->checkbox([],false)->label("Принимать заявки автоматически") ?>

	<?= $form->field($model, 'is_active')->checkbox([],false)->label("Мероприятие активно") ?>
	
	<div class="row">
            <div class="col-lg-6">
				<h3>Дополнительная Информация: </h3>
				<?php $i=0; 
				foreach($attr as $item):?>
						<br>
						<label> <?=$item->attribute_name?> </label>
						<?php if($attr_checked[$i]): ?>
							<input type="checkbox" name="attr[]" value= <?=$item->attribute_id?>  checked = <?=$attr_checked[$i]?> >
						<?php else: ?>
							<input type="checkbox" name="attr[]" value= <?=$item->attribute_id?> >
						<?php endif; $i++; ?>
						<br>
						<?php endforeach;?>
			</div>
			
			
            <div class="col-lg-6">
			<h3>Организаторы: </h3>
				<?php $i = 0;
				foreach($org as $organizator):?>
						<br>
						<label> <?= $organizator->getInfo()?> </label>
						<?php if($org_checked[$i]): ?>
							<input type="checkbox" name="orgs[]" value= <?=$organizator->user_id?> checked = <?=$org_checked[$i]?> >
						<?php else: ?>
							<input type="checkbox" name="orgs[]" value= <?=$organizator->user_id?>  >
						<?php endif; $i++; ?>

						<br>
				<?php endforeach;?>
            </div>
            
    </div>
	<input type="hidden" name="item_id" value="<?=$model->event_id?>">
   
	<div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>
			