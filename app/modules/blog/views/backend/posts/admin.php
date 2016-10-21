<?php

use app\modules\blog\models\Post;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\blog\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['rightTitle'] = Html::a(Yii::t('app', 'Create Post'), ['create'], ['class' => 'btn btn-success pull-right']);
?>
<div class="box">
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table'],
            'columns' => [
                [
                    'header'    => Yii::t('app', 'Title'),
                    'format'    => 'raw',
                    'attribute' => 'searchTitle',
                    'value'     => function($model) {
                        return Html::encode($model->getI18n('title'));
                    },
                    'enableSorting' => false,
                ],      
                [
                    'attribute' => 'category_id',
                    'filter' => [], //Yii::app()->getModule('categories')->getListData('page_category', false),
                    'filterInputOptions' => ['encode' => false]
                ],
                [
                    'attribute' => 'published_at',
                    'filter' => false,
                ],
                [
                    'attribute' => 'status',
                    'value' => function($model) {
                        return Post::statuses($model->status);
                    },
                    'filter' => Post::statuses()
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'options' => ['style' => 'width:80px;'],
                    'buttons' => [
                        'view' => function ($url,$model,$key) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-eye-open"></span>',
                                Yii::$app->urlManagerFrontend->createUrl(['/blog/posts/show', 'slug' => $model->slug]),
                                [
                                    'target' => '_blank',
                                    'data-pjax' => 0
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>