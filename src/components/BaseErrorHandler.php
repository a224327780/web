<?php

namespace app\components;

use app\helpers\AppHelper;
use Error;
use Yii;
use yii\base\InvalidRouteException;
use yii\base\UserException;
use yii\console\Exception;
use yii\web\ErrorHandler;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BaseErrorHandler extends ErrorHandler {

    /**
     * Renders the exception.
     * @param Exception|Error $exception the exception to be rendered.
     * @throws InvalidRouteException
     * @throws Exception
     */
    protected function renderException($exception) {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            $response->isSent = FALSE;
            $response->stream = NULL;
            $response->data = NULL;
            $response->content = NULL;
        } else {
            $response = new Response();
        }

        $controller = Yii::$app->controller->id;
        if (Yii::$app->request->isAjax || $controller == 'api') {
            $trace = sprintf('%s:%s', $exception->getFile(), $exception->getLine());
            $result = AppHelper::error($exception->getMessage(), AppHelper::ERROR_CODE, compact('trace'));

            $response->data = $result;
            $response->format = Response::FORMAT_JSON;
            $response->setStatusCode(200);
        } else {
            $useErrorView = $response->format === Response::FORMAT_HTML && (!YII_DEBUG || $exception instanceof UserException);
            if (YII_ENV_PROD || $exception instanceof NotFoundHttpException) {
                $result = Yii::$app->runAction($this->errorAction, compact('exception'));
                $response->data = $result;
            } else {
                if (YII_DEBUG) {
                    ini_set('display_errors', 1);
                }
                $file = $useErrorView ? $this->errorView : $this->exceptionView;
                $response->data = $this->renderFile($file, [
                    'exception' => $exception,
                ]);
            }
            if ($exception instanceof HttpException) {
                $response->setStatusCode($exception->statusCode);
            } else {
                $response->setStatusCode(500);
            }
        }
        $response->send();
    }

}