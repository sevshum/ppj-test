<?php
namespace app\modules\blog\controllers\backend;

class PostsController extends \app\modules\core\components\BackendController
{
    public $modelName = '\app\modules\blog\models\Post';
    public $modelSearch = '\app\modules\blog\models\PostSearch';
}