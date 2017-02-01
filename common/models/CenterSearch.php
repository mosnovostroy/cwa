<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Center;
use common\models\Station;
use yii\helpers\Html;
use ReflectionClass;

/**
 * CenterSearch represents the model behind the search form about `common\models\Center`.
 */
class CenterSearch extends Center
{
    public $price_month_min;
    public $price_month_max;
    public $text;
    public $metro;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is24x7'], 'integer'],
            [['name', 'description', 'meta_title', 'meta_description', 'meta_keywords','text'], 'safe'],
            [['gmap_lat', 'gmap_lng', 'region', 'rating', 'price_month', 'price_month_min', 'price_month_max', 'metro', 'location'], 'number'],
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
            $fields['price_month_min'] = 'price_month_min';
        //if ($this->price_day_max)
            $fields['price_month_max'] = 'price_month_max';
        //if ($this->text)
            $fields['text'] = 'text';
            $fields['metro'] = 'metro';

        return $fields;
    }

    public function formName ()
    {
        return '';
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
        $this->load($params);

        if (!$this->validate())
            return new ActiveDataProvider(['query' => Center::find()]);

        //if (isset($params['metro'])) {
        if ($this->metro) {
            $mc = Station::findOne($this->metro);
            if ($mc && isset($mc['lat']) && isset($mc['lng'])) {
                $dist = '(gmap_lat-'.$mc["lat"].')*(gmap_lat-'.$mc["lat"].')+(gmap_lng-'.$mc["lng"].')*(gmap_lng-'.$mc["lng"].')';
                $query = Center::find()
                    ->select(['*', 'dist' => $dist])
                    ->from('center')
                    ->where(['not',['gmap_lat' => null]])
                    ->andWhere(['not',['gmap_lng' => null]])
                    ->orderBy('dist ASC');
            }
            else {
                $query = Center::find();
            }
        } else {
            $query = Center::find();
        }

        if ($all) {
            $adpParams = [
              'query' => $query,
              'totalCount' => 1000,
              'pagination' => ['pageSize' => 1000],
            ];
        } else if (isset($params['metro'])) {
            $adpParams = ['query' => $query,
                  'pagination' => ['pageSize' => 10],
            ];
        } else {
            $adpParams = ['query' => $query,
                  'pagination' => ['pageSize' => 10],
                  'sort' => [
                      'defaultOrder' => [
                          'name' => SORT_ASC,
                      ]
                  ],
            ];
        }

        $dataProvider = new ActiveDataProvider($adpParams);

        $query->andFilterWhere([
            'id' => $this->id,
            'region' => $this->region,
            'location' => $this->location,
            'is24x7' => $this->is24x7,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'name', $this->text]);

        $query->andFilterWhere(['>=', 'price_month', $this->price_month_min])
              ->andFilterWhere(['<=', 'price_month', $this->price_month_max]);


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

    public function searchForMainPage()
    {
        $regionId = Yii::$app->regionManager->id;

        if ($regionId) {
            $query = Center::findBySql(
                'SELECT *
                FROM center
                WHERE region = :region
                ORDER BY RAND()',
                [
                    ':region' => $regionId,
                ]
            );
        }
        else {
            $query = Center::findBySql(
                'SELECT *
                FROM center
                ORDER BY RAND()'
            );
        }

        $adpParams = ['query' => $query,
              'pagination' => ['pageSize' => 10],
        ];

        $dataProvider = new ActiveDataProvider($adpParams);

        return $dataProvider;
    }

    public function searchClosest($center)
    {
        $query = Center::findBySql(
            'SELECT *,
            (gmap_lat-:lat)*(gmap_lat-:lat)+(gmap_lng-:lng)*(gmap_lng-:lng) AS `dist`
            FROM center
            WHERE gmap_lat IS NOT NULL
                AND gmap_lng IS NOT NULL
                AND id <> :id
            ORDER BY dist ASC
            LIMIT 3',
            [
                ':lat' => $center->gmap_lat,
                ':lng' => $center->gmap_lng,
                ':id' => $center->id,
            ]
        );

        $adpParams = ['query' => $query,
              'pagination' => ['pageSize' => 10],
        ];

        $dataProvider = new ActiveDataProvider($adpParams);

        return $dataProvider;
    }

}
