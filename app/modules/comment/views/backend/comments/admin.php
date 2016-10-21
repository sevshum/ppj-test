<?php

use app\modules\comment\models\Comment;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $searchModel Comment */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Reviews');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'caption' => $this->title,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table'],
            'columns' => [
                [
                    'attribute' => 'entity_type',
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'entity_id',
                    'enableSorting' => false,
                ],
                [
                    'attribute'     => 'status',
                    'enableSorting' => true,
                    'value' => function($model) {
                        return Comment::statuses($model->status);
                    },
                    'filter' => Comment::statuses()
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
