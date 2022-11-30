<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $model app\models\Admin */
/* @var $this View */

?>
<div class="row">
    <div class="col-xs-12 col-md-6 col-md-push-3">

        <?php $form = ActiveForm::begin(); ?>

<!--        --><?//= $form->field($model, 'password')->passwordInput(); ?>
        <?= $form->field($model, 'newPwd')->passwordInput(); ?>
        <?= $form->field($model, 'newPwd2')->passwordInput(); ?>

        <div class="form-group">
            <?= Html::submitButton($this->params['page-title'], ['class' => 'btn btn-block ladda-button', 'data-style' => 'zoom-in']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
