<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\LocationSearch;
use common\models\RegionSearch;

?>

<div class="location-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'region')->dropDownList(RegionSearch::getArrayWithoutNullItem(), ['class' => 'selectpicker', 'data-width' => '100%']) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'name_tp')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'address_atom')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
        </div>
    </div>

    <?= Html::submitButton($model->isNewRecord ? 'Сохранить изменения' : 'Сохранить изменения', ['class' => 'btn btn-primary center-block']) ?>

    <?php ActiveForm::end(); ?>

</div>
