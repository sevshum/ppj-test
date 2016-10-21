<?php

namespace app\modules\blog\widgets;

use Yii;
use yii\base\Widget;

class BlogArchive extends Widget
{
    public function run()
    {
        $archive = Yii::$app->getModule('blog')->getArchive();
        return $this->render('archive', compact('archive'));
    }
}