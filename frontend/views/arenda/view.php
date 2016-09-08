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
                    echo Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Картинки', ['pictures', 'id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-default',
                          'data' => [
                              'confirm' => 'Действительно хотите удалить объявление?',
                                'method' => 'post',
                                ],
                              ]);
                }
            ?>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-7">

        <div style="font-style: italic; color: #bbb;">
            <?php  ?>
            Объявление <?= $model->alias?>, размещено: <?= $model->username?>, <?= $model->date ?>
          </div>

        <div style="margin-top: 5px;"><?= $model->description?></div>

        <div style="margin-top: 5px;"><?= $model->contacts?></div>

        <?php echo \yii2mod\comments\widgets\Comment::widget([
            'model' => $model,
            //'relatedTo' => 'User ' . \Yii::$app->user->identity->username . ' commented on the page ' . \yii\helpers\Url::current(), // for example
        	'relatedTo' => '', // for example
            'maxLevel' => 3, // maximum comments level, level starts from 1, null - unlimited level. Defaults to `7`
            'showDeletedComments' => true // show deleted comments. Defaults to `false`.
        ]); ?>

    </div>
    <div class="col-md-5">
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

        <h4>Расположение объекта:</h4>
            <div id="yandexmap" class="inline-yandexmap" centerid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "16"></div>

    </div>
</div>
