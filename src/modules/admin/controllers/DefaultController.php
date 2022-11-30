<?php

namespace admin\controllers;

use admin\components\BaseAuthController;
use app\helpers\EncryptHelper;

class DefaultController extends BaseAuthController {

    public function actionIndex() {
        return $this->render();
    }

    public function beforeAction($action) {
        if ($action->id == 'pwd') {
            $this->view->title = \Yii::t('app', 'Edit Password');
        }
        return parent::beforeAction($action);
    }

    public function actionPwd() {
        $model = $this->user;
        $model->setScenario('pwd');
        if (!$model->load($this->post())) {
            return $this->render(compact('model'));
        }

        $newPwd = EncryptHelper::md5($model->newPwd);
        $model->password = $newPwd;

        return $model->save();
    }
}
