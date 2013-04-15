<?php
/* @var $this PlayersController */
/* @var $model Players */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'players-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'esl'); ?>
		<?php echo $form->textField($model,'esl',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'esl'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'waiting'); ?>
		<?php echo $form->textField($model,'waiting',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'waiting'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->