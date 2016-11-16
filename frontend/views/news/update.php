<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Center */

$this->title = 'Редактирование';
$this->params['breadcrumbs'] =
[
    ['label' => 'Коворкинг-центры', 'url' => ['news/index']],
    ['label' => $model->regionName, 'url' => ['news/index', 'region' => $model->region]],
	['label' => $model->title, 'url' => ['news/view', 'id' => $model->id]],
];
$this->params['hasYandexMap'] = true;
?>
<div class="news-update">
    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a('Вернуться на главную', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </h1>
</div>

<?= $this->render('_form', ['model' => $model,]) ?>
