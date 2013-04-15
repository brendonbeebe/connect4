<?php
/* @var $this GamesController */
/* @var $model Games */

$this->breadcrumbs=array(
	'Games'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Games', 'url'=>array('index')),
	array('label'=>'Create Games', 'url'=>array('create')),
	array('label'=>'Update Games', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Games', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Games', 'url'=>array('admin')),
);
?>

<h1>View Games #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'board',
		'player1_id',
		'player2_id',
		'whosTurn',
		'active',
	),
)); ?>
