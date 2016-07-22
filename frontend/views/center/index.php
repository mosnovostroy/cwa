<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;
/* @var $this yii\web\View */
if ($searchModel->region_name)
{
    $this->title = 'Коворкинг-центры в '.$searchModel->region_name_tp;
    $this->registerMetaTag(['name' => 'description', 'content' => 'Коворкинг-центры в '.$searchModel->region_name_tp.': полный список. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг-центры, '.$searchModel->region_name]);
}
else
{
    $this->title = 'Коворкинг-центры: поиск';
    $this->registerMetaTag(['name' => 'description', 'content' => 'Каталог коворкинг-центров в Москве и регионах РФ. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг-центры в россии']);
}

// $this->params['breadcrumbs'] =
// [
//     ['label' => 'Коворкинг-центры', 'url' => ['center/index']],
//     //['label' => $model->region_name, 'url' => ['center/index', 'region' => $model->region]]
// ];
// ?>

<h1>
    <?= $this->title ?>
    <?php
        if (Yii::$app->user && Yii::$app->user->identity && User::isUserAdmin(Yii::$app->user->identity->username))
        {
            echo Html::a('Создать новый', ['create'], ['class' => 'btn btn-default']);
        }
    ?>
</h1>

<div class="raw">
    <div class="col-sm-3" style="margin-bottom: 15px;">

      <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['center/index']]); ?>
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
                  <?= Html::a('Список', ['center/index'], ['class' => 'btn btn-default', 'disabled' => 'disabled']) ?>
                  <?= Html::a('Карта', ['center/map'], ['class' => 'btn btn-primary']) ?>
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
              <ul>
                  <?php foreach ($dataProvider->getModels() as $center): ?>
                      <li>
                          <?= Html::a(Html::encode("{$center->name}"), ['view', 'id' => $center->id]) ?><br>
                          <?= $center->description ?>
                      </li>
                  <?php endforeach; ?>
              </ul>
              <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
              <!-- <div id="yandexmap" style="width: 100%; height: 400px"></div> -->
            </div>
        </div>
    </div>
</div>
