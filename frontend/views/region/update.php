<?php

use yii\helpers\Html;

$this->title = 'Редактирование';
$this->params['breadcrumbs'] =
[
    ['label' => 'Регионы', 'url' => ['region/index']],
    ['label' => $model->name ],
];
?>
<div class="region-update">
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
</div>

<?= $this->render('_form', ['model' => $model,]) ?>
