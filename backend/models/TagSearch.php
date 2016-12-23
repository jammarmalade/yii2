<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Tag;

/**
 * TagSearch represents the model behind the search form about `backend\models\Tag`.
 */
class TagSearch extends Tag
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status'], 'integer'],
            [['name', 'time_create','time_create_from','time_create_to','username' ,'content', 'img', 'time_update'], 'safe'],
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
        $query = Tag::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'uid' => $this->uid,
            'time_update' => $this->time_update,
            'status' => $this->status,
        ]);
        
        if($this->time_create_from!='' && $this->time_create_to!=''){
            $query->andFilterWhere(['between', 'time_create', $this->time_create_from,$this->time_create_to]);
        }elseif($this->time_create_from!=''){
            $query->andFilterWhere(['>=', 'time_create', $this->time_create_from]);
        }elseif($this->time_create_to!=''){
            $query->andFilterWhere(['<=', 'time_create', $this->time_create_to]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'img', $this->img]);

        return $dataProvider;
    }
}
