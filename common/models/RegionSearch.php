<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Region;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use ReflectionClass;

/**
 * RegionSearch represents the model behind the search form about `common\models\Region`.
 */
class RegionSearch extends Region
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getArray()
	{
        return RegionSearch::doGetRegionsArray(true);
	}

    public static function getArrayWithoutNullItem()
	{
        return RegionSearch::doGetRegionsArray(false);
	}

    public static function getArrayForProfile()
	{
        return RegionSearch::doGetRegionsArray(true, true);
	}

    public static function doGetRegionsArray($all = true, $none = false)
	{
        $regions = array();
        if ($all)
          $regions[0] = $none ? 'Не установлен' : 'Все регионы';
        $result = Region::find()->where(['parent' => 0])->orderBy('name')->all();
        $subregions = ArrayHelper::map($result,'id','name');
        $regions = $regions + $subregions;
        return $regions;
	}

    public function search($params, $all = false)
    {
        $query = Region::find()->where(['parent' => 0]);

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

        //Yii::info($params,'myd');
        // //Загружаем данные из GET, пришедшие из текстового поля поиска (параметр CenterSearch[text])
        // if (isset($params['CenterSearch']['text']))
        //     $this->text = $params['CenterSearch']['text'];

        // Yii::info('параметры, пришедшие в search: ', 'myd');
        // Yii::info($params, 'myd');
        // Yii::info('this->is24x7: '.$this->is24x7, 'myd');
        // Yii::info('this->price_month_max: '.$this->price_month_max, 'myd');

        //Загружаем данные из GET, пришедшие из формы поиска (остальные параметры вида CenterSearch[region])
        $this->load($params);

        // Yii::info('this->is24x7: '.$this->is24x7, 'myd');
        // Yii::info('this->price_month_max: '.$this->price_month_max, 'myd');


        if (!$this->validate())
            return $dataProvider;

        return $dataProvider;
    }

}
