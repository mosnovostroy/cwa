<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/signup-confirm', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>!</p>

    <p>Для подтверждения регистрации перейдите по ссылке:</p>

    <p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>
