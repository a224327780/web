<?php

namespace admin\widgets;

use admin\assets\KindEditorAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;

class KindEditorWidget extends InputWidget {

    public $editSetting = [];
    public $type = 'defaults';

    public function init() {
        parent::init();
        if (!isset($this->Setting['class'])) {
            $this->options['class'] = 'form-control';
        }
    }

    public function run() {
        $this->registerClientScript();

        if ($this->hasModel()) {
            $input = Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::textarea($this->name, $this->value, $this->options);
        }
        echo $input;
    }

    protected function registerClientScript() {
        $Setting = [];
        if (method_exists($this, $this->type)) {
            $method = $this->type;
            $Setting = $this->$method();
        }

        $id = Html::getInputId($this->model, $this->attribute);
        $Setting = ArrayHelper::merge($Setting, $this->editSetting);
        $Setting = Json::htmlEncode($Setting);

        $view = $this->getView();
        KindEditorAsset::register($view);

        $view->registerJs("KindEditor.ready(function(K) {K.create('#{$id}', {$Setting});});");
    }

    private function defaults() {
        return [
            'allowFileManager' => FALSE,
            'allowMediaUpload' => FALSE,
            'allowFlashUpload' => FALSE,
            'allowImageUpload' => TRUE,
            'shadowMode' => FALSE,
            'width' => '100%',
            'height' => '350px',
            'pasteType' => 1,
            'formatUploadUrl' => FALSE,
            'filePostName' => 'file',
            'newlineTag' => 'p',
            'cssData' => ".prettyprint {padding: 8px; background-color: #f7f7f9;border: 1px solid #e1e1e8; border-radius:5px;}",
            'items' => [
                'source', 'plainpaste', 'wordpaste', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'flash', 'media', 'link', 'unlink',
                'baidumap', 'quickformat', 'clearhtml', 'pagebreak', 'code', 'anchor', 'preview', '|', 'about'
            ],
            'uploadJson' => Url::to(['/site/upload', 'type' => 'edit']),
            'extraFileUploadParams' => [
                '_csrf' => \Yii::$app->request->getCsrfToken()
            ]
        ];
    }

    private function simple() {
        return [
            'shadowMode' => FALSE,
            'newlineTag' => 'p',
            'pasteType' => 1,
            'width' => '100%',
            'height' => '400px',
            'formatUploadUrl' => FALSE,
            'allowImageUpload' => TRUE,
            'items' => [
                'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist', '|', 'image', 'link', 'source'
            ],
            'uploadJson' => Url::to(['/default/upload', 'type' => 'edit']),
            'filePostName' => 'file',
            'extraFileUploadParams' => [
                '_csrf' => \Yii::$app->request->getCsrfToken()
            ]
        ];
    }
}