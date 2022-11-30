<?php

//use yii\bootstrap\ActiveForm;
use admin\widgets\KindEditorWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $model app\models\Book */
?>
<div class="row">
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <?= $form->field($model, 'title')->textInput(['maxlength' => TRUE]); ?>

        <?= $form->field($model, 'content')->label()->widget(KindEditorWidget::class); ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => TRUE]); ?>

        <div class="form-group">
            <?= Html::submitButton($this->params['page-title'], ['class' => 'btn btn-block ladda-button', 'data-style'=>'zoom-in']) ?>
        </div>

    </div>
    <div class="col-md-4">

    </div>
    <div class="col-md-8">


    </div>

    <?php ActiveForm::end(); ?>

</div>
