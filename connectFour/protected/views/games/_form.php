<?php
/* @var $this GamesController */
/* @var $model Games */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'games-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'board'); ?>
		<?php echo $form->textField($model,'board',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'board'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'player1_id'); ?>
		<?php echo $form->textField($model,'player1_id'); ?>
		<?php echo $form->error($model,'player1_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'player2_id'); ?>
		<?php echo $form->textField($model,'player2_id'); ?>
		<?php echo $form->error($model,'player2_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'whosTurn'); ?>
		<?php echo $form->textField($model,'whosTurn'); ?>
		<?php echo $form->error($model,'whosTurn'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->textField($model,'active',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'active'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->