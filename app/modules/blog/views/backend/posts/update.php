<?php

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\Post */

$this->title = Yii::t('app', 'Update "{title}"', ['title' => $model->getI18n('title')]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['admin']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>