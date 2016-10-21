<?php
$this->title = Yii::t('app', 'Posts from category: {category}', ['category' => $category->getI18n('name')]);
?>

<h1><?= h($this->title) ?></h1>
<?= $this->render('_list', compact('dataProvider')); ?>