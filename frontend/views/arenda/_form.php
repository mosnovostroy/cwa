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
      <div class="col-md-7">

          <?= $form->field($model, 'name')->textInput(['maxlength' => true])->hint('Не более 150 символов') ?>

          <?= $form->field($model, 'description')->textarea(['rows' => 10, 'maxlength' => true])->hint('Не более 1500 символов') ?>

          <?= $form->field($model, 'contacts')->textInput() ?>

          <br><br>
          <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success center-block' : 'btn btn-primary center-block']) ?>

      </div>

      <div class="col-md-5">

          <?php if ($model->anonsImage) echo '<image src="'.$model->anonsImage.'" width=100%>'; ?>

          <?= $form->field($model, 'region')->dropDownList($model->regionsArrayWithoutNullItem,
            [
              'onchange' => "locate_yandex_maps(this.options[this.selectedIndex].value)"
            ])->hint('Укажите точку на карте:')
          ?>

          <div id="yandexmap" class="inline-yandexmap" style="height: 650px!important; margin-bottom: 30px;" arendaid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "9" ></div>

          <?= $form->field($model, 'gmap_lat')->hiddenInput()->label(false) ?>

          <?= $form->field($model, 'gmap_lng')->hiddenInput()->label(false) ?>

        </div>
    </div>

  <?php ActiveForm::end(); ?>
</div>
