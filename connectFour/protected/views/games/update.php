<?php
/* @var $this GamesController */
/* @var $model Games */

$this->breadcrumbs=array(
	'Games'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Games', 'url'=>array('index')),
	array('label'=>'Create Games', 'url'=>array('create')),
	array('label'=>'View Games', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Games', 'url'=>array('admin')),
);
?>

<h1>Update Games <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>