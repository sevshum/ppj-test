<?php use yii\helpers\Html; ?>
<h2>Blog Categories</h2>
<div class="blog-category">
    <ul>
        <?php foreach ($categories as $cat) : ?>
            <li>
                <?= Html::a(h($cat->getI18n('name')) . ' <span>(' . (isset($counters[$cat->code]) ? $counters[$cat->code] : 0) . ')</span>', ['/blog/posts/category', 'code' => $cat->code])?>
            </li>
        <?php endforeach; ?>         
    </ul>
</div>