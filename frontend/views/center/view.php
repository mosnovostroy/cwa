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
    ['label' => $model->regionName, 'url' => ['center/index', 'CenterSearch' => ['region' => $model->region]]]
];
$this->params['hasYandexMap'] = true;
?>

<div class="row">
    <div class="col-xs-12">
        <h1>
            <?= $model->name?>
            <?php
                if (User::isAdmin())
                {
                    echo Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-default']);
					          echo Html::a('Общие условия', ['update-features', 'id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Новый тариф', ['create-tariff', 'center_id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Картинки', ['pictures', 'id' => $model->id], ['class' => 'btn btn-default']);
                    echo Html::a('Удалить центр', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-default',
                          'data' => [
                              'confirm' => 'Действительно хотите удалить центр?',
                                'method' => 'post',
                                ],
                              ]);
                }
            ?>
        </h1>
        <?php /*var_dump(unserialize($model->tariffs));*/ ?>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <p><?= $model->address?></p>
        <p><?= $model->description?></p>
        <!-- <h3>Общие условия</h3> -->
        <div class="row">
            <div class="col-sm-6">
                <?php
                    $features = $model->featuresModel;
                    if ($features && ( $features->issetPrices || $features->issetOptions))
                    {
                        echo '<h4>Общие условия:</h4>';
                        echo '<ul>';
                            foreach ($features->prices as $v) echo '<li>'.$v.'</li>';
                            foreach ($features->paramsList as $v) echo '<li>'.$v.'</li>';
                            foreach ($features->optionsList as $k => $v) echo '<li>'.$v.'</li>';
                        echo '</ul>';
                    }
                    /*if ($features && $features->issetPrices)
                    {
                        echo '<h4>Условия:</h4>';
                        echo '<ul>';
                            foreach ($features->prices as $v) echo '<li>'.$v.'</li>';
                        echo '</ul>';
                    }

                    if ($features && $features->issetOptions)
                    {
                        echo '<h4>Всем посетителям:</h4>';
                        echo '<ul>';
                            foreach ($features->paramsList as $v) echo '<li>'.$v.'</li>';
                            foreach ($features->optionsList as $k => $v) echo '<li>'.$v.'</li>';
                        echo '</ul>';
                    }*/
                ?>
            </div>
            <div class="col-sm-6">
                <?php
                if ($features && $features->issetTimetable)
                {
                    echo '<h4>Часы работы:</h4>';
                    echo '<ul>';
                        foreach($features->timetable as $v) echo '<li>'.$v.'</li>';
                    echo '</ul>';
                }
                ?>
            </div>
        </div>

        <?php foreach($model->tariffModels as $tariff) { ?>
            <h3>
                <?php
                    echo $tariff->name;
                    if (User::isAdmin())
                    {
                        echo '&nbsp;';
                        echo Html::a('Редактировать', ['update-tariff', 'id' => $tariff->id, 'center_id' => $model->id, ], ['class' => 'btn btn-default']);
                      	echo Html::a('Удалить тариф', ['delete-tariff', 'id' => $tariff->id, 'center_id' => $model->id,], [
                      			'class' => 'btn btn-default',
                      			  'data' => [
                      				  'confirm' => 'Действительно хотите удалить тариф?',
                      					'method' => 'post',
                      					],
                      				  ]);
                    }
                    else
                    {
                        echo '<span class="tariff-label"> ТАРИФ</span>';
                    }
                ?>
            </h3>
            <div class="row">
                <div class="col-sm-6">
                    <p><?= $tariff->descr?></p>
                    <?php
                        if ($tariff->issetPrices || $tariff->issetOptions)
                        {
                            echo '<ul>';
                                foreach ($tariff->prices as $v) echo '<li>'.$v.'</li>';
                                foreach ($tariff->paramsList as $v) echo '<li>'.$v.'</li>';
                                foreach ($tariff->optionsList as $k => $v) echo '<li>'.$v.'</li>';
                            echo '</ul>';
                        }
                        /*if ($tariff->issetPrices)
                        {
                            //echo '<h4>Условия:</h4>';
                            echo '<ul>';
                                foreach ($tariff->prices as $v) echo '<li>'.$v.'</li>';
                            echo '</ul>';
                        }

                        if ($tariff->issetOptions)
                        {
                            //echo '<h4>В рамках тарифа:</h4>';
                            echo '<ul>';
                                foreach ($tariff->paramsList as $v) echo '<li>'.$v.'</li>';
                                foreach ($tariff->optionsList as $k => $v) echo '<li>'.$v.'</li>';
                            echo '</ul>';
                        }*/
                    ?>
                </div>
                <div class="col-sm-6">
                    <?php
                    if ($tariff->issetTimetable)
                    {
                        //echo '<h4>Доступное время работы:</h4>';
                        echo '<h4>Доступ:</h4>';
                        echo '<ul>';
                            foreach($tariff->timetable as $v) echo '<li>'.$v.'</li>';
                        echo '</ul>';
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-5">
        <?= $model->site ? '<div style="position: absolute; right: 0; top: -1.7em; margin-right: 15px; font-size: 0.7em; color: #aaa;  float: right;">Фото: '.$model->site.'</div>' : '' ?>
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

		<h4><?= $model->name?> на карте:</h4>
        <div id="yandexmap" class="inline-yandexmap" centerid="<?= $model->id?>" ymaps_lat = "<?= $model->gmap_lat?>" ymaps_lng = "<?= $model->gmap_lng?>"  ymaps_scale = "16"></div>

        <h4>Контакты коворкинга</h4>
        <table class="contacts">
            <tr>
                <td>Адрес</td>
                <td><?= $model->address?></td>
            </tr>
                <td>Телефон</td>
                <td><?= $model->phone?></td>
            <tr>
                <td>Email</td>
                <td><?= $model->email?></td>
            </tr>
            <tr>
                <td>Сайт</td>
                <td><?= $model->site?></td>
            </tr>
        </table>
    </div>
</div>


<?php echo \yii2mod\comments\widgets\Comment::widget([
    'model' => $model,
    //'relatedTo' => 'User ' . \Yii::$app->user->identity->username . ' commented on the page ' . \yii\helpers\Url::current(), // for example
	'relatedTo' => '', // for example
    'maxLevel' => 3, // maximum comments level, level starts from 1, null - unlimited level. Defaults to `7`
    'showDeletedComments' => true // show deleted comments. Defaults to `false`.
]); ?>
