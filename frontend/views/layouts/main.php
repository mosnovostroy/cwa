<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
//use common\assets\CommonAppAsset;
use frontend\assets\BootstrapSelectAsset;
use common\widgets\Alert;
//use yii\widgets\ActiveForm;
use Yii;


//CommonAppAsset::register($this);
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

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Коворкинг-ревю',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);


    ?>

    <?= Html::beginForm(['center/index-submit'], 'get', ['class' => 'navbar-form form-inline navbar-left']) ?>
        <div class="form-group input-group">
            <?= Html::input('text', 'CenterSearch[text]',
              isset(Yii::$app->request->queryParams['CenterSearch']['text']) ? Yii::$app->request->queryParams['CenterSearch']['text'] : null,
              ['class' => 'form-control', 'style' => 'border-radius: 0;', 'placeholder' => 'Коворкинг-центры'])?>


              <?= isset(Yii::$app->request->queryParams['CenterSearch']['region']) ? Html::input('hidden', 'CenterSearch[region]', Yii::$app->request->queryParams['CenterSearch']['region']) : '' ?>

            <span class="input-group-btn">
                <?= Html::submitButton('Найти', ['class' => 'btn btn-default', 'style' => 'border-radius: 0;']) ?>
            </span>
        </div>
    <?= Html::endForm() ?>

<?php
    $menuItems = [
        ['label' => 'Коворкинг-центры', 'url' => ['/center/index']],
        ['label' => 'Совместная аренда', 'url' => ['/arenda/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'homeLink' => false,
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?=  $content ?>
    </div>
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
