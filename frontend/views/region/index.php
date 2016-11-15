<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\LinkSorter;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;
/* @var $this yii\web\View */
    $this->title = 'Коворкинги: поиск';
    $this->registerMetaTag(['name' => 'description', 'content' => 'Каталог коворкингов в Москве и регионах РФ. Цены, условия, фото, отзывы посетителей']);
    $this->registerMetaTag(['name' => 'keywords', 'content' => 'коворкинг, коворкинг-центры в россии']);

    $this->params['showCounters'] = true;
?>


<?php foreach ($dataProvider->getModels() as $region): ?>
    <?php echo $region->name."<br>"; ?>
<?php endforeach; ?>
