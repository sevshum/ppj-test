<?php

namespace app\modules\comment;

use app\modules\core\components\AppModule;
use app\modules\comment\models\Comment;

class Module extends AppModule
{
    public $controllerNamespace = 'app\modules\comment\controllers';
    
    public function getNewCount()
    {
        return Comment::find()->where(['status' => Comment::STATUS_NEW])->count();
    }
}
