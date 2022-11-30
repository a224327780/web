<?php


namespace app\controllers;


use app\models\Post;

class ProductController extends NewsController {

    protected $title = '产品中心';
    protected $type = Post::TYPE_PRODUCT;

}