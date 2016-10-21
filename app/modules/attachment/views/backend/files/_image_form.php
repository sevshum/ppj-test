<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<li class="col-md-4 col-xs-6 image-item file-<?= $model->id ?>" data-id="<?= $model->id ?>" id="ids-<?= $model->id ?>">
    <a class="close" data-op="ajax" class="image-delete" href="<?= Url::toRoute(['/attachment/files/delete', 'id' => $model->id]) ?>" data-confirming="Are you ready to delete?">&times;</a>
    <?= Html::img($model->getImageUrl('file', 'image_media_preview'), ['class' => 'img-rounded']) ?>
    
<?php $form = ActiveForm::begin([    
    'options' => ['class' => 'image-item-form pull-left', 'id' => "file-{$model->id}-form"],
    'action' => ['/attachment/files/update', 'id' => $model->id]
]); ?>
    <?php foreach ($model->getAdditionalParams() as $key => $value) : ?>
    <?= Html::activeHiddenInput($model, $key, ['id' => "{$key}-{$model->id}", 'name' => 'Attachment[additionalparams][' . $key . ']']); ?>
    <?php endforeach; ?>
<?php ActiveForm::end(); ?>
</li>

