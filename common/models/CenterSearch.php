<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Center;
use yii\helpers\Html;
use ReflectionClass;

/**
 * CenterSearch represents the model behind the search form about `common\models\Center`.
 */
class CenterSearch extends Center
{
    public $price_day_min;
    public $price_day_max;
    public $text;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'description', 'meta_title', 'meta_description', 'meta_keywords','text'], 'safe'],
            [['gmap_lat', 'gmap_lng', 'region', 'rating', 'price_day', 'price_day_min', 'price_day_max'], 'number'],
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

    public function fields()
    {
        $fields = parent::fields();
        //if ($this->price_day_min)
            $fields['price_day_min'] = 'price_day_min';
        //if ($this->price_day_max)
            $fields['price_day_max'] = 'price_day_max';
        //if ($this->text)
            $fields['text'] = 'text';

        return $fields;
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
                  'pagination' => ['pageSize' => 3],
                  'sort' => [
                      'defaultOrder' => [
                          'price_day' => SORT_ASC,
                      ]
                  ],
            ];


        $dataProvider = new ActiveDataProvider($adpParams);

        //Yii::info($params,'myd');
        // //Загружаем данные из GET, пришедшие из текстового поля поиска (параметр CenterSearch[text])
        // if (isset($params['CenterSearch']['text']))
        //     $this->text = $params['CenterSearch']['text'];

        //Загружаем данные из GET, пришедшие из формы поиска (остальные параметры вида CenterSearch[region])
        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gmap_lat' => $this->gmap_lat,
            'gmap_lng' => $this->gmap_lng,
            'region' => $this->region,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])

            ->andFilterWhere(['like', 'name', $this->text]);

        $query->andFilterWhere(['>=', 'price_day', $this->price_day_min])
              ->andFilterWhere(['<=', 'price_day', $this->price_day_max]);

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
