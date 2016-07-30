<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Center;
use yii\helpers\Html;

/**
 * CenterSearch represents the model behind the search form about `common\models\Center`.
 */
class CenterSearch extends Center
{
    // public $price_day_min;
    // public $price_day_max;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'description', 'meta_title', 'meta_description', 'meta_keywords'], 'safe'],
            [['gmap_lat', 'gmap_lng', 'region', 'rating', 'price_day'], 'number'],
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
    public function search($params, $all = false)
    {
        $query = Center::find();

        if ($all)
            $adpParams = ['query' => $query];
        else
            $adpParams = ['query' => $query,
                  'pagination' => ['pageSize' => 1],
                  'sort' => [
                      'defaultOrder' => [
                          'price_day' => SORT_ASC,
                      ]
                  ],
            ];


        $dataProvider = new ActiveDataProvider($adpParams);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

        $this->initMembers();

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gmap_lat' => $this->gmap_lat,
            'gmap_lng' => $this->gmap_lng,
            'region' => $this->region,
            'price_day' => $this->price_day,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords]);

        //$query->andFilterWhere(['between', 'price_day', $this->price_day_min, $this->price_day_max]);

        // Yii::info($query,'myd');
        // Yii::info($this->price_day_min,'myd');
        // Yii::info($this->price_day_max,'myd');

        return $dataProvider;
    }

    public function searchCoords($params)
    {
        $result = $this->search($params, true)->getModels();

        $coords_data = array();
        $coords_data['type'] = 'FeatureCollection';
        $coords_data['features'] = array();

        $coords_item = array();

        foreach($result as $row)
        {
            $coords_item['type'] = 'Feature';
            $coords_item['id'] = $row['id'];
            $coords_item['geometry'] =  [
                'type' => 'Point',
                'coordinates' => [$row['gmap_lat'], $row['gmap_lng']]
            ];
            $coords_item['properties'] = [
                'balloonContent' => Html::a($row['name'], ['center/view', 'id' => $row['id']]),
                'clusterCaption' => 'Еще одна метка',
                'hintContent' => $row['name']
            ];
            $coords_data['features'][] = $coords_item;
        }
        return json_encode($coords_data);
    }
}
