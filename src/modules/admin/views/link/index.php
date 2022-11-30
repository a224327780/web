<?php

use admin\components\BaseGridView;
use app\models\Meta;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $dataProvider */
?>


<div class="mb-2">
    <?= Html::a(Yii::t('app', 'Add New') . $this->title, ['save'], ['class' => 'btn']); ?>
</div>

<?= BaseGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => NULL,
    'columns' => [
        ['attribute' => 'name', 'contentOptions' => ['class' => 'text-left'], 'headerOptions' => ['class' => 'text-left']],
        'slug',
        ['class' => 'admin\components\BaseActionColumn'],
    ],
]); ?>


