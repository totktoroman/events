/**
* вью принимает на вход массив студентов и выводит его на экран
* @param $students Array - коллекция студентов
*/
<table >
	<tr>
		<td>Имя</td>
	</tr>
<?php foreach($students as $student):?>
<tr>
<td><?=$student->student_name?></td>
</tr>
<?php endforeach;?>
</table>
//<?=$this->render("../widgets/students_table",['students'=>$students]);?>