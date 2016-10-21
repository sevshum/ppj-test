<?php

namespace app\modules\blog\widgets;

use Yii;
use yii\base\Widget;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class BlogCategory extends Widget
{
    public $code = 'blog_category';
    
    public $countModel = '\app\modules\blog\models\Post';
    
    public function run()
    {
        $categories = Yii::$app->getModule('category')->getListCategory($this->code, false, false);
        $counters = $this->_getCounters();
        return $this->render('categories', compact('categories', 'counters'));
    }
    
    private function _getCounters()
    {
        if ($this->countModel === null) {
            return [];
        }
        $class = $this->countModel;
        return ArrayHelper::map((new Query)->from($class::tableName())
            ->select(['category_id', 'COUNT(*) as count'])
            ->groupBy(['category_id'])
            ->where(['status' => $class::PUBLISHED])
            ->createCommand()
            ->queryAll(), 'category_id', 'count');
    }
    
    
}