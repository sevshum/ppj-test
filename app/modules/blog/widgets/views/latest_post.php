<?php use yii\helpers\Html; ?>
<h3>Latest posts</h3>
<ul>
    <?php foreach ($posts as $post) { ?>
    <li><?= Html::a($post->getI18n('title'), $post->getUrl())?></li>
    <?php } ?>
</ul>