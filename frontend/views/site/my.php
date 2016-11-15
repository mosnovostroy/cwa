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
$this->params['showCounters'] = true;
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
<?php foreach ($dataProvider->getModels() as $model): ?>
  <?php $url = Url::to(['arenda/view', 'id' => $model->id]); ?>
  <div class="row">
      <div class="col-xs-12 card arenda noclickable">
          <div class="clearfix" >
            <?php if ($model->anons3x2) echo '<a href="'.$url.'"><image class="card-image" src="'.$model->anons3x2.'"></a>'; ?>
            <h3><a href="<?=$url?>"><?=Html::encode("{$model->name}")?></a></h3>
            <?php
            ?>
            <?php
                echo '<div class="card-params">';

                    echo Html::a('Редактировать', ['arenda/update', 'id' => $model->id], ['class' => 'btn btn-default buttton-width-100']);
                    //echo '<br>';
                    echo Html::a('Удалить', ['arenda/delete', 'id' => $model->id], [
                        'class' => 'btn btn-default buttton-width-100',
                          'data' => [
                              'confirm' => 'Действительно хотите удалить объявление навсегда?',
                                'method' => 'post',
                                ],
                              ]);
                    //echo '<p>'.$model->regionName.'</p>';
                    //echo '<p>'.$model->date.'</p>';
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
</div>
