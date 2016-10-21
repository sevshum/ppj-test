<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\category\models\Category */

$this->title = 'Create Category';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['admin']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?= $this->render('_form', ['model' => $model]) ?>