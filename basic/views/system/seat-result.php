<?php 
use app\models\School;
?>

<?php
echo '<br>';
	echo '<br>';
	echo '<table class="table table-striped">';
	echo '<tr><th>Аудиория</th><th>Фамилия</th><th>Имя</th> <th>Отчество</th> <th>Школа</th><th>Класс</th></tr>';
	foreach($student as $fio){
				echo '<tr>';
				echo '<td>'.$fio['aud_title'].'</td>';
				echo '<td>'.$fio['user_family'].'</td>';
				echo '<td>'.$fio['user_name'].'</td>';
				echo '<td>'.$fio['user_patronymic'].'</td>';
				if(is_object(School::findone($fio['user_school'])))
				echo '<td>'.School::findone($fio['user_school'])->getName().'</td>';
				else
				echo '<td style="color:red">Unnamed school</td>';
				echo '<td>'.$fio['user_class'].'</td>';
				echo '</tr>';
	}
  echo '</table>';

	
?>