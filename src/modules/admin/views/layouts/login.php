<?php

use yii\helpers\Html;

\admin\assets\AppAsset::register($this);
/* @var $content */
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>后台<?= Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<?= $content; ?>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
