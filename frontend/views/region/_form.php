<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\RegionSearch;

?>

<div class="region-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'name_tp')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'parent')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'map_lat')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'map_lng')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'map_zoom')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'hh_api_region')->textInput(['maxlength' => true]) ?>
            <div style="margin-bottom: 15px;"><a href="https://api.hh.ru/areas" target="_blank">Открыть в новой вкладке справочник городов hh.ru</a></div>
        </div>
    </div>

    <?= Html::submitButton($model->isNewRecord ? 'Сохранить изменения' : 'Сохранить изменения', ['class' => 'btn btn-primary center-block']) ?>

    <?php ActiveForm::end(); ?>

</div>
