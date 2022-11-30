<?php
namespace admin\components;

use yii\grid\ActionColumn;
use yii\helpers\Html;
use Yii;

class BaseActionColumn extends ActionColumn {

    public $template = '{save}';
    public $viewSetting = [];
    public $saveSetting = [];
    public $contentOptions = ['width' => '8%'];
    public $checkAccess;

    public function init() {
        if (!isset($this->buttons['save'])) {
            $this->buttons['save'] = function ($url, $model, $key) {
                $Setting = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'data-toggle' => 'tooltip',
                    'class' => 'dialog'
                ], $this->saveSetting);
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $Setting);
            };
        }
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $Setting = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'data-toggle' => 'tooltip',
                    'class' => 'view'
                ], $this->viewSetting);
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $Setting);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $Setting = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'class' => 'delete',
                    'data-toggle' => 'tooltip'
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $Setting);
            };
        }
        parent::init();
    }
}