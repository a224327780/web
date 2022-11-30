<?php
/**
 * Created by PhpStorm.
 * User: zouhua
 * Date: 2019/2/26
 * Time: 21:49
 */

namespace admin\controllers;


use app\models\Post;

class PageController extends PostController {

    public $type = Post::TYPE_PAGE;

    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return FALSE;
        }
        $this->view->title = \Yii::t('app', 'Pages');
        return TRUE;
    }

}