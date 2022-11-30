<?php

use admin\components\BaseGridView;
use app\models\Post;
use yii\helpers\Html;

/* @var $dataProvider */
?>

<div class="mb-2">
    <?= Html::a(Yii::t('app', 'Add New') . $this->title, ['save'], ['class' => 'btn']); ?>

    <div class="search has-feedback pull-right">
        <?= Html::beginForm('', 'post'); ?>
        <?= Html::textInput('wd', Yii::$app->request->post('wd'), ['placeholder' => '', 'class' => 'form-control']); ?>
        <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
        <?= Html::endForm(); ?>
    </div>
</div>

<?= BaseGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => NULL,
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],
//        ['class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['width' => '5%']],
        ['attribute' => 'meta_id', 'format' => 'raw', 'value' => function (Post $model) {
            return $model->category->name;
        }],
        ['attribute' => 'title', 'contentOptions' => ['class' => 'text-left'], 'headerOptions' => ['class' => 'text-left']],
        'update_time:datetime',
        ['class' => 'admin\components\BaseActionColumn'],
    ],
]); ?>
