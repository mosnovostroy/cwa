<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\RegionSearch;


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

          <?= $form->field($model, 'region')->dropDownList(RegionSearch::getArrayWithoutNullItem(),
            [
              'onchange' => "locate_yandex_maps(this.options[this.selectedIndex].value)"
            ])->hint('Выберите регион и укажите точку на карте')
          ?>

          <div id="yandexmap" class="h360-yandexmap" style="margin-bottom: 30px;" arendaid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "9" ymaps_show_search = "1" ymaps_clickable = "1" ></div>

          <?= $form->field($model, 'gmap_lat')->hiddenInput()->label(false) ?>

          <?= $form->field($model, 'gmap_lng')->hiddenInput()->label(false) ?>

        </div>
    </div>

        <?= $form->field($model->imageUploadModel, 'uploadFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

        <!-- <h3>Загруженные изображения</h3> -->
        <?php if ($model->images) {?>
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th></th>
                        <th>Имя</th>
                        <th>Разрешение</th>
                        <th>Размер, кб</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                    <?php foreach ($model->images as $file) {?>
                    <tr>
                        <td><img src="<?= $file['thumbnail'] ?>" ></td>
                        <td>
                            <?= end(explode('/',$file['file'])) ?>
                            <?= $file['is_anons'] ? '<span class="label label-primary label-lg">Анонс</span>' : Html::a('Анонс', ['file-set-as-anons', 'filename' => $name = end(explode('/',$file['file'])), 'id' => $model->id], ['class' => 'btn btn-default btn-xs']); ?>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <?= Html::a('Удалить', ['delete-file', 'filename' => $name = end(explode('/',$file['file'])), 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Удалить файл?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>

    <?= Html::submitButton($model->isNewRecord ? 'Опубликовать объявление' : 'Сохранить изменения', ['class' => 'btn btn-primary center-block']) ?>

  <?php ActiveForm::end(); ?>
</div>
