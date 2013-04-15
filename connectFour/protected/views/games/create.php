<?php
/* @var $this GamesController */
/* @var $model Games */

$this->breadcrumbs=array(
	'Games'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Games', 'url'=>array('index')),
	array('label'=>'Manage Games', 'url'=>array('admin')),
);
?>

<h1>Create Games</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>