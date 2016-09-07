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

          <?= $form->field($model, 'region')->dropDownList($model->regionsArray) ?>

          <?= $form->field($model, 'name')->textInput(['maxlength' => true])->hint('Не более 150 символов') ?>

          <?= $form->field($model, 'description')->textarea(['rows' => 10, 'maxlength' => true])->hint('Не боле 1500 символов') ?>

          <?= $form->field($model, 'contacts')->textInput() ?>

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
