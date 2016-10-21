<?php

use app\modules\core\components\widgets\fileuploader\Uploader;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
?>


<div id="dropbox"></div>
<?= Uploader::widget([
    'selector' => 'dropbox',
    'options' => [
        'action' => Url::toRoute(['/attachment/files/upload', 'type' => $type, 'id' => $id, 'model' => $model]),
        'name'   => 'Attachment[file]',
        'allowedExtensions' => ['jpeg', 'jpg', 'gif', 'png'],
        'maxConnections' => '1',
        'messages' => [
            'typeError' => "У файла {file} запрещенное расширение. Разрешены только файлы {extensions}.",
            'sizeError' => "Файл {file} слишком большого размера. Разрешено не более {sizeLimit}.",
            'minSizeError' => "{file} is too small, minimum file size is {minSizeLimit}.",
            'emptyError' => "{file} is empty, please select files again without it.",
            'onLeave:' => "The files are uploading. Если вы уйдете, загрузка будет прервана.",
        ],
        'onComplete' => new JsExpression('function(id, filename, response) {
            if (response.success) {
                $("' . $target . '").append(response.html);
            } else {
                alert(response.message);
            }
            $(".qq-upload-list li.qq-upload-success,.qq-upload-list li.qq-upload-fail").remove();
        }'),
        'template' =>
            '<div class="dropbox">
                <div class="inner"></div>
                <div class="qq-upload-button button"><span class="fa fa-plus-circle"></span> Upload image</div>
                <div class="drop-area" id="drag-zone"><span class="fa fa-plus-circle"></span> Upload or drag image</div>
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
                <span class="qq-upload-failed-text" style="display:none">Error</span>
                <span class="qq-upload-file"></span>
            </li>',
    ]
]); 

$this->registerJs('App.uploadImage.init("' . $target . '");');
?>

