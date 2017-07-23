<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Source;

/**
 * SourceSearch represents the model behind the search form about `backend\models\Source`.
 */
class SourceSearch extends Source
{
    public $imagePath;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'page', 'status', 'count','digest'], 'integer'],
            [['name', 'sid', 'surl', 'subject', 'content', 'tags', 'path', 'psid', 'exe_time', 'time_create','time_create_from','time_create_to'], 'safe'],
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
        $query = Source::find();
//        $query->select('s.*,si.path as ipath')->from('{{%source}} AS s')->leftJoin('{{%source_image}} si', 'si.psid = s.id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->orderBy('id DESC');
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'page' => $this->page,
            'status' => $this->status,
            'digest' => $this->digest,
            'count' => $this->count,
            'exe_time' => $this->exe_time,
            'time_create' => $this->time_create,
        ]);
        if($this->time_create_from!='' && $this->time_create_to!=''){
            $query->andFilterWhere(['between', 'time_create', $this->time_create_from,$this->time_create_to]);
        }elseif($this->time_create_from!=''){
            $query->andFilterWhere(['>=', 'time_create', $this->time_create_from]);
        }elseif($this->time_create_to!=''){
            $query->andFilterWhere(['<=', 'time_create', $this->time_create_to]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sid', $this->sid])
            ->andFilterWhere(['like', 'surl', $this->surl])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'psid', $this->psid]);

        return $dataProvider;
    }
    
}
