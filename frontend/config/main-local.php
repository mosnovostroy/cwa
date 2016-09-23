<?php

$config = [
    'components' => [
            'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => [
                // uncomment this to use streams in safe_mode
                //'useStreamsFallback' => true,
            ],
            'services' => [ // You can change the providers and their classes.
                'facebook' => [
                    // register your app here: https://developers.facebook.com/apps/
                    //'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                    'class' => 'frontend\components\FacebookOAuth2Service',
                    'clientId' => '1077026359047962',
                    'clientSecret' => 'bbf173eb54af179c66c30600411bd41b',
                ],
                'google_oauth' => array(
                    // register your app here: https://code.google.com/apis/console/
                    //'class' => 'nodge\eauth\services\GoogleOAuth2Service',
                    'class' => 'frontend\components\GoogleOAuth2Service',
                    'clientId' => '817686579012-kg17i48081p5tft0c7qvg9v0c0p6rj9q.apps.googleusercontent.com',
                    'clientSecret' => 'Rpxj2Rr89RkCrG49VsB2ZzQO',
                ),
                'yandex_oauth' => array(
                    // register your app here: https://oauth.yandex.ru/client/my
                    //'class' => 'nodge\eauth\services\YandexOAuth2Service',
                    'class' => 'frontend\components\YandexOAuth2Service',
                    'clientId' => 'e112643995a7489eaba28b61286ec6a0',
                    'clientSecret' => '2a0c47959a974264985ffd0198ba8e8f',
                ),
                'vkontakte' => array(
                    // register your app here: https://vk.com/editapp?act=create&site=1
                    //'class' => 'nodge\eauth\services\VKontakteOAuth2Service',
                    'class' => 'frontend\components\VKontakteOAuth2Service',
                    'clientId' => '5579638',
                    'clientSecret' => 'eU8FDaUebJaRRaP69kt6',
                ),
                'mailru' => array(
                    // register your app here: http://api.mail.ru/sites/my/add
                    //'class' => 'nodge\eauth\services\MailruOAuth2Service',
                    'class' => 'frontend\components\MailruOAuth2Service',
                    'clientId' => '746565',
                    'clientSecret' => 'a337ba9e765e28680c7d36fa65a996a1',
                ),
                'odnoklassniki' => array(
                    // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
                    // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
                    //'class' => 'nodge\eauth\services\OdnoklassnikiOAuth2Service',
                    'class' => 'frontend\components\OdnoklassnikiOAuth2Service',
					'clientId' => '1247873024',
                    'clientSecret' => 'A8E9D551AE41C8CAF89D46D5',
                    'clientPublic' => 'CBAJACGLEBABABABA',
                    //'title' => 'Odnoklas.',
                ),
			],
        ],

        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6Le3JyoTAAAAAAPRmrBO85LXHjR6AVfQzCN5nNvi',
            'secret' => '6Le3JyoTAAAAAClPSsqK9Fw5adC4UC5xXcc6EJpg',
        ],

        'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
            ],
        ],

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
                        'centers' => 'center/index',
                        'centers/map' => 'center/map',
                        'centers/coordinates' => 'center/coordinates',
                        'arenda' => 'arenda/index',
                        'arenda/map' => 'arenda/map',
                        'arenda/coordinates' => 'arenda/coordinates',
                        'signup' => 'site/signup',
                        'request-password-reset' => 'site/request-password-reset',
                        'reset-password' => 'site/reset-password',
                        'logout' => 'site/logout',
                        'my' => 'site/my',
                        'profile' => 'site/profile',
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
	$config['modules']['debug']['allowedIPs'] = ['93.191.17.238'];


    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
