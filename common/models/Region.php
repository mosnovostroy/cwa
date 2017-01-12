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
