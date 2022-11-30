<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('PRC');

defined('APP_ROOT') or define('APP_ROOT', dirname(__DIR__));
defined('SYS_ROOT') or define('SYS_ROOT', APP_ROOT . '/src');
defined('RUNTIME_ROOT') or define('RUNTIME_ROOT', SYS_ROOT . '/runtime');
defined('VENDOR_ROOT') or define('VENDOR_ROOT', APP_ROOT . '/vendor');
defined('YII_ROOT') or define('YII_ROOT', VENDOR_ROOT . '/yiisoft/yii2');

defined('APP_KEY') or define('APP_KEY', '998BBDA9F0228E098FDBEEEF83AE373C');
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('YII_DEBUG') or define('YII_DEBUG', YII_ENV !== 'prod');

require(VENDOR_ROOT . '/autoload.php');
require(YII_ROOT . '/Yii.php');

Yii::setAlias('admin', SYS_ROOT . '/modules/admin');
$config = require(SYS_ROOT . '/config/main.php');
(new \yii\web\Application($config))->run();

