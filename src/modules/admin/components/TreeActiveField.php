<?php

namespace admin\components;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveField;

class TreeActiveField extends ActiveField {

    public function checkboxList($items, $Setting = []) {
        $Setting['item'] = function ($index, $label, $name, $checked, $value) use ($items) {
            $_parent = Html::checkbox($name, $checked, ['label' => $label, 'value' => $value, 'id' => "data-$value"]);
            $html = Html::tag('div', $_parent, ['class' => 'checkbox checkbox-0']);
            if (isset($items[$value]['child'])) {
                $nodes = ArrayHelper::map($items[$value]['child'], 'id', 'name');

                $name = isset($Setting['name']) ? $Setting['name'] : Html::getInputName($this->model, $this->attribute);
                $selection = Html::getAttributeValue($this->model, $this->attribute);
                $Setting = [
                    'class' => 'checkbox-1',
                    'id' => "checkbox-data-{$value}",
                    'itemSetting' => ['labelSetting' => ['class' => 'checkbox-inline']]
                ];
                $html .= Html::checkboxList($name, $selection, $nodes, $Setting);
            }
            return $html;
        };

        parent::checkboxList(ArrayHelper::map($items, 'id', 'name'), $Setting);
        return $this;
    }
}