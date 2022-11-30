<?php

namespace admin\widgets;

use yii;

class AdminMenuWidget extends yii\bootstrap\Nav {

    public $encodeLabels = FALSE;
    public $activateParents = TRUE;
    public $isMenu = TRUE;

    public function init() {
        parent::init();
        $this->route = str_replace('/index', '/', $this->route);

        if ($this->isMenu) {
            $this->menuItems();
        } else {
            $this->rightItems();
        }
    }

    protected function isItemActive($item) {
        if (parent::isItemActive($item)) {
            return TRUE;
        }
        if (!isset($item['url'])) {
            return FALSE;
        }
        $route = ltrim($item['url'][0], '/');
        $action = Yii::$app->controller->action->id;
        $module_name = Yii::$app->controller->module->getUniqueId();
        if ($route !== $module_name) {
            $route = "{$module_name}/{$route}/{$action}";
        } else {
            $controller = Yii::$app->controller->module->defaultRoute;
            $route = "{$module_name}/{$controller}";
        }
        $route = trim(str_replace('//', '/', $route), ' /');
//        print_r([$this->route, $route]);

        if (trim($this->route, '/') === $route) {
            return TRUE;
        }

        return FALSE;
    }

    private function menuItems() {
        yii\helpers\Html::addCssClass($this->options, ['menu']);
        $this->items = [];
        $items = [
            ['label' => 'Dashboard', 'url' => ['/admin'], 'icon' => 'dashboard'],
            ['label' => 'Settings', 'url' => ['setting/'], 'icon' => 'cog'],
            ['label' => 'Categories', 'url' => ['category/'], 'icon' => 'book'],
            [
                'label' => 'News',
                'url' => ['news/'],
                'icon' => 'grain',
            ],
            [
                'label' => 'Product',
                'url' => ['product/'],
                'icon' => 'leaf',
            ],
            ['label' => 'Pages', 'url' => ['page/'], 'icon' => 'book'],
            ['label' => 'Link', 'url' => ['link/'], 'icon' => 'link'],

        ];
        foreach ($items as $item) {
            $label = [];
            $label[] = yii\helpers\Html::tag('i', '', ['class' => "glyphicon glyphicon-{$item['icon']}"]);
            $label[] = Yii::t('app', $item['label']);
            $data = [
                'label' => join('', $label),
                'url' => $item['url']
            ];
            if (isset($item['items'])) {
                $data['items'] = $item['items'];
            }
            $this->items[] = $data;
        }
    }

    private function rightItems() {
        yii\helpers\Html::addCssClass($this->options, ['navbar-nav navbar-right']);
        $items = [
            ['label' => 'Edit Password', 'url' => ['default/pwd'], 'icon' => 'lock'],
            ['label' => 'Log Out', 'url' => ['logout'], 'icon' => 'off'],
            ['label' => 'Visit Site', 'url' => ['/'], 'icon' => 'home'],
        ];
        foreach ($items as $item) {
            $label = [];
            $label[] = yii\helpers\Html::tag('i', '', ['class' => "glyphicon glyphicon-{$item['icon']}"]);
            $label[] = Yii::t('app', $item['label']);
            $this->items[] = [
                'label' => join('', $label),
                'url' => $item['url']
            ];
        }
    }
}