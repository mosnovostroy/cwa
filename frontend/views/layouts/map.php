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


<div class="wrap-map">
	<?php $this->beginContent('@app/views/layouts/_nav.php'); ?>
	<?php $this->endContent(); ?>
	<div class="container-map">
        <?= Alert::widget() ?>
        <?=  $content ?>	
	</div>
</div>




<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>



