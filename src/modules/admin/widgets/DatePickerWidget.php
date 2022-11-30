<?php

namespace admin\widgets;

use admin\assets\DatePickerAsset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class DatePickerWidget extends Widget {

    public $datePickerSetting = [];
    protected $defaults = ['format' => 'yyyy-mm-dd', 'language' => 'zh-CN', 'autoclose' => 'true', 'todayHighlight' => 'true'];

    public function run() {
        $Setting = ArrayHelper::merge($this->defaults, $this->datePickerSetting);
        $Setting = Json::htmlEncode($Setting);
        $view = $this->getView();
        DatePickerAsset::register($view);
        $view->registerJs("jQuery('.datepicker').datepicker({$Setting});");
    }
}