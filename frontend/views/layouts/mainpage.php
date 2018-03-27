<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\assets\BootstrapSelectAsset;
use common\widgets\Alert;
use Yii;

AppAsset::register($this);
BootstrapSelectAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="yandex-verification" content="74914594b8342c21" />
    <meta name="google-site-verification" content="0nMysBjx94fqc-B3As1GzpbfVWZCasepczBhoAJOYpM" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

<!-- Скрипты карты (при необходимости) -->
    <?php if (isset($this->params['hasYandexMap'])) {
        echo '<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"> </script>';
        }
    ?>

<!-- Счетчики, размещаемые _до_ тега body -->
    <?php
        if (isset($this->params['showCounters'])) {
            $this->beginContent('@app/views/layouts/_countersBeforeBody.php');
            $this->endContent();
        }
    ?>

</head>
<body>

<!-- Счетчики, размещаемые внутри body -->
    <?php
        if (isset($this->params['showCounters'])) {
            $this->beginContent('@app/views/layouts/_countersWithinBody.php');
            $this->endContent();
        }
    ?>

<?php $this->beginBody() ?>

<!-- Горизонтальное меню в шапке -->
<?php $this->beginContent('@app/views/layouts/_nav.php'); ?>
<?php $this->endContent(); ?>

<!-- Контент страницы -->
<div class="wrap">

    <div style="width: 100%;">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- Коворкинг - новый адаптивный -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-8483812460071635"
             data-ad-slot="5626077765"
             data-ad-format="auto"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>

    <?= Breadcrumbs::widget([
        'homeLink' => false,
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <div class="alert-widget"><?= Alert::widget() ?></div>
    <?=  $content ?>
</div>

<!-- Футер -->
<?php $this->beginContent('@app/views/layouts/_footer2.php'); ?>
<?php $this->endContent(); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
