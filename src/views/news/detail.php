<?php

use yii\helpers\Url;
use yii\web\View;

/* @var $model app\models\Post */
/* @var $this View */
$controller_id = Yii::$app->controller->id;
?>

<div class="cur-title"><b><?= $model->title; ?></b></div>
<div class="post">
    <div class="post-title"><?= $model->title; ?></div>
    <p class="postmeta"> 日期：<?= date('Y-m-d H:i:s', $model->update_time); ?> &nbsp;&nbsp;&nbsp;&nbsp; 分类：<a href="<?= Url::to(["/{$controller_id}", 'meta_id' => $model->category->id]); ?>" rel="category tag"><?= $model->category->name; ?></a></p>
    <div class="entry"><?= $model->content; ?></div>
    <div class="clearfix"></div>
</div>