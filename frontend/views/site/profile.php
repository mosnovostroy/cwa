<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\VarDumper;
use common\models\RegionSearch;

$this->title = 'Профиль пользователя: | Коворкинг-ревю';
$this->registerMetaTag(['name' => 'description', 'content' => 'Профиль пользователя: контактная информация, смена пароля, настройки']);
$this->registerMetaTag(['name' => 'keywords', 'content' => '']);
$this->params['showCounters'] = true;
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

          if ($model->social_service_name)
              $attributes[] =
              [
                  'label' => 'Авторизация через',
                  'value' => $model->social_service_name,
              ];

     ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>
  </div>
  <div class="col-md-6">
      <h3>&nbsp;</h3>
      <?php echo Html::img($model->getFoto(), ['alt' => $model->username]); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <h3>Ваш регион</h3>

    <?php $form = ActiveForm::begin(['id' => 'profile-form', 'action' => ['site/update-user', 'id' => $model->id]]); ?>

      <?= $form->field($model, 'region')->dropDownList(RegionSearch::getArrayForProfile())->label(false) ?>

      <div class="login-button-container">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary login-button', 'name' => 'login-button']) ?>
      </div>

    <?php ActiveForm::end(); ?>
  </div>
</div>
