<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use Yii;
use common\models\Center;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
?>

<div class="row">
    <div class="col-md-7">

        <?php
            $url = \yii\helpers\Url::to(['news-list']);

            echo Select2::widget([
                      'id' => 'www111',
                      'name' => 'state_10',
                      'options' => [
                          'placeholder' => 'Привязать новость',
                      ],
                      'pluginEvents' => [
                          "select2:select" => 'function() { $.pjax({url: "/center/add-news-link/?center_id='.$model->id.'&news_id=" + $("#www111").val(), type: "GET", container: "#w0", push: false}); }',
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

            foreach($model->news as $news) {
                echo '<div style="margin-bottom: 15px;"><span style="margin-right: 5px; ">';
                //echo Html::a($news->title, Url::toRoute(['news/update', 'id' => $news->id]));
                echo "Новость id".$news->id.": ".$news->title;
                echo '</span>';
                echo Html::a('Удалить привязку', Url::toRoute(['center/delete-news-link', 'center_id' => $model->id, 'news_id' => $news->id]), [
                               'class' => 'btn btn-default btn-xs',
                           ]);
                echo '</div>';
            }
        ?>
    </div>
</div>
