<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\LinkSorter;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;

$this->title = 'Страница пользователя: | Коворкинг-ревю';
$this->registerMetaTag(['name' => 'description', 'content' => 'База объявлений о совместной аренде офиса в Москве и регионах РФ. Условия, фото, цены']);
$this->registerMetaTag(['name' => 'keywords', 'content' => 'совместная аренда офиса']);
?>

<div id="mainform-small" class="row visible-xs">
    <div class="col-xs-12" style="">
        <span class="serp-title">Объявления</span>
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
    <span class="serp-title">Мои объявления</span>
    <?php
        if (User::isUser())
            echo Html::a('Разместить объявление', ['arenda/create'], ['class' => 'btn btn-info', 'style' => 'margin-top: -5px;']);
    ?>
    </div>
</div>

<div class="row serp-links">
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

<div class="">
<?php foreach ($dataProvider->getModels() as $center): ?>
  <?php $url = Url::to(['arenda/view', 'id' => $center->id]); ?>
  <div class="row">
      <div class="col-xs-12 center-index-col" onclick="location.href='<?= $url ?>';">
          <div class="clearfix" >
            <?php if ($center->anons3x2) echo '<image class="arenda-index-image" src="'.$center->anons3x2.'">'; ?>
            <h3><a href="<?=$url?>"><?=Html::encode("{$center->name}")?></a></h3>
            <?php
            ?>
            <?php
                echo '<div class="center-index-params">';
                    echo '<p>'.$center->regionName.'</p>';
                    echo '<p>'.$center->date.'</p>';
                echo '</div>';
            ?>

            <div><?= $center->anons_text ?></div>
          </div>
      </div>
  </div>
<?php endforeach; ?>
<?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
</div>
