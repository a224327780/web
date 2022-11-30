<?php


namespace admin\controllers;


use app\models\Post;

class NewsController extends PostController {

    public $type = Post::TYPE_NEWS;

    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return FALSE;
        }
        $this->view->title = \Yii::t('app', 'News');
        return TRUE;
    }

}