<?php
/**
 * Created by PhpStorm.
 * User: zouhua
 * Date: 2019/2/26
 * Time: 21:49
 */

namespace admin\controllers;


use admin\components\BaseAuthController;
use app\models\Meta;

class LinkController extends BaseAuthController {

    public function beforeAction($action) {
        $this->view->title = \Yii::t('app', 'Link');
        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $model = new Meta();
        $params = $this->getParam();
        $params['type'] = Meta::TYPE_LINK;
        $dataProvider = $model->search($params);
        return $this->render(compact('dataProvider'));
    }

    public function actionSave($id = NULL) {
        /* @var $model Meta */
        $model = $this->findModel($id, Meta::class);
        $model->setScenario('link');

        if (!$model->load($this->post())) {
            return $this->render(compact('model'));
        }
        $model->parent_id = 0;

        return $model->save();
    }
}