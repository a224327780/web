<?php


namespace app\actions;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\Response;

class CaptchaAction extends \yii\captcha\CaptchaAction {

    public $transparent = FALSE;

    /**
     * @return array|string
     * @throws InvalidConfigException
     */
    public function run() {
        $this->fontFile = Yii::getAlias('@theme_root/fonts/consola.ttf');
        $this->setHttpHeaders();
        Yii::$app->response->format = Response::FORMAT_RAW;
        return $this->renderImage($this->getVerifyCode(TRUE));
    }
}