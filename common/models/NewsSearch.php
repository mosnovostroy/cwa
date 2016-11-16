<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\News;
use yii\helpers\Html;
use ReflectionClass;

/**
 * NewsSearch represents the model behind the search form about `common\models\News`.
 */
class NewsSearch extends News
{
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
        $query = News::find();

        if ($all)
            $adpParams = [
              'query' => $query,
              'totalCount' => 1000,
              'pagination' => ['pageSize' => 1000],
            ];
        else
            $adpParams = ['query' => $query,
                  'pagination' => ['pageSize' => 10],
                  'sort' => [
                      'defaultOrder' => [
                          'createdAt' => SORT_DESC,
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'gmap_lat' => $this->gmap_lat,
            // 'gmap_lng' => $this->gmap_lng,
            'region' => $this->region,
            'is_lead' => $this->is_lead,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords]);

        return $dataProvider;
    }

    public function searchLead()
    {
        $query = News::findBySql('SELECT * FROM news WHERE is_lead=1 ORDER BY createdAt DESC LIMIT 1');
        $adpParams = ['query' => $query, 'pagination' => ['pageSize' => 10]];
        $dataProvider = new ActiveDataProvider($adpParams);
        return $dataProvider;
    }

    public function searchOther()
    {
        $lead = Yii::$app->db->createCommand('SELECT id FROM news WHERE is_lead=1 DESC LIMIT 1')
			->queryScalar();

        $query = News::findBySql(
            'SELECT * FROM news WHERE id!=:lead ORDER BY createdAt DESC LIMIT 5',
            [
                ':lead' => $lead,
            ]
        );
        $adpParams = ['query' => $query, 'pagination' => ['pageSize' => 10]];
        $dataProvider = new ActiveDataProvider($adpParams);
        return $dataProvider;
    }

}
