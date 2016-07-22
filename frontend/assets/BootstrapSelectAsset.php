<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class BootstrapSelectAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-select/dist';
    public $css = [
        'css/bootstrap-select.min.css',
    ];
    public $js = [
        'js/bootstrap-select.min.js'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
