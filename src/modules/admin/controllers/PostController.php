<?php

namespace admin\controllers;


use admin\components\BaseAuthController;
use app\models\Post;

class PostController extends BaseAuthController {

    public $type = NULL;

    public function beforeAction($action) {
        $this->view->title = \Yii::t('app', 'Posts');
        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $model = new Post();
        $params = $this->getParam();
        $params['type'] = $this->type;
        if (!empty($params['wd'])) {
            $model->where = ['like', 'title', $params['wd']];
            unset($params['wd']);
        }
        if (intval($this->type) !== Post::TYPE_PAGE) {
            $model->with = ['category'];
        }
        $dataProvider = $model->search($params);

        return $this->render(compact('dataProvider'));
    }

    public function actionSave($id = NULL) {
        /* @var $model Post */
        $model = $this->findModel($id, Post::class);
        $model->type = $this->type;

        if (!$model->load($this->post())) {
            return $this->render(compact('model'));
        }
        return $model->save();
    }
}