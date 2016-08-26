<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Общие условия';
$this->params['breadcrumbs'] =
[
    ['label' => 'Коворкинг-центры', 'url' => ['center/index']],
    ['label' => $model->regionName, 'url' => ['center/index', 'region' => $model->region]],
	['label' => $model->name, 'url' => ['center/view', 'id' => $model->id]],
];
?>

<h1>
    <?= Html::encode($this->title) ?>
    <?= Html::a('Вернуться на главную', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
</h1>
<div class="row">
    <div class="col-md-12">
        <?= $this->render('_features-form', ['model' => $model->featuresModel]) ?>
    </div>
</div>
