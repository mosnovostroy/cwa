<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);

$this->registerMetaTag(['property' => 'og:title', 'content' => $model->meta_og_title]);
$this->registerMetaTag(['property' => 'og:description', 'content' => $model->meta_description]);
$this->registerMetaTag(['property' => 'og:url', 'content' => Url::to('', true)]);
$this->registerMetaTag(['property' => 'og:image', 'content' => ($model->images && count($model->images) > 0) ? Url::to($model->images[0]['file'], true) : '']);

$this->params['breadcrumbs'] =
[
    ['label' => 'Новости', 'url' => ['news/index']],
];
$this->params['showCounters'] = true;
?>

<div class="row">
    <div class="col-xs-12">
        <h1><p>
            <!-- <div class="adapt"> -->
            <?= $model->title?>
            <?php
                if (User::isAdmin())
                {
                    echo Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Удалить новость', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-default',
                          'data' => [
                              'confirm' => 'Действительно хотите удалить новость?',
                                'method' => 'post',
                                ],
                              ]);
                }
            ?>
        </p></h1>
        <?php /*var_dump(unserialize($model->tariffs));*/ ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <div class="clearfix">
            <p style="" class="special-interval"><?= $model->text?></p>

            <script type="text/javascript" src="http://yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
            <script type="text/javascript" src="http://yastatic.net/share2/share.js" charset="utf-8"></script>
            <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter"></div>

        </div>
    </div>
    <div class="col-sm-5">
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


<?php
    echo \yii2mod\comments\widgets\Comment::widget([
        'model' => $model,
        //'relatedTo' => 'User ' . \Yii::$app->user->identity->username . ' commented on the page ' . \yii\helpers\Url::current(), // for example
    	'relatedTo' => '', // for example
        'maxLevel' => 3, // maximum comments level, level starts from 1, null - unlimited level. Defaults to `7`
        // 'showDeletedComments' => true // show deleted comments. Defaults to `false`.
    ]);

    if (Yii::$app->user->isGuest) {
        echo '<div class="row"><div class="col-xs-12"><div style="margin-top: -50px;">Для комментирования нужно '.(Html::a('авторизоваться', ['site/login'])).'</div></div></div>';
    }
?>
