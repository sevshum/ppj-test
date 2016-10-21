<?php
namespace app\modules\blog\models;

use app\modules\core\components\AppActiveRecord;
use Yii;
use yii\helpers\Url;

class PostTag extends AppActiveRecord
{
    /**
     * The followings are the available columns in table 'tbl_tag':
     * @var integer $id
     * @var string $name
     * @var integer $type
     * @var integer $frequency
     */

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'post_tags';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['frequency', 'type'], 'numerical', 'integerOnly' => true],
            ['name', 'string', 'max' => 128],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'name'      => Yii::t('app', 'Name'),
            'frequency' => Yii::t('app', 'Frequency'),
        ];
    }

    /**
     * Returns tag names and their corresponding weights.
     * Only the tags with the top weights will be returned.
     * @param integer $limit the maximum number of tags that should be returned
     * @param integer $type
     * @return array weights indexed by tag names.
     */
    public function findTagWeights($limit = 20, $type = null)
    {
        $find = static::find()
            ->where('frequency > 0')
            ->orderBy(['frequency' => SORT_DESC])
            ->limit($limit);
        if ($type !== null) {
            $find->andWhere(['type' => $type]);
        }
        return $find->all();
    }

    /**
     * Suggests a list of existing tags matching the specified keyword.
     * @param string the keyword to be matched
     * @param integer maximum number of tags to be returned
     * @return array list of matching tag names
     */
    public static function suggest($keyword, $limit = 20)
    {
        $tags = static::find()
            ->select(['id', 'name'])
            ->where(['like', 'name', $keyword])
            ->asArray(true)
            ->orderBy(['frequency' => SORT_DESC, 'name' => SORT_ASC])
            ->asArray(true)
            ->limit($limit)
            ->all();
        $names = [];
        foreach ($tags as $tag) {
            $names[] = ['id' => $tag['id'], 'text' => $tag['name']];
        }
        return $names;
    }
    
    public function getUrl()
    {
        return Url::toRoute(['/blog/posts/index', 'tag' => $this->id . '-' . $this->name]);
    }

}