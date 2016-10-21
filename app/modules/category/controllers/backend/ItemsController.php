<?php

namespace app\modules\category\controllers\backend;

use app\modules\core\components\BackendController;
use app\modules\category\models\Category;
use app\modules\category\models\CategoryItem;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\BadRequestHttpException;

class ItemsController extends BackendController 
{    
    public function actionIndex() 
    {
        $request = Yii::$app->getRequest();
        $id = $request->get('id');
        $category = $this->loadModel(Category::className(), $id);
        $provider = $this->_getProvider($category->getTree());
        if ($request->getIsAjax()) {
            return $this->render('index', compact('category', 'provider'));
        }
        return $this->render('index', compact('category', 'provider'));
    }
    
    public function actionEdit($categoryId, $id = null)
    {
        $category = $this->loadModel(Category::className(), $categoryId);
        if ($id == null) {
            $item = new CategoryItem;
        } else {
            $item = $this->loadModel(CategoryItem::className(), $id);
            $item->populateParent();
        }
        $item->category_id = $category->id;
        $ajax = false;
        if ($item->load($_POST)) {
            if ($item->validate()) {
                $response = ['success' => false, 'target' => '#category-' . $categoryId];
                $node = $this->loadModel(CategoryItem::className(), $item->parent_id);
                if ($item->getIsNewRecord()) {
                    if ($item->appendTo($node)) {
                        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successfully created.'));
                        $response['success'] = true;
                    }
                } else {
                    if ($node->equals($item->parents()->one())) {
                        $item->save(false);
                    } else {
                        $item->appendTo($node);
                    }
                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successfully updated.'));
                    $response['success'] = true;
                }
                if ($response['success']) {
                    $response['html'] = $this->renderPartial('_list', [
                        'category' => $category, 'provider' => $this->_getProvider($category->getTree())
                    ]);
                    
                    return $this->renderJson($response);
                }
                
            } else {
                $ajax = true;
            }
        }
        $params = [
            'item' => $item,
            'parents' => $category->listData() 
        ];
        if ($ajax) {
            return $this->renderJson([
                'success' => false,
                'target' => '#category-item-' . $item->id,
                'html' => $this->renderPartial('_form', $params)
            ]);
        } else {
            return $this->render('_form', $params);            
        }
    }
    
    public function actionMove($id, $dir)
    {
        if (Yii::$app->getRequest()->getIsAjax()) {
            $current = $this->loadModel(CategoryItem::className(), $id);
            if ($dir == $current::MOVE_UP) {
                $prev = $current->prev()->one();
                if ($prev) {
                    $current->insertBefore($prev, false);
                }
            } elseif ($dir == $current::MOVE_DOWN) {
                $next = $current->next()->one();
                if ($next) {
                    $current->insertAfter($next, false);
                }
            }
            $category = $current->category;        
            return $this->renderJson([
                'html' => $this->renderPartial('_list', [
                    'category' => $category, 'provider' => $this->_getProvider($category->getTree())
                ]),
                'success' => true,
                'target' => '#category-' . $category->id
            ]);
        } else {
            throw new BadRequestHttpException('Invalid request. Please do not repeat this request again.');
        }
    }
    
    public function actionDelete($id)
    {
        $model = $this->loadModel(CategoryItem::className(), $id);
        $model->delete();
        return $this->redirect(['/category/categories/admin', 'category_id' => $model->category_id]);
    }
    
    protected function _getProvider($data)
    {
        unset($data[0]);
        return new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => ['pageSize' => 1000]
        ]);
    }
}