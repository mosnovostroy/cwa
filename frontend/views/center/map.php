<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
$this->title = 'Коворкинг-цетры: поиск';
$this->registerMetaTag(['name' => 'description', 'content' => 'Коворкинг-центры: поиск']);
$this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг-центры']);
// $this->params['breadcrumbs'] =
// [
//     ['label' => 'Коворкинг-центры', 'url' => ['center/index']],
//     //['label' => $model->region_name, 'url' => ['center/index', 'region' => $model->region]]
// ];
// ?>

<h1>
    Коворкинг-центры в Москве и МО
    <?php
        if (Yii::$app->user && Yii::$app->user->identity && User::isUserAdmin(Yii::$app->user->identity->username))
        {
            echo Html::a('Создать новый', ['create'], ['class' => 'btn btn-default']);
        }
    ?>
</h1>

<div class="raw">
    <div class="col-sm-3" style="margin-bottom: 15px;">

      <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['center/map']]); ?>
          <?= $form->field($searchModel, 'region')->dropDownList($searchModel->regions_array, ['class' => 'selectpicker', 'data-width' => '100%'])->label(false) ?>
          <div class="form-group">
              <?= Html::submitButton('Найти', ['class' => 'btn btn-block btn-primary']) ?>
          </div>
      <?php ActiveForm::end(); ?>

    </div>
    <div class="col-sm-9">
      <div class="raw">
          <div class="col-xs-12" style="margin-bottom: 15px;">
              <div class="btn-group pull-left">
                  <?= Html::a('Список', ['center/index'], ['class' => 'btn btn-primary']) ?>
                  <?= Html::a('Карта', ['center/map'], ['class' => 'btn btn-default', 'disabled' => 'disabled']) ?>
              </div>
              <select class="selectpicker pull-right" data-width="fit">
                  <option>Сортировка:</option>
                  <option>Cначала дешевые</option>
                  <option>Cначала дорогие</option>
              </select>
          </div>
      </div>
      <div class="raw">
          <div class="col-xs-12">
              <div id="yandexmap" style="width: 100%; height: 400px"></div>
            </div>
        </div>
    </div>
</div>
