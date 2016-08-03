<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
/* @var $this yii\web\View */
$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->params['breadcrumbs'] =
[
    ['label' => 'Коворкинг-центры', 'url' => ['center/index']],
    ['label' => $model->region_info->name, 'url' => ['center/index', 'CenterSearch' => ['region' => $model->region]]]
];
$this->params['hasYandexMap'] = true;
?>

<div class="raw">
    <div class="col-xs-12">
        <h1>
            <?= $model->name?>
            <?php
                if (Yii::$app->user && Yii::$app->user->identity && User::isUserAdmin(Yii::$app->user->identity->username))
                {
                    echo Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Картинки', ['pictures', 'id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-default',
                          'data' => [
                              'confirm' => 'Действительно хотите удалить центр?',
                                'method' => 'post',
                                ],
                              ]);
                }
            ?>
        </h1>
    </div>
</div>

<div class="raw">
    <div class="col-md-6">
        <p><?= $model->region_info->name?></p>
        <p><?= $model->description?></p>
    </div>
    <div class="col-md-6">
        <?php
            if ($model->imageFiles)
            {
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
                foreach ($model->imageFiles as $file)
                {
                    echo '<img src="'.$file['file'].'" width=100%>';
                    //echo $file.' - '.basename($file).'<br>';
                }
                $fotorama->end();
            }
        ?>
    </div>
</div>

<div class="raw">
    <div class="col-xs-12">
        <div id="yandexmap" class="wideyandexmap" centerid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "16"></div>
    </div>
</div>

<div class="raw">
    <div class="col-xs-12">
      <!-- <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
      <?= Html::a('Картинки', ['pictures', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
      <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
          'class' => 'btn btn-danger',
          'data' => [
              'confirm' => 'Действительно хотите удалить центр?',
              'method' => 'post',
          ],
      ]) ?> -->
    </div>
</div>
