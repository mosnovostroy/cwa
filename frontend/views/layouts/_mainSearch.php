<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\web\JsExpression;
    use kartik\typeahead\Typeahead;
?>

<div id="main-search-form" style="display: none;">
    <?= Html::beginForm(['search/index-submit'], 'get', ['class' => 'form-inline search-form', ]) ?>

        <?php

        $template_centers = "<div><a href=\"{{url}}\">{{value}}</a></div>";
        $template_stations = "<div><a href=\"{{url}}\">{{value}}</a></div>";

        echo Typeahead::widget([
            'id' => 'msf-input',
            'name' => 'text',
            'options' => ['placeholder' => 'Метро, район, название', 'style' => 'width: 100%; margin-bottom: 15px;' ],
            'container' => ['class' => 'search-field'],
            //'scrollable' => true,
            'pluginOptions' => ['highlight'=>true],
            'pluginEvents' => [
                "typeahead:select" => 'function() { window.location = "/search/?text=" + $("#msf-input").val() }',
            ],
            'dataset' => [
                [
                    //'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                    'display' => 'value',
                    'remote' => [
                        'url' => Url::to(['search/centers-list']) . '?q=%QUERY',
                        'wildcard' => '%QUERY'
                    ],
                    'templates' => [
                        'header' => '<span style="margin: 10px;">Коворкинги</span>',
                        //'notFound' => '<div class="text-danger" style="padding:0 8px">Ничего не найдено</div>',
                        'suggestion' => new JsExpression("Handlebars.compile('{$template_centers}')")
                    ]
                ],
                [
                    //'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                    'display' => 'value',
                    'remote' => [
                        'url' => Url::to(['search/stations-list']) . '?q=%QUERY',
                        'wildcard' => '%QUERY'
                    ],
                    'templates' => [
                        'header' => '<span style="margin: 10px;">Станции метро</span>',
                        //'notFound' => '<div class="text-danger" style="padding:0 8px">Ничего не найдено</div>',
                        'suggestion' => new JsExpression("Handlebars.compile('{$template_stations}')")
                    ]
                ],

            ]
        ]);

        ?>
        <span class="input-group-btn search-button">
          <?= Html::submitButton('Найти', ['class' => 'btn btn-default', 'style' => 'width: 100%; border-radius: 0;']) ?>
      </span>


    <?= Html::endForm() ?>
</div>
