<?php
namespace app\modules\attachment\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class MediaWidget extends Widget
{
    public $model;
    public $options = [];

    public function run()
    {
        $class = $this->model->formName();
        $id = $this->model->getIsNewRecord() ? $this->model->temp_id : $this->model->id;
        $params = [
            'class' => 'btn btn-success', 
            'data-op' => 'modal', 
            'data-title' => 'Загрузка изображений', 
            'data-skip' => 1,
            'data-class' => 'modal-lg',
        ];
        if (isset($this->options['class'])) {
            Html::addCssClass($params, $this->options['class']);
            unset($this->options['class']);
        }
        
        echo Html::a(
            'Изображения (<span class="media-lib-counter">' . Yii::$app->getModule('attachment')->getMediaCount($class, $id) . '</span>)' , 
            ['/attachment/files/images', 'model' => $class, 'id' => $id],
            array_merge($this->options, $params)
        );
        $this->_registerAssets();
    }
    
    /**
     * Register client assets
     */
    protected function _registerAssets()
    {
        MediaAsset::register($this->getView());
    }
}

