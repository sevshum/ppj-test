<?php
namespace app\modules\category\models;

use app\modules\core\components\AppActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use Yii;
use yii\db\ActiveRecord;

class CategoryItem extends AppActiveRecord
{
    use \app\modules\core\traits\I18nActiveRecordTrait;
    use \app\modules\core\traits\TreeTrait;
    
    public $i18nModel = 'app\modules\category\models\CategoryItemI18n';

    public static function tableName() 
    {
        return 'category_items';
    }

    public function rules() 
    {
        return [
            ['parent_id', 'required'],
            [['url', 'code'], 'string', 'max' => 255],
            [['translateattrs', 'code'], 'safe']
        ];
    }
    /**
     * @return static
     */
    public static function find()
    {
        $query = parent::find();
        $query->attachBehavior('nested_set', NestedSetsQueryBehavior::className());
        return $query;
    }
    
    
    public function behaviors()
    {
        return [
            'nestedSetBehavior' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
                'attributes' => [
                    AppActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
            'slugable' => [
                'class' => '\app\modules\core\components\behaviors\SlugBehavior',
                'enabled' => $this->getIsNewRecord(),
                'slugAttribute' => 'code',
                'sourceAttribute' => function($owner) {
                    return trim($owner->code) === '' ? 
                        $owner->getI18n('name', false, Yii::$app->getModule('language')->getDefault()) :
                        $owner->code;
                }
            ]
        ];
    }
    
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function attributeLabels() 
    {
        return [
            'id' => 'ID',
            'url' => Yii::t('app', 'Url'),
            'code' => Yii::t('app', 'Code'),
            'parent_id' => Yii::t('app', 'Parent'),
        ];
    }
    
    public static function getByCode($code, $lang = null)
    {
        static $codeCategories = [];
        if (!array_key_exists($code, $codeCategories)) {
            $codeCategories[$code] = static::find()->withLang($lang)->where(['code' => $code])->one();
        }
        return $codeCategories[$code];
    }
    
    /**
     * @staticvar array $idCategories
     * @param integer $id
     * @param string $lang
     * @return CategoryItem
     */
    public static function getById($id, $lang = null)
    {
        static $idCategories = [];
        if (!array_key_exists($id, $idCategories)) {
            $idCategories[$id] = static::find()->withLang($lang)->where(['id' => $id])->one();
        }
        return $idCategories[$id];
    }
    
    public static function getByEntity($model, $parentCode = null)
    {
        $on = 'ON ce.item_id = categories.id AND ce.entity_type = :type AND ce.entity_id = :id';
        $params = [':type' => $model->formName(), ':id' => $model->id];
        if ($parentCode !== null) {
            $on .= ' AND ce.parent_code = :code ';
            $params[':code'] = $parentCode;
        }
        return static::find()->withLang()->innerJoin('`categories_entities` AS ce', $on, $params)->all();
    }
    
    /**
     * @param ActiveRecord $model
     * @param array $ids
     * @param string $parentCode
     */
    public static function setForEntity($model, $ids, $parentCode = null)
    {
        static::removeByEntity($model, $parentCode);
        if (empty($ids)) {
            return;
        }
        $type = $model->formName();
        $keys = ['item_id', 'entity_id', 'entity_type', 'parent_code'];
        $data = [];
        foreach ($ids as $id) {
            $data[] = [$id, $model->id, $type, $parentCode];
        }
        
        return static::getDb()->createCommand()->batchInsert('categories_entities', $keys, $data)->execute();
    }
    
    public static function removeByEntity($model, $parentCode = null)
    {
        $params = ['entity_type' => $model->formName(), 'entity_id' => $model->id];
        if ($parentCode !== null) {
            $params['parent_code'] = $parentCode;
        }
        return static::getDb()->createCommand()->delete('categories_entities', $params)->execute();
    }
}