<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Record;

/**
 * RecordSearch represents the model behind the search form about `backend\models\Record`.
 */
class RecordSearch extends Record
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'type', 'imgstatus','status'], 'integer'],
            [['username', 'content', 'weather', 'remark', 'time_create','time_create_from','time_create_to',], 'safe'],
            [['account', 'longitude', 'latitude'], 'number'],
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
        $query = Record::find();

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
            'account' => $this->account,
            'type' => $this->type,
            'imgstatus' => $this->imgstatus,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'time_create' => $this->time_create,
        ]);
        if($this->time_create_from!='' && $this->time_create_to!=''){
            $query->andFilterWhere(['between', 'time_create', $this->time_create_from,$this->time_create_to]);
        }elseif($this->time_create_from!=''){
            $query->andFilterWhere(['>=', 'time_create', $this->time_create_from]);
        }elseif($this->time_create_to!=''){
            $query->andFilterWhere(['<=', 'time_create', $this->time_create_to]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'weather', $this->weather])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
