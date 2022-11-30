<?php

/* @var $placeholder */
?>
<div class="col-md-3 col-sm-4 col-xs-5 pull-right">
    <div class="search has-feedback">
        <form action="<?= \yii\helpers\Url::to([Yii::$app->controller->id . '/']); ?>" method="get">
            <?php foreach (Yii::$app->request->queryParams as $key => $value): ?>
                <?php if($key == 'wd') continue; ?>
                <?= \yii\helpers\Html::hiddenInput($key, $value); ?>
            <?php endforeach; ?>

            <?= \yii\helpers\Html::textInput('wd', Yii::$app->request->get('wd'), ['placeholder' => $placeholder, 'class' => 'form-control']); ?>

            <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
        </form>
    </div>
</div>