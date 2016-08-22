<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Картинки';
$this->params['breadcrumbs'] =
[
    ['label' => 'Коворкинг-центры', 'url' => ['center/index']],
    ['label' => $model->regionName, 'url' => ['center/index', 'region' => $model->region]],
	['label' => $model->name, 'url' => ['center/view', 'id' => $model->id]],
];
?>

<h1>
    <?= Html::encode($this->title) ?>
    <?= Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
	<?= Html::a('Тарифы', ['features', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
</h1>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model->imageUploadModel, 'uploadFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
    <!-- <button>Submit</button> -->
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end() ?>

<div class="table-responsive">
    <table class="table table-hover">
        <tbody>
            <tr>
                <th></th>
                <th>Имя</th>
                <th>Разрешение</th>
                <th>Размер, кб</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($model->images as $file) {?>
            <tr>
                <td><img src="<?= $file['thumbnail'] ?>" ></td>
                <td>
                    <?= end(explode('/',$file['file'])) ?>
                    <?= $file['is_anons'] ? '<span class="label label-primary label-lg">Анонс</span>' : Html::a('Анонс', ['file-set-as-anons', 'filename' => $name = end(explode('/',$file['file'])), 'id' => $model->id], ['class' => 'btn btn-default btn-xs']); ?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <?= Html::a('Удалить', ['delete-file', 'filename' => $name = end(explode('/',$file['file'])), 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Удалить файл?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
