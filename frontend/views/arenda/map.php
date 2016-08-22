<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
if ($searchModel->regionNameTp)
{
    $this->title = 'Коворкинг-центры в '.$searchModel->regionNameTp;
    $this->registerMetaTag(['name' => 'description', 'content' => 'Коворкинг-центры в '.$searchModel->regionNameTp.': полный список. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг-центры, '.$searchModel->regionName]);
}
else
{
    $this->title = 'Коворкинг-центры: поиск';
    $this->registerMetaTag(['name' => 'description', 'content' => 'Каталог коворкинг-центров в Москве и регионах РФ. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг-центры в россии']);
}

$this->params['hasYandexMap'] = true;
?>

<div id="mainform-large" class="container-fluid main-form-position hidden">
	<div class="row">
		<div class="col-xs-12" style="">
			<span class="pull-right">
				<a class="btn btn-default" onclick="
					document.getElementById('mainform-large').classList.add('hidden');
					">Скрыть</a>
			</span>		
			<?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['arenda/map-submit'],
											'options' => ['class' => 'form-inline']]); ?>
					<span style="font-size: 1.6em; padding-right: 10px;">Коворкинг-центры</span>
					<?= $form->field($searchModel, 'region')->dropDownList($searchModel->regionsArray, ['class' => 'selectpicker', 'data-width' => 'auto'])->label(false) ?>
					<?= $form->field($searchModel, 'price_day_min')->textInput(['placeholder' => 'Цена за день'])->label(false) ?>
					<?= $form->field($searchModel, 'price_day_max')->textInput(['placeholder' => 'Цена за день'])->label(false) ?>
					<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary', 'style' => 'margin-top: -10px;']) ?>
			<?php ActiveForm::end(); ?>
			<?php
				if (User::isUser())
					echo Html::a('Создать новый', ['create'], ['class' => 'btn btn-default']);
			?>
		</div>
	</div>
	<div class="row hidden">
		<div class="col-xs-12" style="">
			<div class="pull-left">
				<?= Html::a('список', ['arenda/index', 'ArendaSearch' => $searchModel->toArray()]) ?>
				| карта
			</div>
		</div>
	</div>
</div>

<div id="yandexmap" class="wide-yandex-map"

  <?php
	  $params = $searchModel->toArray();
	  foreach ($params as $k => $v)
		  echo ' '.$k.' = "'.$v.'" ';
  ?>
  region_id = "<?= $searchModel->regionId ?>"
  ymaps_lat = "<?= $searchModel->regionMapLat ?>"
  ymaps_lng = "<?= $searchModel->regionMapLng ?>"
  ymaps_scale = "<?= $searchModel->regionMapZoom ?>"
></div>

