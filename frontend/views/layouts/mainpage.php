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
    <?php if (isset($this->params['hasYandexMap']))
        echo '<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"> </script>';
    ?>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter40340380 = new Ya.Metrika({
                        id:40340380,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/40340380" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
        
</head>
<body>
<?php $this->beginBody() ?>

<?php $this->beginContent('@app/views/layouts/_nav.php'); ?>
<?php $this->endContent(); ?>

<div class="wrap">
    <!-- <div class="container"> -->
        <?= Breadcrumbs::widget([
            'homeLink' => false,
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="alert-widget"><?= Alert::widget() ?></div>
        <?=  $content ?>
    <!-- </div> -->
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">
            &copy; Коворкинг-ревю <?= date('Y') ?>
            &nbsp;&nbsp;&nbsp;
            <?= Html::a('О проекте', ['site/about']) ?>
            &nbsp;&nbsp;&nbsp;
            <?= Html::a('Контакты', ['site/contacts']) ?>
            &nbsp;&nbsp;&nbsp;
            <?= Html::a('Реклама', ['site/adv']) ?>
        </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
