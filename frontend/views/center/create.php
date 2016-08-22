<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Center */

$this->title = 'Создание страницы коворкинг-центра';
$this->params['breadcrumbs'][] = ['label' => 'Коворкинг-центры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hasYandexMap'] = true;
?>
<div class="center-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <div id="yandexmap" style="width: 100%; height: 400px" centerid="<?= $model->id?>"></div>
</div>
