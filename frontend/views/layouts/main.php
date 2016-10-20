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

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-42047170-3', 'auto');
      ga('send', 'pageview');

    </script>

</head>
<body>

    <!-- Rating@Mail.ru counter -->
    <script type="text/javascript">
    var _tmr = window._tmr || (window._tmr = []);
    _tmr.push({id: "2827040", type: "pageView", start: (new Date()).getTime()});
    (function (d, w, id) {
      if (d.getElementById(id)) return;
      var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
      ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
      var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
      if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
    })(document, window, "topmailru-code");
    </script><noscript><div style="position:absolute;left:-10000px;">
    <img src="//top-fwz1.mail.ru/counter?id=2827040;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
    </div></noscript>
    <!-- //Rating@Mail.ru counter -->

<?php $this->beginBody() ?>

<?php $this->beginContent('@app/views/layouts/_nav.php'); ?>
<?php $this->endContent(); ?>

<div class="wrap">
    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => false,
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="alert-widget"><?= Alert::widget() ?></div>
        <?=  $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="footer-counters">
            <!-- Rating@Mail.ru logo -->
            <a href="http://top.mail.ru/jump?from=2827040">
            <img src="//top-fwz1.mail.ru/counter?id=2827040;t=602;l=1"
            style="border:0;" height="40" width="88" alt="Рейтинг@Mail.ru" /></a>
            <!-- //Rating@Mail.ru logo -->
        </div>
        <div class="footer-links">
            &copy; Коворкинг-ревю <?= date('Y') ?>
            &nbsp;&nbsp;
            <?= Html::a('О&nbsp;проекте', ['site/about']) ?>
            &nbsp;&nbsp;
            <?= Html::a('Контакты', ['site/contacts']) ?>
            &nbsp;&nbsp;
            <?= Html::a('Реклама', ['site/adv']) ?>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
