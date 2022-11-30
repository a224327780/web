<?php

namespace app\widgets;

use yii;

class AppMenuWidget extends yii\widgets\Menu {

    public $encodeLabels = FALSE;
    public $activeCssClass = 'current-post-parent';
    public $options = ['class' => 'navi inner'];
    public $linkTemplate = '<a href="{url}"><span>{label}</span></a>';

    public function init() {
        parent::init();

        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
            $this->route = str_replace(['site/', 'index'], ['', ''], $this->route);
        }
        $this->items = [
            ['label' => '首页', 'url' => ['/']],
            ['label' => '关于我们', 'url' => ['/about']],
            ['label' => '新闻中心', 'url' => ['/news']],
            ['label' => '产品中心', 'url' => ['/product']],
            ['label' => '人才招聘', 'url' => ['/job']],
            ['label' => '联系我们', 'url' => ['/contact']],
        ];
    }

    protected function isItemActive($item) {
        if (parent::isItemActive($item)) {
            return TRUE;
        }
        if (!isset($item['url'])) {
            return FALSE;
        }

        $page_alias = Yii::$app->request->getQueryParam('page_alias');
        if ($page_alias && isset($item['url'][0]) && $page_alias == trim($item['url'][0], '/')) {
            return TRUE;
        }
        $route = ltrim($item['url'][0], '/');
        $action = Yii::$app->controller->action->id;
        if ($action == 'index') {
            $action = '';
        }
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
        return False;
    }
}