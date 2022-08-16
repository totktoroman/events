<?php
use app\models\RegistrEvent;
$this->title = 'Мероприятия';
$this->params['breadcrumbs'][] = $this->title;
// $formatter['locale']='ru-RU';
date_default_timezone_set('Atlantic/Reykjavik');
//echo Yii::$app->formatter->asDateTime('now','');
$dt=Yii::$app->formatter->asDateTime('now','long');
$now=strtotime( date("Y-m-d H:i:s")." +8 hour");

echo '<br>';

function  hour ($ss)
{
		$h = idate('H', $ss);
		$m = idate('i', $ss);
		$d = idate('d', $ss);
		$mon = idate('m', $ss);
		$y = idate('Y', $ss);
		if($d<"10") {$dd='0'.$d;} else {$dd=$d;}
		if($mon<"10") {$month='0'.$mon;} else {$month=$mon;}	
		if($m<"10") { $mm = "0".$m; } else { $mm = $m; }
		if($h<"10") {$hh='0'.$h;} else {$hh=$h;} 
		$date =  $dd.'.'.$month.'.'.$y.'<br>'.
									$hh.":".$mm;
    return ($date);
}

function getDatetimeNow() {
    $tz_object = new DateTimeZone('Asia/Irkutsk');
    $datetime = new DateTime();
    $datetime->setTimezone($tz_object);
    return $datetime->format('Y\-m\-d\ H:i:s');
}


$time = strtotime("+8 hour");
$fecha = date("Y-m-d H:i:s", $time);

// echo '<a href="https://www.timeanddate.com/worldclock/fixedtime.html?day='.idate('d',$now).'&amp;month='.idate('m',$now).'&amp;year='.idate('y',$now).'&amp;hour='.idate('H',$now).'&amp;min='.idate('i',$now).'&amp;sec=0&amp;p1=2220" target="_blank">
// '.$hour.'<sup title="часовой пояс" style="font-size:8px;">UTC+8</sup></a>';
?>
<div class="container">
<p>
	Уважаемые посетители, мы рады приветствовать вас в системе регистрации на мероприятиях.<br>
	Для того, чтобы записаться на мероприятие вам нужно создать аккаунт в нашей системе.<br> 
	Настоятельно рекомендуем ознакомиться с <a href="/index.php?r=event/instruction" target="_blank">инструкцией</a>.	
</p>
<h3>Список мероприятий:</h3>
<?php	
	echo '<br>';
	echo '<br>';
	echo '<table class="table table-dark table-hover">';
	echo '<tr><th>Название</th><th>Начало регистрации</th><th>Конец регистрации</th>';
	if($btn==1){
		echo '<th>Статус</th>';
		echo '<th> </th>';
		echo '<th> </th>';
	}
	if($btn==2){
		echo '<th>Регистрация</th>';
	}
	echo '</tr>';
	foreach($event as $item){
		echo '<tr>';
		
		echo '<td><a href="index.php?r=event%2Fevent-info&event='.$item->event_id.'">'.$item->event_title.'</a></td>';
		
		
		$timestamp = strtotime(Yii::$app->formatter->asDateTime($item->event_start_date,'short'));
		$event_begin=hour($timestamp);
		echo '<td>'.$event_begin.'</td>';

		$timestamp = strtotime(Yii::$app->formatter->asDateTime($item->event_register_end,'short'));
		$event_register_end=hour($timestamp);
		echo '<td>'.$event_register_end.'</td>';
		
		//	echo '<td>'.Yii::$app->formatter->asDateTime($item->event_start_date,'long').'</td>';
					
		//	echo '<td>'.Yii::$app->formatter->asDateTime($item->event_register_end,'short').'</td>';
		// $timestamp = strtotime(Yii::$app->formatter->asDateTime($item->event_register_end,'short'));
		// $register_end=hour($timestamp);
		// echo '<td><a href="https://www.timeanddate.com/worldclock/fixedtime.html?day='.idate('d',$timestamp).'&amp;month='.idate('m',$timestamp).'&amp;year='.idate('y',$timestamp).'&amp;hour='.idate('H',$timestamp).'&amp;min='.idate('i',$timestamp).'&amp;sec=0&amp;p1=2220" target="_blank">
		// '.$register_end.'<sup title="часовой пояс" style="font-size:8px;">UTC+8</sup></a></td>';
		
		if($btn==1){
			echo '<td>'.$item->getStatus().'</td>';
			echo '<td></td>';
			echo '<td>
			<a class="btn button-update" href="index.php?r=event%2Fevent-form&event='.$item->event_id.'" >Редактировать </a>
			<a href="index.php?r=event%2Fdelete-event&event='.$item->event_id.'" class="button-delete">Удалить</a>
			<a href="index.php?r=event-member%2Fevent-member&event='.$item->event_id.'" class="button-update">Заявки на регистрацию</a>
			</td>';

			
		}
		
		if($btn==2){
			$reg=RegistrEvent::find()->where(['user_id' => Yii::$app->user->identity->user_id, 'event_id' => $item->event_id])->one();
				if( $fecha <= $item->event_register_end ){
					if(is_object($reg)  &&  $reg->register_status != 5){
					echo '<td>
						<a href="index.php?r=event%2Fcancel-registr&event='.$item->event_id.'" class="btn btn-warning">Отменить регистрацию</a><br>'
						.$reg->getStatusForUser().
						'</td>';
					}
					else{
						if(is_object($reg)){
							echo '<td>'
							.$reg->getStatusForUser().
							'</td>';
							
						}
						else{
							echo '<td>
								Регистрация на странице мероприятия
							</td>';
					}
				}
				}
				else{
					echo '<td> Регистрация на мероприятие заврешена <br>';
					if(is_object($reg)){
						echo $reg->getStatusForUser();
					}
					echo '</td> ';
				}
		}
		echo '</tr>';
	}
	echo '</table>';

	if($btn==1){
		echo ' <a class="button-add" href="index.php?r=event%2Fevent-form" > Добавить </a>';
	}

?>
</div>
