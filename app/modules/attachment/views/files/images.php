<?php

use yii\helpers\Url;
$context = $this->context;
?>
<?php if ($context->form && $context->model->getIsNewRecord()) : ?>
    <?= $form->field($context->model, 'temp_id', ['template' => '{input}'])->hiddenInput() ?>
<?php endif ?>
<?= $this->render($context->viewPathAlias . '/_form_upload', ['type' => $type, 'model' => $model, 'id' => $id, 'target' => '#images']); ?>
<div class="row">
    <div class="col-md-12">
        <ul id="images" class="clearfix row" data-sort="<?= Url::toRoute(['/attachment/files/sort'])?>">
        <?php foreach ($images as $image) { ?>
            <?= $this->render($context->viewPathAlias . '/_image_form', ['model' => $image]); ?>
        <?php } ?> 
        </ul>
    </div>
</div>
