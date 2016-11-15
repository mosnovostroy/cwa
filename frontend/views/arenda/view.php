<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

$this->title = 'Совместная аренда офиса ('.$model->regionName.') - объявление '.$model->alias.' | Коворкинг-ревю';
$this->registerMetaTag(['name' => 'description', 'content' => $model->name.' (объявление размещено '.$model->date.'). '.$model->anons_text ]);
$this->registerMetaTag(['name' => 'keywords', 'content' => 'совместная аренда офиса, бесплатные объявления, поиск партнера для аренды, сдаю часть офиса, сниму часть офиса']);
$this->params['breadcrumbs'] =
[
    ['label' => 'Совместная аренда офиса', 'url' => ['arenda/index']],
    ['label' => $model->regionName, 'url' => ['arenda/index', 'ArendaSearch' => ['region' => $model->region]]],
];
$this->params['hasYandexMap'] = true;
?>


<div class="row">
    <div class="col-xs-12">
        <h1>
            <?= $model->name?>
            <?php
                if (User::isAdminOrOwner($model->createdBy))
                {
                    echo Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Мои объявления', ['site/my'], ['class' => 'btn btn-default']);
                }
            ?>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">

        <div style="font-style: normal; color: #bbb; margin-top: -7px;">
            <?php  ?>
            Размещено <?= $model->date ?>
          </div>

          <br>
          <script type="text/javascript" src="http://yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
          <script type="text/javascript" src="http://yastatic.net/share2/share.js" charset="utf-8"></script>
          <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter"></div>
          <br>

        <div style="margin-top: 5px;"><?= $model->description?></div>

        <div style="margin-top: 5px;">Контактное лицо: <?= $model->username?></div>

        <?php if ($model->contacts) echo '<div style="margin-top: 5px;">Телефон: '.$model->contacts.'</div>'; ?>

    </div>
    <div class="col-md-6">
        <div class="fotorama-colontitul">Объявление <?= $model->alias ?> </div>
        <?php
			$fotorama = \metalguardian\fotorama\Fotorama::begin(
			  [
				  'options' => [
					  'loop' => true,
					  'hash' => false,
					  'allowfullscreen' => true,
					  //'nav' => 'thumbs',
					  'thumbwidth' => 64,
					  'thumbheight' => 64,
				  ],
				  'spinner' => [
					  'lines' => 20,
				  ],
				  'tagName' => 'span',
				  'useHtmlData' => false,
				  'htmlOptions' => [
					  'class' => 'custom-class',
					  'id' => 'custom-id',
				  ],
			  ]
			);
			foreach ($model->images as $file)
			{
				echo '<img src="'.$file['file'].'" width=100%>';
				//echo $file.' - '.basename($file).'<br>';
			}
			$fotorama->end();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4>Расположение объекта:</h4>
        <div id="yandexmap" class="h360-yandexmap" arendaid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "16" ymaps_hide_filter_button = "1"></div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php echo \yii2mod\comments\widgets\Comment::widget([
            'model' => $model,
            //'relatedTo' => 'User ' . \Yii::$app->user->identity->username . ' commented on the page ' . \yii\helpers\Url::current(), // for example
            'relatedTo' => '', // for example
            'maxLevel' => 3, // maximum comments level, level starts from 1, null - unlimited level. Defaults to `7`
            'showDeletedComments' => true // show deleted comments. Defaults to `false`.
        ]); ?>
    </div>
</div>
