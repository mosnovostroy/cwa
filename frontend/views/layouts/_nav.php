<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use Yii;
use common\models\RegionSearch;
use yii\web\JsExpression;
use kartik\select2\Select2;
use kartik\typeahead\Typeahead;
use common\models\User;

$searchModel = new RegionSearch();
$regions = $searchModel->search(Yii::$app->request->queryParams, true);

?>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Выбор региона</h4>
            </div>
            <div class="modal-body">
                <ul>
                    <?php
                    if (isset($regions)) {
                        foreach ($regions->getModels() as $region) {
                            echo '<li>'.
                                    (Html::a($region->name, ['/site/index', 'region' => $region->id])).
                                '</li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="top-bar">
    <div class="container">
        <div class="pull-left">
            <a href="" data-toggle="modal" data-target=".bs-example-modal-lg">
                <?php
                $regionName = Yii::$app->regionManager->name;
                if ($regionName) {
                    echo $regionName;
                }
                else {
                    echo "Выбрать регион";
                }
                ?>
                <span class="caret"></span>
            </a>
        </div>
        <div class="pull-right top-item">

<?php if (Yii::$app->user->isGuest) { ?>

            <?=Html::a('Вход', ['/site/login'])?>

<?php } else { ?>

            <div class="dropdown">
                <a id="dLabel" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?=Yii::$app->user->identity->username?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                    <?php if (User::isAdmin())
                        echo "<li>".(Html::a('Редактор регионов', ['region/index']))."</li>";
                        echo "<li>".(Html::a('Создать новость', ['news/create']))."</li>";
                        echo "<li>".(Html::a('Администрирование', ['site/admin']))."</li>";
                    ?>
                    <li><?=Html::a('Мои объявления', ['/site/my'])?></li>
                    <li><?=Html::a('Подать объявление', ['/arenda/create'])?></li>
                    <li><?=Html::a('Настройки', ['/site/profile'])?></li>
                    <li><?=Html::a('Выход', ['/site/logout'], ['data-method' => 'POST'])?></li>
                </ul>
            </div>

<?php } ?>

        </div>
    </div>
</div>

<?php
    NavBar::begin([
        'brandLabel' => 'Коворкинг-ревю',
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => [
            'class' => '',
            'style' => 'font-family: Times New Roman, serif; font-size: 32px; font-weight: normal;',
        ],
        'options' => [
            'class' => 'navbar navbar-default',
            'style' => 'box-shadow: none;',
        ],
        'innerContainerOptions' => ['class'=>'container'],

    ]);
?>


<?php
    $menuItems = [
        ['label' => 'Коворкинги', 'url' => ['/center/index']],
        //['label' => 'Блоги', 'url' => ['/blog/index']],
        ['label' => 'Новости', 'url' => ['/news/index']],
        ['label' => 'Объявления', 'url' => ['/arenda/index']],
        ['label' => '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>',
            'url' => null,
            'options'=>['style' => 'cursor: pointer; cursor: hand;', 'onclick'=>'$( "#main-search-form" ).toggle(); if ($( "#msf-input" ).is(":visible")) {$( "#msf-input" ).focus();} '],
        ],
    ];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right', ],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);

    NavBar::end();
?>

<style>
    .search-form {width: 100%; float: left;}
        .search-field {width: calc(100% - 105px); float: left;}
        .search-button {width: 100px; margin-left: 5px; background-color: #afa; float: left; }

    @media (min-width: 768px) {
        	.search-form { padding-left: 0; }
        }

</style>

<!-- Текстовое поле поиска -->
<div class="container">
    <?php
        $this->beginContent('@app/views/layouts/_mainSearch.php');
        $this->endContent();
    ?>
</div>

<style>
    /*.main-search {
        box-shadow: none;
        border: 0;
        width: 100%;
    }

    .main-search:focus {
        box-shadow: none;
    }

    .search-icon {width: 30px; min-height: 50px; float: left;}
    .search-form {width: calc(100% - 30px); float: left;}
        .search-field {width: calc(100% - 100px); float: left;}
        .search-button {width: 100px; float: left;}*/
</style>

<!-- <div class="container" style="">
    <div class="pull-left search-icon">
        <span class="glyphicon glyphicon-search" aria-hidden="true" style="margin-top: 15px;"></span>
    </div>
    <?= Html::beginForm(['search/index-submit'], 'get', ['class' => 'navbar-form form-inline search-form', ]) ?>
    	<div class="form-group input-group search-field">

    		<?= Html::input('text', 'text',
    		  isset(Yii::$app->request->queryParams['text']) ? Yii::$app->request->queryParams['text'] : null,
    		  ['class' => 'form-control main-search', 'placeholder' => 'Поиск по сайту'])?>

          </div>

              <span class="input-group-btn search-button">
      			<?= Html::submitButton('Найти', ['class' => 'btn btn-default', 'style' => 'width: 100%; border-radius: 0;']) ?>
      		</span>

    <?= Html::endForm() ?>
</div> -->
