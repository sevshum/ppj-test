<?php
namespace app\modules\attachment\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\validators\Validator;

class UploadBehavior extends Behavior
{
    public $tempAttribute = 'temp_id';
    
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_INSERT    => 'afterInsert',            
        ];
    }
    
    /**
     * @param Event $event
     */
    public function beforeValidate($event)
    {
        /* @var $owner ActiveRecord */
        $owner = $this->owner;
        $owner->validators[] = Validator::createValidator('integer', $owner, $this->tempAttribute);
    }
    
    /**
     * @param Event $event
     */
    public function afterInsert($event)
    {
        $this->owner->replaceModelId(true);
    }

    
}
