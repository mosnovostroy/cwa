<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;
use common\models\RegionSearch;

/* @var $this yii\web\View */
if ($searchModel->regionNameTp)
{
    $this->title = 'Коворкинг-центры в '.$searchModel->regionNameTp;
    $this->registerMetaTag(['name' => 'description', 'content' => 'Коворкинг-центры в '.$searchModel->regionNameTp.': полный список. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг-центры, '.$searchModel->regionName]);
}
else
{
    $this->title = 'Коворкинг-центры: поиск';
    $this->registerMetaTag(['name' => 'description', 'content' => 'Каталог коворкинг-центров в Москве и регионах РФ. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг-центры в россии']);
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
