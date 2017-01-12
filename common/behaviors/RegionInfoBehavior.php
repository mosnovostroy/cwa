<?php

namespace common\behaviors;


use Yii;
use yii\base\Behavior;
use common\models\Region;
use yii\helpers\ArrayHelper;
use common\models\User;
use yii\helpers\Html;

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

		//Yii::info($this->_regionInfo,'myd');
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


    //////////////////////////////////////////////////////////////////////////////////////////
    // Нужно для правильного позиционирования карты при создании нового объявления
    public function initMapParams()
    {
        // Если есть регион в сессии - приоритет:
        $region = Yii::$app->regionManager->id;
        if ($region) {
            $this->owner->region = $region;
            $map_params = Region::findOne($region);
            if ($map_params) {
                $this->owner->gmap_lat = $map_params->map_lat;
                $this->owner->gmap_lng = $map_params->map_lng;
            }
            return 0;
        }

        // Если установлен регион в настройках пользователя:
        if (Yii::$app->user && Yii::$app->user->identity && Yii::$app->user->identity->id)
        {
            $user = User::findOne( Yii::$app->user->identity->id );
            if ($user && $user->region)
            {
                $this->owner->region = $user->region;
                if (($model = Region::findOne($this->owner->region)) !== null)
                {
                    $this->owner->gmap_lat = $model->map_lat;
                    $this->owner->gmap_lng = $model->map_lng;
                }
            }
        }
    }

}
