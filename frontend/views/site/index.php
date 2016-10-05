<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\User;

/* @var $this yii\web\View */

$this->title = 'Коворкинг-ревю: коворкинги и совместная аренда офиса';
?>

<style>
    .main-form-bg {background-color:#bbd;}
    .main-form-title {color: #fff; }
    @media (max-width: 767px)
    {
      .main-form-container {width: 85%; margin: 30px auto 15px; text-align: left;}
      .main-form-list {width: 100%; float: left; margin-bottom:30px;}
      .main-form-button {width: 100%!important; float: left;}
      /*.main-arenda-control-container {width: 85%; margin: 0 auto;}
      .main-arenda-control-button {width: 100%;}*/
    }
    @media (min-width: 768px)
    {
      .main-form-container {width: calc(70% + 150px); margin: 40px auto 45px; text-align: left;}
      .main-form-list {width: calc(50% - 75px); float: left; border-radius: 0!important;}
      .main-form-button {width: 150px!important; float: left;}
      /*.main-arenda-control-container {width: 100%;}
      .main-arenda-control-button {width: 100%;}*/
    }
    .main-center-links {margin-top: 10px;}
</style>

<div class="main-form-bg">
    <div class="jumbotron">
        <div class="main-form-container">
            <p class="main-form-title">Быстрый поиск вариантов</p>
            <?= Html::beginForm(
                ['site/index-submit'],
                'post',
                ['enctype' => 'multipart/form-data', 'class' => 'input-group']) ?>
                <div class="input-group-btn" id="mainGroup">
                    <div class="main-form-list">
                        <?= Html::dropDownList('type', 1,
                            [1 => 'Коворкинги', 2 => 'Совместная аренда'],
                            ['class' => 'selectpicker', 'data-width' => '100%']) ?>
                    </div>
                    <div class="main-form-list">
                        <?= Html::dropDownList('region', 1,
                                $model->regionsArray,
                                ['class' => 'selectpicker', 'data-width' => '100%']) ?>
                    </div>
                    <div class="main-form-button">
                        <?= Html::submitButton('Поиск',
                            ['class' => 'btn btn-warning main-form-button']) ?>
                    </div>
                </div>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>

<style>
	.tgbcol {border: solid 1px #fff; z-index:20; cursor: pointer; min-height: 270px;}
	.tgbcol:hover
	{
		z-index:21;
		border: solid 1px #66afe9 !important;
		outline: 0;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 12px rgba(102, 175, 233, 0.6);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 12px rgba(102, 175, 233, 0.6);
	}
	.pb {display: none; position: absolute; bottom: 0; right: 0; font-size: 0.82em;}
	.tgbcol:hover .pb, .tgbcol-bordered:hover .pb {display: block;}

	.tgb 	{overflow: hidden; margin: 15px 0; position: relative; top: 0; left: 0;}
	.tgbimg 		{position: relative; top: 0; left: 0; margin-bottom: 15px;}
	.tgbimg img 	{width: 100%;}
  .tgbdate {font-style: italic; font-size: 0.9em; color: #aaa;}

  @media (max-width: 519px)
  {
  	.tgbtitle {font-size: 1.20em;}
  	.tgbtext {font-size:0.9em;}
  }
  @media (min-width: 520px) and (max-width: 767px)
  {
    .tgbtitle {font-size: 1.2em;}
  }
  @media (min-width: 768px)
  {
    .tgbtitle {font-size: 1.3em;}
    .row-flex1234, .row-flex1234 > div[class*='col-'] { margin-top: 15px; display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex; display: flex; flex:1 1 auto;}
    .row-flex1234-wrap {	-webkit-flex-flow: row wrap; align-content: flex-start; flex:0;	}
    .row-flex1234 > div[class*='col-'] { margin:-.2px;}
  }
  .main-h3 {margin-right: 30px; }
  .main-city {font-size: 1em; margin: 10px 30px 10px 0;}
  .main-city.main-city-all{font-weight: bold;}
</style>

<div class="container" style="padding-top: 0px;">
    <h3><?= Html::a('Коворкинги', ['center/index'], ['class' => 'main-h3']) ?></h3>
    <div class="row">
      <div class="col-xs-12">
        <?= Html::a('Москва', ['centers/moscow'], ['class' => 'main-city']) ?>
         <?= Html::a('Санкт-Петербург', ['centers/piter'], ['class' => 'main-city']) ?>
         <?= Html::a('Новосибирск', ['centers/novosibirsk'], ['class' => 'main-city']) ?>
         <?= Html::a('Самара', ['centers/samara'], ['class' => 'main-city']) ?>
         <?= Html::a('Все&nbsp;регионы', ['center/index'], ['class' => 'main-city main-city-all']) ?>
       </div>
    </div>
    <div class="clearfix">&nbsp;</div>
    <div class="row row-flex1234 row-flex1234-wrap" style="margin-top: -5px;">
        <?php $count = 1; ?>
        <?php foreach ($centers->getModels() as $center): ?>
            <?php if ($count > 4) break; $url = Url::to(['center/view', 'id' => $center->id]); ?>
            <div class="col-lg-3 col-xs-6 tgbcol" onclick="location.href='<?= $url ?>';">
                <div class="tgb" id="tgb1184" tblank="1">
                    <?php if ($center->anonsImage)
                        echo '<div class="tgbimg"><img src="'.$center->anons4x3.'"></div>';
                    ?>
                    <div class="tgbtext">
                      <div class="tgbtitle"><a href="<?=$url?>"><?=Html::encode("{$center->name}")?></a></div>
                      <div class="tgbdescr"><?= $center->address ?></div>
                    </div>
                    <div class="pb"><a href=""><span class="glyphicon glyphicon-menu-right"></span></a></div>
                </div>
            </div>
        <?php $count++; endforeach; ?>
    </div>


<style>
    @media (max-width: 519px)
    {
        .main-arenda-control-button {width: 100%; margin-bottom: 30px;}
    }
    @media (min-width: 520px) and (max-width: 767px)
    {
        .main-arenda-control-button {width: 45%; margin-bottom: 30px;}
    }
    @media (min-width: 767px)
    {
        .main-arenda-control-button {width: 100%; margin-bottom: 30px;}
    }
</style>

    <h3 style="margin-top: 50px; margin-bottom: 0px;"><?= Html::a('Совместная аренда офиса', ['arenda/index'], ['class' => 'main-h3']) ?></h3>

    <div class="row">
      <div class="col-xs-12">
          <div style=" margin-bottom: 20px;">Объявления о поиске партнеров для совместной аренды офиса</div>
       </div>
    </div>



    <div class="row">
      <div class="col-sm-3">
        <!-- <div class="main-arenda-control-container"> -->
          <?= Html::a('Подать объявление', ['arenda/create'], ['class' => 'btn btn-danger btn-lg main-arenda-control-button']) ?>
          <!-- </div> -->
      </div>
      <div class="col-sm-9">
        <?php $count = 1; ?>
        <?php foreach ($arenda->getModels() as $center): ?>
        <?php if ($count > 4) break; $url = Url::to(['arenda/view', 'id' => $center->id]); ?>
          <div class="row">
              <div class="col-xs-12 card arenda" onclick="location.href='<?= $url ?>';">
                  <div class="clearfix" >
                    <?php if ($center->anons3x2) echo '<image class="card-image" src="'.$center->anons3x2.'">'; ?>
                    <h3><a href="<?=$url?>"><?=Html::encode("{$center->name}")?></a></h3>
                    <?php
                    ?>
                    <?php
                        echo '<div class="card-params">';
                            echo '<p>'.$center->regionName.'</p>';
                            echo '<p>'.$center->date.'</p>';
                        echo '</div>';
                    ?>

                    <div><?= $center->anons_text ?></div>
                  </div>
              </div>
          </div>
          <?php $count++; endforeach; ?>
      </div>
    </div>

</div>
