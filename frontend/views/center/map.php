<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
if ($searchModel->region_info && $searchModel->region_info->name)
{
    $this->title = 'Коворкинг-центры в '.$searchModel->region_info->name_tp;
    $this->registerMetaTag(['name' => 'description', 'content' => 'Коворкинг-центры в '.$searchModel->region_info->name_tp.': полный список. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг-центры, '.$searchModel->region_info->name]);
}
else
{
    $this->title = 'Коворкинг-центры: поиск';
    $this->registerMetaTag(['name' => 'description', 'content' => 'Каталог коворкинг-центров в Москве и регионах РФ. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг-центры в россии']);
}

$this->params['hasYandexMap'] = true;
?>

<div class="raw">
    <div class="col-xs-12" style="">
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['center/map-submit'],
                                        'options' => ['class' => 'form-inline']]); ?>
                <span style="font-size: 1.6em; padding-right: 10px;">Коворкинг-центры</span>
                <?= $form->field($searchModel, 'region')->dropDownList($searchModel->regions_array, ['class' => 'selectpicker', 'data-width' => 'auto'])->label(false) ?>
                <?= $form->field($searchModel, 'price_day_min')->textInput(['placeholder' => 'Цена за день'])->label(false) ?>
                <?= $form->field($searchModel, 'price_day_max')->textInput(['placeholder' => 'Цена за день'])->label(false) ?>
                <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary', 'style' => 'margin-top: -10px;']) ?>
        <?php ActiveForm::end(); ?>
        <?php
            if (Yii::$app->user && Yii::$app->user->identity && User::isUserAdmin(Yii::$app->user->identity->username))
                echo Html::a('Создать новый', ['create'], ['class' => 'btn btn-default']);
        ?>
    </div>
</div>
<div class="raw">
    <div class="col-xs-12" style="">
        <div class="pull-left">
            <?= Html::a('список', ['center/index', 'CenterSearch' => $searchModel->toArray()]) ?>
            | карта
        </div>
    </div>
</div>
<div class="raw">
    <div class="col-xs-12">
        <div id="yandexmap" class="wideyandexmap"

          <?php
              $params = $searchModel->toArray();
              foreach ($params as $k => $v)
                  echo ' '.$k.' = "'.$v.'" ';
          ?>
          region_id = "<?= $searchModel->region_info->id ?>"
          ymaps_lat = "<?= $searchModel->region_info->map_lat ?>"
          ymaps_lng = "<?= $searchModel->region_info->map_lng ?>"
          ymaps_scale = "<?= $searchModel->region_info->map_zoom ?>"
        ></div>
    </div>
</div>
