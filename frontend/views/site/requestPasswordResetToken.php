<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Восстановление доступа';
//$this->params['breadcrumbs'][] = $this->title;

// Yii::$app->mailer->compose()
//     ->setFrom('inbox@mosnovostroy.ru')
//     ->setTo('mosnovostroy@ya.ru')
//     ->setSubject('Тема сообщения')
//     ->setTextBody('Текст сообщения')
//     ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
//     ->send();

?>
<div class="site-request-password-reset">
    <h2><?= Html::encode($this->title) ?></h2>

    <p>Введите адрес электронный почты, указанный при регистрации:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label(false) ?>

                <div class="login-button-container">
                    <?= Html::submitButton('Сбросить текущий пароль', ['class' => 'btn btn-primary login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
