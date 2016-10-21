<?php
namespace app\modules\comment\widgets;

use app\modules\comment\models\Comment;
use app\modules\core\helpers\App;
use InvalidArgumentException;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;

class CommentWidget extends Widget
{
    public $autoCreate = false;
    public $allowGuest = true;
    public $allowReply = true;

    public $entityType;
    public $entityId;
    
    /**
     * @var ActiveRecord 
     */
    public $model;
    
    public function init()
    {
        parent::init();
        if ($this->allowReply) {
            $this->view->registerAssetBundle(CommentAsset::className(), View::POS_END);
        }
    }
    public function run() 
    {
        
        if (!isset($this->model) && !isset($this->entityType)) {
            throw new InvalidArgumentException('The param "model" or "entityType" is required.');
        }
        if (isset($this->model)) {
            $this->entityId = $this->model->getPrimaryKey();
            $this->entityType = $this->model->formName();
        }
        if ($this->autoCreate) {
            $this->_create();
        }
        if ($this->allowReply) {
            $provider = new ArrayDataProvider([
                'allModels' => App::createForest(
                    Comment::getModelQuery($this->entityType, $this->entityId)->all(),
                    'id', 'parent_id'
                )
            ]);
        } else {
            $provider = new ActiveDataProvider([
               'query' => Comment::getModelQuery($this->entityType, $this->entityId)
            ]);
        }
        $user = Yii::$app->getUser();
        $model = new Comment;
        $model->user_id = $user->getId();
        return $this->render('comments', ['provider' => $provider, 'model' => $model, 'user' => $user]);
    }
    
    private function _create()
    {
        $model = new Comment;
        $model->entity_type = $this->entityType;
        $model->entity_id = $this->entityId;
        return $model->load($_POST) && $model->create(Yii::$app->getUser());
    }
}

