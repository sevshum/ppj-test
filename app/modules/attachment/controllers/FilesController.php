<?php
namespace app\modules\attachment\controllers;

use app\modules\attachment\models\Attachment;
use app\modules\core\components\BaseController;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class FilesController extends BaseController
{
    public $modelName = '\app\modules\attachment\models\Attachment';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['upload', 'delete', 'sort'],
                'rules' => [
                    [
                        'actions' => ['upload', 'delete', 'sort', 'rotate'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'rotate' => ['post'],
                ],
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        if ($action->id === 'upload') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


    public function actionUpload($type, $model, $id)
    {
        session_write_close();
        $module = $this->module;
        $attachment = new Attachment;
        $attachment->type = $type;
        $attachment->model_type = $model;
        $attachment->model_id = $id;
        $attachment->user_id = $this->getUser()->getId();
        if (($message = $module->customValidate($attachment)) !== true) {
            return $this->renderJson(['success' => false, 'message' => $message]);
        }
        
        $attachment->setAdditionalParams($module->getParams($attachment));
        $attachment->setFileInstance(UploadedFile::getInstance($attachment, 'file'));
        
        if ($attachment->save()) {
            return $this->renderJson([
                'success' => true, 
                'id'   => $attachment->id,
                'html' => $this->renderPartial('_' . $type . '_form', ['model' => $attachment])
            ]);
        }
        return $this->renderJson(['success' => false, 'message' => $attachment->getError('file')]);
        
    }
    
    public function actionDelete($id)
    {
        return $this->renderJson([
            'success' => $this->loadModel($this->modelName, $id, ['user_id' => $this->getUser()->getId()])->delete(), 
            'html' => '', 
            'target' => ".file-$id"
        ]);
    }
    
    public function actionRotate($id)
    {
        session_write_close();
        $model = $this->loadModel($this->modelName, $id, ['user_id' => $this->getUser()->getId()]);
        if ($model->type !== Attachment::TYPE_IMAGE) {
            return $this->renderJson(['success' => false, 'message' => Yii::t('app', 'Only images.')]);
        }
        $processor = Yii::$app->get($model->imageProcessor);
        $path = $model->getImagePath('file', 'orig');
        $processor->getImageHandler()->load($path)->rotate(90)->save();
        $model->deleteImage('file', array_keys($processor->presets));
        
        return $this->renderJson([
            'success' => true, 
            'url' => $model->getImageUrl('file', 'image_media_preview') . '?' . time(), 
            'target' => ".file-$id"
        ]);
    }
    
    public function actionSort()
    {
        if (!empty($_POST['ids'])) {
            Attachment::sort($_POST['ids']);
        }        
        return $this->renderJson(['success' => true]);
    }
    
}