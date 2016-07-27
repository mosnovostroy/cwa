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
            'name' => 'Название',
            'description' => 'Описание',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'gmap_lat' => 'Широта',
            'gmap_lng' => 'Долгота',
            'region' => 'Регион',
            'price_day' => 'цена',
            'rating' => 'рейтинг',
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
}
