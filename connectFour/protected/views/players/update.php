<?php
/* @var $this PlayersController */
/* @var $model Players */

$this->breadcrumbs=array(
	'Players'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Players', 'url'=>array('index')),
	array('label'=>'Create Players', 'url'=>array('create')),
	array('label'=>'View Players', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Players', 'url'=>array('admin')),
);
?>

<h1>Update Players <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>