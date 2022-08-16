<?php
use app\models\RegistrEvent;
use app\models\School;
use app\models\Attribute;
use app\models\UserAttribute;


echo '<br>';
	echo '<h2> Всего заявок: '.count($members).'</h2>';
	echo '<br>';
	
	echo '<table class="table table-striped">';
	echo '<tr><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Школа</th><th>Класс</th><th>Почта</th><th>Дата регистрации</th><th>Статус</th>';
	foreach($attribute as $attr){
			echo '<th>'.Attribute::getName($attr->attribute_id).'</th>';
	}
	echo '<th>Изменить заявку</th><th></th></tr>';
	foreach($members as $fio){
				$reg_event=RegistrEvent::find()->where(['user_id' => $fio['user_id'], 'event_id' => $event])->one();
				
				echo '<tr>';
				echo '<td>'.$fio['user_family'].'</td>';
				echo '<td>'.$fio['user_name'].'</td>';
				if(is_null($fio['user_patronymic']))
					echo '<td>Нет Отчества</td>';
				else
					echo '<td>'.$fio['user_patronymic'].'</td>';
				if(is_object(School::findone($fio['user_school'])))
					echo '<td>'.School::findone($fio['user_school'])->getName().'</td>';
				else
					echo '<td style="color:red">Unnamed school</td>';
				echo '<td>'.$fio['user_class'].'</td>';
				echo '<td>'.$fio['user_email'].'</td>';
				echo '<td>'.$reg_event->getRegisterDate().'</td>';
				echo '<td>'.$reg_event->getStatus().'</td>';

				foreach($attribute as $attr){
					$user_attribute = UserAttribute::find()->where(['register_event_id' => $reg_event['register_event_id'], 'attribute_id' => $attr['attribute_id'] ])->one();
					if(is_object($user_attribute) ){
						echo '<td>'.$user_attribute->value.'</td>';
					}
					else 
						echo "<td>empty attr</td>";
				}

				
				echo '<td><a href="index.php?r=event-member%2Faccept-registr&event='.$event.'&user='.$fio['user_id'].'" class="btn btn-success">Принять</a>
									<a href="index.php?r=event-member%2Fcancel-registr&event='.$event.'&user='.$fio['user_id'].'" class="btn btn-warning">Отклонить</a><td>';
				echo '</tr>';
			}
  echo '</table>';

	
	
?>