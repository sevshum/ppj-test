<?php
namespace app\modules\category\models;

use app\modules\core\components\AppActiveRecord;
use app\modules\core\helpers\App;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * This is the model class for table "categories".
 *
 * The followings are the available columns in table 'menus':
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $created_at
 */
class Category extends AppActiveRecord 
{
    protected static $_categories = [];

    public static function tableName() 
    {
        return 'categories';
    }
    
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
                'attributes' => [
                    AppActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
            ]
        ];
    }
    
    public function getRootItem()
    {
        return $this->hasOne(CategoryItem::className(), ['category_id' => 'id'])->where(['depth' => 0]);
    }

    public function rules() 
    {
        return [
            [['name', 'code'], 'required'],
            ['code', 'unique'],
            ['name', 'string', 'max' => 255]
        ];
    }

    public function attributeLabels() 
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
        ];
    }

    public function search($params) 
    {
        $query = Category::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
    
    public static function getByCode($code)
    {
        if (!array_key_exists($code, self::$_categories)) {
            self::$_categories[$code] = static::findOne(['code' => $code]);
        }
        return self::$_categories[$code]; 
    }
    
    /**
     * Create category by code
     * @param string $code
     * @return boolean
     */
    public static function create($code)
    {
        $c = new static;
        $c->code = $code;
        $c->name = Inflector::humanize($code);
        self::$_categories[$code] = $c;
        $c->save(false);
        return $c;
    }
    
    public function getTree($lang = null)
    {
        static $tree = [];        
        if (isset($tree[$this->id])) {
            return $tree[$this->id];
        }
        if ($this->rootItem === null) {
            return [];
        }
        return $tree[$this->id] = CategoryItem::find()->withLang($lang)->where([
            'tree' => $this->rootItem->id,
            'category_id' => $this->id
        ])->orderBy(['lft' => SORT_ASC])->all();
    }
    
    public function listData($lang = null, $index = 'id', $prefix = '  ')
    {
        $tree = $this->getTree($lang);
        if (count($tree) === 0) {
            return [];
        }
        $categories = [$tree[0]->$index => Yii::t('app', 'Without Parent')];
        unset($tree[0]);
        foreach ($tree as $node) {
            $categories[$node->$index] = str_repeat($prefix, $node->depth - 1) . $node->getI18n('name');
        }
        return $categories;
    }
    
    /**
     * Get array for menu widget
     * @return array
     */
    public function getAll($lang = null, $forMenu = true)
    {
        $tree = $this->getTree($lang);
            
        if (count($tree) < 2) {
            return [];
        }
        unset($tree[0]);
        if (!$forMenu) {
            return $tree;
        }
        
        // Trees mapped
        $trees = [];
        $l = 0;
        // Node Stack. Used to help building the hierarchy
        $stack = [];

        foreach ($tree as $node) {
            $item = [
                'url'   => $node->url,
                'items' => [],
                'label' => $node->getI18n('name'),
                'depth' => $node->depth,
            ];
            // Number of stack items
            $l = count($stack);

            // Check if we're dealing with different levels
            while ($l > 0 && $stack[$l - 1]['depth'] >= $node->depth) {
                array_pop($stack);
                $l--;
            }

            // Stack is empty (we are inspecting the root)
            if ($l == 0) {
                // Assigning the root node
                $i = count($trees);
                $trees[$i] = $item;
                $stack[] = & $trees[$i];
            } else {
                // Add node to parent
                $i = count($stack[$l - 1]['items']);
                $stack[$l - 1]['items'][$i] = $item;
                $stack[] = & $stack[$l - 1]['items'][$i];
            }
        }
        return $trees;
    }
    
    public function afterDelete()
    {
        foreach ($this->getTree() as $item) {
            $item->delete();
        }
        return parent::afterDelete();
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $item = new CategoryItem;
            $item->code = 'root';
            $item->category_id = $this->id;
            $item->makeRoot(false);
            $item->setI18n(
                ['parent_id' => $item->id, 'name' => 'root'], 
                Yii::$app->getModule('language')->getDefault(), 
                true
            );
        }
    }
    
    public function getEditLink($params = [])
    {
        return  (App::isFront() ? param('backend-link') : '') . 
            Url::toRoute(['/category/categories/admin', 'category_id' => $this->id]);
    }
    
    public static function getCreateUrl($params = [])
    {
        $params[0] = '/category/categories/create';
        return Url::toRoute($params); 
    }
}