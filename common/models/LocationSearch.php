<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Location;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use ReflectionClass;

class LocationSearch extends Location
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
            [['name', 'name_tp', 'alias', 'address_atom'], 'string', 'max' => 255],
            [['region'], 'integer'],
        ];
    }

    public function formName ()
    {
        return '';
    }


    public function search($params, $all = false)
    {
        $query = Location::find();

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

    public static function getLocationsArray()
	{
        $items = array();
        $items[0] = 'Не указано';
        $result = Location::find()->orderBy('name')->all();
        $tmp = ArrayHelper::map($result,'id','name');
        $items = $items + $tmp;
        return $items;
	}


}
