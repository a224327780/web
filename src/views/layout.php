<?php

use app\assets\AppAsset;
use app\models\Meta;
use app\models\Setting;
use app\widgets\AppMenuWidget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Menu;

AppAsset::register($this);

/* @var $content */
/* @var $this View */

$web_name = Setting::get('web_name');
$page_title = trim(Html::encode($this->title));
$is_home = Yii::$app->controller->id == Yii::$app->defaultRoute;
$title = $is_home ? $web_name : "{$page_title} - {$web_name}";
?>
<?php $this->beginPage(); ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="<?= Yii::$app->charset; ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?= Html::csrfMetaTags() ?>
<?= Setting::get('web_header'); ?>
<title><?= $title; ?></title>
<?php $this->head(); ?>
</head>
<body class="custom-background">
<?php $this->beginBody(); ?>
<header class="header">
    <div class="inner">
        <h1 class="logo fadeInLeft wow animated" style="visibility: visible;">
            <a href="/" rel="首页"><img src="/assets/app/images/logo.png" alt="<?= $title; ?>"></a></h1>
<!--        <div class="rtbox">-->
<!--            <img src="/assets/app/images/hotline.gif" alt="热线电话">-->
<!--        </div>-->
    </div>
</header>

<div class="nav-box">
    <div class="nav-container">
        <div class="inner navbar">
            <nav class="inner main-menu">
                <ul id="menu-headermenu" class="navi">
                    <?= AppMenuWidget::widget(); ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php if($is_home): ?>
<div id="sliderbox">
    <div id="slidebanner">
        <ul id="slideshow">
            <li>
                <a href="#">
                    <img src="/assets/app/images/banner01.jpg" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="/assets/app/images/banner02.jpg" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="/assets/app/images/banner03.jpg" />
                </a>
            </li>
        </ul>
    </div>
    <div class="outside">
        <span id="slider-prev"></span>
        <span id="slider-next"></span>
    </div>
</div>
<?php else: ?>
<div class="banner">
    <img src="/assets/app/images/news.jpg" alt="新闻动态">
</div>
<?php endif; ?>
<div class="banner-shadow"></div>

<div class="inner container">
    <?php if(!$is_home): ?>
    <div class="column-fluid">
        <div class="content">
            <?= $content; ?>
        </div>
    </div>
    <?= $this->render('/left'); ?>
    <?php else: ?>
    <?= $content; ?>
    <?php endif; ?>
    <div class="clearfix"></div>
</div>

<footer class="footer">
    <div class="footbar">
        <div class="inner">
            <div class="widget-column">
                <h3>关于我们</h3>
                <ul>
                    <li>
                        <a href="/about">关于我们</a>
                    </li>
                    <li>
                        <a href="/contact">联系我们</a>
                    </li>
                    <li>
                        <a href="/job">加入我们</a>
                    </li>
                </ul>
            </div>
            <div class="widget-column">
                <h3>产品分类</h3>
                <?= Menu::widget([
                    'items' => Meta::getMenuItems(Meta::TYPE_PRODUCT),
                    'options' => ['class' => 'nav-ul-menu'],
                    'itemOptions' => ['class' => 'cat-item']
                ]); ?>
            </div>
            <div class="widget-column widget_newsletterwidget">
                <h3></h3>
            </div>
            <div class="widget-column">
                <h3>联系我们</h3>
                <div class="contact-widget">
                    <p>
                        <strong>电话</strong>: <strong>022-53683937</strong>
                    </p>
                    <p>
                        <strong>邮箱</strong>: service@destronger.com
                    </p>
                    <p>
                        <strong>地址</strong>: 天津市武清区泗村店镇汤泉世家二期39号楼-2
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="inner">
            <p style="text-align: center;">Copyright © 2022 <a href="/"><?= $title; ?></a> All Rights Reserved. <a href="https://beian.miit.gov.cn/" target="_blank">津ICP备2022009173号-1</a></p>
        </div>
    </div>
</footer>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
