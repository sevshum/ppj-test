<?php

use app\modules\core\components\widgets\fileuploader\Uploader;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
?>

<?php $form = ActiveForm::begin([
    'options' => ['id' => 'image-form'],
]); ?>
<fieldset>
    <div id="dropbox"></div>
    <?= Uploader::widget([
        'selector' => 'dropbox',
        'options' => [
            'action' => Url::toRoute(['/attachment/files/upload', 'type' => $type, 'id' => $id, 'model' => $model]),
            'name'   => 'Attachment[file]',
            'onComplete' => new JsExpression('function(id, filename, response) {
                if (response.success) {
                    var counter = $(".media-lib-counter");
                    counter.text(parseInt(counter.text()) + 1);
                    $("' . $target . '").prepend(response.html);
                } else {
                    alert(response.message);
                }
                $(".qq-upload-list li.qq-upload-success,.qq-upload-list li.qq-upload-fail").remove();
            }'),
            'template' =>
                '<div class="dropbox">
                    <div class="inner"></div>
                    <div class="qq-upload-button button">Select file ...</div>
                    <div class="drop-area" id="drag-zone">Drop image here to upload</div>
                    <ul class="qq-upload-list"></ul>
                    <span class="clearfix"></span>
                </div>',
            'classes' => [
                'dropActive' => 'drop-area-active',
                'list' => 'qq-upload-list',
                'file' => 'qq-upload-file',
                'spinner' => 'qq-upload-spinner',
                'size' => 'qq-upload-size',
                'cancel' => 'qq-upload-cancel',
                'success' => 'qq-upload-success',
                'fail' => 'qq-upload-fail',
                'drop' => 'drop-area',
                'button' => 'button',
            ],
            'fileTemplate' =>
                '<li>
                    <div>
                        <span class="qq-upload-spinner"></span>
                        <span class="qq-upload-size"></span>
                    </div>
                    <a class="qq-upload-cancel" href="#" style="display:none">Cancel</a>
                    <span class="qq-upload-failed-text" style="display:none">Error</span-->
                    <span class="qq-upload-file"></span>
                </li>',
        ]
    ]); ?>
</fieldset>
<?php ActiveForm::end() ?>

<?php $this->registerJs('CMS.media.init("' . $target . '")') ?>
