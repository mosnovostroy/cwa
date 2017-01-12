<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Station;
use common\models\Region;
//use Yii;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
$this->params['showCounters'] = true;
?>
<div class="site-about">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
      <div class="col-md-7">
        <p>coworking.review@gmail.com</p>
      </div>
      <div class="col-md-5">
      </div>
  </div>
</div>
