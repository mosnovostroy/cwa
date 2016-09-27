<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "region".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['map_lat', 'map_lng', 'map_zoom'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent' => 'Parent',
        ];
    }

    public static function getName ($region_id)
    {
        if (!$region_id)
            return false;
        $region_name = Region::find()->where(['id' => $region_id])->one()->getAttribute('name');
        return $region_name;
    }

    public static function getNameTp ($region_id)
    {
        if (!$region_id)
            return false;
        $region_name_tp = Region::find()->where(['id' => $region_id])->one()->getAttribute('name_tp');
        return $region_name_tp;
    }

    public static function getNamesArray ()
    {
        $regions[0] = 'Все регионы';
        $result = Region::find()->where(['parent' => 0])->all();
        $subregions = ArrayHelper::map($result,'id','name');
        $regions = $regions + $subregions;
        return $regions;
    }

    public function getMapParams()
    {
        $data= array();
        $data['lat'] = $this->map_lat;
        $data['lng'] = $this->map_lng;
        $data['zoom'] = $this->map_zoom;

        return json_encode($data);

        // $data = array();
        // $data['type'] = 'FeatureCollection';
        // $data['features'] = array();
        //
        // $item = array();
        // $item['type'] = 'Feature';
        // $item['lat'] = $this->map_lat;
        // $item['lng'] = $this->map_lng;
        // $item['zoom'] = $this->map_zoom;
        //
        // $data['features'][] = $item;

        // return json_encode($data);
    }

}
