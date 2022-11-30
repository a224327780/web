<?php

namespace admin\controllers;

use admin\components\BaseAuthController;
use app\models\Setting;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;

class SettingController extends BaseAuthController {

    /**
     * @return array|bool|string
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionIndex() {
        $this->view->title = Yii::t('app', 'Settings');
        $items = [
            'basic' => Yii::t('app', 'basic'),
            'ad' => Yii::t('app', 'ad')
        ];
        $model = new Setting();
        if (!isset($_POST[$model->formName()])) {
            $type = $this->get('type', 'basic');
            return $this->render(compact('model', 'items', 'type'));
        }

        $data = $_POST[$model->formName()];
        foreach ($data as $name => $val) {
            if (!($ar = Setting::findOne(['id' => $name]))) {
                $ar = new Setting();
                $ar->id = $name;
            }
            $ar->value = $val;
            $ar->save(FALSE);
        }

        return $this->success(NULL, \Yii::t('app', 'Setting saved'));
    }
}
