<?php
namespace app\modules\comment\widgets;

use yii\web\AssetBundle;

class CommentAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/comment/widgets/assets';
    public $js = ['comment.js'];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
