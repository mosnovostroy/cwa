<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Center */

$this->title = $model->name . ': редактирование';
$this->params['breadcrumbs'] =
[
    ['label' => 'Коворкинг-центры', 'url' => ['center/index']],
    ['label' => $model->region_info->name, 'url' => ['center/index', 'region' => $model->region]]
];
$this->params['hasYandexMap'] = true;
?>
<div class="center-update">
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
        <div id="yandexmap" style="width: 100%; height: 400px" centerid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "16"></div>
    </div>
</div>
