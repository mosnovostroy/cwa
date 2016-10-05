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
    $this->title = 'Совместная аренда офиса в '.$searchModel->regionNameTp.' - объявления | Коворкинг-ревю';
    $this->registerMetaTag(['name' => 'description', 'content' => 'Совместная аренда офиса в '.$searchModel->regionNameTp.': объявления. Условия, фото, цены. Бесплатное размещение объявлений']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'совместная аренда офиса, разместить объявление, поиск партнера для аренды, '.$searchModel->regionName]);
}
else
{
    $this->title = 'Совместная аренда офиса - объявления | Коворкинг-ревю';
    $this->registerMetaTag(['name' => 'description', 'content' => 'База объявлений о совместной аренде офиса в Москве и регионах РФ. Условия, фото, цены. Бесплатная подача объявлений']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'совместная аренда офиса, разместить объявление бесплатно, поиск партнера для аренды']);
}
?>

<div id="mainform-small" class="row visible-xs">
    <div class="col-xs-12" style="">
        <span class="serp-title">Объявления</span>
		<span class="pull-right">
			<a class="btn btn-default" onclick="
				document.getElementById('mainform-small').className = 'hidden';
				document.getElementById('mainform-large').className = 'row';
				">Фильтр</a>
		</span>
    </div>
</div>

<div id="mainform-large" class="row hidden-xs">
    <div class="col-xs-12">
		<span class="pull-right visible-xs">
			<a class="btn btn-default" onclick="
				document.getElementById('mainform-small').className = 'row visible-xs';
				document.getElementById('mainform-large').className = 'row hidden-xs';
				">Скрыть</a>
		</span>
        <?php   $form = ActiveForm::begin(['method' => 'get', 'action' => ['arenda/index-submit'],
                                        'options' => ['class' => 'form-inline']]); ?>
                <span class="serp-title">Объявления</span>
                <?= $form->field($searchModel, 'region')->dropDownList($searchModel->regionsArray, ['class' => 'selectpicker', 'data-width' => 'auto'])->label(false) ?>
                <?= $form->field($searchModel, 'text')->hiddenInput()->label(false) ?>

                <?= Html::submitButton('Применить фильтр', ['class' => 'btn btn-primary', 'style' => 'margin-top: -10px;']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 serp-text">
        Совместная аренда офиса<?= $searchModel->regionNameTp ? ' в '.$searchModel->regionNameTp : ''?>. Найдено объявлений: <?= $dataProvider->getTotalCount() ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?php
            if (User::isUser())
                echo Html::a('Подать объявление', ['create'], ['class' => 'btn btn-danger']);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 serp-links">
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

<?php foreach ($dataProvider->getModels() as $model): ?>
    <?php $url = Url::to(['view', 'id' => $model->id]); ?>
    <div class="row">
      <div class="col-xs-12 card arenda" onclick="location.href='<?= $url ?>';">
          <div class="clearfix" >
            <?php if ($model->anons3x2) echo '<image class="card-image" src="'.$model->anons3x2.'">'; ?>
            <h3><a href="<?=$url?>"><?=Html::encode("{$model->name}")?></a></h3>
            <?php
            ?>
            <?php
                echo '<div class="card-params">';
                    echo '<p>'.$model->regionName.'</p>';
                echo '</div>';
            ?>

            <div class="card-text">
                <?= '<p>'.$model->anons_text.'</p>' ?>
                <?= '<p>'.$model->date.'</p>' ?>
            </div>
        </div>
      </div>
    </div>
<?php endforeach; ?>

<?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
