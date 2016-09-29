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

<div class="row">
    <div class="col-sm-6">
        <h3>Мои объявления</h3>
        <?php
            if ($dataProvider->getTotalCount() == 0)
            {
                echo "Пока у вас нет ни одного объявления.";
            }
        ?>
    </div>
    <div class="col-sm-6">
      <div class="new-arenda-button-container">
      <?php
          if (User::isUser())
              echo Html::a('Подать объявление', ['arenda/create'], ['class' => 'btn btn-danger new-arenda-button']);
      ?>
    </div>
    </div>
</div>

<div class="row serp-links">
    <div class="col-xs-12" style="">
          <?php
              if ($dataProvider->getTotalCount() > 4)
              {
                  echo '<div class="pull-right">';
                  $sort = $dataProvider->getSort();
                  if ($sort)
                      echo $sort->link('createdAt');
                  echo '</div>';
              }
          ?>
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

            <div class="center-index-text"><?= $center->anons_text ?></div>
          </div>
      </div>
  </div>
<?php endforeach; ?>
<?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
</div>
