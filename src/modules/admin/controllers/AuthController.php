<?php

namespace admin\controllers;

use admin\components\BaseAuthController;
use app\models\Admin;
use yii;

class AuthController extends BaseAuthController {

    public $layout = '@admin/views/layouts/login';

    public function behaviors() {
        return [];
    }

    public function beforeAction($action) {
        if (!Yii::$app->user->getIsGuest()) {
            return $this->goBack(['/admin']);
        }
        return parent::beforeAction($action);
    }

    public function actionLogin() {
        $model = new Admin();
        $model->setScenario('login');
        if ($this->isAjax() && $model->load($this->post())) {
            return $model->login();
        }
        $this->view->title = Yii::t('app', 'Sign in');
        return $this->render(compact('model'));
    }
}
