
<?php
use app\models\RegistrEvent;
use app\models\School;
use phpDocumentor\Reflection\Location;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Attribute;
use app\models\UserAttribute;
$this->title = $event->event_title;
$this->params['breadcrumbs'][] = ['label' => 'Мероприятия', 'url' => ['events']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$event->event_title?></title>
	

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
	<?= $this->head() ?>
</head>
	



<body class = "background">
<?= $this->beginBody() ?>
<div>
				<div class="block-content" style="max-width: 1000px;margin: 0 auto;">     
					<?=$event->event_info?>
				</div>
    
				<br>
			
                <?php if ($btn==1): ?>
                <a href="index.php?r=system%2Fseat-interface&event=<?=$event->event_id?>" class="btn btn-info">Сгенерировать рассадку по аудиториям</a>&nbsp;&nbsp;&nbsp;

				<a href="index.php?r=system%2Fseat-check&event=<?=$event->event_id?>" class="btn btn-info">Текущая рассадка</a>&nbsp;&nbsp;&nbsp;
				
				<a href="index.php?r=attribute%2Fcreate&event=<?=$event->event_id?>" class="btn btn-info">Добавить поле для регистрации</a>&nbsp;&nbsp;&nbsp;

				<a href="index.php?r=event-member%2Fevent-member&event=<?=$event->event_id?>" class="btn btn-info">Список участников</a>
				<br>

                <?php endif; ?>

				
				<div> 
					<!-- <h2>Список участников</h2>
					<table class="table table-striped">
						<tr><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Школа</th><th>Класс</th><th>Телефонный номер</th></tr>
						<?php
						// foreach($users as $item){
						// 		$reg=RegistrEvent::find()->where(['user_id' => $item->user_id, 'event_id' => $event->event_id])->one();
						// 		if(is_object($reg)  &&  $reg->register_status == 1){
						// 			echo '<tr>';
						// 			echo '<td>'.$item->user_family.'</td>';
						// 			echo '<td>'.$item->user_name.'</td>';			
						// 			echo '<td>'.$item->user_patronymic.'</td>';
						// 			if(is_object(School::findone($item->user_school)))
						// 				echo '<td>'.School::findone($item->user_school)->getName().'</td>';
						// 			else
						// 				echo '<td style="color:red">Unnamed school</td>';
						// 			echo '<td>'.$item->user_class.'</td>';
						// 			echo '<td>'.$item->user_phonenumber.'</td>';	

						// 			echo '</tr>';
						// 	}
						// }
						?>
					</table> -->
					<h2 class="text-center">Регистрация </h2>		
					<?php $item=Attribute::find()->where(['event_id' => $event->event_id])->all(); ?> 
				    <?php $form = ActiveForm::begin(['action'=>'index.php?r=user-attribute%2Fform-process']); ?>
					
					<?php foreach($item as $attr){
							$model=UserAttribute::find()->where(['user_id' => Yii::$app->user->identity->user_id, 'attribute_id' => $attr->attribute_id])->one();
							if(!is_object($model)){
								$model=new UserAttribute();
							}
							if($attr->attribute_name == "Форма участия"){
								echo $form->field($model, 'value')->dropDownList([
									'Дистанционно' => 'Дистанционно',
									'Очно' => 'Очно',	])->label($attr->attribute_name);
								}
							else
								echo $form->field($model, 'value')->label($attr->attribute_name);
							echo $form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->identity->user_id])->label(false);
							echo $form->field($model, 'attribute_id')->hiddenInput(['value'=> $attr->attribute_id])->label(false);
							echo '<input type="hidden" name="event_id" value='.$event->event_id.'>';
							echo '<input type="hidden" name="user_id" value='.Yii::$app->user->identity->user_id.'>';
							echo '<input type="hidden" name="attr_id" value='.$attr->attribute_id.'>';
						}
					?> 


				   <div class="form-group">
					   <?= Html::submitButton('Записаться', ['class' => 'btn btn-success']) ?>
    			   </div>
				   <?php ActiveForm::end(); ?>
				   
					
						
						

				</div>
	
		<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<div class="form-group text-right">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
					</div>
				</div>
				<div class="modal-body text-center">
					<div class="form-group text-center">
							<p class="text-center size-24 text-success"><b>Вы успешно записались!</b></p>
					</div>
				</div>
				
				</div>
			</div>
      </div>
      <!-- Modal -->
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/salvattore.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
	</div>
	<?= $this->endBody() ?>
	</body>

</html>

				
				
				
			
	

				



				


