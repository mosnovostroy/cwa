<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\LinkSorter;
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
?>

<div class="raw">
    <div class="col-xs-12" style="">
        <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['center/index-redirect'],
                                        'options' => ['class' => 'form-inline']]); ?>
                <span style="font-size: 1.6em; padding-right: 10px;">Коворкинг-центры</span>
                <?= $form->field($searchModel, 'region')->dropDownList($searchModel->regions_array, ['class' => 'selectpicker', 'data-width' => 'auto'])->label(false) ?>
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
            список
            | <?= Html::a('карта', ['center/map', 'CenterSearch' => ['region' => $searchModel->region]]) ?>
        </div>

        <div class="pull-right">
          <?php
              $sort = $dataProvider->getSort();
              if ($sort)
                  echo $sort->link('rating') . ' | ' . $sort->link('price_day');
          ?>
        </div>
    </div>
</div>

<div class="raw">
    <div class="col-xs-12">
        <ul>
            <?php foreach ($dataProvider->getModels() as $center): ?>
                <li>
                    <?= Html::a(Html::encode("{$center->name}"), ['view', 'id' => $center->id]) ?><br>
                    <p><?= $center->description ?></p>
                    <?php if($center->price_day) echo '<p>Стоимость: '.$center->price_day.' руб. в день</p>';?>
                    <?php if($center->rating) echo '<p>Рейтинг: '.$center->rating.'</p>';?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
    </div>
</div>
