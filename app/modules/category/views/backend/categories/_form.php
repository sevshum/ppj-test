<?php

use app\modules\category\controllers\backend\CategoriesController;
use app\modules\category\models\Category;
use app\modules\core\helpers\App;
use yii\widgets\ActiveForm;

/* @var $this CategoriesController */
/* @var $model Category */
/* @var $form ActiveForm */
?>
<br />
<?php $form = ActiveForm::begin([
    'id' => 'category-' . $model->id,
]); ?>
    <?= $form->field($model, 'code'); ?>
    <?= $form->field($model, 'name'); ?>

<?= App::saveButtons($model); ?>

<?php ActiveForm::end(); ?>