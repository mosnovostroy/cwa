<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=cwa',
            'username' => 'root',
            'password' => 'avt240390',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.comments' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/comments/messages',
                ],
                // ...
            ],
        ],

        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@webroot/bootstrap/',
                    'css' => [
                        'css/bootstrap.min.css',
                        'css/bootstrap-theme.min.css'
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => '@webroot/bootstrap/',
                    'js' => ['js/bootstrap.min.js']
                ],
            ],
        ],
    ],
	'modules' => [
		'comment' => [
			'class' => 'yii2mod\comments\Module'
		],
	]
];
