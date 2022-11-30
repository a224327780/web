<?php
namespace admin\assets;

use yii\web\AssetBundle;

class DatePickerAsset extends AssetBundle {

    public $basePath = '@webroot/assets';
    public $baseUrl = '@web/assets';
    public $css = [
        'common/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
    ];
    public $js = [
        'common/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
