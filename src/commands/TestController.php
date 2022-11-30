<?php


namespace app\commands;


use app\helpers\AppHelper;
use app\helpers\EmailHelper;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\httpclient\Client;

class TestController extends Controller {

    public function actionSendCloud() {
        $url = 'http://api.sendcloud.net/apiv2/mail/send';
        $API_USER = 'hahamh_register';
        $API_KEY = '4BNB3E6TcyAAEn6O';
        $param = [
            'apiUser' => $API_USER,
            'apiKey' => $API_KEY,
            'from' => 'service@hahamh.net',
            'to' => 'atcaoyufei@gmail.com',
            'subject' => '来自SendCloud的第一封邮件！',
            'html' => '你太棒了！你已成功的从SendCloud发送了一封测试邮件，接下来快登录前台去完善账户信息吧！',
            'respEmailId' => 'true'];

        $data = http_build_query($param);

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            ]];

        $context = stream_context_create($options);
        $result = file_get_contents($url, FALSE, $context);
        print_r($result);
        return $result;
    }

    public function actionMail() {
        print_r(EmailHelper::mailGun('atcaoyufei@gmail.com', 'hahamh.net', 'test'));
        print_r(EmailHelper::sendCode('atcaoyufei@gmail.com'));
        return ExitCode::OK;
    }

    public function actionIndex() {

    }
}