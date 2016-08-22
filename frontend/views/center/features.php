<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Тарифы';
$this->params['breadcrumbs'] =
[
    ['label' => 'Коворкинг-центры', 'url' => ['center/index']],
    ['label' => $centerModel->regionName, 'url' => ['center/index', 'region' => $centerModel->region]],
	['label' => $centerModel->name, 'url' => ['center/view', 'id' => $centerModel->id]],
];
?>

<h1>
    <?= Html::encode($this->title) ?>
	<?= Html::a('Редактирование', ['update', 'id' => $centerModel->id], ['class' => 'btn btn-default']) ?>
    <?= Html::a('Картинки', ['pictures', 'id' => $centerModel->id], ['class' => 'btn btn-default']) ?>
</h1>
<p>
	<?php
		if (count($centerModel->tariffHeaders) === 0)
			echo '<span style="font-style:italic; margin-right: 15px;">тарифов нет</span>';
		foreach($centerModel->tariffHeaders as $k => $v)
		{
			echo Html::a($v, ['update-tariff', 'id' => $k, 'center_id' => $centerModel->id, ], ['class' => 'btn btn-link']);
			echo '&nbsp&nbsp&nbsp';
		}
	?>
	<?= Html::a('Новый тариф', ['create-tariff', 'center_id' => $centerModel->id], ['class' => 'btn btn-default']) ?>			
</p>		

<div class="raw">
    <div class="col-md-12">
		<h4>Общие характеристики центра</h4>
        <?= $this->render('_features-form', ['model' => $featuresModel]) ?>
    </div>
</div>

