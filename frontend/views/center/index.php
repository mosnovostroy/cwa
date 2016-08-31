<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\LinkSorter;
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
?>

<div id="mainform-small" class="row visible-xs">
    <div class="col-xs-12 center-index-form">
        <span class="center-index-title">Коворкинг-центры</span>
		<span class="pull-right">
			<a class="btn btn-default" onclick="
				document.getElementById('mainform-small').className = 'hidden';
				document.getElementById('mainform-large').className = 'row';
				">Фильтр</a>
		</span>
    </div>
</div>

<div id="mainform-large" class="row hidden-xs">
    <div class="col-xs-12 center-index-form">
		<span class="pull-right visible-xs">
			<a class="btn btn-default" onclick="
				document.getElementById('mainform-small').className = 'row visible-xs';
				document.getElementById('mainform-large').className = 'row hidden-xs';
				">Скрыть</a>
		</span>
        <?php   $form = ActiveForm::begin(['method' => 'get', 'action' => ['center/index-submit'],
                                        'options' => ['class' => 'form-inline']]); ?>
                <span class="center-index-title">Коворкинг-центры</span>
                <?= $form->field($searchModel, 'region')->dropDownList($searchModel->regionsArray, ['class' => 'selectpicker', 'data-width' => 'auto'])->label(false) ?>
                <?= $form->field($searchModel, 'price_month_min')->textInput(['placeholder' => 'Цена за месяц, от'])->label(false) ?>
                <?= $form->field($searchModel, 'price_month_max')->textInput(['placeholder' => 'Цена за месяц, до'])->label(false) ?>
                <?= $form->field($searchModel, 'text')->hiddenInput()->label(false) ?>

                <?= Html::submitButton('Применить', ['class' => 'btn btn-default', 'style' => 'margin-top: -10px;']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12" style="">
        <?php
            if (User::isAdmin())
                echo Html::a('Создать новый', ['create'], ['class' => 'btn btn-default']);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12" style="">
        <div class="pull-left">
            список
            | <?= Html::a('карта', ['center/map', 'CenterSearch' => $searchModel->toArray()]) ?>
        </div>

        <div class="pull-right">
          <?php
              $sort = $dataProvider->getSort();
              if ($sort)
                  echo $sort->link('rating') . ' | ' . $sort->link('price_day');
          ?>
        </div>
    </div>
</div>

		<div class="center-index">
		<?php foreach ($dataProvider->getModels() as $center): ?>
			<?php $url = Url::to(['view', 'id' => $center->id]); ?>
      <div class="row">
          <div class="col-xs-12 center-index-col" onclick="location.href='<?= $url ?>';">
      				<div class="clearfix" >
                <?php if ($center->logoImage) echo '<div class="center-index-logo"><image src="'.$center->logoImage.'"></div>'; ?>
      					<?php if ($center->anonsImage) echo '<image class="center-index-image" src="'.$center->anonsImage.'">'; ?>
      					<h3><a href="<?=$url?>"><?=Html::encode("{$center->name}")?></a></h3>
                <?php
                    if ($center->paramsList || $center->is24x7())
                    {
                        echo '<div class="center-index-params">';
                        if ($center->paramsList)
                        {
                            echo '<ul>';
                            foreach($center->paramsList as $param)
                                echo '<li>'.$param.'</li>';
                            echo '<ul>';
                        }
                        if ($center->is24x7())
                        {
                            echo '<ul style="margin-top: 10px;">';
                            echo '<li style="font-weight: bold;">Круглосуточно</li>';
                            echo '<ul>';
                        }
                        echo '</div>';
                    }
                ?>
                <?php
                    $arr = array();
                    if ($center->regionName) $arr[] = $center->regionName;
                    if ($center->address) $arr[] = $center->address;
                    echo '<p>'.implode(' | ', $arr).'</p>';
                ?>

      					<p><?= $center->description ?></p>
      				</div>
          </div>
      </div>
		<?php endforeach; ?>
		</div>
        <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
