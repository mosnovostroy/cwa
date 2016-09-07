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
    $this->title = 'Совместная аренда офиса в '.$searchModel->regionNameTp;
    $this->registerMetaTag(['name' => 'description', 'content' => 'Совместная аренда офиса в '.$searchModel->regionNameTp.': объявления. Условия, фото, цены']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'совместная аренда офиса, '.$searchModel->regionName]);
}
else
{
    $this->title = 'Совместная аренда офиса: размещение объявлений';
    $this->registerMetaTag(['name' => 'description', 'content' => 'База объявлений о совместной аренде офиса в Москве и регионах РФ. Условия, фото, цены']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'совместная аренда офиса']);
}
?>

<div id="mainform-small" class="row visible-xs">
    <div class="col-xs-12" style="">
        <span style="font-size: 1.6em; padding-right: 10px;">Объявления</span>
		<span class="pull-right">
			<a class="btn btn-default" onclick="
				document.getElementById('mainform-small').className = 'hidden';
				document.getElementById('mainform-large').className = 'raw';
				">Фильтр</a>
		</span>
    </div>
</div>

<div id="mainform-large" class="row hidden-xs">
    <div class="col-xs-12" style="">
		<span class="pull-right visible-xs">
			<a class="btn btn-default" onclick="
				document.getElementById('mainform-small').className = 'raw visible-xs';
				document.getElementById('mainform-large').className = 'raw hidden-xs';
				">Скрыть</a>
		</span>
        <?php   $form = ActiveForm::begin(['method' => 'get', 'action' => ['arenda/index-submit'],
                                        'options' => ['class' => 'form-inline']]); ?>
                <span style="font-size: 1.6em; padding-right: 10px;">Объявления</span>
                <?= $form->field($searchModel, 'region')->dropDownList($searchModel->regionsArray, ['class' => 'selectpicker', 'data-width' => 'auto'])->label(false) ?>
                <?= $form->field($searchModel, 'text')->hiddenInput()->label(false) ?>

                <?= Html::submitButton('Применить', ['class' => 'btn btn-default', 'style' => 'margin-top: -10px;']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12" style="">
        <?php
            if (User::isUser())
                echo Html::a('Разместить объявление', ['create'], ['class' => 'btn btn-default']);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12" style="">
        <div class="pull-left">
            список
            | <?= Html::a('карта', ['arenda/map', 'ArendaSearch' => $searchModel->toArray()]) ?>
        </div>

        <div class="pull-right">
          <?php
              $sort = $dataProvider->getSort();
              if ($sort)
                  echo $sort->link('createdAt');
          ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <ul>
            <?php foreach ($dataProvider->getModels() as $arenda): ?>
                <li>
                    <?= Html::a(Html::encode("{$arenda->name}"), ['view', 'id' => $arenda->id]) ?><br>
                    <p><?= $arenda->description ?></p>
                    <?php if($arenda->rating) echo '<p>Рейтинг: '.$arenda->rating.'</p>';?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
    </div>
</div>
