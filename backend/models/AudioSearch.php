<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Audio;

/**
 * AudioSearch represents the model behind the search form about `common\models\Audio`.
 */
class AudioSearch extends Audio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'spd', 'pit', 'vol', 'per', 'status'], 'integer'],
            [['content', 'path', 'time_create'], 'safe'],
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
        $query = Audio::find();

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
            'uid' => $this->uid,
            'spd' => $this->spd,
            'pit' => $this->pit,
            'vol' => $this->vol,
            'per' => $this->per,
            'status' => $this->status,
            'time_create' => $this->time_create,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'path', $this->path]);

        return $dataProvider;
    }
}
