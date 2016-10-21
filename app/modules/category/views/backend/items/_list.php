<?php

use yii\grid\GridView;
use yii\helpers\Html;

?>

<?= GridView::widget([
    'dataProvider' => $provider,
    'layout' => "{items}",
    'options' => [
        'id' => 'category-' . $category->id,
    ],
    'columns' => [
        [
            'header' => 'Name', 
            'value' => function($model) {
                return str_repeat("&nbsp;&nbsp;&nbsp;", $model->depth) . $model->getI18n('name');
            }, 
            'format' => 'raw'
        ],
        'code',
        'url',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{up} {down} {update} {delete}',
            'options' => ['style' => 'width:100px;'],
            'buttons' => [
                'update' => function ($url, $model) use ($category) {
                    return $model->depth != 0 ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/category/items/edit', 'id' => $model->id, 'categoryId' => $category->id], [
                        'title' => 'Update',
                        'data-op' => 'modal', 
                        'data-title' => 'Edit category', 
                        'data-skip' => 1,
                    ]) : '';
                },               
                'delete' => function ($url, $model) {
                    return $model->depth != 0 ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/category/items/delete', 'id' => $model->id], [
                        'title' => 'Delete',
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                    ]) : '';
                },
                'up' => function ($url, $model) use ($provider) {
                    return $model->canMove($provider->getModels(), 'up') ? Html::a('<span class="glyphicon glyphicon-chevron-up"></span>', ['/category/items/move', 'id' => $model->id, 'dir' => 'up'], [
                        'title' => 'Up',
                        'data-op' => 'ajax'
                    ]) : '';
                },
                'down' => function ($url, $model) use ($provider) {
                    return $model->canMove($provider->getModels(), 'down') ? Html::a('<span class="glyphicon glyphicon-chevron-down"></span>', ['/category/items/move', 'id' => $model->id, 'dir' => 'down'], [
                        'title' => 'Down',
                        'data-op' => 'ajax'
                    ]) : '';
                },
            ]
        ]
    ],
]);