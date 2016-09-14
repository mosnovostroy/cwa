<?php

namespace common\behaviors;


use Yii;
use yii\base\Behavior;
use common\behaviors\Region;
use yii\helpers\ArrayHelper;

/**
 * Class ImageBehavior
 */
class RegionInfoBehavior extends Behavior
{
    private $_regionInfo = null;

    public function init()
    {
        parent::init();

		// if ($this->regionId)
			// $this->_regionInfo = Region::findOne($this->regionId);
    }

	public function getRegionId()
	{
		return $this->owner->region;
	}

	public function getRegionName()
	{
		if (!$this->_regionInfo && $this->regionId)
			 $this->_regionInfo = Region::findOne($this->regionId);

		Yii::info($this->_regionInfo,'myd');
		if ($this->_regionInfo && isset($this->_regionInfo->name))
			return $this->_regionInfo->name;
		return '';
	}

	public function getRegionNameTp()
	{
		if (!$this->_regionInfo && $this->regionId)
			 $this->_regionInfo = Region::findOne($this->regionId);
		if ($this->_regionInfo && isset($this->_regionInfo->name_tp))
			return $this->_regionInfo->name_tp;
		return '';
	}

	public function getRegionMapLat()
	{
		if (!$this->_regionInfo && $this->regionId)
			 $this->_regionInfo = Region::findOne($this->regionId);
		if ($this->_regionInfo && isset($this->_regionInfo->map_lat))
			return $this->_regionInfo->map_lat;
		return '';
	}

	public function getRegionMapLng()
	{
		if (!$this->_regionInfo && $this->regionId)
			 $this->_regionInfo = Region::findOne($this->regionId);
		if ($this->_regionInfo && isset($this->_regionInfo->map_lng))
			return $this->_regionInfo->map_lng;
		return '';
	}

	public function getRegionMapZoom()
	{
		if (!$this->_regionInfo && $this->regionId)
			 $this->_regionInfo = Region::findOne($this->regionId);
		if ($this->_regionInfo && isset($this->_regionInfo->map_zoom))
			return $this->_regionInfo->map_zoom;
		return '';
	}

	public function getRegionsArray()
	{
        return $this->dogetRegionsArray(true);
	}

  public function getRegionsArrayWithoutNullItem()
	{
        return $this->dogetRegionsArray(false);
	}

  public function getRegionsArrayForProfile()
	{
        return $this->dogetRegionsArray(true, true);
	}

  public function doGetRegionsArray($all = true, $none = false)
	{
      $regions = array();
      if ($all)
          $regions[0] = $none ? 'Не установлен' : 'Все регионы';
      $result = Region::find()->where(['parent' => 0])->all();
      $subregions = ArrayHelper::map($result,'id','name');
      $regions = $regions + $subregions;
      return $regions;
	}

}
