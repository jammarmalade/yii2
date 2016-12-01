<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\UserBackend;

/**
 * UserBackendSearch represents the model behind the search form about `backend\models\UserBackend`.
 */
class UserBackendSearch extends UserBackend
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'notice', 'group_id', 'status'], 'integer'],
            [['username', 'password', 'email', 'time_login', 'time_register'], 'safe'],
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
        $query = UserBackend::find();

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
            'notice' => $this->notice,
            'group_id' => $this->group_id,
            'time_login' => $this->time_login,
            'time_register' => $this->time_register,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
