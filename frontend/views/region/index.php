<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\LinkSorter;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;
/* @var $this yii\web\View */
    $this->title = 'Регионы';
    $this->registerMetaTag(['name' => 'description', 'content' => 'Регионы РФ для поиска коворкингов']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'регионы рф, коворкинг, коворкинг-центр']);

    $this->params['showCounters'] = true;

?>

<div class="row">
    <div class="col-xs-12">
        <h1><p>
            <?= $this->title?>
            <?php
                if (User::isAdmin()) {
                    echo Html::a('Создать регион', ['create'], ['class' => 'btn btn-default']);
                }
            ?>
        </p></h1>
    </div>
</div>

<style>
    .regions-table, .regions-table tr, .regions-table td {border: none;}
    .regions-table tr { height: 30px; border-bottom: 1px solid #ccc;}
    .regions-table tr:last-child { border-bottom: none;}
    .regions-table td{ padding: 0 5px; border-right: 1px solid #ccc; }
    .regions-table td:last-child{ border-right: none; }
    .regions-table td:first-child{ width: 200px; }
</style>

<table class="regions-table">
<?php foreach ($dataProvider->getModels() as $region): ?>
    <tr>
        <td>
            <?= Html::a($region->name, ['site/index', 'region' => $region->id], ['class' => '']) ?>
            <?php if (User::isAdmin() && $region->hh_api_region) { ?>
                <span style="float:right"><?= $region->hh_api_region ?></span>
            <?php } ?>
        </td>
        <?php if (User::isAdmin()) { ?>
            <td>в <?= $region->name_tp ?></td>
            <td><?= $region->alias ?></td>
            <td><?= $region->map_lat ?></td>
            <td><?= $region->map_lng ?></td>
            <td><?= $region->map_zoom ?></td>
            <td><?= Html::a('Редактировать', ['update', 'id' => $region->id], ['class' => '']) ?></td>
        <?php } ?>
    </tr>
<?php endforeach; ?>
</table>
