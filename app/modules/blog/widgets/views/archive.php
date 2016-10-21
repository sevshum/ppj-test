<?php 
use yii\helpers\Html;
$module = Yii::$app->getModule('blog');
?>
<h2>Archive</h2>
<ul>
<?php foreach ($archive as $info) : ?>
    <li><?= Html::a($module->getArchiveDate($info['archive_date']) . " ({$info['post_count']})", ['/blog/posts/archive', 'date' => $info['archive_date']])?></li>
<?php endforeach; ?>         
</ul>