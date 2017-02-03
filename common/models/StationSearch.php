<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Station;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use ReflectionClass;

class StationSearch extends Station
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'string', 'max' => 255],
            [['region'], 'integer'],
        ];
    }

    public function formName ()
    {
        return '';
    }


    public function search($params, $all = false)
    {
        $query = Station::find();

        if ($all)
            $adpParams = [
              'query' => $query,
              'totalCount' => 1000,
              'pagination' => ['pageSize' => 1000],
              'sort' => [
                  'defaultOrder' => [
                      'name' => SORT_ASC,
                  ]
              ],
            ];
        else
            $adpParams = ['query' => $query,
                  'pagination' => ['pageSize' => 10],
                  'sort' => [
                      'defaultOrder' => [
                          'name' => SORT_ASC,
                      ]
                  ],
            ];


        $dataProvider = new ActiveDataProvider($adpParams);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

        $query->andFilterWhere([
            'id' => $this->id,
            'region' => $this->region,
        ]);


        return $dataProvider;
    }

    public static function getStationsArray()
	{
        $items = array();
        $items[0] = 'Не указано';
        $result = Station::find()->orderBy('name')->all();
        $tmp = ArrayHelper::map($result,'id','name');
        $items = $items + $tmp;
        return $items;
	}


}
