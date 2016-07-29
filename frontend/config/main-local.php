<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'YJRZcBCukXpSrGIidD9-EnsFptGiYbbq',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 0 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
                    'class'=>'frontend\components\CenterUrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                    'enableStrictParsing' => false,
                    'suffix' => '/',
                    'rules' => [
                        [
                            'class' => 'common\models\CenterUrlRule',
                        ],
                        'centers' => 'center/index',
                        'centers/map' => 'center/map',
                        'centers/<id:\d+>' => 'center/view',
                        //'centers/<:[a-z-]+>' => 'center/view',
                    ],
                ],
    ],
];

if (!YII_ENV_TEST) {
    $config['components']['log']['targets'][] = [
        'class' => 'yii\log\FileTarget',
        'levels' => ['info'],
        'categories' => ['myd'],
        'logFile' => '@frontend/runtime/logs/API/myd.log',
        'maxFileSize' => 1024 * 2,
        'maxLogFiles' => 20,
    ];
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
