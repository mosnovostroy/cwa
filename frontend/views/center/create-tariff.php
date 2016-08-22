<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Новый тариф';
$this->params['breadcrumbs'] =
[
    ['label' => 'Коворкинг-центры', 'url' => ['center/index']],
    ['label' => $centerModel->regionName, 'url' => ['center/index', 'region' => $centerModel->region]],
	['label' => $centerModel->name, 'url' => ['center/view', 'id' => $centerModel->id]],
];
?>

<h1>
    <?= Html::encode($this->title) ?>
	<?= Html::a('Список тарифов', ['features', 'id' => $centerModel->id], ['class' => 'btn btn-default']) ?>
</h1>

<div class="raw">
    <div class="col-md-12">
        <?= $this->render('_features-form', ['model' => $featuresModel]) ?>
    </div>
</div>

