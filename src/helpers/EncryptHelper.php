<?php
/**
 * Created by PhpStorm.
 * User: zouhua
 * Date: 2017/6/8
 * Time: 15:14
 */

namespace app\helpers;


class EncryptHelper {

    const KEY = '1d408599f8620128ae4dcb9d7649f257';
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.';
    const ikey = '-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm';

    private static function key() {
        return defined('APP_KEY') ? APP_KEY : static::KEY;
    }

    public static function encode($txt) {
        $key = static::key();
        $chars = static::chars;
        $ikey = static::ikey;
        $nh1 = rand(0, 64);
        $nh2 = rand(0, 64);
        $nh3 = rand(0, 64);
        $ch1 = $chars{$nh1};
        $ch2 = $chars{$nh2};
        $ch3 = $chars{$nh3};
        $n1 = $nh1 + $nh2 + $nh3;
        $n2 = $i = 0;
        while (isset($key{$i})) {
            $n2 += ord($key{$i++});
        }

        $mdKey = substr(md5(md5(md5($key . $ch1) . $ch2 . $ikey) . $ch3), $n1 % 8, $n2 % 8 + 16);
        $txt = base64_encode($txt);
        $txt = str_replace(array('+', '/', '='), array('-', '_', '.'), $txt);
        $tmp = '';
        $k = 0;
        $n3 = strlen($txt);
        $n4 = strlen($mdKey);
        for ($i = 0; $i < $n3; $i++) {
            $k = $k == $n4 ? 0 : $k;
            $j = ($n1 + strpos($chars, $txt{$i}) + ord($mdKey{$k++})) % 64;
            $tmp .= $chars{$j};
        }
        $n5 = strlen($tmp);
        $tmp = substr_replace($tmp, $ch3, $nh2 % ++$n5, 0);
        $tmp = substr_replace($tmp, $ch2, $nh1 % ++$n5, 0);
        $tmp = substr_replace($tmp, $ch1, $n2 % ++$n5, 0);
        return $tmp;
    }

    public static function decode($txt) {
        $key = static::key();
        $chars = static::chars;
        $ikey = static::ikey;
        $num = $i = 0;
        $n = strlen($txt);
        while (isset($key{$i})) {
            $num += ord($key{$i++});
        }

        $ch1 = $txt{$num % $n};
        $nh1 = strpos($chars, $ch1);
        $txt = substr_replace($txt, '', $num % $n--, 1);
        $ch2 = $txt{$nh1 % $n};
        $nh2 = strpos($chars, $ch2);
        $txt = substr_replace($txt, '', $nh1 % $n--, 1);
        $ch3 = $txt{$nh2 % $n};
        $nh3 = strpos($chars, $ch3);
        $txt = substr_replace($txt, '', $nh2 % $n--, 1);
        $num1 = $nh1 + $nh2 + $nh3;
        $mdKey = substr(md5(md5(md5($key . $ch1) . $ch2 . $ikey) . $ch3), $num1 % 8, $num % 8 + 16);
        $tmp = '';
        $k = 0;
        $n1 = strlen($txt);
        $n2 = strlen($mdKey);
        for ($i = 0; $i < $n1; $i++) {
            $k = $k == $n2 ? 0 : $k;
            $j = strpos($chars, $txt{$i}) - $num1 - ord($mdKey{$k++});
            while ($j < 0)
                $j += 64;
            $tmp .= $chars{$j};
        }
        $tmp = str_replace(array('-', '_', '.'), array('+', '/', '='), $tmp);
        return base64_decode($tmp);
    }

    public static function md5($str, $len = 16) {
        $mds = md5($str . static::key());
        $md_asc = '';
        for ($i = 1; $i < strlen($mds); $i++) {
            $md_asc .= 168 / ord(substr($mds, $i, 1));
        }
        $string = strtolower(md5($md_asc . static::key()));
        return substr($string, 8, $len);
    }

    public static function inviteCode($id) {
        $x = crc32($id);
        $code = '';
        while ($x > 0) {
            $s = $x % 62;
            if ($s > 35) {
                $s = chr($s + 61);
            } elseif ($s > 9 && $s <= 35) {
                $s = chr($s + 55);
            }
            $code .= $s;
            $x = floor($x / 62);
        }
        return $code;
    }

}