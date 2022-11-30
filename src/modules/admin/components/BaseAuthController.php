<?php

namespace admin\components;

use app\components\BaseController;
use app\helpers\AppHelper;
use app\models\Admin;
use yii;
use yii\base\ViewEvent;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;

class BaseAuthController extends BaseController {

    public $name = '';
    /**
     * @var Admin
     */
    public $user;

    protected $view_id = NULL;

    public function init() {
        parent::init();
    }

    public function beforeAction($action) {
        $this->user = Yii::$app->user->identity;
        return parent::beforeAction($action);
    }

    public function render($params = [], $view = NULL) {
        if (NULL === $view) {
            if (empty($this->view_id)) {
                $this->view_id = $this->id;
            }
            $view = "/{$this->view_id}/{$this->action->id}";
        }
        return $this->isAjax() ? parent::renderAjax($view, $params) : parent::render($params, $view);
    }

    public function afterAction($action, $result) {
        if (in_array(Yii::$app->response->format, [Response::FORMAT_RAW])) {
            return $result;
        }
        $result = parent::afterAction($action, $result);
        if (is_array($result) && isset($result['code'])) {
            $type = AppHelper::isOk($result) ? 'success' : 'error';
            if (!empty($result['msg']) && ($this->isAjax() && AppHelper::isOk($result)) && $action->id !== 'login') {
                Yii::$app->session->setFlash($type, $result['msg']);
            }
            if (AppHelper::isOk($result) && empty($result['redirect'])) {
                $result['redirect'] = Url::to(["{$this->id}/"]);
            }
            return $result;
        }
        return parent::afterAction($action, $result);
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['actions' => ['login'], 'allow' => TRUE, 'roles' => ['?']],
                    ['actions' => ['error'], 'allow' => TRUE, 'roles' => ['@']],
                    ['allow' => TRUE, 'roles' => ['@']]
                ]
            ]
        ];
    }


    public function actionError(\Exception $exception) {
        return $this->error($exception->getMessage());
    }

    public function beforeRender(ViewEvent $event) {
        $params = $event->params;
        if (isset($params['content']) || $this->isAjax()) {
            return;
        }

        $this->view->params['breadcrumbs'] = [];
        $this->view->params['breadcrumbs'][] = ['label' => $this->view->title, 'url' => ["{$this->id}/"]];
        $this->view->params['page-title'] = $this->view->title;

        if ($this->action->id === 'save' && isset($params['model'])) {
            $model = $params['model'];
            $act = $model->IsNewRecord ? 'Add New' : 'Edit';
            $act = Yii::t('app', $act) . $this->view->title;
            $this->view->params['breadcrumbs'][] = $act;
            $this->view->params['page-title'] = $act;
        } elseif ($this->action->id === 'view') {
            $title = "查看{$this->view->title}";
            $title = str_replace('管理', '', $title);
            $this->view->params['breadcrumbs'][] = $this->view->title = $title;
        } elseif ($this->action->id !== 'index') {
            if (!empty($this->view->title)) {
                $this->view->params['breadcrumbs'][] = $this->view->title;
            }
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return Yii::$app->getResponse()->redirect(['/admin/auth/login']);
    }
}