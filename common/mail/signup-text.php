<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/signup-confirm', 'token' => $user->password_reset_token]);
?>
Здравствуйте,  <?= $user->username ?>!

Для подтверждения регистрации перейдите по ссылке:

<?= $confirmLink ?>
