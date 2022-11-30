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
<ul class="piclist piclist-col3">
    <?php foreach ($models as $model): ?>
        <li>
            <div class="folio-item">
                <a href="<?= Url::to(['product/detail', 'id' => $model->id]); ?>" title="<?= $model->title ?>">
                    <div class="folio-thumb">
                        <div class="mediaholder" style="text-align: center;">
                            <img src="<?= $model->thumbnail ?>" title="<?= $model->title ?>" onerror="this.src='/assets/app/images/none.png';" style="width: 155px;height: 140px; display: inline;" />
                        </div>
                        <div class="opacity-pic"></div>
                    </div>

                    <h3><?= $model->title ?></h3>
                </a>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
<div class="clearfix"></div>
<?php if(!empty($models)): ?><style>.page-item{float: left;}</style>
<div class="wpagenavi">
    <?= BasePager::widget(['pagination' => $dataProvider->getPagination()]); ?>
</div>
<?php endif; ?>