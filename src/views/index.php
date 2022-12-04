<?php

use app\helpers\AppHelper;
use yii\helpers\Url;
use yii\web\View;

function photo($id) {
    $a = "$('#s{$id}')";
    $arr = array(
        "s0" => "{$a}.cycle({ fx: 'curtainX', sync: false, delay: -2000 });",
        "s2" => "{$a}.cycle({ fx: 'scrollDown', delay: -2000 });",
        "s4" => "{$a}.cycle({ fx: 'blindY', delay: -2000 });",
        "s6" => "{$a}.cycle({ fx: 'blindZ', delay: -2000 });",
        "s8" => "{$a}.cycle({ fx: 'scrollLeft', delay: -2000,  });",
        "s10" => "{$a}.cycle({ fx: 'turnDown', delay: -2000 });",
        "s12" => "{$a}.cycle({ fx: 'zoom', sync:  false, delay: -2000 });",
        "s14" => "{$a}.cycle({ fx: 'shuffle', delay: -2000 });"
    );
    $key = "s{$id}";
    return $arr[$key];
}

/* @var $about app\models\Post */
/* @var $this View */
/* @var $newsList */
/* @var $productList */


?>
<div class="row-fluid">
    <div class="col-lt">
        <h2><a href="<?= Url::to(["/about"]); ?>" title="关于我们">关于我们</a></h2>
        <div class="col-3-inner"><p><img style="float: left; padding: 0 10px 0 0;" src="/assets/app/images/about.jpg" alt=""><?= AppHelper::convertText($about->content); ?></p></div>
    </div>
    <div class="col-rt">
        <h2>新闻动态</h2>
        <div class="col-3-inner">
            <ul>
                <?php foreach ($newsList as $news): ?>
                    <li><a href="<?= Url::to(['news/detail', 'id' => $news['id']]); ?>"
                           title="<?= $news['title'] ?>"><?= $news['title'] ?></a><span
                                class='date'><?= date('Y-m-d', $news['update_time']) ?></span></li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</div>

<div class="row-fluid inner">
    <h2><a href="<?= Url::to(["/product"]); ?>" title="最新产品">最新产品</a></h2>
    <div class="col-3-inner">
        <ul class="piclist piclist-col4">
            <?php foreach ($productList as $k => $product): ?>
                <li class="fadeInDown wow">
                    <a href="<?= Url::to(['product/detail', 'id' => $product['id']]); ?>">
                        <div class="folio-thumb"><div class="mediaholder">
                                <img src="<?= $product['thumbnail'] ?>" alt="iPhone 7" class="thumb" style="width: 279px; height: 209.25px;"></div><div class="opacity-pic"></div></div>
                        <h3><?= $product['title'] ?></h3>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

