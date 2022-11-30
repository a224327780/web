<?php

use admin\components\BaseGridView;
use app\models\Meta;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $dataProvider */
?>


<div class="mb-2">
    <?= Html::a(Yii::t('app', 'Add New Category'), ['save'], ['class' => 'btn']); ?>
</div>

<?= BaseGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => NULL,
    'columns' => [
        ['attribute' => 'name', 'contentOptions' => ['class' => 'text-left'], 'headerOptions' => ['class' => 'text-left'], 'value' => '_title'],
        ['attribute' => 'type', 'format' => 'raw', 'value' => function ($model) {
            return ArrayHelper::getValue(Meta::$typeLabels, $model['type']);
        }],
//        'slug',
        ['class' => 'admin\components\BaseActionColumn'],
    ],
]); ?>


