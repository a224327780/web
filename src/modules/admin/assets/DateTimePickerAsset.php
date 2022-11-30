<?php
namespace admin\assets;

use yii\web\AssetBundle;

class DateTimePickerAsset extends AssetBundle {

    public $basePath = '@webroot/assets';
    public $baseUrl = '@web/assets';
    public $css = [
        'common/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
    ];
    public $js = [
        'common/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
