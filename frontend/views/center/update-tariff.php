<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование тарифа';
$this->params['breadcrumbs'] =
[
    ['label' => 'Коворкинг-центры', 'url' => ['center/index']],
    ['label' => $center->regionName, 'url' => ['center/index', 'region' => $center->region]],
	['label' => $center->name, 'url' => ['center/view', 'id' => $center->id]],
];
?>

<h1>
    <?= Html::encode($this->title) ?>
</h1>

<div class="raw">
    <div class="col-md-12">
        <?= $this->render('_features-form', ['model' => $tariff]) ?>
    </div>
</div>
