<?php
/**
 * Created by PhpStorm.
 * User: zouhua
 * Date: 2017/5/10
 * Time: 14:58
 */

namespace admin\components;


use app\components\BaseActiveRecord;
use yii\base\Action;

class DeleteAction extends Action {

    public $modelClass;
    public $status;

    /**
     * @var BaseAuthController
     */
    public $controller;

    public function run() {
        $lists = $this->controller->getParam('id-map');
        $id = $this->controller->getParam('id');

        /* @var $modelClass BaseActiveRecord */
        $modelClass = $this->modelClass;
        if (NULL !== $id) {
            $model = $modelClass::findOne($id);
            if (!$model) {
                return $this->controller->error('错误');
            }
            $model->setAttribute('status', $this->status);
            return $model->save();
        } elseif (!empty($lists)) {
            $modelClass::updateAll(['status' => $this->status], ['id' => $lists]);
            return $this->controller->success();
        }

        return $this->controller->error();
    }
}