<?php


/* @var $this yii\web\View */
/* @var $model app\modules\comment\models\Comment */

$name = $model->entity_type . '-' . $model->entity_id;
$this->title = Yii::t('app', 'Update "{title}"', ['title' => $name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Comments'), 'url' => ['admin']];
$this->params['breadcrumbs'][] = $name;
?>

<?= $this->render('_form', ['model' => $model]) ?>

