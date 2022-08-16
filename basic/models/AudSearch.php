<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Aud;

/**
 * AudSearch represents the model behind the search form of `app\models\Aud`.
 */
class AudSearch extends Aud
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aud_id', 'aud_count'], 'integer'],
            [['aud_title'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Aud::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'aud_id' => $this->aud_id,
            'aud_count' => $this->aud_count,
        ]);

        $query->andFilterWhere(['like', 'aud_title', $this->aud_title]);

        return $dataProvider;
    }
}
