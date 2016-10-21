<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\page\models\Page */

$this->title = Yii::t('app', 'Create Post');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['admin']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?= $this->render('_form', ['model' => $model]) ?>