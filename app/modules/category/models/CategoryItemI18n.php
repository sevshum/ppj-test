<?php
namespace app\modules\category\models;

use app\modules\core\components\AppActiveRecord;
use Yii;

/**
 * @property $id
 * @property $lang_id
 * @property $parent_id
 * @property $name
 */
class CategoryItemI18n extends AppActiveRecord 
{
    public $dirtyParams = ['name'];

    public static function tableName() 
    {
        return 'category_item_i18ns';
    }

    public function rules() 
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            [['name', 'lang_id'], 'required'],
            ['name', 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() 
    {
        return [
            'name' => Yii::t('app', 'Name'),
        ];
    }
}