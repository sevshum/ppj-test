<?php

use app\modules\core\helpers\App;
use app\modules\comment\controllers\backend\CommentsController;
use app\modules\comment\models\Comment;
use yii\widgets\ActiveForm;

/* @var $this CommentsController */
/* @var $model Comment */
/* @var $form ActiveForm */
?>
<br />
<?php $form = ActiveForm::begin([
    'id' => 'comment-' . $model->id,
    
]); ?>
    <?= $form->field($model, 'author_name'); ?>
    <?= $form->field($model, 'author_email'); ?>
    <?= $form->field($model, 'content')->textarea(['rows' => 5]); ?>
    <?= $form->field($model, 'request_info')->textarea(['disabled' => true]); ?>
    <?= $form->field($model, 'created_at')->textInput(['disabled' => true]); ?>
    <?= $form->field($model, 'status')->dropDownList(Comment::statuses()); ?>
    <?= App::saveButtons($model); ?>

<?php ActiveForm::end(); ?>