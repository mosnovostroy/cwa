<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Arenda;
use yii\helpers\Html;
use ReflectionClass;
use common\behaviors\ImageBehavior;

/**
 * ArendaSearch represents the model behind the search form about `common\models\Arenda`.
 */
class ArendaSearch extends Arenda
{
    public $text;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'description', 'meta_title', 'meta_description', 'meta_keywords','text'], 'safe'],
            [['gmap_lat', 'gmap_lng', 'region', 'rating', 'contacts'], 'number'],
			[['createdBy', 'updatedBy', 'createdAt', 'updatedAt'], 'integer'],
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
    public function search($params, $all = false, $my = false)
    {
        $query = Arenda::find();

        if ($all)
            $adpParams = ['query' => $query];
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
        // //Загружаем данные из GET, пришедшие из текстового поля поиска (параметр ArendaSearch[text])
        // if (isset($params['ArendaSearch']['text']))
        //     $this->text = $params['ArendaSearch']['text'];

        //Загружаем данные из GET, пришедшие из формы поиска (остальные параметры вида ArendaSearch[region])
        if ($my && !Yii::$app->user->isGuest)
        {
            $this->createdBy = Yii::$app->user->identity->id;
        }
        else
        {
            $this->load($params);
        }

        if (!$this->validate())
            return $dataProvider;

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gmap_lat' => $this->gmap_lat,
            'gmap_lng' => $this->gmap_lng,
            'region' => $this->region,
            'createdBy' => $this->createdBy,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])

            ->andFilterWhere(['like', 'name', $this->text]);


        return $dataProvider;
    }

    public function searchCoords($params)
    {
        $result = $this->search($params, true, false)->getModels();

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
                'balloonContent' => Html::a($row['name'], ['arenda/view', 'id' => $row['id']]),
                'clusterCaption' => 'Еще одна метка',
                'hintContent' => $row['name']
            ];
            $coords_data['features'][] = $coords_item;
        }
        return json_encode($coords_data);
    }

    public function searchMy()
    {
        return $this->search([], false, true);
    }
}
