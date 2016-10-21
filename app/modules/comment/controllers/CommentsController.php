<?php

namespace app\modules\comment\controllers;

use app\modules\core\components\BaseController;
use app\modules\comment\models\Comment;
use app\modules\page\models\Page;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class CommentsController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex($type, $id)
    {
        return $this->render('index', ['type' => $type, 'id' => $id]);
    }

    /*public function actionWrite()
    {
        $model = Page::find()->where(['slug' => 'reviews'])->one();
        return $this->render('write', ['model' => $model]);
    }*/

    public function actionCreate($type, $id)
    {
        $model = new Comment;
        $request = Yii::$app->getRequest();
        $model->entity_type = $type;
        $model->entity_id = $id;
        if ($model->load($_POST) && $model->create($this->getUser())) {
            $newFlag = $model->status === Comment::STATUS_NEW;
            Yii::$app->getSession()->setFlash($newFlag ? 'info' : 'success', $newFlag ? 
                Yii::t('app', 'Your review will be added after administrator approval.') :
                Yii::t('app', 'Your review successfully added.')
            );
            return $this->redirect($request->getReferrer());
        }
        return $this->redirect($request->getReferrer());
//        return $this->render('create', ['model' => $model]);
    }
}