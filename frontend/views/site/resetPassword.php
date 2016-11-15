<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Новый пароль';
//$this->params['breadcrumbs'][] = $this->title;
$this->params['showCounters'] = true;
?>
<div class="site-reset-password">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <h3>Введите новый пароль:</h3>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label(false) ?>

                <div class="login-button-container">
                    <?= Html::submitButton('Сохранить пароль', ['class' => 'btn btn-primary login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
