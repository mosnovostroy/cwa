<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;
use common\models\RegionSearch;

/* @var $this yii\web\View */
if ($locationName && $locationNameTp && $searchModel->regionName) {
    $this->title = 'Коворкинги в '.$locationNameTp.' на карте';
    $h1 = $this->title;
    $this->registerMetaTag(['name' => 'description', 'content' => 'Коворкинги в '.$locationNameTp.' ('.$locationAddressAtom.') на карте']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг, коворкинг-центр, карта,  '.$locationName.', '.$locationName]);
} else if ($metroName) {
    $this->title = 'Коворкинги на карте: метро '.$metroName.'';
    $h1 = $this->title;
    $this->registerMetaTag(['name' => 'description', 'content' => 'Коворкинги в '.$searchModel->regionNameTp.' - поиск по карте в районе станции метро '.$metroName]);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг, коворкинг-центр, карта, '.$metroName]);
} else if ($searchModel->regionNameTp) {
    $this->title = 'Коворкинги в '.$searchModel->regionNameTp.' на карте';
    $h1 = $this->title;
    $this->registerMetaTag(['name' => 'description', 'content' => 'Коворкинги в '.$searchModel->regionNameTp.': поиск по расположению на карте']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг, коворкинг-центр, карта '.$searchModel->regionName]);
} else {
    $this->title = 'Коворкинги: поиск по расположению на карте';
    $h1 = $this->title;
    $this->registerMetaTag(['name' => 'description', 'content' => 'Карта коворкингов в Москве и регионах РФ. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг, коворкинг-центр, карта, регионы']);
}

$this->params['hasYandexMap'] = true;
$this->params['showCounters'] = true;
?>

<div id="yandexmap" class="wide-yandex-map"

  <?php
	  $params = $searchModel->toArray();
	  foreach ($params as $k => $v)
		  echo ' '.$k.' = "'.$v.'" ';
  ?>
  region_id = "<?= $searchModel->regionId ?>"
  ymaps_lat = "<?= $searchModel->regionMapLat ?>"
  ymaps_lng = "<?= $searchModel->regionMapLng ?>"
  ymaps_scale = "<?= $searchModel->regionMapZoom ?>"
  ymaps_show_tolist_button = "1"
></div>
