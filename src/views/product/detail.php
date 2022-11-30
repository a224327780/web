<?php

use yii\web\View;

/* @var $model app\models\Post */
/* @var $this View */
?>
<div class="cur-title"><b><?= $model->title; ?></b></div>
<div class="post">
    <div class="post-title"><?= $model->title; ?></div>
    <p class="postmeta"></p>
    <div class="entry"><?= $model->content; ?></div>
    <div class="clearfix"></div>
</div>