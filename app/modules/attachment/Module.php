<?php
namespace app\modules\attachment;

use app\modules\core\components\AppModule;
use app\modules\attachment\models\Attachment;
use yii\db\ActiveRecord;

class Module extends AppModule 
{
    public $controllerNamespace = 'app\modules\attachment\controllers';   
    
    /**
     * Form name => validator settings
     * @var array
     */
    public $ruleMapping = [];
    
    /**
     * Custom fields for each model
     * @var array 
     */
    public $paramsMapping = [];
    
    /**
     * Custom validate for each model
     * @var array 
     */
    public $validateMapping = [];

    /**
     * @param ActiveRecord $model
     * @param mixed $condition
     * @param array $params
     * @return Attachment[]
     */
    public function getImages($model, $condition = null, $params = [])
    {
        return $this->getAttachments($model, Attachment::TYPE_IMAGE, $condition, $params);
    }
    
    /**
     * @param ActiveRecord $model
     * @param mixed $condition
     * @param array $params
     * @return Attachment[]
     */
    public function getFiles($model, $condition = null, $params = [])
    {
        return $this->getAttachments($model, Attachment::TYPE_FILE, $condition, $params);
    }
    
    public function getAttachments($model, $type = null, $condition = null, $params = [])
    {
        $find = Attachment::find()->ordered();
        if ($condition !== null) {
            $find->where($condition, $params);
        }
        $where = [
            'model_type' => $model->formName(), 
            'model_id' => $model->id,
        ];
        if ($type) {
            $where['type'] = $type;
        }
        return $find->andWhere($where)->all();
    }
    
    public function getMediaCount($modelType, $id, $type = Attachment::TYPE_IMAGE)
    {
        return Attachment::getMediaCount($modelType, $id, $type);
    }
    
    /**
     * @param \app\modules\attachment\models\Attachment $model
     */
    public function getRules(Attachment $model)
    {
        if (isset($this->ruleMapping[$model->model_type])) {
            return $this->ruleMapping[$model->model_type];
        }
        return ['file', 'file'];
    }
    
    /**
     * @param \app\modules\attachment\models\Attachment $model
     */
    public function getParams(Attachment $model)
    {
        if (isset($this->paramsMapping[$model->model_type])) {
            return $this->paramsMapping[$model->model_type];
        }
        return ['title' => '', 'description' => ''];
    }
    
    /**
     * @param \app\modules\attachment\models\Attachment $model
     * @return string|boolean
     */
    public function customValidate(Attachment $model)
    {
        if (isset($this->validateMapping[$model->model_type])) {
            return call_user_func($this->validateMapping[$model->model_type], $model);
        }
        return true;
    }
}
