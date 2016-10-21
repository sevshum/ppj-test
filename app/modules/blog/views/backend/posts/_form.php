<?php

use app\modules\core\helpers\App,
    app\modules\blog\models\Post,
    kartik\widgets\ActiveForm,
    yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Post */
/* @var $form ActiveForm */

$model->published_at = $model->published_at ? $model->published_at : date('Y-m-d');
?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => true,
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]); ?>

<?= $this->render('_i18ns', ['model' => $model, 'form' => $form]) ?>
<?= $form->field($model, 'slug')->textInput(['maxlength' => 255]) ?>
<?= $form->field($model, 'stringTags')->widget(\kartik\widgets\Select2::className(), [
    'options' => ['multiple' => true],
    'pluginOptions' => [
        'tags' => $model->getAllTags(),
//        'asDropDownList' => false,
        'width' => '618px',
        'placeholder' => '',
        'tokenSeparators' => [','],
        'minimumInputLength' => 2,
    ]
]) ?>
<?= $form->field($model, 'category_id')->dropDownList(
    Yii::$app->getModule('category')->getListData('blog_category', false), 
    ['prompt' => Yii::t('app', 'Select category')]
); ?>

<?= $form->field($model, 'published_at')->widget(\kartik\widgets\DatePicker::className(), [
    'pluginOptions' => ['format' => 'yyyy-mm-dd']
]) ?>
<?= $form->field($model, 'image')->fileInput()->hint($model->image ? Html::img($model->getImageUrl('image', 'image_preview')) : '') ?>
<?= $form->field($model, 'status')->dropDownList($model::statuses()) ?>

<?= \app\modules\attachment\widgets\UploadImageWidget::widget(['model' => $model, 'form' => $form]) ?>


<?= App::saveButtons($model) ?>

<?php ActiveForm::end(); ?>
