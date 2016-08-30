<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Center */

$this->title = 'Редактирование';
$this->params['breadcrumbs'] =
[
    ['label' => 'Коворкинг-центры', 'url' => ['center/index']],
    ['label' => $model->regionName, 'url' => ['center/index', 'region' => $model->region]],
	['label' => $model->name, 'url' => ['center/view', 'id' => $model->id]],
];
$this->params['hasYandexMap'] = true;
?>
<div class="center-update">
    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a('Вернуться на главную', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </h1>
</div>

<?= $this->render('_form', ['model' => $model,]) ?>
