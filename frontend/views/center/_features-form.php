<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Center */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="features-form">

	<?php $templ_col3 = '{label}<div class="col-sm-3">{input}<span style="font-size:0.7em;">{error}</span></div>'; ?>
	<?php $templ_col6 = '{label}<div class="col-sm-6">{input}<span style="font-size:0.7em;">{error}</span></div>'; ?>
	<?php $templ_col7 = '{label}<div class="col-sm-7">{input}<span style="font-size:0.7em;">{error}</span></div>'; ?>
	<?php $templ_col8 = '{label}<div class="col-sm-8">{input}<span style="font-size:0.7em;">{error}</span></div>'; ?>
	<?php $templ_col9 = '{label}<div class="col-sm-9">{input}<span style="font-size:0.7em;">{error}</span></div>'; ?>
	<?php $templ_col9hint = '{label}<div class="col-sm-9">{input}{hint}<span style="font-size:0.7em;">{error}</span></div>'; ?>
	<?php $templ_col11 = '<div class="col-sm-11">{input}<span style="font-size:0.7em;">{error}</span></div>'; ?>

	<?php $templ1 = '{label}<div class="col-sm-3">{input}<span style="font-size:0.7em;">{error}</span></div>'; ?>
	<?php $templ2 = '<div class="col-sm-3">{input}<span style="font-size:0.7em;">{error}</span></div>'; ?>

    <?php $form = ActiveForm::begin([
		'layout' => 'horizontal',
		'fieldConfig' => [
			'labelOptions' => ['class' => 'col-sm-3 control-label'],
			'hintOptions' => ['class' => 'col-sm-12', 'style' => 'color:#aaa;']
		]
	]); ?>

		<div class="row">
			<div class="col-md-4">
				<!-- <h2>Тариф</h2> -->
				<?= $model->isTariff ? $form->field($model, 'name', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->textInput() : '' ?>
				<?= $form->field($model, 'price_minute', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->textInput() ?>
				<?= $form->field($model, 'price_hour', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->textInput() ?>
				<?= $form->field($model, 'price_day', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->textInput() ?>
				<?= $form->field($model, 'price_week', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->textInput() ?>
				<?= $form->field($model, 'price_month', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->textInput() ?>

				<?= $form->field($model, 'is_fixed', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->dropDownList($model->fixMap, ['class' => 'selectpicker', 'data-width' => '100%']) ?>

				<?php /*echo $form->field($model, 'options', ['labelOptions' => ['class' => 'col-md-3 control-label'], 'template' => $templ_col9])->checkboxList($model->optionsMap);*/ ?>

				<?php echo $form->field($model, 'optionsStorage', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->checkboxList($model->optionsStorageMap); ?>

				<?php echo $form->field($model, 'optionsMeal', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->checkboxList($model->optionsMealMap); ?>

				<?php echo $form->field($model, 'optionsRecr', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->checkboxList($model->optionsRecrMap); ?>

				<?php echo $form->field($model, 'optionsTech', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->checkboxList($model->optionsTechMap); ?>

				<?php echo $form->field($model, 'optionsMisc', ['labelOptions' => ['class' => 'col-md-5 control-label'], 'template' => $templ_col7])->checkboxList($model->optionsMiscMap); ?>

			</div>
			<div class="col-md-8">

				<?= $model->isTariff ? $form->field($model, 'descr', ['labelOptions' => ['class' => 'col-md-3 control-label'], 'template' => $templ_col9])->textarea(['rows' => 2]) : '' ?>

				<?php
					for ($i = 1; $i <= 7; $i++)
					{
						echo $form->field($model, 'days_'.$i.'_mode', ['options' => ['class' => ''], 'template' => $templ1])->dropDownList($model->modeMap, ['class' => 'selectpicker', 'data-width' => '100%']);
						echo $form->field($model, 'days_'.$i.'_open', ['options' => ['class' => ''], 'template' => $templ2])->dropDownList($model->timeMap1, ['class' => 'selectpicker', 'data-width' => '100%', 'data-size' => '10']);
						echo $form->field($model, 'days_'.$i.'_close', ['options' => ['class' => 'clearfix'], 'template' => $templ2])->dropDownList($model->timeMap2, ['class' => 'selectpicker', 'data-width' => '100%', 'data-size' => '10']);
					}
				?>
				<br><br>
				<?= $form->field($model, 'printer_mode', ['options' => ['class' => ''], 'template' => $templ_col6])->dropDownList($model->printerModeMap, ['class' => 'selectpicker', 'data-width' => '100%']); ?>
				<?= $form->field($model, 'printer_pages', ['options' => ['class' => 'clearfix'], 'template' => $templ_col3])->textInput()->label(false) ?>

				<?= $form->field($model, 'meeting_room_mode', ['options' => ['class' => ''], 'template' => $templ_col6])->dropDownList($model->meetingRoomModeMap, ['class' => 'selectpicker', 'data-width' => '100%']); ?>
				<?= $form->field($model, 'meeting_room_hours', ['options' => ['class' => 'clearfix'], 'template' => $templ_col3])->textInput()->label(false) ?>

				<?= $form->field($model, 'courier_mode', ['options' => ['class' => ''], 'template' => $templ_col6])->dropDownList($model->courierModeMap, ['class' => 'selectpicker', 'data-width' => '100%']); ?>
				<?= $form->field($model, 'courier_count', ['options' => ['class' => 'clearfix'], 'template' => $templ_col3])->textInput()->label(false) ?>

				<?= $form->field($model, 'guest_mode', ['options' => ['class' => ''], 'template' => $templ_col6])->dropDownList($model->guestModeMap, ['class' => 'selectpicker', 'data-width' => '100%']); ?>
				<?= $form->field($model, 'guest_count', ['options' => ['class' => 'clearfix'], 'template' => $templ_col3])->textInput()->label(false) ?>

				<?= $form->field($model, 'options', ['labelOptions' => ['class' => 'col-md-3 control-label'], 'template' => $templ_col9hint])->textarea(['rows' => 5])->hint('Специфические опции, которые не могут быть включены в группы (Хранение, Питание и пр.): перечисляем через запятую и пробел') ?>


				<br>
				<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary center-block']) ?>

			</div>
		</div>

    <?php ActiveForm::end(); ?>

</div>
