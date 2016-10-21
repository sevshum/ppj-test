<?php
namespace app\modules\attachment\controllers\backend;

use app\modules\core\components\BackendController;
use app\modules\attachment\models\Attachment;
use yii\helpers\Json;
use yii\web\UploadedFile;

class FilesController extends BackendController
{
    public $modelName = '\app\modules\attachment\models\Attachment';
    
    public function beforeAction($action)
    {
        if ($action->id === 'upload' || $action->id === 'redactor-upload') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


    public function actionUpload($type, $model, $id)
    {
        $attachment = new Attachment;
        $attachment->type = $type;
        $attachment->model_type = $model;
        $attachment->model_id = $id;
        $attachment->setAdditionalParams($this->module->getParams($attachment));
        $attachment->setFileInstance(UploadedFile::getInstance($attachment, 'file'));
        
        if ($attachment->save()) {
            return $this->renderJson([
                'success' => true, 
                'id'   => $attachment->id, 
                'html' => $this->renderPartial('_' . $type . '_form', ['model' => $attachment])
            ]);
        } else {
            return $this->renderJson(['success' => false, 'message' => $attachment->getError('file')]);
        }
    }
    
    public function actionUpdate($id)
    {
        $model = $this->loadModel($this->modelName, $id);
        
        if ($model->load($_POST)) {
            return $this->renderJson(['success' => $model->save()]);
        }
        return $this->renderJson(['success' => false]);
    }
    
    public function actionDelete($id)
    {
        return $this->renderJson([
            'success' => $this->loadModel($this->modelName, $id)->delete(), 
            'html' => '', 
            'target' => ".file-$id"
        ]);
    }
    
    public function actionRedactorUpload($type)
    {
        $attachment = new Attachment;
        $attachment->type = $type;
        $attachment->model_type = 'Redactor';
        $attachment->model_id = 1;
        $attachment->setFileInstance(UploadedFile::getInstanceByName('file'), true);
        if ($attachment->save()) {
            return $this->renderJson([
                'success' => true, 
                'filelink' => $type === Attachment::TYPE_IMAGE ? 
                    $attachment->getImageUrl('file', 'orig') :
                    $attachment->getFileUrl('file')
            ]);
        } else {
            return $this->renderJson(['success' => false, 'message' => $attachment->getError('file')]);
        }
    }
    
    public function actionRedactorList($type)
    {
        $result = [];
        foreach (Attachment::findAll(['type' => $type]) as $attach) {
            if ($type === Attachment::TYPE_IMAGE) {
                $result[] = [
                    'thumb' => $attach->getImageUrl('file', 'image_preview'),
                    'image' => $attach->getImageUrl('file', 'orig'),
                ];
            } else {
                $result[] = [
                    'title' => $attach->origin_name,
                    'name' => $attach->origin_name,
                    'size' => $attach->size,
                    'link' => $attach->getFileUrl('file'),
                ];
            }
        }
        return $this->renderJson($result);
    }
    
    public function actionImages($model, $id)
    {
        $type = Attachment::TYPE_IMAGE;
        $images = Attachment::find()->ordered()->where([
            'model_type' => $model, 'model_id' => $id, 'type' => $type
        ])->all();
        $attach = new Attachment();
        $attach->type = Attachment::TYPE_IMAGE;
        $attach->model_type = $model;
        $fields = $this->module->getParams($attach);
        return $this->render('images', compact('model', 'type', 'id', 'images', 'fields'));
    }
    
    public function actionSort()
    {
        if (!empty($_POST['ids'])) {
            Attachment::sort($_POST['ids']);
        }        
        return $this->renderJson(['success' => true]);
    }
    
}