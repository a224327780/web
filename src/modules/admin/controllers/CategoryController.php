<?php
/**
 * Created by PhpStorm.
 * User: zouhua
 * Date: 2018/9/5
 * Time: 16:10.
 */

namespace admin\controllers;

use admin\components\BaseAuthController;
use app\helpers\ArrayHelper;
use app\models\Meta;

class CategoryController extends BaseAuthController {


    public function beforeAction($action) {
        $this->view->title = \Yii::t('app', 'Categories');
        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $model = new Meta();
        $params = $this->getParam();
        $params['type'] = [Meta::TYPE_NEWS, Meta::TYPE_PRODUCT];
        $dataProvider = $model->search($params, TRUE);

        $models = $dataProvider->getModels();

        ArrayHelper::arrayDepthSort($models, $lists);
        $dataProvider->setModels($lists);
        $dataProvider->setKeys(NULL);
        $dataProvider->prepare();
//        print_r($lists);

        return $this->render(compact('dataProvider', 'model'));
    }

    public function actionSave($id = NULL) {
        /* @var $model Meta */
        $model = $this->findModel($id, Meta::class);

        if (!$model->load($this->post())) {
            return $this->render(compact('model'));
        }
        if (!$model->parent_id) {
            $model->parent_id = 0;
        }

        return $model->save();
    }
}
