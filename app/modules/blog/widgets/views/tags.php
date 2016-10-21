<?php 
use yii\helpers\Html;
?>
<h2>Tags</h2>
<ul>
<?php foreach ($tags as $tag) : ?>
    <li><?= Html::a(h($tag->name), $tag->getUrl()); ?></li>
<?php endforeach; ?>         
</ul>