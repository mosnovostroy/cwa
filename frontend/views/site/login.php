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

						<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

						<?= $form->field($model, 'password')->passwordInput() ?>

						<?= $form->field($model, 'rememberMe')->checkbox() ?>
						
						<div class="form-group">
							<?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
							
						</div>

						<span id="helpBlock" class="help-block">
							Забыли пароль? Попробуйте <?= Html::a('восстановить', ['site/request-password-reset']) ?>.
						</span>

					<?php ActiveForm::end(); ?>

				</div>
				<div class="col-lg-1">
				</div>
				<div class="col-lg-4">
					<div style="font-weight: normal; margin-bottom: 5px;">Еще нет логина?</div>
					<?= Html::a('Регистрация', ['site/signup'], ['class' => 'btn btn-success']) ?>
					<div style="font-weight: normal; margin-top: 15px; margin-bottom: 5px;">Войти с помощью:</div>
					<?php echo \nodge\eauth\Widget::widget(array('action' => 'site/login')); ?>								
				</div>
			</div>
        </div>
    </div>
		
</div>
