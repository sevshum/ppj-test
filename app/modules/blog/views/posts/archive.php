<?php 
$this->title = Yii::t('app', 'Posts archived {date}', ['date' => $date]);
?>
<h1><?= h($this->title) ?></h1>
<?= $this->render('_list', compact('dataProvider')); ?>