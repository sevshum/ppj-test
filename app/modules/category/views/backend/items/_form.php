<?php

use app\modules\category\models\CategoryItem,
    yii\widgets\ActiveForm,
    yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $item CategoryItem */
/* @var $form ActiveForm */
$formId = 'category-item-' . $item->id;
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => $formId,
        'enctype' => 'multipart/form-data'
    ],
    'action' => ['/category/items/edit', 'categoryId' => $item->category_id, 'id' => $item->id],
]); ?>

<?= $this->render('_i18ns', ['model' => $item, 'form' => $form]) ?>
<?= $form->field($item, 'parent_id')->dropDownList($parents, ['encodeSpaces' => true]) ?>
<?= $form->field($item, 'code') ?>
<?php // $form->field($item, 'url') ?>

<?= Html::submitButton($item->getIsNewRecord() ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>
<?php $this->registerJs('    
    $("#' . $formId . '").submit(function() {
        var form = $(this),
            submitButton = form.find(":submit");
        if (submitButton.hasClass("disabled")) {
            return false;
        }
        form.data("dataType", "json");
        submitButton.attr("disabled", "disabled").addClass("disabled");
        CMS.submitForm(form, function(err, rsp) {
            if (err) {
                console.log(err);
                submitButton.removeAttr("disabled").removeClass("disabled");
                return;
            } else if (rsp.target && rsp.html) {
                $(rsp.target).html($(rsp.target, $("<div>" + rsp.html + "</div>")).html());
            }
            if (rsp.success) {
                CMS.popup.el.modal("hide");
            }
        });
        return false;
    }); 

');

