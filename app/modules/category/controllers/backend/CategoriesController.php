<?php

namespace app\modules\category\controllers\backend;

use app\modules\core\components\BackendController;

class CategoriesController extends BackendController
{
    public $modelName = '\app\modules\category\models\Category';
    public $modelSearch = '\app\modules\category\models\Category';

    public function actionCreate()
    {
        $this->params['code'] = isset($_GET['code']) ? $_GET['code'] : '';
        unset($_GET['code']);
        return parent::actionCreate();
    }
}
