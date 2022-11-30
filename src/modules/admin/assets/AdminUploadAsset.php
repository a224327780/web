<?php

namespace admin\assets;

use yii\web\AssetBundle;

class AdminUploadAsset extends AssetBundle {

    public $basePath = '@webroot/assets/admin';
    public $baseUrl = '@asset/admin/';
    public $css = [
    ];
    public $js = [
        'p-upload/upload.full.min.js',
        'p-upload/upload.action.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}