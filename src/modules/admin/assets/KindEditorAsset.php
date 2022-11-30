<?php

namespace admin\assets;

use yii\web\AssetBundle;

class KindEditorAsset extends AssetBundle{

    public $basePath = '@webroot/assets/admin';
    public $baseUrl = '@asset/admin/';

    public $css = [
        'kindeditor/themes/default/default.css'
    ];
    public $js = [
        'kindeditor/kindeditor-all-min.js',
    ];
    public $depends = [];
}