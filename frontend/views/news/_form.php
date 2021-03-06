<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\RegionSearch;
use yii\widgets\Pjax;
use vova07\imperavi\Widget;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Center */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <div class="row">
        <div class="col-md-8">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <div class="row">
                <div class="col-md-7">
                    <?= $form->field($model, 'eventDate')
                        ->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'Для новости оставить поле пустым'],
                            'pluginOptions' => [
                                'format' => 'dd.mm.yyyy',
                                'autoclose'=>true,
                            ]
                        ]);
                    ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'is_lead')->checkbox(); ?>
                </div>
            </div>


            <?= $form->field($model, 'text')->widget(Widget::className(), [
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'plugins' => [
                        'clips',
                        'fullscreen'
                    ]
                ]
            ]) ?>

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
        <div class="col-md-4">
            <?php if ($model->anonsImage) echo '<div style="margin-bottom: 30px;"><image src="'.$model->anonsImage.'" width=100%></div>'; ?>

            <div>
                <?php
                    Pjax::begin(['enablePushState' => false]);
                        echo $this->render('_centers', ['model' => $model]);
                    Pjax::end();

                    Pjax::begin(['enablePushState' => false]);
                        echo $this->render('_regions', ['model' => $model]);
                    Pjax::end();
                ?>
            </div>

        </div>
     </div>



</div>
