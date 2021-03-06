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
            [['name', 'name_tp', 'alias'], 'string', 'max' => 255],
            [['parent', 'hh_api_region', 'map_zoom'], 'integer'],
            [['map_lat', 'map_lng'], 'number'],

            [['name', 'name_tp', 'alias'], 'required'],
            [['parent', 'hh_api_region'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'name_tp' => 'Название в творительном падеже ("Коворкинги в ... ")',
            'alias' => 'Алиас',
            'parent' => 'ID родительского региона',
            'map_lat' => 'Широта',
            'map_lng' => 'Долгота',
            'map_zoom' => 'Масштаб',
            'hh_api_region' => 'Номер этого региона в hh.ru',
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

    public function beforeSave($insert)
	{
	    if (parent::beforeSave($insert)) {

            if (!$this->default_address_atom) {
                $this->default_address_atom = $this->name;
            }
	        return true;
	    } else {
	        return false;
		}
	}


}
