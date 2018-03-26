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
        <div class="container">

            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <ins class="adsbygoogle"
                style="display:block"
                data-ad-client="ca-pub-8483812460071635"
                data-ad-slot="1785873902"
                data-ad-format="auto">
            </ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>

            <?= Breadcrumbs::widget([
                'homeLink' => false,
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- <div class="alert-widget"><?= Alert::widget() ?></div> -->
            <?=  $content ?>
        </div>
    </div>

<!-- Футер -->
    <?php $this->beginContent('@app/views/layouts/_footer2.php'); ?>
    <?php $this->endContent(); ?>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
