<?php

namespace admin\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class AdminToolbar extends Widget {

    public $Setting = [];
    public $placeholder = '';

    public function run() {
        $url = Url::to(['save']);
        if (isset($this->Setting['url'])) {
            $url = $this->Setting['url'];
            unset($this->Setting['url']);
        }

        $button = '';
        if (NULL !== $this->Setting) {
            $name = ArrayHelper::getValue($this->Setting, 'name', Yii::$app->controller->name);
            if (strpos($name, '管理员') === FALSE) {
                $name = str_replace('管理', '', $name);
            }
            $name = '添加' . $name;

            $Setting = array_merge($this->Setting, ['class' => 'btn dialog', 'data-href' => $url]);
            $button = Html::button($name, $Setting);
        }

        return Yii::$app->controller->renderPartial('/widget/toolbar', [
            'button' => $button,
            'placeholder' => $this->placeholder
        ]);
    }
}