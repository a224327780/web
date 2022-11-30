<?php

namespace app\helpers;


use app\models\Book;
use yii\helpers\Html;
use yii\helpers\Url;

class BookHelper {

    public static function formatTag($tags, $name = 't') {
        if (empty($tags)) {
            return '';
        }
        $labels = ['1', '2', '3', '4', 5];
        $items = [];
        $numbers = range(0, count($labels) - 1);
        shuffle($numbers);
        foreach ($tags as $i => $item) {
            if (empty($item['meta'])) {
                continue;
            }
            $tag = $item['meta']['name'];
            $classes = $labels[$numbers[$i]];
            $items [] = Html::a($tag, ['category/', $name => $tag], ['class' => "label bg-{$classes} text-light"]);
        }
        return join("\n", $items);
    }

    public static function getFinishName($finish, $last_item) {
        if (intval($finish) === intval(Book::FINISH_YES)) {
            return Book::$finishLabels[$finish];
        }
        return "更至 {$last_item}";
    }

    public static function getBookItemName($bookItem) {
        $sort = '';
        if (isset($bookItem['item_sort'])) {
            $sort = $bookItem['item_sort'];
        } elseif (isset($bookItem['sort'])) {
            $sort = $bookItem['sort'];
        }
        return "第{$sort}话";
    }

    public static function getFullBookItemName($bookItem) {
        $name = self::getBookItemName($bookItem);
        $title = self::getBookItemTitle($bookItem);
        return trim("{$name} {$title}");
    }

    public static function getBookItemTitle($bookItem) {
        $title = $bookItem['name'];
        if (preg_match('/^第[\d]+话$/', $title)) {
            $title = '';
        } elseif (preg_match('/第[\d]+话(.+)/i', $title, $match)) {
            $title = $match[1];
        } elseif (preg_match('/^[0-9]*$/', $title)) {
            $title = '';
        }
        return trim($title);
    }


    public static function listFilter($items, $param_name, $k_is_value = TRUE) {
        $params = $_params = \Yii::$app->request->queryParams;
        unset($params['page']);

        array_unshift($items, '全部');
        $data = [];
        foreach ($items as $k => $v) {
            $options = [];

            $param_value = $k_is_value ? $k : $v;
            if (isset($_params[$param_name]) && $param_value && $_params[$param_name] == $param_value) {
                $options = ['class' => 'active'];
            }
            $params[$param_name] = $param_value;
            $params[0] = \Yii::$app->controller->route;
            if ($v == '全部') {
                unset($params[$param_name]);
                if (!isset($_params[$param_name])) {
                    $options = ['class' => 'active'];
                }
            }
            $url = Url::to($params);
            $data[] = Html::a($v, $url, $options);
        }
        return join("\n", $data);
    }
}