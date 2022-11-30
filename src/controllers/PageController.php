<?php


namespace app\controllers;


use app\components\AppController;
use app\helpers\AppHelper;
use app\models\Post;
use Yii;
use yii\web\NotFoundHttpException;

class PageController extends AppController {

    public function actionIndex() {
        $page_alias = $this->getParam('page_alias');
        $model = Post::find()->where(['alias' => $page_alias])->one();
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        $this->view->title = $model->title;
        $this->description = AppHelper::convertText($model->content);
        return $this->render(compact('model'), '/page');
    }
}