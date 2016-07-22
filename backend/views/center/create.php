<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Center */

$this->title = 'Create Center';
$this->params['breadcrumbs'][] = ['label' => 'Centers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="center-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
