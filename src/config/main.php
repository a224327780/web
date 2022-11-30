<?php

$config = [
    'id' => 'APP',
    'language' => 'zh-CN',
    'basePath' => SYS_ROOT,
    'runtimePath' => RUNTIME_ROOT,
    'vendorPath' => VENDOR_ROOT,
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => ['class' => 'app\modules\admin\Module'],
    ],
    //'extensions' => [],
    'as behaviors' => ['class' => 'app\behavior\AppBehavior'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
    ],
    'components' => [
        'assetManager' => require(__DIR__ . '/asset.php'),
        'log' => require(__DIR__ . '/log.php'),
        'urlManager' => require(__DIR__ . '/url.php'),
        'formatter' => [
            'datetimeFormat' => 'yyyy-MM-dd HH:mm',
            'dateFormat' => 'yyyy-MM-dd',
        ],
        'cache_db_schema' => require(__DIR__ . '/cache_db_schema.php'),
        'cache' => require(__DIR__ . '/cache.php'),
        'request' => [
            'enableCookieValidation' => defined('APP_KEY'),
            'cookieValidationKey' => defined('APP_KEY') ? APP_KEY : ''
        ],
        'errorHandler' => [
            'class' => 'app\components\BaseErrorHandler'
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/languages',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ]
    ],
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*']
    ];
}
if (file_exists(SYS_ROOT . '/config/db.php')) {
    $config['components']['db'] = require(SYS_ROOT . '/config/db.php');
}

return $config;