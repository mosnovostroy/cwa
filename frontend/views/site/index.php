<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
