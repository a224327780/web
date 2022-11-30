<?php

use admin\assets\AppAsset;
use admin\widgets\AdminMenuWidget;
use app\models\Setting;
use yii\bootstrap\Alert;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
/* @var $content */

$web_name = Setting::get('web_name') . '管理系统';
$page_title = trim(Html::encode($this->title));
$title = Yii::$app->controller->id == Yii::$app->controller->module->defaultRoute ? $web_name : "{$page_title} - {$web_name}";
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= $title; ?></title>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>

<div class="page-container">
    <div class="left">
        <ul class="nav">
            <li class="nav-profile">
                <div class="media">
                    <div class="media-left media-middle">
                        <a href="javascript:;">
                            <?= Html::img('@asset/admin/images/avatar.png'); ?>
                        </a>
                    </div>
                    <div class="media-body">
                        <b>Hello, <?= Yii::$app->user->identity['username']; ?></b>
                        <a class="" href="<?= Url::to(['user/save', 'id' => Yii::$app->user->id]) ?>">我的资料</a>
                    </div>
                </div>
            </li>
        </ul>

        <?= AdminMenuWidget::widget(); ?>
    </div>

    <div class="right">
        <div class="navbar">
            <div class="container-fluid">
                <?= \yii\bootstrap\Nav::widget([
                    'items' => [
                        [
                            'label' => '<i class="glyphicon glyphicon-th-large"></i>' . Setting::get('web_name'),
                            'url' => ['/admin/'],
                        ]
                    ],
                    'encodeLabels' => FALSE,
                    'options' => ['class' => 'navbar-nav']
                ]); ?>

                <?= AdminMenuWidget::widget(['isMenu' => FALSE]); ?>
            </div>
        </div>

        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
            <?= Alert::widget([
                'options' => ['class' => 'alert-info'],
                'body' => $message
            ]); ?>
        <?php endforeach; ?>

        <div class="main container-fluid">
            <div class="page-title">
                <?= $this->params['page-title']; ?>
            </div>
            <?= $content; ?>
        </div>
    </div>
</div>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
