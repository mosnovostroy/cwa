<?php

use yii\helpers\Html;
use common\models\User;


$this->title = 'Редактирование';
$this->params['breadcrumbs'] =
[
    ['label' => 'Регионы', 'url' => ['region/index']],
    ['label' => $model->name ],
];
?>

<div class="row">
    <div class="col-xs-12">
        <h1><p>
            <?= $this->title?>
            <?php
                if (User::isAdmin()) {
                    echo Html::a('Населенные пункты', ['location/index', 'region' => $model->id], ['class' => 'btn btn-default']);
                }
            ?>
        </p></h1>
    </div>
</div>


<?= $this->render('_form', ['model' => $model,]) ?>
