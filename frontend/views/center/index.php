<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
    use yii\widgets\LinkSorter;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
    use common\models\User;
    use common\models\Station;
    use yii\web\JsExpression;
    use kartik\typeahead\Typeahead;
    use kartik\select2\Select2;

    if ($searchModel->regionNameTp)
    {
        $this->title = 'Коворкинги в '.$searchModel->regionNameTp;
        $this->registerMetaTag(['name' => 'description', 'content' => 'Коворкинги в '.$searchModel->regionNameTp.': полный список. Цены, условия, фото, отзывы посетителей']);
        $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг, коворкинг-центр, '.$searchModel->regionName]);
    }
    else
    {
        $this->title = 'Коворкинги: поиск';
        $this->registerMetaTag(['name' => 'description', 'content' => 'Каталог коворкингов в Москве и регионах РФ. Цены, условия, фото, отзывы посетителей']);
        $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг, коворкинг-центры в россии']);
    }

    $this->params['showCounters'] = true;
?>
<h1><p>
    Коворкинги в <?=$searchModel->regionNameTp?>

    <?php
        //echo Html::a('Метро или район <span class="caret"></span>', ['site/location'], ['class' => 'btn btn-link']);
        if (User::isAdmin())
            echo Html::a('Создать новый', ['create'], ['class' => 'btn btn-default']);
    ?>
    <script type="text/javascript" src="http://yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="http://yastatic.net/share2/share.js" charset="utf-8"></script>
    <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter" ></div>

</p></h1>
<!-- <p>
    <span style="margin-right: 20px">Метро Академическая</span>
</p> -->
<!-- <p>
    <span style="margin-right: 20px">География поиска: метро Академическая</span>
    <?=Html::a('Указать метро или район', ['site/location'], ['class' => ''])?>
</p> -->

<div style="margin-bottom: 15px;">
    Найдено: <?= $dataProvider->getTotalCount() ?>

    <span class="visible-xs-inline">
        <span id="filter-link-show" >
            &nbsp;
    		<a href="#" onclick='
                            $( "#filter-link-show" ).toggle();
                            $( "#filter-link-hide" ).toggle();
                            $( "#centers-filter" ).toggleClass("hidden-xs");
                            '>
                Показать фильтр ...
            </a>
    	</span>
        <span id="filter-link-hide" style="display: none!important;">
            &nbsp;
            <a href="#" onclick='
                            $( "#filter-link-show" ).toggle();
                            $( "#filter-link-hide" ).toggle();
                            $( "#centers-filter" ).toggleClass("hidden-xs");
                            '>
                Скрыть фильтр
            </a>
    	</span>
    </span>
</div>


<div class="row">
    <div class="col-sm-9">
        <div id="centers-filter" class="row hidden-xs" style="background-color:#eee; padding-top: 10px; margin-bottom: 15px; min-height: 50px;">
            <div class="col-xs-12">

                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['center/index-submit'],
                                                'options' => ['class' => 'form-inline']]); ?>
                        <?php
                        $url = \yii\helpers\Url::to(['site/stations-list']);
                        $initMetro = empty($searchModel->metro) ? 'Метро или район' : Station::findOne($searchModel->metro)->name;

                        //echo Select2::widget([
                        echo $form->field($searchModel, 'metro')->label(false)->widget(Select2::classname(), [
                                  'id' => 'www111',
                                  //'name' => 'state_10',
                                  'initValueText' => $initMetro,
                                  'options' => [
                                      'placeholder' => 'Метро или район',
                                  ],
                                  'pluginEvents' => [
                                      "select2:select" => 'function() { $.pjax({url: "/news/add-center-link/?news_id='.$searchModel->id.'&center_id=" + $("#www111").val(), type: "GET", container: "#w0", push: false}); }',
                                  ],
                                  'pluginOptions' => [
                                      'ajax' => [
                                          'url' => $url,
                                          'dataType' => 'json',
                                          'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                      ],
                                      'width' => '200px',
                                      'allowClear' => true,
                                  ],
                              ]);
                        ?>

                        <?= $form->field($searchModel, 'price_month_min')->textInput(['placeholder' => 'Цена за месяц, от'])->label(false) ?>
                        <?= $form->field($searchModel, 'price_month_max')->textInput(['placeholder' => 'Цена за месяц, до'])->label(false) ?>

                        <?= $form->field($searchModel, 'is24x7')->checkbox() ?>

                        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary', 'style' => 'margin-top: -10px;']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <?php
        $target = $searchModel->toArray();
        $target[0] = 'center/map';
        $url = Url::to($target);
        // $url = Url::to(['center/map', 'CenterSearch' => $searchModel->toArray(), 'region' => $searchModel->region]);
    ?>
    <div class="col-sm-3" onclick="location.href='<?= $url ?>';">
        <div class="button-main clearfix">
            <a href="<?=$url?>"><h4><p><span class="glyphicon glyphicon-map-marker"> </span> На карте</p></h4></a>
        </div>
    </div>
</div>



<?php foreach ($dataProvider->getModels() as $center): ?>
    <?php $url = Url::to(['view', 'id' => $center->id]); ?>
    <div class="row">
      <div class="col-xs-12 card" onclick="location.href='<?= $url ?>';">
    				<div class="clearfix" >
            <?php if ($center->logoImage) echo '<div class="card-logo"><image src="'.$center->logoImage.'"></div>'; ?>
    					<?php if ($center->anons3x2) echo '<image class="card-image" src="'.$center->anons3x2.'">'; ?>
    					<h3><p><a href="<?=$url?>"><?=Html::encode("{$center->name}")?></a></p></h3>
            <?php
                if ($center->paramsList || $center->is24x7())
                {
                    echo '<div class="card-params">';
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

<?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
