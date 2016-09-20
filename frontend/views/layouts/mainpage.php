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
        <?= Alert::widget() ?>
        <?php
          foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
            echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
          }
        ?>
        <?=  $content ?>
    <!-- </div> -->
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">
            &copy; Коворкинг-ревю <?= date('Y') ?>
            &nbsp;&nbsp;&nbsp;
            <?= Html::a('О проекте', ['site/about']) ?>
        </p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
