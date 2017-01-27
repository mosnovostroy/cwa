<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use Yii;
use common\models\News;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
?>

<div class="row">
    <div class="col-md-12" style="margin-bottom: 15px;">
        <?php
            $url = \yii\helpers\Url::to(['regions-list']);

            echo Select2::widget([
                      'id' => 'www222',
                      'name' => 'state_20',
                      'options' => [
                          'placeholder' => 'Присоединить регион',
                      ],
                      'pluginEvents' => [
                          "select2:select" => 'function() { $.pjax({url: "/news/add-region-link/?news_id='.$model->id.'&region_id=" + $("#www222").val(), type: "GET", container: "#w2", push: false}); }',
                      ],
                      'pluginOptions' => [
                          'ajax' => [
                              'url' => $url,
                              'dataType' => 'json',
                              'data' => new JsExpression('function(params) { return {q:params.term}; }')
                          ],
                      ],
                  ]);

            echo '</div><div class="col-md-12" style="margin-bottom: 15px;">';

            foreach($model->regions as $region) {
                echo '<div><span style="margin-right: 5px; ">';
                echo $region->name;
                echo '</span>';
                echo Html::a('Удалить', Url::toRoute(['news/delete-region-link', 'news_id' => $model->id, 'region_id' => $region->id]), [
                               'class' => 'btn btn-default btn-xs',
                           ]);
                echo '</div>';
            }
        ?>
    </div>
</div>
