<?php
namespace app\modules\blog\models;

use app\modules\core\components\AppActiveRecord;
use Yii;


class PostI18n extends AppActiveRecord
{   
    public $dirtyParams = [
        'title', 'content', 'short_content', 'meta_title', 'meta_keywords', 'meta_description'
    ];

    public static function tableName()
    {
        return 'post_i18ns';
    }

    public function rules()
    {
        return [
            [['title', 'content', 'lang_id'], 'required'],            
            ['title', 'string', 'max' => 255],
            [['short_content', 'meta_title', 'meta_keywords', 'meta_description'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'            => Yii::t('app', 'Title'),
            'content'          => Yii::t('app', 'Content'),
            'short_content'    => Yii::t('app', 'Short Content'),
            'meta_title'       => Yii::t('app', 'Title (SEO)'),
            'meta_keywords'    => Yii::t('app', 'Keywords (SEO)'),
            'meta_description' => Yii::t('app', 'Description (SEO)'),
        ];
    }

}