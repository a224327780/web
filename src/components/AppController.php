<?php

namespace app\components;

use app\helpers\AppHelper;
use app\models\Setting;
use app\models\User;
use Yii;
use yii\base\ViewEvent;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

class AppController extends BaseController {

    public $layout = '@app/views/layout';
    protected $modelClass;

    public $keywords;
    public $description;

    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return FALSE;
        }

        return TRUE;
    }

    public function beforeRender(ViewEvent $event) {
        $params = $event->params;
        if (!isset($params['content']) || $this->isAjax()) {
            return;
        }

        if (empty($this->keywords)) {
            $this->keywords = Setting::get('web_keyword', '');
        }
        if (empty($this->description)) {
            $this->description = Setting::get('web_description', '');
        }

        $this->view->registerMetaTag(['name' => 'description', 'content' => $this->description]);
        $this->view->registerMetaTag(['name' => 'keywords', 'content' => $this->keywords]);

        $this->view->registerMetaTag(['property' => 'og:title', 'content' => $this->view->title]);
        $this->view->registerMetaTag(['property' => 'og:url', 'content' => Url::to('', TRUE)]);
        $this->view->registerMetaTag(['property' => 'og:site_name', 'content' => Setting::get('web_name')]);
        $this->view->registerMetaTag(['property' => 'og:description', 'content' => $this->description]);
    }
}