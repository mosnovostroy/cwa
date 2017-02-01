<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\LinkSorter;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;

if ($searchModel->regionNameTp) {
    $this->title = 'Населенные пункты в ' . $searchModel->regionNameTp;
    $this->registerMetaTag(['name' => 'description', 'content' => 'Населенные пункты регионов РФ для поиска коворкингов']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'населенные пункты, регионы рф, коворкинг, коворкинг-центр']);
} else {
    $this->title = 'Населенные пункты';
    $this->registerMetaTag(['name' => 'description', 'content' => 'Населенные пункты регионов РФ для поиска коворкингов']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'населенные пункты, регионы рф, коворкинг, коворкинг-центр']);
}

if ( User::isAdmin() && $searchModel->regionName && $searchModel->regionId ) {
    $breadcrumps = [
        ['label' => 'Регионы', 'url' => ['region/index']],
        ['label' => $searchModel->regionName, 'url' => ['region/update', 'id' => $searchModel->regionId]],
        ['label' => 'Населенные пункты'],
    ];
} else {
    $breadcrumps = [
        ['label' => 'Регионы', 'url' => ['region/index']],
        ['label' => 'Населенные пункты'],
    ];
}

    $this->params['breadcrumbs'] = $breadcrumps;

    $this->params['showCounters'] = true;

?>

<div class="row">
    <div class="col-xs-12">
        <h1><p>
            <?= $this->title?>
            <?php
                if (User::isAdmin()) {
                    echo Html::a(
                            'Создать населенный пункт',
                            [
                                'create',
                                'region' => ($searchModel->regionId ? $searchModel->regionId : null)
                            ],
                            ['class' => 'btn btn-default']
                        );
                }
            ?>
        </p></h1>
    </div>
</div>

<table class="regions-table">
    <?php if (User::isAdmin() && $dataProvider->getTotalCount()) { ?>
        <tr>
            <td>Название</td>
            <td>Творительный падеж</td>
            <td>Алиас</td>
            <td>Наименование региона в адресе</td>
            <td>Регион</td>
            <td></td>
        </tr>
    <?php } ?>
<?php foreach ($dataProvider->getModels() as $location): ?>
    <tr>
        <td>
            <?= $location->name ?>
        </td>
        <?php if (User::isAdmin()) { ?>
            <td>в <?= $location->name_tp ?></td>
            <td><?= $location->alias ?></td>
            <td><?= $location->address_atom ?></td>
            <td><?= $location->regionName ?></td>
            <td><?= Html::a('Редактировать', ['update', 'id' => $location->id], ['class' => '']) ?></td>
        <?php } ?>
    </tr>
<?php endforeach; ?>
</table>
