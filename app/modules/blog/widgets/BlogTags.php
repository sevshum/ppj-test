<?php

namespace app\modules\blog\widgets;

use app\modules\blog\models\PostTag;
use yii\base\Widget;

class BlogTags extends Widget
{
    public function run()
    {
        $tags = PostTag::find()->all();
        return $this->render('tags', compact('tags'));
    }
}