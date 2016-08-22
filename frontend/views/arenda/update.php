<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Arenda */

$this->title = $model->name . ': редактирование';
$this->params['breadcrumbs'] =
[
    ['label' => 'Коворкинг-центры', 'url' => ['arenda/index']],
    ['label' => $model->regionName, 'url' => ['arenda/index', 'region' => $model->region]]
];
$this->params['hasYandexMap'] = true;
?>
<div class="arenda-update">
    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a('Страница', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Картинки', ['pictures', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </h1>
</div>

<div class="raw">
    <div class="col-md-6">
        <?= $this->render('_form', ['model' => $model,]) ?>
    </div>
    <div class="col-md-6">
        <image src="<?= $model->anonsImage ?>" width=100%>
          <br><br>
        <div id="yandexmap" style="width: 100%; height: 400px" arendaid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "16"></div>
    </div>
</div>
