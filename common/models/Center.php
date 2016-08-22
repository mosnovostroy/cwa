<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use common\behaviors\ImageBehavior;
use common\behaviors\RegionInfoBehavior;


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
	public $_features = null;
	public $_tariffs = null;
	public $_tariff_headers = null;
	
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
            [['name', 'alias'], 'required'],
            [['description', 'meta_title', 'meta_description', 'meta_keywords', 'features', 'tariffs'], 'string'],
            [['gmap_lat', 'gmap_lng', 'region', 'price_day', 'rating'], 'number'],
            [['region', 'price_hour', 'price_day', 'price_week', 'price_month', 'is24x7', 'has_fixed', 'has_storage', 'has_meeting_room', 'has_printer', 'has_internet', 'rating'], 'integer'],
            [['name', 'alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'image' => [
                'class' => ImageBehavior::className(),
            ],
            'regionInfo' => [
                'class' => RegionInfoBehavior::className(),
            ],
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
            'alias' => 'Алиас',
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
	
	public function addTariff($tariffModel)
	{
		$tariffs = array();
		if ($this->tariffs)
			$tariffs = unserialize($this->tariffs);
		$tariffs[] = $tariffModel->toArray();
		$this->tariffs = serialize($tariffs);
		$this->_tariffs = null;
		$this->_tariff_headers = null;
		return $this->save();
	}

	public function updateTariff($id, $tariffModel)
	{
		if (!$this->_tariffs)
			$this->_tariffs = $this->getTariffArrays();		
		$this->_tariffs[$id] = $tariffModel->toArray();
		$this->tariffs = serialize($this->_tariffs);
		$this->_tariffs = null;
		$this->_tariff_headers = null;
		return $this->save();
	}

	public function deleteTariff($id)
	{
		if (!$this->_tariffs)
			$this->_tariffs = $this->getTariffArrays();		
		unset($this->_tariffs[$id]);
		$this->tariffs = serialize($this->_tariffs);
		$this->_tariffs = null;
		$this->_tariff_headers = null;
		return $this->save();
	}

	public function getTariffArray($id)
	{
		if (!$this->tariffArrays)
			$this->_tariffs = $this->getTariffArrays();
		
		if (isset($this->_tariffs[$id]))
		{
			$this->_tariffs[$id]['id'] = $id;
			$this->_tariffs[$id]['isTariff'] = 1;
			return $this->_tariffs[$id];
		}
		else
			return [];
	}
	
	public function getTariffHeaders()
	{
		if ($this->_tariff_headers)
			return $this->_tariff_headers;

		$this->_tariff_headers = array();
		if ($this->tariffs)
		{
			$tariffs = unserialize($this->tariffs);
			foreach($tariffs as $k => $v)
				$this->_tariff_headers[$k] = isset($v['name']) && $v['name'] ? $v['name'] : 'Без имени '.$k;
		}
		return $this->_tariff_headers;
	}

	public function getTariffArrays()
	{
		if ($this->_tariffs)
			return $this->_tariffs;

		$this->_tariffs = array();
		if ($this->tariffs)
		{
			$tariffs = unserialize($this->tariffs);
			foreach($tariffs as $k => $v)
				$this->_tariffs[$k] = $v;
		}
		return $this->_tariffs;
	}
	
}
