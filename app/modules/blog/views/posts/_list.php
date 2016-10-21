<?= yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'post-list',
    'itemView' => '_view',
]); ?>
