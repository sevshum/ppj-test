<?php

namespace app\modules\comment\models;

use app\modules\core\components\AppActiveRecord;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $entity_type
 * @property integer $entity_id
 * @property string $author_name
 * @property string $author_email
 * @property integer $user_id
 * @property string $content
 * @property integer $status
 * @property integer $rating
 * @property string $request_info
 * @property string $created_at
 */
class Comment extends AppActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_APPROVED = 1;
    const STATUS_DELETED = 2;
    
    /**
     * @var Comment[] 
     */
    public $children = [];
    public $verifyCode;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    public static function urlToLinkPattern()
    {
        return '/\b(?:(?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4‌​}\/)(?:[^\s()<>]+|((?:[^\s()<>]+|(?:([^\s()<>]+)))*))+(?:((?:[^\s()<>]+|(?:\‌​([^\s()<>]+)))*)|[^\s`!()[]{};:\'".,<>?«»“”‘’]))/';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['user_id', 'status', 'parent_id'], 'integer'],
            [['content'], 'string'],
            [['rating'], 'number'],
            [['author_name', 'author_email'], 'string', 'max' => 255],
            [['author_name'], 'required', 'when' => function($model) {
                return (boolean) !$model->user_id;
            }],
            ['verifyCode', 'captcha'],
        ];
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
                    AppActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
            'attribute' => [
                'class' => 'yii\behaviors\AttributeBehavior',
                'value' => function() {
                    $request = Yii::$app->getRequest();
                    return Json::encode([
                        'user_ip' => $request->getUserIP(),
                        'user_agent' => $request->getUserAgent(),
                        'referrer' => $request->getReferrer(),
                    ]);
                },
                'attributes' => [
                    AppActiveRecord::EVENT_BEFORE_INSERT => 'request_info',
                ],
            ],
        ];
    }
    
    /**
     * 
     * @param string $type
     * @param integer $id
     * 
     * @return \app\modules\core\components\AppActiveQuery 
     */
    public static function getModelQuery($type, $id)
    {
        return static::find()
            ->where(['entity_type' => $type, 'entity_id' => $id])
            ->byStatus(self::STATUS_APPROVED)
            ->orderBy(['created_at' => SORT_ASC]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'author_name'  => Yii::t('app', 'Your Name'),
            'author_email' => Yii::t('app', 'Your Email'),
            'content'      => Yii::t('app', 'Review'),
            'rating'      => Yii::t('app', 'Rating'),
            'status'       => Yii::t('app', 'Status'),
            'request_info' => Yii::t('app', 'Request Info'),
            'verifyCode' => 'Verification Code',
        ];
    }

    public function getHandledContent()
    {
        $content = Html::encode($this->content);
        return preg_replace(self::urlToLinkPattern(), "<a data-pjax=\"0\" rel=\"nofollow\" href=\"\\0\">\\0</a>", $this->content);
    }

    public function search($params) 
    {
        $query = static::find()->orderBy(['status' => SORT_ASC, 'created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        if (!$this->load($params)) {
            return $dataProvider;
        }

        $query->andFilterWhere(['status' => $this->status]);

        return $dataProvider;
    }
    
    public static function statuses($status = null)
    {
        static $statuses = [
            self::STATUS_NEW => 'New',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_DELETED => 'Deleted',
        ];
        return $status === null ? $statuses : $statuses[$status];
    }
    
    /**
     * @paran \yii\web\User $user
     * @return boolean
     */
    public function create($user)
    {
        if ($this->parent_id) {
            $parent = static::findOne($this->parent_id);
            if ($parent === null || $parent->entity_type !== $this->entity_type || $parent->entity_id != $this->entity_id) {
                throw new NotFoundHttpException('The parent comment is invalid.');
            }
        }
        $this->status = param('comments.need_approve') ? self::STATUS_NEW : self::STATUS_APPROVED;
        if ($this->user_id = $user->getId()) {
            $this->author_name = $user->getIdentity()->username;
        }
        return $this->save();
    }
}