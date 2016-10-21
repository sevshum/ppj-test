<?= yii\helpers\Html::a('Create new category', 
    ['/category/items/edit', 'categoryId' => $category->id], 
    ['class' => 'btn btn-primary', 'data-skip' => 1, 'data-op' => 'modal', 'data-title' => 'Edit category']
); ?>
<br />
<br />
<?= $this->render('_list', compact('category', 'provider')) ?>