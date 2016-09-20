<?php
use Yii;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/signup-confirm', 'token' => $user->password_reset_token]);

Yii::$app->formatter->locale = 'ru-RU';
?>
Здравствуйте,  <?= $user->username ?>!

Для подтверждения регистрации перейдите по ссылке:

<?= $confirmLink ?>

Ссылка активна до <?= Yii::$app->formatter->asDate($user->created_at, 'long') ?>.
