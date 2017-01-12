<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\RegionSearch;

/* @var $this yii\web\View */
/* @var $model common\models\Center */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-7">

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'is_lead')->checkbox(); ?>

        </div>

        <div class="col-md-5">

            <?php if ($model->anonsImage) echo '<div style="margin-bottom: 30px;"><image src="'.$model->anonsImage.'" width=100%></div>'; ?>

            <?= $form->field($model, 'region')->dropDownList(RegionSearch::getArrayWithoutNullItem(),
              [
                'onchange' => ""
              ])
            ?>

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

    <?= Html::submitButton($model->isNewRecord ? 'Сохранить изменения' : 'Сохранить изменения', ['class' => 'btn btn-primary center-block']) ?>

    <?php ActiveForm::end(); ?>

</div>
