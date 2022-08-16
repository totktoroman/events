<?php
	use app\models\School;
	$this->title = 'Анкета';
	$this->params['breadcrumbs'][] = $this->title;
	echo '<br>';
	echo '<br>';
	echo '<table class="table table-striped">';
		echo '<tr>';
		echo '<td>Фамилия:</td><td>'.$model->user_family.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Имя:</td><td>'.$model->user_name.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Отчество:</td><td>'.$model->user_patronymic.'</td>';
		echo '</tr>';
		echo '<tr>';
		if(is_object(School::findone($model->user_school)))
			echo '<td>Школа:</td><td>'.School::findone($model->user_school)->getName().'</td>';
		else
			echo '<td>Школа:</td><td style="color:red">Укажите школу</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Класс:</td><td>'.$model->user_class.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Телефонный номер:</td><td>'.$model->user_phonenumber.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Электронная почта:</td><td>'.Yii::$app->formatter->asEmail($model->user_email).'</td>';
		echo '</tr>';
		
	
	echo '</table>';
	echo '<a href="index.php?r=info%2Fuser-form&user='.$model->user_id.'" class="btn btn-warning"> Редактировать </a>';



?>