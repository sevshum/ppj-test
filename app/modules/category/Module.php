<?php
namespace app\modules\category;

use app\modules\category\models\Category;
use app\modules\category\models\CategoryItem;
use app\modules\core\components\AppModule;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class Module extends AppModule
{
    public $controllerNamespace = 'app\modules\category\controllers';
    
    public function getListCategory($code, $forMenu = true, $throwException = true)
    {
        $category = $this->getByCode($code, $throwException);
        return $category->getAll(null, $forMenu);
    }
    
    public function getListData($code, $throwException = true, $prefix = '  ')
    {
        $category = $this->getByCode($code, $throwException);
        $list = $category->listData(null, 'id', $prefix);
        ArrayHelper::remove($list, array_keys($list)[0]);
        return $list;
    }
    
    /**
     * @param integer $id
     * @return CategoryItem;
     */
    public function getItemById($id, $throwException = true)
    {
        $category = CategoryItem::getById($id);
        if ($category === null && $throwException) {
            throw new NotFoundHttpException('Unable to find the requested object.');
        }
        return $category;
    }
    
    /**
     * @param string $code
     * @return CategoryItem;
     */
    public function getItemByCode($code, $throwException = true)
    {
        $category = CategoryItem::getByCode($code);
        if ($category === null && $throwException) {
            throw new NotFoundHttpException('Unable to find the requested object.');
        }
        return $category;
    }
    
    /**
     * @param string $code
     * @return Category
     */
    public function getByCode($code, $throwException = true)
    {
        $category = Category::getByCode($code);
        if ($category === null) {
            if ($throwException) {
                throw new NotFoundHttpException('The list of categories "' . $code . '" was not found.');
            } else {
                $category = Category::create($code);
            }
        }
        return $category;
    }
    
    
    /**
     * @param ActiveRecord $model
     * @return CategoryItem[]
     */
    public function getCategories($model, $parentCode = null)
    {
        if ($model->getIsNewRecord()) {
            return [];
        }
        return CategoryItem::getByEntity($model, $parentCode);
    }
    
    /**
     * @param ActiveRecord $model
     */
    public function setCategories($model, $ids, $parentCode = null)
    {
        return CategoryItem::setForEntity($model, $ids, $parentCode);
    }
    
    /**
     * @param ActiveRecord $model
     */
    public function removeCategories($model, $parentCode = null)
    {
        return CategoryItem::removeByEntity($model, $parentCode);
    }
    
}
