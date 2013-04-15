<?php
/* @var $this PlayersController */
/* @var $data Players */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('esl')); ?>:</b>
	<?php echo CHtml::encode($data->esl); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('waiting')); ?>:</b>
	<?php echo CHtml::encode($data->waiting); ?>
	<br />


</div>