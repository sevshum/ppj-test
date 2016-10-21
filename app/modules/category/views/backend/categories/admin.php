<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\category\models\Category */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['rightTitle'] = Html::a(Yii::t('app', 'Create Category List'), ['create'], ['class' => 'btn btn-success pull-right']);
if ($categoryId = Yii::$app->getRequest()->get('category_id')) {
    $this->registerJs('$("#category-list").find(".related-link[data-id=\'' . $categoryId . '\']").trigger("click");');
}
?>
<div class="box">
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table'],
            'options' => ['id' => 'category-list'],
            'columns' => [
                [
                    'attribute'     => 'name',
                    'enableSorting' => false,
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a($model->name, '#', [
                            'data-url' => Url::toRoute(['/category/items/index', 'id' => $model->id]), 
                            'data-id' => $model->id,
                            'class' => 'related-link'
                        ]);
                    }
                ],
                [
                    'attribute'     => 'code',
                    'enableSorting' => false,
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'options' => ['style' => 'width:60px;']
                ],
            ],
        ]); ?>
    </div>
</div>
