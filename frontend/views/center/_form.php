<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\RegionSearch;
use yii\widgets\Pjax;
use common\models\LocationSearch;

/* @var $this yii\web\View */
/* @var $model common\models\Center */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="center-form">

    <?php
        Pjax::begin(['enablePushState' => false]);
            echo $this->render('_news', ['model' => $model]);
        Pjax::end();
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-7">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->hint('Без кавычек и без слова "коворкинг", если только оно не является частью бренда. Ромашка | Romashka | Коворкинг 14') ?>

            <?= $form->field($model, 'alias')->textInput(['maxlength' => true])->hint('Отдельные слова соединяем дефисом: siniy-kaktus. используем только маленькие латинские буквы и цифры') ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

            <?= $form->field($model, 'meta_title')->textarea(['rows' => 1])->hint('Название со словом "коворкинг" (обращаем внимание на три варианта расстановки кавычек в зависимости от языка и слова "коворкинг" в бренде), затем в скобках короткое указание на то, "где находится" (метро, город или район - причем только если само название не содержит такой "подсказки") и потом через тире общая фраза про регион: Коворкинг "Ромашка" (метро Академическая) - коворкинги Москвы и МО | Коворкинг Romashka (Путиловский район)- коворкинги Санкт-Петербурга | "Коворкинг на Рижской" - коворкинги Москвы и МО') ?>

            <?= $form->field($model, 'meta_description')->textarea(['rows' => 2])->hint('Коворкинг Romashka: тарифы, фото, часы работы, проезд, условия работы. Отзывы посетителей. Сравнить с другими коворкингами на Северо-Западе Москвы') ?>

            <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 1])->hint('Через запятую все запросы, по которым поисковику было бы разумно выдавать этот конкретный центр: коворкинг ромашка, коворкинг метро сходненская, коворкинг в бизнес-парке ленинградский, коворкинг бизнес-парк ленинградский, коворкинг метро сходненская, коворкинги в СЗАО, коворкинг ленинский проспект') ?>

            <?= $form->field($model, 'location')->dropDownList(LocationSearch::getLocationsArray())->hint('Город выбираем только для Москвы и МО')
            ?>

            <?= $form->field($model, 'address')->textInput()->hint('!! Регион не указываем !! Cразу начинаем с улицы') ?>

            <?= $form->field($model, 'phone')->textInput()->hint('Можно указать несколько через запятую. Но лучше все же один (смотрится лучше)') ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'site')->textInput()->hint('Отображается не только в контактах, но и как источник фотографий') ?>

        </div>

        <div class="col-md-5">

            <?php if ($model->anonsImage) echo '<div style="margin-bottom: 30px;"><image src="'.$model->anonsImage.'" width=100%></div>'; ?>

            <?= $form->field($model, 'region')->dropDownList(RegionSearch::getArrayWithoutNullItem(),
              [
                'onchange' => "locate_yandex_maps(this.options[this.selectedIndex].value)"
              ])->hint('Укажите точку на карте:')
            ?>

            <div id="yandexmap" class="h600-yandexmap" style="margin-bottom: 30px;" centerid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "9" ymaps_show_search = "1" ymaps_clickable = "1"></div>

            <?= $form->field($model, 'gmap_lat')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'gmap_lng')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'metro')->hiddenInput()->label(false) ?>

          </div>
      </div>

    <?= Html::submitButton($model->isNewRecord ? 'Сохранить изменения' : 'Сохранить изменения', ['class' => 'btn btn-primary center-block']) ?>

    <?php ActiveForm::end(); ?>

</div>
