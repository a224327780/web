<?php

namespace admin\controllers;

use admin\components\BaseAuthController;
use app\models\UserAr;

class UserController extends BaseAuthController {

    public function actionIndex() {
        $model = new UserAr();
        $dataProvider = $model->search();

        $this->view->title = \Yii::t('app', 'Users');

        return $this->render(compact('dataProvider'));
    }

    public function actionSave($id = FALSE) {
        $this->view->title = \Yii::t('app', 'Users');

        /* @var $model UserAr */
        $model = $this->findModel($id, UserAr::class);
        $model->setScenario($model->isNewRecord ? 'new' : 'save');

        if (!$model->load($this->post())) {
            return $this->render(compact('model'));
        }
        return $model->saveUser();
    }

}
