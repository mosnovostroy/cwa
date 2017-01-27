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
    // Когда отображается список новостей конкретного коворкинга, в этой переменной сохраняется его имя
    public $centerName;

    // Переменная для "промежуточного" хранения id главной новости между двумя вызовами разных search
    // Без нее бы пришлось ресурсоемкие запросы повторять два раза.
    protected $lead_id;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
    * Формирует нужное SQL-выражение. Вызывается тремя способами.
    * 1) getSql() - "стандартный" SQL для дата-провайдера (список новостей с поиском по строке и пр.)
    * 2) isSpecial => true - SQL для поиска id "главных" новостей (в текущей версии всего одна главная новость)
    * 3) isSpecial=>true, excludedIds => [..]) - SQL для поиска остальных новостей для анонсной страницы.
    */
    protected function getSql($options = [])
    {
        $isSpecial = isset($options['isSpecial']) ? $options['isSpecial'] : false;

        $excludedIds = isset($options['excludedIds']) ? $options['excludedIds'] : [];

        $isEvent = isset($options['isEvent']) ? $options['isEvent'] : false;

        if (!$isSpecial) {
            $specCond = '';
            $limit = '';
        } else if ( count($excludedIds) > 0 ) {
            $specCond = ' AND n.id NOT IN ('.(implode(",", $excludedIds)).') ';
            $limit = ' LIMIT 5 ';
        } else {
            $specCond = ' AND n.is_lead = 1 ';
            $limit = ' LIMIT 1 ';
        }

        if ($isEvent) {
            return
                '(SELECT n.*
                FROM 	news AS n,
                        news_center AS nc,
                        center AS c
                WHERE 	n.id = nc.news_id
                        AND nc.center_id = c.id
                        AND c.region = :region_id
                        AND FROM_UNIXTIME(n.eventAt) >= CURDATE()
                        '.$specCond.')

                UNION

                (SELECT  n.*
                FROM 	news AS n,
                        news_region AS nr
                WHERE 	n.id = nr.news_id
                        AND nr.region_id = :region_id
                        AND FROM_UNIXTIME(n.eventAt) >= CURDATE()
                        '.$specCond.')

                UNION

                (SELECT  n.*
                FROM 	news AS n
                WHERE 	n.id NOT IN (SELECT DISTINCT news_id FROM news_region)
                        AND n.id NOT IN (SELECT DISTINCT news_id FROM news_center)
                        AND FROM_UNIXTIME(n.eventAt) >= CURDATE()
                        '.$specCond.')

                ORDER BY eventAt';
        } else {
            return
                '(SELECT n.*
                FROM 	news AS n,
                        news_center AS nc,
                        center AS c
                WHERE 	n.id = nc.news_id
                        AND nc.center_id = c.id
                        AND c.region = :region_id
                        AND n.eventAt IS NULL
                        '.$specCond.')

                UNION

                (SELECT  n.*
                FROM 	news AS n
                WHERE 	n.id NOT IN (SELECT DISTINCT news_id FROM news_center)
                        AND n.eventAt IS NULL
                        '.$specCond.')

                ORDER BY createdAt DESC' . $limit;
        }
    }


    public function search($params, $isEvent = false)
    {
        if ( isset($params['centerid']) && $params['centerid'] > 0 ) {
            return $this->searchForCenter ($params['centerid'], $isEvent);
        }

        $this->load($params);

        if (!$this->validate())
            return new ActiveDataProvider(['query' => News::find()]);


        if (isset($params['region'])) {
            $regionId = $params['region'];
        } else if (Yii::$app->regionManager->id) {
            $regionId = Yii::$app->regionManager->id;
        }
        else {
            $regionId = 1; // Москва до умолчанию
        }
        $this->region = $regionId;

        // if ($isEvent)
        //     Yii::info(Yii::$app->regionManager->id, 'myd');

        $sql = $this->getSql([ 'isEvent' => $isEvent ]);
        $query = News::findBySql($sql, [ ':region_id' => $regionId ] );

        $adpParams = ['query' => $query,
              'pagination' => ['pageSize' => 10],
              'sort' => [
                  'defaultOrder' => [
                      'createdAt' => SORT_DESC,
                  ]
              ],
        ];

        $dataProvider = new ActiveDataProvider($adpParams);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
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
        $regionId = Yii::$app->regionManager->id;
        if (!$regionId) $regionId = 1;
        $this->region = $regionId;

        $sql = $this->getSql([
            'isSpecial' => true,
        ]);
        $ids = Yii::$app->db->createCommand($sql, [ ':region_id' => $regionId ] )
            ->queryAll();
        //Yii::info($ids, 'myd');
        $this->lead_id = count($ids) > 0 ? $ids[0]['id'] : 0;

        $query = News::find()->where(['id' => $this->lead_id]);
        $adpParams = ['query' => $query, 'pagination' => ['pageSize' => 10]];
        $dataProvider = new ActiveDataProvider($adpParams);
        return $dataProvider;
    }

    public function searchOther()
    {
        $regionId = Yii::$app->regionManager->id;
        if (!$regionId) $regionId = 1;
        $this->region = $regionId;

        if (!$this->lead_id) $this->lead_id = 0;

        $sql = $this->getSql([
            'isSpecial' => true,
            'excludedIds' => [ $this->lead_id ],
        ]);

        $query = News::findBySql($sql, [ ':region_id' => $regionId ] );
        $adpParams = ['query' => $query, 'pagination' => ['pageSize' => 10]];
        $dataProvider = new ActiveDataProvider($adpParams);
        return $dataProvider;
    }

    public function searchForCenter($id, $isEvent = false, $limit = null )
    {
        $this->centerName = Center::findOne($id)->name;

        $sql = 'SELECT n.*
        FROM 	news AS n,
                news_center AS nc
        WHERE 	n.id = nc.news_id
                AND nc.center_id = :center_id
                AND n.eventAt IS '.($isEvent ? 'NOT' : '').' NULL
        ORDER BY createdAt DESC';

        if ($limit) {
            $sql .= ' LIMIT '.$limit;
        }

        $query = News::findBySql($sql, [ ':center_id' => $id ] );
        $adpParams = ['query' => $query, 'pagination' => ['pageSize' => 10]];
        $dataProvider = new ActiveDataProvider($adpParams);
        return $dataProvider;
    }

}
