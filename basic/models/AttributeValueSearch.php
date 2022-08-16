<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Attribute;

/**
 * AttributeValueSearch represents the model behind the search form of `app\models\AttributeValue`.
 */
class AttributeValueSearch extends AttributeValue
{
    public $attributeName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_value_id', 'attribute_id'], 'integer'],
            [['value', 'attributeName'], 'safe'],
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
        $query = AttributeValue::find();
       // $query->joinWith(['Attribute']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        // $dataProvider->sort->attributes['attribute.attribute_name'] = [
        //     'asc' => [Attribute::tableName().'.attribute_name' => SORT_ASC],
        //     'desc' => [Attribute::tableName().'.attribute_name' => SORT_DESC]
         
        // ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'attribute_value_id' => $this->attribute_value_id,
            'attribute_id' => $this->attribute_id,
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);
              //->andFilterWhere(['like', Attribute::tableName().'.attribute_name', $this->attributeName]);

        return $dataProvider;
    }
}
