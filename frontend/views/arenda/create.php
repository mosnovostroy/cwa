<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Arenda */

$this->title = 'Create Arenda';
$this->params['breadcrumbs'][] = ['label' => 'Arendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hasYandexMap'] = true;
?>
<div class="arenda-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <div id="yandexmap" style="width: 100%; height: 400px" arendaid="<?= $model->id?>"></div>
</div>
