<?php
namespace app\modules\attachment\traits;

use app\modules\attachment\behaviors\UploadBehavior;
use app\modules\attachment\models\Attachment;
use app\modules\core\helpers\App;
use Yii;

/**
 * Some cool methods to share amount your models
 */
trait UploadTrait
{
    private $_images;
    public $temp_id;
    
    public function init()
    {
        $this->temp_id = -App::uid();
        if ($this->getIsNewRecord()) {
            $this->attachBehavior('upload_behavior', UploadBehavior::className());
        }
        parent::init();
    }


    public function replaceModelId($insert)
    {
        if ($insert && $this->temp_id && $this->temp_id < 0) {
            return Attachment::updateAll(['model_id' => $this->id, 'user_id' => $this->user_id], [
                'model_type' => $this->formName(),
                'model_id' => $this->temp_id,
            ]);
        }
        return false;
    }
    
    /**
     * @return Attachment
     */
    public function getFirstImage()
    {
        $images = $this->getImages();
        return isset($images[0]) ? $images[0] : null;
    }
    
    /**
     * @return Attachment[]
     */
    public function getImages()
    {
        if ($this->_images === null) {
            $this->_images = Yii::$app->getModule('attachment')->getImages($this);
        }
        return $this->_images;
    }
}
