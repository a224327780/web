<?php

namespace app\helpers;


use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class AppHelper {

    const ERROR_CODE = 1;
    const SUCCESS_CODE = 0;

    const QI_NIU_DOMAIN = 'http://p6yhjn52d.bkt.clouddn.com/';

    public static function generateUUid($n = 12, $uid = '') {
        if (NULL === $uid) {
            $uid = '';
        }
        $uuid = uniqid($uid);
        $rand = mt_rand(10000000, 99999999);
        $str = substr(md5($uuid . $rand), 8, 8);
        return substr(bin2hex($str), 0, $n);
    }

    public static function success($data = NULL, $msg = '') {
        return ['msg' => $msg, 'code' => self::SUCCESS_CODE, 'data' => $data];
    }

    public static function isOk($res) {
        return $res['code'] === self::SUCCESS_CODE;
    }

    public static function error($msg = NULL, $code = self::ERROR_CODE, $data = NULL) {
        if (NULL === $msg) {
            $msg = '网络异常，请稍后再试！';
        }
        return ['msg' => $msg, 'code' => $code, 'data' => $data];
    }

    public static function getClientIp() {
        $ip = '0.0.0.0';
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = trim($_SERVER['HTTP_X_REAL_IP']);
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $tmp = array_slice($arr, -1, 1);
            $ip = trim($tmp[0]);
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return static::encodeIp($ip);
    }

    public static function encodeIp($ip) {
        return sprintf("%u", ip2long($ip));
    }

    public static function decodeIp($ip) {
        return long2ip($ip);
    }

    public static function hidePhone($phone) {
        return substr_replace($phone, '****', 3, 4);
    }

    public static function qiNiuImage($key) {
        return self::QI_NIU_DOMAIN . $key;
    }

    public static function thumbnail($file) {
        if (strpos($file, 'http') !== FALSE) {
            $file = str_replace('http://ww3.sinaimg.cn', self::getCdnDomain(), $file);
            return $file;
        }
        return self::formatImage($file);
    }

    public static function getCdnDomain() {
        $domain = \Yii::$app->request->hostName;
        $domain = str_replace(['www.', 'www'], '', $domain);
        return "//cdn.{$domain}";
    }

    public static function formatImage($image) {
        if (empty($image)) {
            return '';
        }
        if (YII_DEBUG) {
            return $image;
        }

        $cdn = self::getCdnDomain();
        if (strpos($image, 'oss.mkzcdn.com') !== FALSE) {
            $image = str_replace(['http://oss.mkzcdn.com', 'https://oss.mkzcdn.com'], $cdn, $image);
        }

        if (strpos($image, '//') !== FALSE) {
            return $image;
        }
        if (strpos($image, 'uploads') !== FALSE) {
            $image = '/' . trim($image, '/');
            if (strpos($image, 'http') === FALSE) {
                $image = Url::to([$image]);
            }
            return $image;
        }
        return $image;
    }

    public static function formatImages($data) {
        if (empty($data)) {
            return [];
        }
        $items = [];
        foreach ($data as $img) {
            $items[] = self::formatImage($img);
        }
        return $items;
    }

    public static function isValid($phone) {
        return preg_match('/^(1\d{10})$/', $phone);
    }

    public static function img($src, $Setting = ['style' => 'width:300px;']) {
        if (empty($src)) {
            return '';
        }
        return Html::img(self::formatImage($src), $Setting);
    }

    public static function toImages($data, $Setting = []) {
        $data = Json::decode($data, TRUE);
        if (empty($data)) {
            return '';
        }
        $items = [];
        foreach ($data as $img) {
            $items[] = self::toImage($img, $Setting);
        }
        return join("\n", $items);
    }

    public static function toImage($img, $Setting = []) {
        $src = self::img($img, $Setting);
        return Html::a($src, self::formatImage($img), ['target' => '_blank']);
    }

    public static function convertText($string, $len = 180) {
        $string = strip_tags($string);
        $string = str_replace(['&nbsp;', "\r", "\n", "\t", "　"], '', $string);
        return trim(mb_substr(trim($string), 0, $len));
    }

    public static function formatNumber($number) {
        if ($number > 1000 * 100000) {
            $number = $number / 1000 * 100000 . '亿';
        }
        if ($number > 10000) {
            $number = number_format($number / 10000, 2) . '万';
        }
        return str_replace('.00', '', $number);
    }

}