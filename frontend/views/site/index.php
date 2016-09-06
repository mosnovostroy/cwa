<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Коворкинг-ревю: коворкинг-центры и совместная аренда офиса';
?>

<div class="row" style="background-color:#bbd; margin: -19px -76px 0px -64px;">
    <div class="col-lg-12">
        <div class="jumbotron" >
            <div class="row">
                <div style="padding-left: calc(15% - 75px); float: left;" class="">
                    <p class="lead" style="color: #fff;">Быстрый поиск вариантов</p>
                </div><br>
                <?= Html::beginForm(
                    ['site/index-submit'],
                    'post',
                    ['enctype' => 'multipart/form-data', 'class' => 'input-group col-md-12']) ?>
                    <div class="input-group-btn" id="mainGroup">
                        <?= Html::dropDownList('type', 1,
                            [1 => 'Коворкинг-центры', 2 => 'Совместная аренда'],
                            ['class' => 'selectpicker', 'data-width' => '35%']) ?>
                        <?= Html::dropDownList('region', 1,
                                $model->regionsArray,
                                ['class' => 'selectpicker', 'data-width' => '35%']) ?>
                        <?= Html::submitButton('Поиск',
                            ['class' => 'btn btn-warning', 'style' => 'width:150px!important;']) ?>
                    </div>
                <?= Html::endForm() ?>
            </div>
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
</style>

<div class="row row-flex1234 row-flex1234-wrap">
    <?php foreach ($centers->getModels() as $center): ?>
        <?php $url = Url::to(['center/view', 'id' => $center->id]); ?>
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
    <?php endforeach; ?>
</div>
