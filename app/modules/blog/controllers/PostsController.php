<?php
namespace app\modules\blog\controllers;

use app\modules\blog\models\Post;
use Yii;
use yii\data\ActiveDataProvider;

class PostsController extends \app\modules\core\components\FrontendController
{
    public $layout = 'blog';
    public $modelName = '\app\modules\blog\models\Post';
    
    public function actionArchive($date)
    {
        $class = $this->modelName;
        $dataProvider = new ActiveDataProvider([
            'query' => $class::find()->published()->withLang()->archive($date)
        ]);
        return $this->render('archive', ['dataProvider' => $dataProvider, 'date' => $date]);
    }
    
    public function actionCategory($code)
    {
        $class = $this->modelName;
        $category = Yii::$app->getModule('category')->getItemByCode($code);        
        $dataProvider = new ActiveDataProvider([
            'query' => $class::find()->published()->withLang()->byCategory($code)
        ]);
        return $this->render('category', ['dataProvider' => $dataProvider, 'category' => $category]);
    }
    
    public function actionIndex()
    {
        /** @var Post $class */
        $class = $this->modelName;
        
        $query = $class::find()->published()->withLang();
        
        if ($tag = \Yii::$app->getRequest()->get('tag')) {
            $tagId = explode('-', $tag, 1);
            $query->byTag($tagId);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }
}