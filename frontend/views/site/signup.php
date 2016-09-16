<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="row">
        <div class="col-md-2">
		    </div>
        <div class="col-md-8">
			      <h2><?= Html::encode($this->title) ?></h2>
			      <div class="row">
                <div class="col-md-7">
					          <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

						              <?= $form->field($model, 'username')->textInput(['autofocus' => true])->hint('Отображается в объявлениях и комментариях') ?>

						              <?= $form->field($model, 'email')->hint('Не отображается на сайте. Используется для подтверждения регистрации и в качестве логина') ?>

						              <?= $form->field($model, 'password')->passwordInput()->hint('Минимум 6 символов') ?>

                          <?= $form->field($model, 'reCaptcha')->widget(
                              \himiklab\yii2\recaptcha\ReCaptcha::className(), ['siteKey' => '6Le3JyoTAAAAAAPRmrBO85LXHjR6AVfQzCN5nNvi']) ?>

						              <div class="login-button-container">
							                <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success login-button', 'name' => 'signup-button']) ?>
						              </div>

					          <?php ActiveForm::end(); ?>
				        </div>
                <div class="col-md-1">
				        </div>
                <div class="col-md-4">
                    <p>Уже зарегистрированы?</p>
					          <div class="register-button-container">
					              <?= Html::a('Вход на сайт', ['site/login'], ['class' => 'btn btn-primary login-button']) ?>
				            </div>
			          </div>
           </div>
        </div>
        <div class="col-md-2">
        </div>
    </div>
</div>
