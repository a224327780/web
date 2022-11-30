<?php

namespace admin\components;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

class BaseDetailView extends DetailView {

    public $Setting = ['class' => 'table detail-view'];
    public $firstWidth = '';
    public $template = '<tr><td width="{w}" class="text-left">{label}:</td><td class="text-left">{value}</td></tr>';
    public $header = '<tr><th colspan="2" class="text-left">{title}</th></tr>';
    public $title;

    protected function renderAttribute($attribute, $index) {
        if (is_string($this->template)) {
            if (!$attribute['value']) {
                $attribute['value'] = NULL;
            }

            if ($this->title && $index == 0) {
                return str_replace('{title}', $this->title, $this->header);
            }

            return strtr($this->template, [
                '{label}' => $attribute['label'],
                '{w}' => $this->firstWidth,
                '{value}' => $this->formatter->format($attribute['value'], $attribute['format']),
            ]);
        }
        return call_user_func($this->template, $attribute, $index, $this);
    }

    protected function normalizeAttributes() {
        parent::normalizeAttributes();
        if ($this->title) {
            array_unshift($this->attributes, ['label' => 'header', 'value' => '']);
        }
    }
}