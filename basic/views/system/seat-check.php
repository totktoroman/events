<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="row">
	<div class="col-md-3">
	<?php $form = ActiveForm::begin(['action'=>'index.php?r=system%2Fcheck-seat']); ?>
		<input type="hidden" name="event_id" value=<?=$event?> >
		<select class="form-control" name="class">
		<?php 
            for( $i=1; $i<12; $i++){
			    echo '<option value='.$i.'>'.$i.' класс</option>';
 		    } 
        ?>
		</select>
		<div class="form-group">
        	<br><?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
    	</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
