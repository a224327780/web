<?php

namespace admin\widgets;

use admin\assets\AdminUploadAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;

class AdminUploadWidget extends InputWidget {

    public $uploadSetting = [];
    public $imageSetting = [];

    public $template = '{input} {image}';

    public $Setting = ['class' => 'form-control', 'data-toggle' => 'tooltip', 'title' => '点击上传', 'placeholder' => '点击上传'];

    public function init() {
        parent::init();

        if (!isset($this->imageSetting['id'])) {
            $this->imageSetting['id'] = $this->id . '-image';
            $this->imageSetting['class'] = 'upload-preview';
            $this->imageSetting['style'] = 'display:none;';
        }
    }

    public function run() {
        $this->registerClientScript();

        if ($this->hasModel()) {
            $input = Html::activeTextInput($this->model, $this->attribute, $this->Setting);
        } else {
            $input = Html::textInput($this->name, $this->value, $this->Setting);
        }

        $src = ArrayHelper::getValue($this->model, $this->attribute);
        $image = empty($src) ? '' : Html::a(Html::img($src, $this->imageSetting), $src, ['target' => '_bank']);
        echo strtr($this->template, [
            '{input}' => $input,
            '{image}' => $image,
        ]);
    }

    protected function registerClientScript() {
        $Setting = ArrayHelper::merge($this->uploadSetting, $this->Setting);
        if (!isset($Setting['url'])) {
            $Setting['url'] = Url::to(['default/upload']);
        }
        $Setting = Json::htmlEncode($Setting);

        $id = Html::getInputId($this->model, $this->attribute);
        $view = $this->getView();
        AdminUploadAsset::register($view);
        $view->registerJs("jQuery('#$id').pUpload($Setting);");
    }
}