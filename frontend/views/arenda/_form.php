<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Arenda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="arenda-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'region')->dropDownList($model->regionsArray) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'meta_title')->textarea(['rows' => 1]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 1]) ?>

    <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 1]) ?>

    <?= $form->field($model, 'gmap_lat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gmap_lng')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_day')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rating')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
