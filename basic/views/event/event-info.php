
<?php
use app\models\RegistrEvent;
use app\models\School;
use phpDocumentor\Reflection\Location;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Attribute;
use app\models\EventAttribute;
use app\models\UserAttribute;
use app\models\AttributeValue;

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
                	&nbsp;&nbsp;&nbsp;

				<a href="index.php?r=system%2Fseat-check&event=<?=$event->event_id?>" class="btn btn-info">Текущая рассадка</a>&nbsp;&nbsp;&nbsp;
				
				<a href="index.php?r=attribute%2Fcreate&event=<?=$event->event_id?>" class="btn btn-info">Добавить поле для регистрации</a>&nbsp;&nbsp;&nbsp;

				<a href="index.php?r=event-member%2Fevent-member&event=<?=$event->event_id?>" class="btn btn-info">Список участников</a>&nbsp;&nbsp;&nbsp;

				<a href="index.php?r=system%2Fseat-interface&event=<?=$event->event_id?>" class="btn btn-info">Произвести рассадку</a>&nbsp;&nbsp;&nbsp;

				<br>

                <?php endif; ?>
				
				<?php 
					$time = strtotime("+6 hour");
					$fecha = date("Y-m-d H:i:s", $time);
				
					if($fecha <= $event->event_register_end):
				?>		
					<p><a href="index.php?r=event%2Finstruction" target="_blank" class="btn button-update" style="margin-left:39%">Инструкция по регистрации</a></p>
						<?php if(Yii::$app->user->isGuest==false): ?>
							 
							
							<div class="row">
								<div class="col-lg-1"></div>
								<div class="col-lg-10">
									<h2 class="text-center">Регистрация</h2>		
									<?php  
								
									$reg_event = RegistrEvent::find()->where(['event_id' => $event->event_id, 'user_id' => Yii::$app->user->identity->user_id])->one();
									$form = ActiveForm::begin(['action'=>'index.php?r=event%2Fregistr&event='.$event->event_id]);

									foreach ($user_attribute as $index => $setting)	 {
										$attr = Attribute::findOne($setting->attribute_id);

										if($attr->attribute_type == "DropdownList"){
											$attr_value = AttributeValue::find()->where(['attribute_id' => $attr->attribute_id])->all();
											$value = array();
											foreach($attr_value as $v){
												$value[$v->value] =  $v->value;
											}
											echo $form->field($setting, "[$index]value")->dropDownList($value)->label($attr->attribute_name);
											
										}
										else{
											echo $form->field($setting, "[$index]value")->label($attr->attribute_name);
										}

									}
									
									?>
								<div class="form-group">
									<?= Html::submitButton('Записаться', ['class' => 'btn btn-success']) ?>
								</div>
								</div>
								<div class="col-lg-1"></div>
							</div>
							<?php ActiveForm::end();	?>
						
							
							
							
						<?php else: ?>   
							<a href="index.php?r=site%2Flogin" class="btn button-add" style="margin-left:44%" >Регистрация</a>&nbsp;&nbsp;&nbsp;
						<?php endif; ?>
					
					<?php else: ?>   
						<br>
						<h4 class="text-center" style="color:red">Регистрация на мероприятие заврешена</h4>
					<?php endif; ?>
					
					
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

				
				
				
			
	

				



				


