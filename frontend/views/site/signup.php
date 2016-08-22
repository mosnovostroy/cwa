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
        <div class="col-lg-2">
		</div>
        <div class="col-lg-8">
			<h1><?= Html::encode($this->title) ?></h1>
			<div class="row">
				<div class="col-lg-7">
					<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

						<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
						<span id="helpBlock" class="help-block">
							Например, "Иван Фёдоров" или "ivan31415". Ваш логин понадобится для входа на сайт и будет отображаться как Ваше имя. 
						</span>				

						<?= $form->field($model, 'email') ?>
						<span id="helpBlock" class="help-block">
							На указанный адрес будет выслана ссылка для активации. 
						</span>				

						<?= $form->field($model, 'password')->passwordInput() ?>

						<div class="form-group">
							<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
						</div>

					<?php ActiveForm::end(); ?>
				</div>
				<div class="col-lg-1">
				</div>
				<div class="col-lg-4">
					<div style="font-weight: normal; margin-bottom: 5px;">Уже зарегистрированы?</div>
					<?= Html::a('Вход на сайт', ['site/login'], ['class' => 'btn btn-primary']) ?>
				</div>				
			</div>
        </div>
        <div class="col-lg-2">
		</div>
    </div>
</div>
