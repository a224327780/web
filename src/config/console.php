<?php
$config = [
    'id' => 'console',
    'basePath' => SYS_ROOT,
    'vendorPath' => YII_ROOT,
    'runtimePath' => RUNTIME_ROOT,
    'bootstrap' => ['log'],
    'modules' => [],
    'extensions' => [],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'log' => require(__DIR__ . '/log.php'),
        'cache_db_schema' => require(__DIR__ . '/cache_db_schema.php'),
        'cache' => require(__DIR__ . '/cache.php'),
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\UserAr',
            'enableAutoLogin' => TRUE,
            'identityCookie' => ['name' => '__admin', 'httpOnly' => TRUE],
            'idParam' => '_auth',
            'loginUrl' => ['/auth/login'],
        ],
    ],
];
if (file_exists(RUNTIME_ROOT . '/config/db.php')) {
    $config['components']['db'] = require(RUNTIME_ROOT . '/config/db.php');
}

return $config;