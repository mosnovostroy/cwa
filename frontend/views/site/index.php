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

<div class="container" style="padding-top: 0px;">

    <h2><p><?= Html::a('Коворкинги', ['center/index']) ?></p></h2>

    <ul class="list-inline">
        <li><?= Html::a('Москва', ['centers/moscow']) ?></li>
        <li><?= Html::a('Санкт-Петербург', ['centers/piter']) ?></li>
        <li><?= Html::a('Новосибирск', ['centers/novosibirsk']) ?></li>
        <li><?= Html::a('Самара', ['centers/samara']) ?></li>
        <li><strong><?= Html::a('Все&nbsp;регионы', ['center/index'])?></strong></li>
    </ul>

    <div class="row row-flex1234 row-flex1234-wrap" style="margin-bottom: 50px;">
        <?php $count = 1; ?>
        <?php foreach ($centers->getModels() as $center): ?>
            <?php if ($count > 3) break; $url = Url::to(['center/view', 'id' => $center->id]); ?>
            <div class="col-md-4 tgbcol" onclick="location.href='<?= $url ?>';">
                <div class="tgb">
                    <?php if ($center->anonsImage)
                        echo '<div class="tgbimg"><img src="'.$center->anons16x9.'"></div>';
                        // echo '<div class="redlabel">'.$center->regionName.'</div>'
                        if ($center->price_day > 0) echo '<div class="redlabel">'.$center->price_day.' руб./день</div>';
                    ?>
                    <h4><p><a href="<?=$url?>"><?=Html::encode("{$center->name}")?></a></p></h4>

                    <p style="margin-top: 0px;">
                        <?php

                            if ($center->metro)
                                echo '<span style="background: url(\'/img/moscow_metro.png\') no-repeat 0px 0px; padding-left: 22px;"> '.$center->metro.'</span>';
                            else
                                echo $center->address;
                        ?>
                    </p>

                    <div class="lgray" style="margin-top: 7px;"><?= $center->regionName ?></div>

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

    <h2><p><?= Html::a('Совместная аренда офиса', ['arenda/index'], ['class' => 'main-h3']) ?></p></h2>

    <p style=" margin-bottom: 20px;">Объявления о поиске партнеров для совместной аренды офиса</p>

<style>
    .arenda-main {
        width: 100%;
        min-height: 108px;
        border-radius: 5px;
        padding: 10px 10px;
        background-color: #ffc;
        border: 1px solid #aaa;
        margin-bottom: 30px;
        cursor: pointer;
    }
    .arenda-main:hover {
        background-color: #ff7;
    }
    .arenda-main img {width: 25%; max-width: 90px; float: left; margin-right: 10px; }
    .arenda-main-button {
        background-color: #fff;
        text-align: center;
        padding-top: 32px;
    }

    @media (min-width: 768px)
    {
        .row-flex123, .row-f#lex123 > div[class*='col-'] { margin-top: 15px; display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex; display: flex; flex:1 1 auto;}
        .row-flex123-wrap {	-webkit-flex-flow: row wrap; align-content: flex-start; flex:0;	}
        .row-flex123 > div[class*='col-'] { margin:-.2px;}
    }

</style>
    <div class="row row-flex1234 row-flex1234-wrap">

        <?php $count = 1; ?>
        <?php foreach ($arenda->getModels() as $center): ?>
        <?php if ($count > 2) break; $url = Url::to(['arenda/view', 'id' => $center->id]); ?>
            <div class="col-sm-4" onclick="location.href='<?= $url ?>';">
                <div class="arenda-main clearfix">
                    <?php if ($center->anons120) echo '<img src="'.$center->anons120.'">'; ?>
                    <div class=""><a href="<?=$url?>"><?=Html::encode("{$center->name}")?></a></div>
                    <!-- <div class="small italic" style="margin-top: -2px; margin-bottom: 7px;"><?= $center->regionName ?></div> -->
                    <div><?= $center->anons_text_short ?></div>
                    <div class="dgray small" style="margin-top: 7px;"><?= $center->regionName ?></div>
                </div>
            </div>
        <?php $count++; endforeach; ?>

        <?php $url = Url::to(['arenda/create']); ?>
        <div class="col-sm-4 ">
            <div class="arenda-main arenda-main-button" onclick="location.href='<?= $url ?>';">
                <div class="arenda-button">
                    <a href="<?=$url?>"><h4><p>Подать объявление</p></h4></a>
                </div>
            </div>
        </div>

    </div>


</div>
