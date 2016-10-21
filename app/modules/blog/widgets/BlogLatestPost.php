<?php

namespace app\modules\blog\widgets;

use app\modules\blog\models\Post;
use Yii;
use yii\base\Widget;

class BlogLatestPost extends Widget
{
    public $limit = 5;
    public $view = 'latest_post';
    
    public function run()
    {
        Yii::$app->getModule('blog');
        $posts = Post::find()->resent()->published()->withLang()->limit($this->limit)->all();
        return $this->render($this->view, compact('posts'));
    }
}