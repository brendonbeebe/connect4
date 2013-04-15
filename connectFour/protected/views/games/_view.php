<?php
/* @var $this GamesController */
/* @var $data Games */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('board')); ?>:</b>
	<?php echo CHtml::encode($data->board); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('player1_id')); ?>:</b>
	<?php echo CHtml::encode($data->player1_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('player2_id')); ?>:</b>
	<?php echo CHtml::encode($data->player2_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('whosTurn')); ?>:</b>
	<?php echo CHtml::encode($data->whosTurn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
	<?php echo CHtml::encode($data->active); ?>
	<br />


</div>