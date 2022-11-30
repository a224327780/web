<?php

use app\models\Meta;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $model app\models\Meta */
/* @var $this View */

?>
<div class="row">
    <div class="col-xs-12 col-md-6 col-md-push-3">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name'); ?>
<!--        --><?//= $form->field($model, 'slug'); ?>
        <?= $form->field($model, 'type')->dropDownList(Meta::$typeLabels); ?>
        <?= $form->field($model, 'parent_id')->dropDownList($model->getSaveCategory(), ['prompt' => '无']); ?>
        <?= $form->field($model, 'description')->textarea(); ?>
        <?= $form->field($model, 'is_hide')->checkbox(); ?>

        <div class="form-group">
            <?= Html::submitButton($this->params['page-title'], ['class' => 'btn btn-block ladda-button', 'data-style' => 'zoom-in']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
