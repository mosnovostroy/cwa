<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;
use yii\widgets\DetailView;


$this->title = 'Профиль пользователя: | Коворкинг-ревю';
$this->registerMetaTag(['name' => 'description', 'content' => 'Профиль пользователя: контактная информация, смена пароля, настройки']);
$this->registerMetaTag(['name' => 'keywords', 'content' => '']);
?>

<h3>Профиль и настройки</h3>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'username',
        'email',
        [
            'label' => 'Дата регистрации',
            'value' => Yii::$app->formatter->asDate($model->created_at, 'long'),
        ],
    ],
]) ?>
