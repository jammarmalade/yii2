<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Image;

/**
 * ImageSearch represents the model behind the search form about `backend\models\Image`.
 */
class ImageSearch extends Image
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'type', 'size', 'width', 'height', 'width_thumb', 'height_thumb', 'status'], 'integer'],
            [['username', 'path', 'exif', 'time_create'], 'safe'],
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
        $query = Image::find();

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
            'uid' => $this->uid,
            'type' => $this->type,
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'width_thumb' => $this->width_thumb,
            'height_thumb' => $this->height_thumb,
            'status' => $this->status,
            'time_create' => $this->time_create,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'exif', $this->exif]);

        return $dataProvider;
    }
}
