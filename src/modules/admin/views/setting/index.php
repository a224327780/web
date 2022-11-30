<?php

use admin\widgets\AdminTabs;
use app\models\Setting;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $model Setting */
/* @var $this View */
/* @var array $items */
/* @var string $type */

?>

<div class="row">

    <div class="col-xs-12 col-md-6 col-md-push-3">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'web_name')->textInput(); ?>
        <?= $form->field($model, 'web_description')->textarea(['rows' => 3]); ?>
        <?= $form->field($model, 'web_keyword')->textarea(); ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save Settings'), ['class' => 'btn btn-block ladda-button', 'data-style'=>'zoom-in']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>