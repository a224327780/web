<?php

use app\models\Admin;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model Admin */
?>
<div class="login-error"></div>

<div class="login-container">
    <div class="login-logo"><?= Yii::$app->request->hostName; ?></div>
    <div class="login-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username')])->label(FALSE); ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(FALSE); ?>

        <?= $form->field($model, 'rememberMe')->checkbox(); ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-block ladda-button', 'data-style' => 'zoom-in']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
