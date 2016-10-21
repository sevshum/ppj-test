<?php

use yii\helpers\Html;

?>
<div class="post-item">
    <p class="date"><?= $model->published_at; ?></p>
    <p class="title"><?= h($model->getI18n('title')); ?></p>
    <div>
        <?= $model->getI18n('short_content'); ?>
    </div>
    <p class="read_more"><?= Html::a('Read more &raquo;', $model->getUrl());?></p>
</div>