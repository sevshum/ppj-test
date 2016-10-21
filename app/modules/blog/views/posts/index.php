<?php
$this->title = Yii::t('app', 'Posts');
?>
<h1><?= h($this->title) ?></h1>
<?= $this->render('_list', compact('dataProvider')); ?>