<?php
namespace app\modules\attachment\widgets;

use app\modules\attachment\models\Attachment;
use yii\base\Widget;

class UploadImageWidget extends Widget
{
    public $model;
    public $form;
    
    public $viewPathAlias = '@app/modules/attachment/views/files';

    public function run()
    {
        $this->_registerAssets();
        $model = $this->model;
        return $this->render($this->viewPathAlias . '/images', [
            'images' => $model->getIsNewRecord() ? [] : $model->getImages(),
            'id' => $model->getIsNewRecord() ? $model->temp_id : $model->id,
            'type' => Attachment::TYPE_IMAGE,
            'model' => $model->formName(),
            'form' => $this->form,
        ]);
    }
    
    /**
     * Register client assets
     */
    protected function _registerAssets()
    {
        UploadImageAsset::register($this->getView());
    }
}

