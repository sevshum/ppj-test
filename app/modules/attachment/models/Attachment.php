<?php
namespace app\modules\attachment\models;

use app\modules\core\components\AppActiveRecord;
use Yii;
use yii\web\UploadedFile;

/**
 * @property integer $id
 * @property string $type
 * @property string $file
 * @property string $origin_name
 * @property string $size
 * @property integer $order
 * @property string $title
 * @property string $params
 * @property string $model_type
 * @property integer $model_id
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class Attachment extends AppActiveRecord
{
    const TYPE_IMAGE = 'image';
    const TYPE_FILE = 'file';
    
    protected $additionalParams = ['title' => '', 'description' => ''];
    
    /**
     * @var UploadedFile 
     */
    private $_file; 
    

    public static function tableName()
    {
        return 'attachments';
    }

    public function rules()
    {
        $rules = [
            [['model_type', 'model_id'], 'required'],
            ['additionalparams', 'safe'],
        ];
        if ($this->type === self::TYPE_IMAGE) {
            $rules[] = [
                'file', '\maxlapko\components\ImageValidator',
                'extensions' => ['jpg', 'png', 'jpeg', 'gif'], 'maxSize' => 5 * 1024 * 1024
            ];
        } else {
            $rules[] = Yii::$app->getModule('attachment')->getRules($this);
        }
        return $rules;
    }
    
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => function() {
                    return date('Y-m-d H:i:s');
                }
            ],
            'mImage' => ['class' => '\maxlapko\components\ImageBehavior'],
            'mSaver' => ['class' => '\app\modules\attachment\behaviors\SaverBehavior'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'model_type' => Yii::t('app', 'Model type'),
            'model_id'   => Yii::t('app', 'Model ID'),
            'created_at' => Yii::t('app', 'Create date'),
            'updated_at' => Yii::t('app', 'Update date'),
            'order'      => Yii::t('app', 'Order'),
        ];
    }
    
    public static function getMediaCount($modelType, $id, $type = self::TYPE_IMAGE)
    {
        $where = ['model_type' => $modelType, 'model_id' => $id];
        if ($type !== null) {
            $where['type'] = $type;
        }
        return static::find()->where($where)->count();
    }
    
    public static function sort($ids) 
    {
        return parent::sorting(static::tableName(), $ids);
    }
    
    public function afterFind()
    {
        $this->additionalParams = json_decode($this->params, true);
        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->order = $this->getMaxOrder([
                'model_type' => $this->model_type, 
                'type' => $this->type
            ]) + 1;
        }
        if ($this->_file) {
            $this->origin_name = $this->_file->name;
            $this->size = $this->_file->size;
            if ($this->type === self::TYPE_IMAGE) {
                $this->uploadImage($this->_file, 'file');
            } elseif ($this->type === self::TYPE_FILE) {
                $this->uploadFile($this->_file, 'file', !$insert);
            }
        }
        $this->params = json_encode($this->additionalParams);
        return parent::beforeSave($insert);
    }
    
    public function afterDelete()
    {
        parent::afterDelete();
        if ($this->type === self::TYPE_IMAGE) {
            $presents = ['image_preview', 'orig'];
            $this->deleteImage('file', $presents);
        } elseif ($this->type === self::TYPE_FILE) {
            $this->deleteFile('file');
        }
    }
    
    public function setAdditionalParams($params)
    {
        $this->additionalParams = $params;
    }
    
    public function getAdditionalParams()
    {
        return $this->additionalParams;
    }
    
    /**
     * @param UploadedFile $file
     */
    public function setFileInstance($file, $origin = false)
    {
        $this->_file = $file;
        if ($origin) {
            $this->file = $file;
        }
    }

    public function __get($name)
    {
        if (isset($this->additionalParams[$name])) {
            return $this->additionalParams[$name];
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (isset($this->additionalParams[$name])) {
            $this->additionalParams[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }
    
}