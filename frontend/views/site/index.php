

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Region;

/* @var $this yii\web\View */

$this->title = 'Коворкинг-ревю: ';
?>

<div class="row" style="background-color:#bbd; margin: -19px -76px 0px -64px;">
    <div class="col-lg-12">
        <div class="jumbotron" >
            <div class="row">
                <div style="padding-left: calc(15% - 75px); float: left;" class="">
                    <p class="lead" style="color: #fff;">Быстрый поиск вариантов</p>
                </div><br>

                <?= Html::beginForm(
                    ['site/index-go'],
                    'post',
                    ['enctype' => 'multipart/form-data', 'class' => 'input-group col-md-12']) ?>
                    <div class="input-group-btn" id="mainGroup">
                        <?= Html::dropDownList('type', 1,
                            [1 => 'Коворкинг-центры', 2 => 'Совместная аренда'],
                            ['class' => 'selectpicker', 'data-width' => '35%']) ?>
                        <?= Html::dropDownList('region', 1,
                                Region::getNamesArray(),
                                ['class' => 'selectpicker', 'data-width' => '35%']) ?>
                        <?= Html::submitButton('Поиск',
                            ['class' => 'btn btn-warning', 'style' => 'width:150px!important;']) ?>
                    </div>
                <?= Html::endForm() ?>

                <!-- <form class="input-group">
                    <div class="col-md-12">
                        <div class="input-group-btn">
                            <select class="selectpicker" data-width="100%" >
                                <option>Коворкинг-центры</option>
                                <option>Совместная аренда офиса</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group-btn">
                            <select class="selectpicker" data-width="100%">
                                <option>Москва и область</option>
                                <option>Санкт-Петербург</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary">Поиск</button>
                        </div>
                    </div>
                </form> -->
            </div>
        </div>
    </div>
</div>

<div class="site-index" ">




    <div class="jumbotron">
    </div>

    <div class="body-content">
    </div>
</div>
