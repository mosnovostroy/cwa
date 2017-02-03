<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\User;

/* @var $this yii\web\View */

$this->title = 'Администрирование';
$this->params['showCounters'] = false;

?>

<div class="container" style="padding-top: 0px;">

    <h1><p><?= $this->title ?></p></h1>

    <p>
        <?= Html::a('Редактор регионов', ['region/index']) ?>
    </p>

    <p>
        <?php
        echo Html::a('Обновить поля fastsearch у всех сущностей', ['site/update-fastsearch'], [
            'class' => 'btn btn-default',
              'data' => [
                  'confirm' => 'Действительно хотите обновить fastsearch у всех сущностей всех типов?',
                    'method' => 'post',
                    ],
                  ]);
        ?>
    </p>

    <p>
        <?php
        echo Html::a('Обновить все станции метро', ['site/update-metro'], [
            'class' => 'btn btn-default',
              'data' => [
                  'confirm' => 'Действительно хотите обновить все станции метро во всех городах?',
                    'method' => 'post',
                    ],
                  ]);
        ?>
    </p>

</div>
