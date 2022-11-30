<?php
return [
    'appendTimestamp' => TRUE,
    'basePath' => '@webroot/assets/yii',
    'baseUrl' => '@web/assets/yii',
    'bundles' => [
        'yii\web\JqueryAsset' => [
            'sourcePath' => NULL,
            'baseUrl' => '@web/assets',
            'js' => ['yii/jquery.js']
        ],
        'yii\web\YiiAsset' => [
            'sourcePath' => NULL,
            'baseUrl' => '@web/assets',
            'js' => ['yii/yii.js']
        ],
        'yii\captcha\CaptchaAsset' => [
            'sourcePath' => NULL,
            'baseUrl' => '@web/assets',
            'js' => ['yii/yii.captcha.js']
        ],
        'yii\widgets\PjaxAsset' => [
            'sourcePath' => NULL,
            'baseUrl' => '@web/assets',
            'js' => ['pjax.min.js']
        ],
        'yii\validators\ValidationAsset' => [
            'sourcePath' => NULL,
            'baseUrl' => '@web/assets',
            'js' => ['yii/yii.validation.js']
        ],
        'yii\widgets\ActiveFormAsset' => [
            'sourcePath' => NULL,
            'baseUrl' => '@web/assets',
            'js' => ['yii/yii.activeForm.js']
        ],
        'yii\bootstrap\BootstrapAsset' => [
            'sourcePath' => NULL,
            'baseUrl' => '@web/assets',
            'css' => ['https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css']
        ],
        'yii\bootstrap\BootstrapPluginAsset' => [
            'sourcePath' => NULL,
            'baseUrl' => '@web/assets',
            'js' => ['https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js']
        ],
        'yii\grid\GridViewAsset' => [
            'sourcePath' => NULL,
            'baseUrl' => '@web/assets',
            'js' => ['yii/yii.gridView.js']
        ],
    ],
];