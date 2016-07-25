<?php

namespace common\models;

use Yii;
use common\models\Region;
use common\models\CenterPictures;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\FileHelper;


/**
 * This is the model class for table "center".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $gmap_lat
 * @property string $gmap_lng
 * @property integer $region
 */
class Center extends \yii\db\ActiveRecord
{
    //public $region_name;
    //public $region_name_tp;
    public $region_info;
    public $regions_array;
    public $imageFiles;
    public $anonsImage;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'center';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description', 'meta_title', 'meta_description', 'meta_keywords'], 'string'],
            [['gmap_lat', 'gmap_lng', 'region'], 'number'],
            [['name'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'gmap_lat' => 'Gmap Lat',
            'gmap_lng' => 'Gmap Lng',
            'region' => 'Регион',
        ];
    }

    public function initMembers()
    {
        //Название региона в разных падежах
        //$this->region_name = Region::getName($this->region);
        //$this->region_name_tp = Region::getNameTp($this->region);

        //Название региона в разных падежах, параметры карты региона
        $this->region_info = Region::findOne($this->region);

        //Список всех регионов для выпадающего списка
        $this->regions_array = Region::getNamesArray();
    }

    public function initPictures()
    {
        $this->imageFiles = CenterPictures::readPictures($this->id, false);
    }

    public function initAnonsPicture()
    {
        if (!$this->id)
            return false;
        $anons_image_name = Yii::$app->db->createCommand('SELECT name FROM image WHERE is_anons = 1 AND cid = :cid', [':cid' => $this->id])
         ->queryScalar();
         $this->anonsImage = 'upload/centers/'.$this->id.'/'.$anons_image_name;
    }

    public static function getCoordsJson($filter = [])
    {
        if (isset($filter['center']))
            $center = $filter['center'];
        else
            $center = 0;

        if (isset($filter['region']))
            $region = $filter['region'];
        else
            $region = 0;

        $coords_data = array();
        $coords_data['type'] = 'FeatureCollection';
        $coords_data['features'] = array();

        $coords_item = array();

        $sql = 'SELECT id, name, gmap_lat, gmap_lng FROM center';
        if ($center)
            $result = Yii::$app->db->createCommand($sql.' WHERE id=:id', [':id' => $center])->queryAll();
        else if ($region)
            $result = Yii::$app->db->createCommand($sql.' WHERE region=:region ORDER BY name ', [':region' => $region])->queryAll();
        else
            $result = Yii::$app->db->createCommand($sql.' ORDER BY name ')->queryAll();

        foreach($result as $row)
        {
            $coords_item['type'] = 'Feature';
            $coords_item['id'] = $row['id'];
            $coords_item['geometry'] =  [
                'type' => 'Point',
                'coordinates' => [$row['gmap_lat'], $row['gmap_lng']]
            ];
            $coords_item['properties'] = [
                'balloonContent' => Html::a($row['name'], ['center/view', 'id' => $row['id']]),
                'clusterCaption' => 'Еще одна метка',
                'hintContent' => $row['name']
            ];
            $coords_data['features'][] = $coords_item;
        }
        return json_encode($coords_data);
    }

    // public static function getCoordsJson($center = 0)
    // {
    //     $coords_data = array();
    //     $coords_data['type'] = 'FeatureCollection';
    //     $coords_data['features'] = array();
    //
    //     $coords_item = array();
    //
    //     $sql = 'SELECT id, name, gmap_lat, gmap_lng FROM center';
    //     if ($center)
    //         $result = Yii::$app->db->createCommand($sql.' WHERE id=:id', [':id' => $center])->queryAll();
    //     else
    //         $result = Yii::$app->db->createCommand($sql.' ORDER BY name ')->queryAll();
    //     foreach($result as $row)
    //     {
    //         $coords_item['type'] = 'Feature';
    //         $coords_item['id'] = $row['id'];
    //         $coords_item['geometry'] =  [
    //             'type' => 'Point',
    //             'coordinates' => [$row['gmap_lat'], $row['gmap_lng']]
    //         ];
    //         $coords_item['properties'] = [
    //             'balloonContent' => Html::a($row['name'], ['center/view', 'id' => $row['id']]),
    //             'clusterCaption' => 'Еще одна метка',
    //             'hintContent' => $row['name']
    //         ];
    //         $coords_data['features'][] = $coords_item;
    //     }
    //     return json_encode($coords_data);
    // }
}
