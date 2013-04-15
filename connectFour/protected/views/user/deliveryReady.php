<?php
/* @var $this DeliveryReadyFormController */
/* @var $model DeliveryReadyForm */
/* @var $form CActiveForm */
?>

<div class="form">
    <?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'delivery-ready-form-deliveryReady-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'address_to'); ?>
		<?php echo $form->textField($model,'address_to'); ?>
		<?php echo $form->error($model,'address_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address_from'); ?>
		<?php echo $form->textField($model,'address_from'); ?>
		<?php echo $form->error($model,'address_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_from'); ?>
		<?php echo $form->textField($model,'time_from'); ?>
		<?php echo $form->error($model,'time_from'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'time_to'); ?>
        <?php echo $form->textField($model,'time_to'); ?>
        <?php echo $form->error($model,'time_to'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->