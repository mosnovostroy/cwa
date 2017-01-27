<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\User;

/* @var $this yii\web\View */

$this->title = 'Коворкинг-ревю: коворкинги и совместная аренда офиса';
$this->params['showCounters'] = true;

?>

<div class="container" style="padding-top: 0px;">

    <?php $section_title = 'Коворкинги'.($model->region ? ' в '.$model->regionNameTp : ''); ?>
    <h2><p><?= Html::a($section_title, ['center/index']) ?></p></h2>

    <div class="row row-flex1234 row-flex1234-wrap" style="margin-bottom: 50px;">
        <?php $count = 1; ?>
        <?php foreach ($centers->getModels() as $center): ?>
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

                    <div class="lgray" style="margin-top: 7px;"><?= $center->regionName ?></div>

                    <div class="pb"><a href=""><span class="glyphicon glyphicon-menu-right"></span></a></div>
                </div>
            </div>
        <?php $count++; endforeach; ?>
        <?php if ($count == 3)  echo '<div class="col-md-4"></div>';?>
        <?php if ($count == 2)  echo '<div class="col-md-4"></div><div class="col-md-4"></div>';?>
    </div>

    <div class="row">
        <!-- <div class="col-sm-1">
        </div> -->
        <?php $url = Url::to(['center/index']); ?>
        <div class="col-sm-6" onclick="location.href='<?= $url ?>';">
            <div class="button-main clearfix">
                <a href="<?=$url?>"><h4><p>Поиск коворкингов</p></h4></a>
            </div>
        </div>
        <!-- <div class="col-sm-1">
        </div> -->
        <?php $url = Url::to(['center/map']); ?>
        <div class="col-sm-6" onclick="location.href='<?= $url ?>';">
            <div class="button-main clearfix">
                <a href="<?=$url?>"><h4><p>Коворкинги на карте</p></h4></a>
            </div>
        </div>
        <!-- <div class="col-sm-1">
        </div> -->
    </div>


    <h2><p><?= Html::a('Новости рынка', ['news/index']) ?></p></h2>

    <div class="row" style="margin-bottom: 50px;">

        <?php foreach ($lead->getModels() as $news): ?>
        <?php if (!$news->anonsImage) break; ?>
        <?php $url = Url::to(['news/view', 'id' => $news->id]); ?>
            <div class="col-sm-6" onclick="location.href='<?= $url ?>';">
                <div class="news-lead">
                    <img src="<?= $news->anons16x9 ?>" width=100%>
                    <div class="news-lead-gradient"></div>
                    <div class="news-lead-text">
                        <h4><p><a href="<?=$url?>"><?= $news->title ?></a></p></h4>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>


        <div class="col-sm-6">
            <?php foreach ($other->getModels() as $news): ?>
            <?php $url = Url::to(['news/view', 'id' => $news->id]); ?>

            <div class="">
                <p>
                    <a href="<?=$url?>"><?=$news->title?></a>
                    <span class="news-date"><?=$news->date?></span>
                </p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($events && $events->getCount()): ?>
        <h2><p><?= Html::a('Мероприятия в коворкингах', ['event/index']) ?></p></h2>

        <div class="row cr-events">
            <?php $count = 1; ?>
            <?php foreach ($events->getModels() as $event): ?>
                <?php if ($count > 3) break; $url = Url::to(['event/view', 'id' => $event->id]); ?>
                <div class="col-md-4">

                    <h4><p><a href="<?=$url?>"><?=Html::encode("{$event->title}")?></a></p></h4>

                    <?php
                        $centerLogo = $event->centerLogo;
                        if ($centerLogo) {
                            echo '<div class="cr-events-logo">
                                <a href="'.$url.'">
                                    <img width=100% src="'.$centerLogo.'">
                                </a>
                            </div>';
                        }
                    ?>

                    <p><?=Html::encode("{$event->anons_text}")?></p>

                    <p><?= $event->eventDate ?></p>

                </div>
            <?php $count++; endforeach; ?>
            <?php if ($count == 3)  echo '<div class="col-md-4"></div>';?>
            <?php if ($count == 2)  echo '<div class="col-md-4"></div><div class="col-md-4"></div>';?>
        </div>
    <?php endif; ?>

    <h2><p><?= Html::a('Объявления', ['arenda/index'], ['class' => 'main-h3']) ?></p></h2>

    <?php $section_descr = 'Поиск партнеров для совместной аренды офиса'.($model->region ? ' в '.$model->regionNameTp : ''); ?>
    <p style=" margin-bottom: 20px;"><?=$section_descr?></p>

    <div class="row row-flex1234 row-flex1234-wrap">

        <?php $count = 1; ?>
        <?php foreach ($arenda->getModels() as $center): ?>
        <?php if ($count > 2) break; $url = Url::to(['arenda/view', 'id' => $center->id]); ?>
            <div class="col-sm-4" onclick="location.href='<?= $url ?>';">
                <div class="arenda-main clearfix">
                    <?php if ($center->anons120) echo '<img src="'.$center->anons120.'">'; ?>
                    <div class=""><a href="<?=$url?>"><?=Html::encode("{$center->name}")?></a></div>
                    <!-- <div class="small italic" style="margin-top: -2px; margin-bottom: 7px;"><?= $center->regionName ?></div> -->
                    <div><?= $center->anons_text_short ?></div>
                    <div class="dgray small" style="margin-top: 7px;"><?= $center->regionName ?></div>
                </div>
            </div>
        <?php $count++; endforeach; ?>

        <?php $url = Url::to(['arenda/create']); ?>
        <div class="col-sm-4 ">
            <div class="arenda-main arenda-main-button" onclick="location.href='<?= $url ?>';">
                <div class="arenda-button">
                    <a href="<?=$url?>"><h4><p>Подать объявление</p></h4></a>
                </div>
            </div>
        </div>

        <?php if ($count == 2)  echo '<div class="col-md-4"></div>';?>
        <?php if ($count == 1)  echo '<div class="col-md-4"></div><div class="col-md-4"></div>';?>

    </div>


</div>
