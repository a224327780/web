<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {

    public $basePath = '@webroot/assets';
    public $baseUrl = '@web/assets';
    public $css = [
        'app/main.css',
    ];
    public $js = [
        'app/lazyload.min.js',
        'app/bxSlider.min.js',
        'app/main.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init() {
        parent::init();
        if(\Yii::$app->controller->id == 'site'){
            $this->js[] = 'app/jquery.cycle.all.js';
        }
    }
}