<?php 
/* @var app\modules\comment\models\Comment $model */

use kartik\widgets\StarRating;

?>
<br/>
<?php if ($model->parent_id): ?><blockquote><?php endif; ?>
    <div id="comment-<?= $model->id ?>" class="comment">
        <div class="b-recall">
            <div class="recall-text">
                 <strong><?= Yii::t('app', 'Comment:') ?></strong>   <?= $model->handledContent ?>
            </div>
            <div class="recall-author">
                <span>
                    <strong><?= Yii::t('app', 'Name:') ?></strong>  <?= h($model->author_name) ?> (<?= h($model->author_email) ?>)
                </span>
                <span class="pull-right"><?= date('m/d/Y', strtotime($model->created_at)) ?></span>
            </div>
            <?php if ($canReply) : ?>
                | <a href="#" class="reply-comment" data-author="<?= h($model->author_name) ?>" data-id="<?= $model->id ?>"><?= Yii::t('app', 'Reply') ?></a>
            <?php endif; ?>
            <?php if ($canReply && count($model->children)) : ?>
                <div class="comment-children">
                    <?php foreach ($model->children as $child) : ?>
                        <?= $this->render('_comment', ['model' => $child, 'canReply' => $canReply])?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php if ($model->parent_id) : ?></blockquote><?php endif; ?>
