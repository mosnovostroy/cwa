<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);

$this->registerMetaTag(['name' => 'og:title', 'content' => $model->meta_title]);
$this->registerMetaTag(['name' => 'og:description', 'content' => $model->meta_description]);
$this->registerMetaTag(['name' => 'og:url', 'content' => Url::to('', true)]);
$this->registerMetaTag(['name' => 'og:image', 'content' => ($model->images && count($model->images) > 0) ? Url::to($model->images[0]['file'], true) : '']);

$this->params['breadcrumbs'] =
[
    ['label' => $model->regionName, 'url' => ['site/index', 'region' => $model->region]],
    ['label' => 'Коворкинги', 'url' => ['center/index', 'region' => $model->region]],
];
$this->params['hasYandexMap'] = true;
$this->params['showCounters'] = true;
?>

<div class="row">
    <div class="col-xs-12">
        <h1><p>
            <!-- <div class="adapt"> -->
            <?= $model->name?>
            <?php
                if (User::isAdmin())
                {
                    echo Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-default']);
					          echo Html::a('Общие условия', ['update-features', 'id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Новый тариф', ['create-tariff', 'center_id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Картинки', ['pictures', 'id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Удалить центр', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-default',
                          'data' => [
                              'confirm' => 'Действительно хотите удалить центр?',
                                'method' => 'post',
                                ],
                              ]);
                }
            ?>
        </p></h1>
        <?php /*var_dump(unserialize($model->tariffs));*/ ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="clearfix">
            <?php if ($model->logoImage) echo '<div class="center-view-logo"><image src="'.$model->logoImage.'"></div>'; ?>
            <p><?= $model->address?></p>
            <p style="" class="special-interval"><?= $model->description?></p>

            <script type="text/javascript" src="http://yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
            <script type="text/javascript" src="http://yastatic.net/share2/share.js" charset="utf-8"></script>
            <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter"></div>

        </div>
        <!-- <h3><p>Общие условия</p></h3> -->
        <div class="row special-interval">
            <div class="col-sm-6">
                <?php
                    $features = $model->featuresModel;
                    if ($features && ( $features->issetPrices || $features->issetOptions || $features->descr))
                    {
                        echo '<h4><p>Общие условия:</p></h4>';
                        echo '<p>'.$features->descr.'</p>';
                        echo '<ul>';
                            foreach ($features->prices as $v) echo '<li>'.$v.'</li>';
                            foreach ($features->paramsList as $v) echo '<li>'.$v.'</li>';
                            foreach ($features->optionsList as $k => $v) echo '<li>'.$v.'</li>';
                        echo '</ul>';
                    }
                    /*if ($features && $features->issetPrices)
                    {
                        echo '<h4><p>Условия:</p></h4>';
                        echo '<ul>';
                            foreach ($features->prices as $v) echo '<li>'.$v.'</li>';
                        echo '</ul>';
                    }

                    if ($features && $features->issetOptions)
                    {
                        echo '<h4><p>Всем посетителям:</p></h4>';
                        echo '<ul>';
                            foreach ($features->paramsList as $v) echo '<li>'.$v.'</li>';
                            foreach ($features->optionsList as $k => $v) echo '<li>'.$v.'</li>';
                        echo '</ul>';
                    }*/
                ?>
            </div>
            <div class="col-sm-6">
                <?php
                if ($features && $features->issetTimetable)
                {
                    echo '<h4><p>Часы работы:</p></h4>';
                    echo '<ul>';
                        foreach($features->timetable as $v) echo '<li>'.$v.'</li>';
                    echo '</ul>';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <?= $model->site ? '<div class="dgray" style="position: absolute; right: 0; top: -1.7em; margin-right: 15px; font-size: 0.7em; float: right;">Фото: '.$model->site.'</div>' : '' ?>
        <?php
			$fotorama = \metalguardian\fotorama\Fotorama::begin(
			  [
				  'options' => [
					  'loop' => true,
					  'hash' => false,
					  'allowfullscreen' => true,
					  //'nav' => 'thumbs',
					  'thumbwidth' => 64,
					  'thumbheight' => 64,
				  ],
				  'spinner' => [
					  'lines' => 20,
				  ],
				  'tagName' => 'span',
				  'useHtmlData' => false,
				  'htmlOptions' => [
					  'class' => 'custom-class',
					  'id' => 'custom-id',
				  ],
			  ]
			);
			foreach ($model->images as $file)
			{
				echo '<img src="'.$file['file'].'" width=100%>';
				//echo $file.' - '.basename($file).'<br>';
			}
			$fotorama->end();
        ?>

    </div>
</div>

<?php if ($model->tariffsCount > 0) {?>
<!-- <div class="title-block clearfix"> -->
    <h3 class="h3-body-title">
        <p>Тарифы</p>
    </h3>
    <!-- <div class="title-separator"></div> -->
<!-- </div> -->
<?php }?>


<style>
    .tariff {
        width: 100%;
        background-color: #fee;
        border: 1px solid #bbb;
        padding: 15px;
        margin-bottom: 15px;
    }
    .tariff .head-tariff {
        width: 100%;
        text-align: center;
        margin-bottom: 15px;
    }
    .tariff .head-tariff h3{
        margin-top: 0px;
    }
</style>

<div class="row row-flex1234 row-flex1234-wrap">
    <?php $count = 0; ?>
    <?php foreach($model->tariffModels as $tariff) { ?>
        <div class="col-md-4">
        <div class="tariff">
            <div class="head-tariff">
            <h3><p>
                <?php
                    echo $tariff->name;
                    if (User::isAdmin())
                    {
                        echo '&nbsp;';
                        echo Html::a('Редактировать', ['update-tariff', 'id' => $tariff->id, 'center_id' => $model->id, ], ['class' => 'btn btn-default', 'style' => 'margin-bottom: 15px;']);
                      	echo Html::a('Удалить тариф', ['delete-tariff', 'id' => $tariff->id, 'center_id' => $model->id,], [
                      			'class' => 'btn btn-default',
                                'style' => 'margin-bottom: 15px;',
                      			  'data' => [
                      				  'confirm' => 'Действительно хотите удалить тариф?',
                      					'method' => 'post',
                      					],
                      				  ]);
                    }
                    else
                    {
                        //echo '<span style="font-size:0.5em;" class="lgray"> ТАРИФ</span>';
                    }
                ?>
            </p></h3>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p><?= $tariff->descr?></p>
                    <?php
                        if ($tariff->issetPrices || $tariff->issetOptions)
                        {
                            echo '<ul>';
                                foreach ($tariff->prices as $v) echo '<li>'.$v.'</li>';
                                foreach ($tariff->paramsList as $v) echo '<li>'.$v.'</li>';
                                foreach ($tariff->optionsList as $k => $v) echo '<li>'.$v.'</li>';
                            echo '</ul>';
                        }
                    ?>
                </div>
                <div class="col-sm-12">
                    <?php
                    if ($tariff->issetTimetable)
                    {
                        echo '<h4>Доступ:</h4>';
                        echo '<ul>';
                            foreach($tariff->timetable as $v) echo '<li>'.$v.'</li>';
                        echo '</ul>';
                    }
                    ?>
                </div>
            </div>
        </div>
        </div>
    <?php $count++; ?>
    <?php if ($count % 3 == 0) echo '</div><div class="row row-flex1234 row-flex1234-wrap">' ?>
    <?php } ?>

    <?php if ($count % 3 == 2) echo '<div class="col-md-4"></div>' ?>
    <?php if ($count % 3 == 1) echo '<div class="col-md-4"></div><div class="col-md-4"></div>' ?>
</div>

<!-- <div class="row">
    <div class="col-md-7">
        <?php foreach($model->tariffModels as $tariff) { ?>
            <h4><p>
                <?php
                    echo $tariff->name;
                    if (User::isAdmin())
                    {
                        echo '&nbsp;';
                        echo Html::a('Редактировать', ['update-tariff', 'id' => $tariff->id, 'center_id' => $model->id, ], ['class' => 'btn btn-default']);
                      	echo Html::a('Удалить тариф', ['delete-tariff', 'id' => $tariff->id, 'center_id' => $model->id,], [
                      			'class' => 'btn btn-default',
                      			  'data' => [
                      				  'confirm' => 'Действительно хотите удалить тариф?',
                      					'method' => 'post',
                      					],
                      				  ]);
                    }
                    else
                    {
                        echo '<span style="font-size:0.5em;" class="lgray"> ТАРИФ</span>';
                    }
                ?>
            </p></h4>
            <div class="row">
                <div class="col-sm-6">
                    <p><?= $tariff->descr?></p>
                    <?php
                        if ($tariff->issetPrices || $tariff->issetOptions)
                        {
                            echo '<ul>';
                                foreach ($tariff->prices as $v) echo '<li>'.$v.'</li>';
                                foreach ($tariff->paramsList as $v) echo '<li>'.$v.'</li>';
                                foreach ($tariff->optionsList as $k => $v) echo '<li>'.$v.'</li>';
                            echo '</ul>';
                        }
                    ?>
                </div>
                <div class="col-sm-6">
                    <?php
                    if ($tariff->issetTimetable)
                    {
                        echo '<h4>Доступ:</h4>';
                        echo '<ul>';
                            foreach($tariff->timetable as $v) echo '<li>'.$v.'</li>';
                        echo '</ul>';
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div> -->

<div class="row">
    <div class="col-sm-8">
        <h3 style="margin-top: 30px;"><p>Новости</p></h3>
        <?php $count = 0; ?>
        <?php foreach ($news->getModels() as $news): ?>
        <?php if ($count > 3) break; ?>
        <?php $url = Url::to(['news/view', 'id' => $news->id]); ?>
        <div class="clearfix">
            <a href="<?=$url?>">
                <img style="float:left; margin: 0 15px 15px 0;" src="<?=$news->anons150100?>">
            </a>
            <p>
                <a href="<?=$url?>"><?=$news->title?></a>
                <span class="news-date"><?=$news->date?></span>
            </p>
            <p>
                <?=$news->anons_text?>
            </p>
        </div>
        <?php $count++; ?>
        <?php endforeach; ?>
        <a href="">Все новости коворкинга >> </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">

        <h3 style="margin-top: 30px;"><p><?= $model->name?> на карте:</p></h3>

        <div
            id="yandexmap"
            class="h360-yandexmap"
            centerid="<?= $model->id?>"
            ymaps_lat = "<?= $model->gmap_lat?>"
            ymaps_lng = "<?= $model->gmap_lng?>"
            ymaps_scale = "16">
        </div>
    </div>
    <div class="col-md-6">

        <h3 style="margin-top: 30px;"><p>Контакты</p></h3>

        <div class="table-responsive">
            <table class="table table-hover">
                <tr>
                    <td>Адрес</td>
                    <td><?= $model->address?></td>
                </tr>
                    <td>Телефон</td>
                    <td><?= $model->phone?></td>
                <tr>
                    <td>Email</td>
                    <td><?= $model->email?></td>
                </tr>
                <tr>
                    <td>Сайт</td>
                    <td><?= $model->site?></td>
                </tr>
                <!-- <tr><td></td><td></td></tr> -->
            </table>
        </div>

        <div id="closest_metro" class="closest-metro"></div>

    </div>
</div>

<?php
    echo \yii2mod\comments\widgets\Comment::widget([
        'model' => $model,
        //'relatedTo' => 'User ' . \Yii::$app->user->identity->username . ' commented on the page ' . \yii\helpers\Url::current(), // for example
    	'relatedTo' => '', // for example
        'maxLevel' => 3, // maximum comments level, level starts from 1, null - unlimited level. Defaults to `7`
        'showDeletedComments' => true // show deleted comments. Defaults to `false`.
    ]);

    if (Yii::$app->user->isGuest) {
        echo '<div class="row"><div class="col-xs-12"><div style="margin-top: -50px;">Для комментирования нужно '.(Html::a('авторизоваться', ['site/login'])).'</div></div></div>';
    }
?>

<!-- <div class="title-block clearfix"> -->
    <h3 class="h3-body-title">
        <p>Коворкинги рядом</p>
    </h3>
    <!-- <div class="title-separator"></div> -->
<!-- </div> -->

<div class="row row-flex1234 row-flex1234-wrap" style="margin-bottom: 50px;">
    <?php $count = 1; ?>
    <?php foreach ($closestCenters->getModels() as $center): ?>
        <?php if ($count > 3) break; $url = Url::to(['center/view', 'id' => $center->id]); ?>
        <div class="col-md-4 tgbcol" onclick="location.href='<?= $url ?>';">
            <div class="tgb">
                <?php if ($center->anonsImage)
                    echo '<div class="tgbimg"><img src="'.$center->anons16x9.'"></div>';
                    // echo '<div class="redlabel">'.$center->regionName.'</div>'
                    if ($center->price_day > 0) echo '<div class="redlabel">'.$center->price_day.' руб./день</div>';
                ?>
                <h4><p><a href="<?=$url?>"><?=Html::encode("{$center->name}")?></a></p></h4>

                <p style="margin-top: -6px;">
                    <?php

                        if ($center->metro)
                            echo '<span class="metro-icon"> '.$center->metro.'</span>';
                        else
                            echo $center->address;
                    ?>
                </p>

                <div class="pb"><a href=""><span class="glyphicon glyphicon-menu-right"></span></a></div>
            </div>
        </div>
    <?php $count++; endforeach; ?>
</div>
