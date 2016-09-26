<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Center */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="center-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-7">

            <?= $form->field($model, 'region')->dropDownList($model->regionsArray,
              [
                //'onchange' => "alert(this.options[this.selectedIndex].value)"
              ])
            ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->hint('Без кавычек и без слова "коворкинг", если только оно не является частью бренда. Ромашка | Romashka | Коворкинг 14') ?>

            <?= $form->field($model, 'alias')->textInput(['maxlength' => true])->hint('Отдельные слова соединяем дефисом: siniy-kaktus. используем только маленькие латинские буквы и цифры') ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

            <?= $form->field($model, 'meta_title')->textarea(['rows' => 1])->hint('Название со словом "коворкинг" (обращаем внимание на три варианта расстановки кавычек в зависимости от языка и слова "коворкинг" в бренде), затем в скобках короткое указание на то, "где находится" (метро, город или район - причем только если само название не содержит такой "подсказки") и потом через тире общая фраза про регион: Коворкинг "Ромашка" (метро Академическая) - коворкинги Москвы и МО | Коворкинг Romashka (Путиловский район)- коворкинги Санкт-Петербурга | "Коворкинг на Рижской" - коворкинги Москвы и МО') ?>

            <?= $form->field($model, 'meta_description')->textarea(['rows' => 2])->hint('Коворкинг Romashka: тарифы, фото, часы работы, проезд, условия работы. Отзывы посетителей. Сравнить с другими коворкингами на Северо-Западе Москвы') ?>

            <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 1])->hint('Через запятую все запросы, по которым поисковику было бы разумно выдавать этот конкретный центр: коворкинг ромашка, коворкинг метро сходненская, коворкинг в бизнес-парке ленинградский, коворкинг бизнес-парк ленинградский, коворкинг метро сходненская, коворкинги в СЗАО, коворкинг ленинский проспект') ?>

            <?= $form->field($model, 'address')->textInput()->hint('Регион не указываем. Только город в области ("Химки"), если есть. Или сразу начинаем с улицы, если в областном центре (Москва, Новосибирск и пр.)') ?>

            <?= $form->field($model, 'phone')->textInput()->hint('Можно указать несколько через запятую. Но лучше все же один (смотрится лучше)') ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'site')->textInput()->hint('Отображается не только в контактах, но и как источник фотографий') ?>

            <br><br>
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success center-block' : 'btn btn-primary center-block']) ?>

        </div>

        <div class="col-md-5">

            <?php if ($model->anonsImage) echo '<image src="'.$model->anonsImage.'" width=100%>'; ?>

            <?= $form->field($model, 'gmap_lat')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'gmap_lng')->textInput(['maxlength' => true]) ?>

            <div id="yandexmap" class="inline-yandexmap" style="height: 650px!important; margin-bottom: 30px;" centerid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "16" ></div>

          </div>
      </div>

    <?php ActiveForm::end(); ?>

</div>
