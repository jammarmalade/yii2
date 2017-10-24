<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Comment;

/**
 * CommentSearch represents the model behind the search form about `common\models\Comment`.
 */
class CommentSearch extends Comment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rid', 'ruid', 'aid', 'authorid', 'like', 'type', 'status'], 'integer'],
            [['username', 'author', 'content', 'create_time'], 'safe'],
            [['time_create_from','time_create_to',], 'safe'],
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Comment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if($this->time_create_from!='' && $this->time_create_to!=''){
            $query->andFilterWhere(['between', 'create_time', $this->time_create_from,$this->time_create_to]);
        }elseif($this->time_create_from!=''){
            $query->andFilterWhere(['>=', 'create_time', $this->time_create_from]);
        }elseif($this->time_create_to!=''){
            $query->andFilterWhere(['<=', 'create_time', $this->time_create_to]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'rid' => $this->rid,
            'ruid' => $this->ruid,
            'aid' => $this->aid,
            'authorid' => $this->authorid,
            'like' => $this->like,
            'type' => $this->type,
            'status' => $this->status,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
