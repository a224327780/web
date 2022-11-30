<?php

namespace app\assets;

use yii\web\AssetBundle;

class Error404Asset extends AssetBundle {

    public $basePath = '@theme_root';
    public $baseUrl = '@theme';

    public $css = [
        'css/404.css',
    ];
    public $js = [
    ];
    public $depends = [
    ];
}