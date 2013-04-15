<?php
/* @var $this GamesController */
/* @var $model Games */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'board'); ?>
		<?php echo $form->textField($model,'board',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'player1_id'); ?>
		<?php echo $form->textField($model,'player1_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'player2_id'); ?>
		<?php echo $form->textField($model,'player2_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'whosTurn'); ?>
		<?php echo $form->textField($model,'whosTurn'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'active'); ?>
		<?php echo $form->textField($model,'active',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->