<?php

namespace app\modules\admin;

use Yii;
use yii\base\InvalidConfigException;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'admin\controllers';
    public $layout = '@admin/views/layouts/main';

    /**
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();

        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Admin',
            'enableAutoLogin' => TRUE,
            'identityCookie' => ['name' => '__admin', 'httpOnly' => TRUE],
            'idParam' => '_admin',
            'loginUrl' => ['admin/auth/login'],
        ]);

        Yii::$app->errorHandler->errorAction = "/admin/{$this->defaultRoute}/error";
    }
}
