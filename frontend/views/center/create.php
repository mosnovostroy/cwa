<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Center */

$this->title = 'Новый коворкинг-центр';
$this->params['breadcrumbs'][] = ['label' => 'Коворкинг-центры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hasYandexMap'] = true;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_form', ['model' => $model,]) ?>
