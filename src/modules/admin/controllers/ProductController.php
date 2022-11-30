<?php


namespace admin\controllers;


use app\models\Post;

class ProductController extends PostController {

    public $type = Post::TYPE_PRODUCT;
    protected $view_id = 'news';

    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return FALSE;
        }
        $this->view->title = \Yii::t('app', 'Product');
        return TRUE;
    }

}