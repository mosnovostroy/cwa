<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\db\Query;
use common\models\Item;
use yii\data\ActiveDataProvider;
use common\models\Center;


class ItemSearch extends Item
{

    public function fields()
    {
        $fields = parent::fields();

        //if ($this->text)
            $fields['text'] = 'text';

        return $fields;
    }

    public function search($params, $all = false)
    {
        $query = Center::find();
        //$query = (new Query())->from('center');

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
                      ]
                  ],
            ];


        $dataProvider = new ActiveDataProvider($adpParams);

        //$this->load($params);
        $this->text = $params['text'];

        if (!$this->validate())
            return $dataProvider;

        // grid filtering conditions
        $query->andFilterWhere(['like', 'name', $this->text])
            ->orFilterWhere(['like', 'description', $this->text])
            ->orFilterWhere(['like', 'meta_title', $this->text])
            ->orFilterWhere(['like', 'meta_description', $this->text])
            ->orFilterWhere(['like', 'meta_keywords', $this->text])
        ;
        return $dataProvider;
    }

}
