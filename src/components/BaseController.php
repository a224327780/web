<?php

namespace app\components;

use app\actions\CaptchaAction;
use app\helpers\AppHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class BaseController extends Controller {

    const ERROR_MSG = '网络异常,请稍后在试';

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action) {
        Yii::setAlias('asset', "@web/assets");
        return parent::beforeAction($action);
    }

    public function actions() {
        return [
            'upload' => 'app\actions\UploadAction',
            'site-map' => 'app\actions\SiteMapAction',
            'captcha' => [
                'class' => CaptchaAction::class,
                'backColor' => 0x343a40,
                'maxLength' => 5,
                'minLength' => 5,
                'padding' => 1,
                'height' => 33,
                'width' => 110,
                'foreColor' => 0xffffff,
                'offset' => 5,
            ]
        ];
    }

    public function render($params = [], $view = NULL) {
        if (NULL === $view) {
            $view = "/{$this->action->id}";
        }
        return $this->isAjax() ? parent::renderAjax($view, $params) : parent::render($view, $params);
    }

    public function exitJson(array $data) {
        header('Content-Type: application/json; charset=utf-8');
        echo Json::encode($data);
        exit;
    }

    public function sendCookie($name, $value, $duration = 0) {
        $cookie = new Cookie(['name' => $name]);
        $cookie->value = $value;
        $cookie->expire = time() + $duration;
        Yii::$app->getResponse()->getCookies()->add($cookie);
    }

    public function getCookie($name) {
        return Yii::$app->request->cookies->getValue($name);
    }

    public function deleteCookie($name) {
        return Yii::$app->response->cookies->remove($name);
    }

    public function success($data = NULL, $msg = '') {
        return AppHelper::success($data, $msg);
    }

    public function error($msg = NULL, $code = AppHelper::ERROR_CODE, $data = NULL) {
        return AppHelper::error($msg, $code, $data);
    }

    public function post($name = NULL, $defaultValue = NULL) {
        return Yii::$app->request->post($name, $defaultValue);
    }

    public function get($name = NULL, $defaultValue = NULL) {
        return Yii::$app->request->get($name, $defaultValue);
    }

    public function getParam($name = NULL, $defaultValue = NULL) {
        $data = $this->get($name, $defaultValue);
        return !empty($data) && $data !== $defaultValue ? $data : $this->post($name, $defaultValue);
    }

    public function isAjax() {
        return Yii::$app->request->isAjax;
    }

    public function isPost() {
        return Yii::$app->request->isPost;
    }

    /**
     * @param string $id
     * @param string $modelClass
     * @return BaseActiveRecord the model found
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id, $modelClass = NULL) {
        if (NULL == $modelClass) {
            $modelClass = '\app\models\\' . ucfirst($this->id);
        }

        /* @var $modelClass BaseActiveRecord */
        if ($id) {
            $model = $modelClass::findOne($id);
        } else {
            $model = new $modelClass();
        }
        if (NULL != $model) {
            return $model;
        }
        throw new NotFoundHttpException("Object not found: $id");
    }

    public function actionError(\Exception $exception) {
        if ($exception instanceof yii\web\NotFoundHttpException) {
            $this->view->title = '404';
            return $this->renderPartial('/404');
        }
        return $this->renderPartial('/500');
    }

    /**
     * @param $result
     * @return array
     * @throws InvalidConfigException
     */
    public function serializer($result) {
        $serializer = [
            'class' => 'yii\rest\Serializer',
            'collectionEnvelope' => 'items',
            'metaEnvelope' => 'pager',
        ];
        $result = Yii::createObject($serializer)->serialize($result);
        if (!is_array($result)) {
            return $this->error($result);
        }

        if (isset($result['_links'])) {
            unset($result['_links']);
        }
        return $this->success($result);
    }
}