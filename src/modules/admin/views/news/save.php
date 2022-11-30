<?php

//use yii\bootstrap\ActiveForm;
use admin\widgets\AdminUploadWidget;
use admin\widgets\KindEditorWidget;
use app\models\Meta;
use app\models\Post;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model app\models\Post */

$type = $model->type == Post::TYPE_PRODUCT ? Meta::TYPE_PRODUCT : Meta::TYPE_NEWS;

?>
<div class="row">
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <?= $form->field($model, 'title')->textInput(['maxlength' => TRUE]); ?>

        <?= $form->field($model, 'content')->label(FALSE)->widget(KindEditorWidget::class); ?>

        <?= $form->field($model, 'meta_id')->dropDownList($model->getPostCategory($type)); ?>

        <?= $form->field($model, 'thumbnail')->label()->widget(AdminUploadWidget::class); ?>

        <div class="form-group">
            <?= Html::submitButton($this->params['page-title'], ['class' => 'btn btn-block ladda-button', 'data-style'=>'zoom-in']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
