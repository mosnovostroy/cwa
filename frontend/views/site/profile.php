<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;


$this->title = 'Профиль пользователя: | Коворкинг-ревю';
$this->registerMetaTag(['name' => 'description', 'content' => 'Профиль пользователя: контактная информация, смена пароля, настройки']);
$this->registerMetaTag(['name' => 'keywords', 'content' => '']);
?>

<div class="row">
  <div class="col-md-6">
    <h3>Учетная запись</h3>

    <?php
        $attributes = [
            'username',
            'email',
            [
                'label' => 'Дата регистрации',
                'value' => Yii::$app->formatter->asDate($model->created_at, 'long'),
            ],
          ];

          if ($model->social_id)
              $attributes[] =
              [
                  'label' => 'Авторизация через',
                  'value' => $model->social_id,
              ];

     ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>
  </div>
  <div class="col-md-6">
    <h3>Ваш регион</h3>

    <?php $form = ActiveForm::begin(['id' => 'profile-form', 'action' => ['site/update-user', 'id' => $model->id]]); ?>

      <?= $form->field($model, 'region')->dropDownList($model->regionsArrayForProfile)->label(false) ?>

      <div class="login-button-container">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary login-button', 'name' => 'login-button']) ?>
      </div>

    <?php ActiveForm::end(); ?>
  </div>
</div>
