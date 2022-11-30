<?php

namespace admin\components;

use yii\rest\Action;

class ViewAction extends Action{

    public $title;

    public function run($id){
        $model = $this->findModel($id);

        $this->controller->view->title = $this->title;
        return $this->controller->render(compact('model'));
    }
}