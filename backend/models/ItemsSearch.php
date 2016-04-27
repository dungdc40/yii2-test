<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Items;

/**
 * ItemsSearch represents the model behind the search form about `backend\models\Items`.
 */
class ItemsSearch extends Items
{		
	
	public $vendor;
	public $type;
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['vendor','type', 'item_name', 'serial_number', 'color', 'release_date', 'photo', 'tags', 'created_date'], 'safe'],
            [['price', 'weight'], 'number'],		
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
        $query = Items::find();
		$query->joinWith(['vendor', 'type']);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query			
        ]);
		
		$dataProvider->setSort([
           'attributes' => [
               'vendor' => [
					'asc' => ['vendors.name' => SORT_ASC],
					'desc' => ['vendors.name' => SORT_DESC],
				],
				'type' => [
					'asc' => ['types.name' => SORT_ASC],
					'desc' => ['types.name' => SORT_DESC],
				],
			   'item_name',
			   'price',
			   'serial_number', 
			   'color'
           ]
        ]);
	
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }		
		
        $query->andFilterWhere([
            'id' => $this->id,            
            'price' => $this->price,
            'weight' => $this->weight,
            'release_date' => $this->release_date,
            'created_date' => $this->created_date,
        ]);
		
		
        $query->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'vendors.name', $this->vendor])
            ->andFilterWhere(['like', 'types.name', $this->type]);

        return $dataProvider;
    }
}
