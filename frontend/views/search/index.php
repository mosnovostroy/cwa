<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
    use yii\widgets\LinkSorter;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
    use common\models\User;

    $this->title = 'Коворкинги: поиск';
    $this->registerMetaTag(['name' => 'description', 'content' => 'Каталог коворкингов в Москве и регионах РФ. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг, коворкинг-центры в россии']);

    $this->params['showCounters'] = true;
?>

<div class="row">
    <div class="col-xs-12">
        <h1><p>
            Результаты поиска
        </p></h1>
    </div>
</div>


<div class="row">
    <div class="col-xs-12 serp-text">
        Найдено страниц: <?= $dataProvider->getTotalCount() ?>
    </div>
</div>

<?php $cnt=0; ?>
<?php foreach ($dataProvider->getModels() as $model): ?>
    <?php $url = Url::to(['center/view', 'id' => $model->id]); ?>
    <div class="row">
      <div class="col-xs-12 card arenda" onclick="location.href='<?= $url ?>';">
          <div class="clearfix" >
            <h3><a href="<?=$url?>"><?=Html::encode("{$model->name}")?></a></h3>
            <?php
            ?>
            <?php
                echo '<div class="card-params">';
                    echo '<p>'.$model->regionName.'</p>';
                echo '</div>';
            ?>

            <div class="card-text">
                <?= '<p>'.$model->description.'</p>' ?>
            </div>
        </div>
      </div>
    </div>
<?php endforeach; ?>

<?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
