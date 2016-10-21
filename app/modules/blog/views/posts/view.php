<?php
$this->title = $model->getI18n('title');
?>

<div class="post-item">
    <p class="date"><?= $model->published_at; ?></p>
    <h2><?= h($this->title); ?></h2>
    <div class="desc">
        <?= $model->getI18n('content'); ?>
    </div>
    <?= \app\modules\comment\widgets\CommentWidget::widget([
        'model' => $model,
        'allowGuest' => true,
        'autoCreate' => true,
        'allowReply' => true
    ]) ?>
</div>