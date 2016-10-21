<?php

namespace app\modules\blog\controllers\backend;

use app\modules\blog\models\PostTag;
use app\modules\core\components\BackendController;
use yii\helpers\Json;

class TagsController extends BackendController
{
	public $modelName = '\app\modules\blog\models\PostTag';
    
    public function actionSuggest()
    {
        if (isset($_GET['q']) && ($q = trim($_GET['q'])) !== '') {
            echo Json::encode(PostTag::suggest($q));
        }
    }
    
}