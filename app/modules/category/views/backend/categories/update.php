<?php


/* @var $this yii\web\View */
/* @var $model app\modules\category\models\Category */

$this->title = 'Update "' . h($model->name) . '" Category';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['admin']];
$this->params['breadcrumbs'][] = $model->name;
?>

<?= $this->render('_form', ['model' => $model]) ?>

