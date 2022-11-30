<?php

use admin\components\BaseGridView;
use yii\helpers\Html;

/* @var $dataProvider */
?>
<div class="mb-2">
    <?= Html::a(Yii::t('app', 'Add New') . Yii::t('app', 'Pages'), ['save'], ['class' => 'btn']); ?>
</div>


<?= BaseGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => NULL,
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],
//        ['class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['width' => '5%']],
        ['attribute' => 'title', 'contentOptions' => ['class' => 'text-left'], 'headerOptions' => ['class' => 'text-left']],
        'alias',
        'update_time:datetime',
        ['class' => 'admin\components\BaseActionColumn'],
    ],
]); ?>
