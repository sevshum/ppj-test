<?php
namespace app\modules\blog\models;

use app\modules\core\components\AppActiveRecord;
use app\modules\core\helpers\App;
use Yii;
use yii\helpers\Url;


class Post extends AppActiveRecord
{
    use \app\modules\core\traits\I18nActiveRecordTrait;
    use \app\modules\category\traits\CategoryTrait;
    use \app\modules\attachment\traits\UploadTrait;
    
    const DRAFT = '0';
    const PUBLISHED = '1';
    const ARCHIVED = '2';
    
    public $i18nModel = '\app\modules\blog\models\PostI18n';

    public static function tableName()
    {
        return 'posts';
    }

    public function rules()
    {
        return [
            [
                'image', '\maxlapko\components\ImageValidator',
                'extensions' => ['jpg', 'png', 'jpeg'], 'maxSize' => 5 * 1024 * 1024, 
//                'minWidth' => 10, 'minHeight' => 10
            ],
            [['stringTags', 'status', 'published_at', 'category_id', 'translateattrs', 'slug'], 'safe'],
            
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'slug'         => Yii::t('app', 'Slug'),
            'stringTags'   => Yii::t('app', 'Tags'),
            'image'        => Yii::t('app', 'Image'),
            'published_at' => Yii::t('app', 'Publish date'),
            'status'       => Yii::t('app', 'Status'),
            'category_id'  => Yii::t('app', 'Category'),
        ];
    }
    
    public function getTags()
    {
        return $this->hasMany(PostTag::className(), ['id' => 'tag_id'])
            ->viaTable('posts_tags', ['post_id' => 'id']);
    }
    
    /**
     * @return PostQuery
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
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
            'slugable' => [
                'class' => '\app\modules\core\components\behaviors\SlugBehavior',
                'enabled' => $this->getIsNewRecord(),
                'sourceAttribute' => function($owner) {
                    return trim($owner->slug) === '' ? 
                        $owner->getI18n('title', false, Yii::$app->getModule('language')->getDefault()) :
                        $owner->slug;
                }
            ],
            'mImage' => ['class' => '\maxlapko\components\ImageBehavior'],
            'taggable' => [
                'class'                   => '\app\modules\core\components\behaviors\TaggableBehavior',
                'tagTable'                => 'post_tags',
                'tagBindingTable'         => 'posts_tags',
                'modelTableFk'            => 'post_id',
                'tagTablePk'              => 'id',
                'tagTableName'            => 'name',
                'tagTableCount'           => 'frequency',
                'tagBindingTableTagId'    => 'tag_id',
                'cacheID'                 => false,
                'createTagsAutomatically' => true,
            ]
        ];
    }
    
    public function getUrl()
    {
        $url = Url::toRoute(['/blog/posts/show', 'slug' => $this->slug]);
        if (!App::isFront()) {
            $url = strtr($url, [param('backend-link') => '']);
        }
        return $url;
    }
    
    public function getEditUrl($params = [])
    {
        $params['id'] = $this->id;
        $params[0] = '/blog/posts/update';
        return (App::isFront() ? param('backend-link') : '') . Url::toRoute($params);
    }
    
    public static function getArchive()
    {
        return static::getDb()->createCommand('
            SELECT DATE_FORMAT(published_at, "%Y-%m") AS archive_date, COUNT(*) AS post_count
            FROM posts
            WHERE 
                posts.`status` = ' . self::PUBLISHED . '
            GROUP BY DATE_FORMAT(published_at, "%Y-%m")
            ORDER BY archive_date DESC'
        )->queryAll();
    }

    public function getStringTags()
    {
        return implode(', ', $this->getBehavior('taggable')->getTags());
    }
    
    public function setStringTags($tags)
    {
        return $this->getBehavior('taggable')->setTags($tags);
    }
    
    public static function getIds()
    {
        static $ids;
        if ($ids === null) {
            $cache = Yii::$app->getCache();
            if (($ids = $cache->get('post_ids')) === false) {
                $ids = static::find()->select('id')->published()->resent()->createCommand()->queryColumn();
                $cache->set('post_ids', $ids);
            }
        }
        return $ids;
    }
    
    public function getNext($lang = null)
    {
        $ids = self::getIds();
        if (($i = array_search($this->id, $ids)) !== false && isset($ids[$i + 1])) {
            return static::find()->withLang($lang)->where(['id' => $ids[$i + 1]])->one();
        }
        return null;
    }
    
    public function getPrev($lang = null)
    {
        $ids = self::getIds();
        if (($i = array_search($this->id, $ids)) !== false && isset($ids[$i - 1])) {
            return static::find()->withLang($lang)->where(['id' => $ids[$i + 1]])->one();
        }
        return null;
    }
    
    public function beforeSave($insert)
    {
        $this->published_at = date('Y-m-d', strtotime($this->published_at));
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->published_at = date('Y-m-d', strtotime($this->published_at));
        return parent::afterFind();
    }
    
    public static function statuses($status = null)
    {
        static $statuses = [
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived',
        ];
        return $status === null ? $statuses : $statuses[$status];
    }

}