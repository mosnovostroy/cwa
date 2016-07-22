<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CenterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Centers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="center-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Center', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn'],
            'id',
            'name',
            'description:ntext',
            'meta_title:ntext',
            'meta_description:ntext',
            // 'meta_keywords:ntext',
            // 'gmap_lat',
            // 'gmap_lng',
        ],
    ]); ?>
</div>
