<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Arenda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="arenda-form">

  <?php $form = ActiveForm::begin(); ?>

  <div class="row">
      <div class="col-md-6">

          <?= $form->field($model, 'name')->textInput(['maxlength' => true])->hint('Не более 150 символов') ?>

          <?= $form->field($model, 'description')->textarea(['rows' => 10, 'maxlength' => true])->hint('Не более 1500 символов') ?>

          <?= $form->field($model, 'contacts')->textInput() ?>

      </div>

      <div class="col-md-6">

          <?php if ($model->anonsImage) echo '<image src="'.$model->anonsImage.'" width=100%>'; ?>

          <?= $form->field($model, 'region')->dropDownList($model->regionsArrayWithoutNullItem,
            [
              'onchange' => "locate_yandex_maps(this.options[this.selectedIndex].value)"
            ])->hint('Выберите регион и укажите точку на карте')
          ?>

          <div id="yandexmap" class="h360-yandexmap" style="margin-bottom: 30px;" arendaid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "9" ></div>

          <?= $form->field($model, 'gmap_lat')->hiddenInput()->label(false) ?>

          <?= $form->field($model, 'gmap_lng')->hiddenInput()->label(false) ?>

        </div>
    </div>

    <?= Html::submitButton($model->isNewRecord ? 'Опубликовать объявление' : 'Сохранить изменения', ['class' => 'btn btn-primary center-block']) ?>


  <?php ActiveForm::end(); ?>
</div>
