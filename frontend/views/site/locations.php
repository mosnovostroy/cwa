<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
//use Yii;

$this->title = 'Города, метро и районы';
$this->params['breadcrumbs'][] = $this->title;
$this->params['showCounters'] = true;
?>
<div class="site-about">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?php if ($model->locationDataprovider->getCount()>0 ) : ?>
        <h3>Города</h3>
        <ul style="margin-left: 0; padding-left: 0; list-style-type: none;">
            <?php foreach ($model->locationDataprovider->getModels() as $v): ?>
                <li>
                    <?= Html::a($v->name, ['center/index', 'location' => $v->id]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($model->stationDataprovider->getCount()>0 ) : ?>
        <h3>Станции метро</h3>
        <ul style="margin-left: 0; padding-left: 0; list-style-type: none;">
            <?php foreach ($model->stationDataprovider->getModels() as $v): ?>
                <li>
                    <?= Html::a($v->name, ['center/index', 'metro' => $v->id]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>
