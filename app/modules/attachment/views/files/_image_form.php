<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<li class="image-item file-<?= $model->id ?>" data-id="<?= $model->id ?>" id="ids-<?= $model->id ?>">
   <a title="Удалить" class="close image-delete" href="<?= Url::toRoute(['/attachment/files/delete', 'id' => $model->id]) ?>" data-confirming="Удалить это изображение?">&times;</a>
   <a title="Повернуть" class="rotate image-rotate" href="<?= Url::toRoute(['/attachment/files/rotate', 'id' => $model->id]) ?>"><span class="fa fa-rotate-right"></span></a>
   <?= Html::img($model->getImageUrl('file', 'image_media_preview'), ['class' => 'img-rounded img']) ?>
</li>

