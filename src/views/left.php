<div class="sidebar">
    <ul>
        <?php use app\models\Meta;
        use yii\widgets\Menu;

        if (Yii::$app->controller->id == 'news'): ?>
        <li class="widget_nav_menu">
            <h3>新闻中心</h3>
            <?= Menu::widget([
                'items' => Meta::getMenuItems(Meta::TYPE_NEWS),
                'options' => ['class' => 'nav-ul-menu'],
                'itemOptions' => ['class' => 'cat-item']
            ]); ?>
        </li>
        <?php else: ?>
        <li class="widget_nav_menu">
            <h3>产品中心</h3>
            <?= Menu::widget([
                'items' => Meta::getMenuItems(Meta::TYPE_PRODUCT),
                'options' => ['class' => 'nav-ul-menu'],
                'itemOptions' => ['class' => 'cat-item']
            ]); ?>
        </li>
        <?php endif; ?>
        <li>
            <h3>联系我们</h3>
            <div>
                <p><a href="/contact"><img src="/assets/app/images/contact-us.jpg" width="228" alt="联系我们" title="Contact us"></a><br>
                <b style="font-size:14px;">热线电话：400 - 666 - 8888</b><br>
                <strong>客服热线</strong>：010-87265544<br>
                <strong>服务支持</strong>：010-87265544<br>
                <strong>Q Q</strong>： <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=352988439&amp;site=WPYOU.com&amp;menu=yes" title="QQ:84198860">84198860</a><br>
                <strong>电子邮箱</strong>： <a target="_blank" href="sevice@visionshow.net.cn">sevice@visionshow.net.cn</a>
                </p>
            </div>
        </li>
    </ul>
</div>
