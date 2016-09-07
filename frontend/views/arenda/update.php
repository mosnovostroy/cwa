<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Arenda */

$this->title = 'Редактирование';
$this->params['breadcrumbs'] =
[
    ['label' => 'Совместная аренда', 'url' => ['arenda/index']],
    ['label' => $model->regionName, 'url' => ['arenda/index', 'region' => $model->region]],
];
$this->params['hasYandexMap'] = true;
?>
<div class="arenda-update">
    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a('Вернуться на главную', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </h1>
</div>

<?= $this->render('_form', ['model' => $model,]) ?>
