<?php

use app\components\BasePager;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;

/* @var $dataProvider ActiveDataProvider */
/* @var $this View */
$models = $dataProvider->getModels();

?>
<div class="cur-title"><b><?= $this->title; ?></b></div>
<ul class="postlist">
    <?php foreach ($models as $model): ?>
        <li>
            <a href="<?= Url::to(['news/detail', 'id' => $model->id]); ?>" title="<?= $model->title ?>"><?= $model->title ?></a>
            <span class='date'><?= date('Y-m-d H:i:s', $model->update_time) ?></span>
        </li>
    <?php endforeach; ?>
</ul>
