<?php

namespace admin\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii;

class AdminTabs extends Widget {

    public $items = [];
    public $all = false;
    public $id;
    public $defaultValue = NULL;
    public $options = ['class' => 'nav nav-tabs'];
    public $exclude;
    public $linkOptions = [];


    public function run() {
        if (empty($this->items)) {
            return '';
        }

        $headers = [];
        $route = '/' . str_replace('/index', '/', Yii::$app->controller->getRoute());
        if (FALSE === $this->all) {
            $keys = array_keys($this->items);
            $this->defaultValue = current($keys);
        } else {
            $label = Yii::t('app', 'All');
            $headerOptions = null === Yii::$app->request->get($this->id, $this->defaultValue) ? ['class' => 'active'] : [];
            $headers[] = Html::tag('li', Html::a($label, [$route], $this->linkOptions), $headerOptions);
        }


        $current = Yii::$app->request->get($this->id, $this->defaultValue);
        $params = Yii::$app->request->get();
        if (isset($params[$this->id])) {
            unset($params[$this->id]);
        }

        if (!empty($this->exclude)) {
            foreach ($this->exclude as $v) {
                unset($params[$v]);
            }
        }
//        if (NULL === $this->defaultValue) {
//            $url = ArrayHelper::merge($params, ['']);
//            $header = Html::a($this->all, $url);
//            $headerOptions = NULL === $current ? ['class' => 'active'] : [];
//            $headers[] = Html::tag('li', $header, $headerOptions);
//        }
        foreach ($this->items as $value => $label) {
            $url = $value;
            if (strpos($value, 'http') === FALSE) {
                $url = ArrayHelper::merge($params, [$this->id => $value]);
            }
            $url[0] = $route;
            $header = Html::a($label, $url, $this->linkOptions);

            $headerOptions = [];
            if (NULL !== $current && strcmp($current, $value) === 0) {
                Html::addCssClass($headerOptions, 'active');
            }
            $headers[] = Html::tag('li', $header, $headerOptions);
        }
        return Html::tag('div', implode("\n", $headers), $this->options);
    }
}