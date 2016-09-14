<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход на сайт';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">


	<?php
		if (Yii::$app->getSession()->hasFlash('error')) {
			echo '<div class="alert alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
		}
	?>

    <div class="row">
        <div class="col-lg-2">
		</div>
        <div class="col-lg-8">
			<h1><?= Html::encode($this->title) ?></h1>
			<div class="row">
				<div class="col-lg-7">
					<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

						<?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

						<?= $form->field($model, 'password')->passwordInput() ?>

            <div class="row">
      				  <div class="col-xs-6">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
                <div class="col-xs-6">
                    <div style="margin-top: 10px; float:right;"><?= Html::a('Забыли пароль?', ['site/request-password-reset']) ?></div>
                </div>
            </div>

						<div class="login-button-container">
							<?= Html::submitButton('Войти', ['class' => 'btn btn-primary login-button', 'name' => 'login-button']) ?>
						</div>
					<?php ActiveForm::end(); ?>

				</div>
				<div class="col-lg-1">
				</div>
				<div class="col-lg-4">
					<h4>Еще нет логина?</h4>
          <div class="login-button-container">
					       <?= Html::a('Регистрация', ['site/signup'], ['class' => 'btn btn-success login-button']) ?>
          </div>
					<h4>Войти с помощью:</h4>
					<?php echo \nodge\eauth\Widget::widget(array('action' => 'site/login')); ?>
				</div>
			</div>
        </div>
    </div>

</div>
