<?php
use yii\helpers\Html;
use Yii;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/signup-confirm', 'token' => $user->password_reset_token]);

Yii::$app->formatter->locale = 'ru-RU';
?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>!</p>

    <p>Для подтверждения регистрации на сайте Коворкинг-ревю перейдите по ссылке:</p>

    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>

    <p>Ссылка активна до <?= Yii::$app->formatter->asDate($user->created_at + Yii::$app->params['user.signupConfirmTokenExpire'], 'long') ?>. </p>
</div>
