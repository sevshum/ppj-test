<?php
namespace app\modules\category\traits;

use Yii;

/**
 * Some cool methods to share amount your models
 * @property integer $category_id
 */
trait CategoryTrait
{
    
    public function getCategory()
    {
        return Yii::$app->getModule('category')->getItemById($this->category_id);
    }
}
