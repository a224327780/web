<?php

namespace app\controllers;

use app\components\AppController;
use app\models\Post;

class SiteController extends AppController {

    public function actionIndex() {
        $about = Post::findOne(['alias' => 'about']);
        $duration = 3600;
        $newsList = Post::find()->where(['type' => Post::TYPE_NEWS])->cache($duration)->limit(4)->orderBy('id desc')->asArray()->all();
        $productList = Post::find()->where(['type' => Post::TYPE_PRODUCT])->cache($duration)->limit(8)->orderBy('id desc')->asArray()->all();

        return $this->render(compact('about', 'newsList', 'productList'));
    }


}
