<?php

use yii\helpers\Html;
use common\models\User;

$this->title = 'Создание населенного пункта';

if ( User::isAdmin() && $model->regionName && $model->regionId ) {
    $breadcrumps = [
        ['label' => 'Регионы', 'url' => ['region/index']],
        ['label' => $model->regionName, 'url' => ['region/update', 'id' => $model->regionId]],
        ['label' => 'Населенные пункты', 'url' => ['index', 'region' => $model->regionId]],
        ['label' => $this->title],
    ];
} else {
    $breadcrumps = [
        ['label' => 'Регионы', 'url' => ['region/index']],
        ['label' => 'Населенные пункты', 'url' => ['index']],
        ['label' => $this->title],
    ];
}

    $this->params['breadcrumbs'] = $breadcrumps;

?>

<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_form', ['model' => $model,]) ?>
