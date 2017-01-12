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
    <div class="col-md-7" style="margin-bottom: 15px;">
        <?php
            $url = \yii\helpers\Url::to(['centers-list']);

            echo Select2::widget([
                      'id' => 'www111',
                      'name' => 'state_10',
                      'options' => [
                          'placeholder' => 'Присоединить коворкинг',
                      ],
                      'pluginEvents' => [
                          "select2:select" => 'function() { $.pjax({url: "/news/add-center-link/?news_id='.$model->id.'&center_id=" + $("#www111").val(), type: "GET", container: "#w0", push: false}); }',
                      ],
                      'pluginOptions' => [
                          'ajax' => [
                              'url' => $url,
                              'dataType' => 'json',
                              'data' => new JsExpression('function(params) { return {q:params.term}; }')
                          ],
                      ],
                  ]);

            echo '</div class="col-md-7"><div class="col-md-5" style="margin-bottom: 20px;">';

            foreach($model->centers as $center) {
                echo '<div><span style="margin-right: 5px; ">';
                //echo Html::a($center->name, Url::toRoute(['center/update', 'id' => $center->id]));
                echo $center->name;
                echo '</span>';
                echo Html::a('Удалить', Url::toRoute(['news/delete-center-link', 'news_id' => $model->id, 'center_id' => $center->id]), [
                               'class' => 'btn btn-default btn-xs',
                           ]);
                echo '</div>';
            }
        ?>
    </div>
</div>
