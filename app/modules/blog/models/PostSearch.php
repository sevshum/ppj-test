<?php
namespace app\modules\blog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class PostSearch extends Post
{
    public $searchTitle;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'searchTitle'], 'safe'],
        ];
    }
    

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find()->select('posts.*')
            ->innerJoin('post_i18ns', '`post_i18ns`.`parent_id` = `posts`.`id`')
            ->groupBy('`posts`.`id`');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'       => ['defaultOrder' => '`published_at` DESC'],
            'pagination' => ['pageSize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'post_i18ns.title', $this->searchTitle]);

        return $dataProvider;
    }
}