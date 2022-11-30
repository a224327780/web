<?php

namespace admin\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {

    public $basePath = '@webroot/assets/admin';
    public $baseUrl = '@asset/admin/';
    public $css = [

        'https://cdn.bootcss.com/ladda-bootstrap/0.9.4/ladda-themeless.min.css',
        'css/main.css',
    ];
    public $js = [
        'https://cdn.bootcss.com/ladda-bootstrap/0.9.4/spin.min.js',
        'https://cdn.bootcss.com/ladda-bootstrap/0.9.4/ladda.min.js',
        'js/main.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
