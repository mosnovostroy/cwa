<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\behaviors\RegionInfoBehavior;
use common\models\StationSearch;
use common\models\LocationSearch;


/**
 * This is the model class for table "region".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 */
class Locations extends \yii\base\Model
{
    public $region;

    public function behaviors()
    {
        return [
            'regionInfo' => [
                'class' => RegionInfoBehavior::className(),
            ],
        ];
    }

    public function getLocationDataProvider()
    {
        $model = new LocationSearch();
        $dataProvider = $model->search(['region' => $this->region], true);
        return $dataProvider;
    }

    public function getStationDataProvider()
    {
        $model = new StationSearch();
        $dataProvider = $model->search(['region' => $this->region], true);
        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
    }
}
