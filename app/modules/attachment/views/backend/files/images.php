<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

?>
<?= $this->render('_form_upload', ['type' => $type, 'model' => $model, 'id' => $id, 'target' => '#images']); ?>
<div class="row">
    <div class="col-md-8">
        <ul id="images" class="clearfix row" data-sort="<?= Url::toRoute(['/attachment/files/sort'])?>">
        <?php foreach ($images as $image) { ?>
            <?= $this->render('_image_form', ['model' => $image]); ?>
        <?php } ?> 
        </ul>
    </div>
    <div class="col-md-4">
        <form id="media-form">
            <?php if ($fields) : ?>
                <?php foreach ($fields as $key => $value) : ?>
                <div class="form-group">
                    <?= Html::textInput('[params][' . $key . ']', '', ['id' => $key, 'placeholder' => Inflector::titleize($key), 'class' => 'form-control']); ?>
                </div>
                <?php endforeach; ?>
                <div>
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'disabled btn btn-primary']); ?>            
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>
