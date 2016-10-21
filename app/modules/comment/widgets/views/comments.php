<?php

use kartik\widgets\StarRating;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var yii\web\User $user */
/* @var yii\web\View $this */
/* @var app\modules\comment\models\Comment $model */

$isGuest = $user->getIsGuest();
$canReply = $this->context->allowReply && (!$isGuest || $this->context->allowGuest);
$pjax = $this->context->autoCreate;
?>
<h3><?= Yii::t('app', 'Comments:') ?></h3>
<?php if ($pjax) Pjax::begin(['id' => 'pjax-comments', 'enablePushState' => false]) ?>
    <?= ListView::widget([
        'dataProvider' => $provider,
        'itemView' => '_comment',
        'summary' => '',
        'viewParams' => ['canReply' => $canReply],
        'options' => [
            'id' => 'comments'
        ]
    ]) ?>
<br/><br/>
    <h3><?= Yii::t('app', 'Leave a Comment') ?></h3>
    <?php if (!$isGuest || $this->context->allowGuest) : ?>
        <?php 
        $options = [
            'options' => ['id' => 'comment-form'],
            'action'  => ['/comment/comments/create', 'type' => $this->context->entityType, 'id' => $this->context->entityId]
        ];
        if ($pjax) {
            unset($options['action']);
            $options['options']['data-pjax'] = 1;
        }
        
        $form = ActiveForm::begin($options) ?>
            <?= $form->field($model, 'parent_id', ['template' => '{input}'])->label(null)->hiddenInput() ?>
<!--            --><?php //if ($isGuest) : ?>

                
                <?= $form->field($model, 'author_name') ?>
                <?= $form->field($model, 'author_email') ?>

<!--            --><?php //endif ?>
            <?= $form->field($model, 'content')->textarea(['rows' => 5]) ?>
            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'captchaAction' => '/site/captcha',
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>
        <div class="pull-left" style="margin-top: 30px">
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-lg turquoise btn-success btn-block']) ?>
        </div>

            <div id="reply-author-box" style="display: none;">
                <span></span>
                <a class="remove-reply" href="#">&times;</a>
            </div>
        <?php ActiveForm::end() ?>
    <?php else : ?>
    <div>
        Please <?= Html::a('login', ['/site/login']) ?> to comment 
    </div>
    <?php endif ?>
<?php if ($pjax) Pjax::end() ?>
