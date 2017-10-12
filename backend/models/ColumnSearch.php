<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Column;

/**
 * ColumnSearch represents the model behind the search form about `common\models\Column`.
 */
class ColumnSearch extends Column
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'order_number', 'pid'], 'integer'],
            [['name', 'url', 'create_time', 'class', 'remark'], 'safe'],
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
        $query = Column::find();

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
            $query->andFilterWhere(['between', 'time_create', $this->time_create_from,$this->time_create_to]);
        }elseif($this->time_create_from!=''){
            $query->andFilterWhere(['>=', 'time_create', $this->time_create_from]);
        }elseif($this->time_create_to!=''){
            $query->andFilterWhere(['<=', 'time_create', $this->time_create_to]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'order_number' => $this->order_number,
            'create_time' => $this->create_time,
            'pid' => $this->pid,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
