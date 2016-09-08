<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use Yii;
?>

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
		  ['class' => 'form-control', 'style' => 'border-radius: 0;', 'placeholder' => 'Поиск по сайту'])?>


		  <?= isset(Yii::$app->request->queryParams['CenterSearch']['region']) ? Html::input('hidden', 'CenterSearch[region]', Yii::$app->request->queryParams['CenterSearch']['region']) : '' ?>

		<span class="input-group-btn">
			<?= Html::submitButton('Найти', ['class' => 'btn btn-default', 'style' => 'border-radius: 0;']) ?>
		</span>
	</div>
<?= Html::endForm() ?>

<?php
    $menuItems = [
        ['label' => 'Коворкинги', 'url' => ['/center/index']],
        ['label' => 'Совместная аренда', 'url' => ['/arenda/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
    } else {
        $temp = array();
        $temp['label'] = Yii::$app->user->identity->username;
        $temp['url'] = ['/site/my'];
        $menuItems[] = $temp;
        //$menuItems[] = ['label' => 'Мой КР', 'url' => ['/site/cabinet']];
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выход',
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
