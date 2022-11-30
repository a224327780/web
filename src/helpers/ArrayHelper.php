<?php
/**
 * Created by PhpStorm.
 * User: zouhua
 * Date: 2017/6/8
 * Time: 17:43
 */

namespace app\helpers;


class ArrayHelper {

    public static function arrayTreeIndex($arrays, $id = 'id', $pid = 'parent_id') {
        $tree = array();
        foreach ($arrays as $arr) {
            $_id = $arr[$id];
            if (isset($arrays[$arr[$pid]])) {
                $arrays[$arr[$pid]]['child'][$_id] = &$arrays[$_id];
            } else {
                $tree[$_id] = &$arrays[$_id];
            }
        }
        unset($arrays);
        return $tree;
    }

    public static function arrayTree($arrays, $id = 'id', $pid = 'parent_id') {
        $tree = array();
        foreach ($arrays as $arr) {
            $_id = $arr[$id];
            if (isset($arrays[$arr[$pid]])) {
                $arrays[$arr[$pid]]['child'][] = &$arrays[$_id];
            } else {
                $tree[] = &$arrays[$_id];
            }
        }
        unset($arrays);
        return $tree;
    }

    public static function arrayDepthSort($arrays, & $result, $parent = 0, $child = 'parent_id', $name = 'title', $icon = "├", $depth = 0) {
        if (empty($arrays)) {
            return;
        }
        foreach ($arrays as $v) {
            if (intval($v[$child]) === intval($parent)) {
                $l = str_repeat("　 ", $depth) . ($depth ? $icon : "");
                $result[$v['id']] = $v;
                $result[$v['id']]['depth'] = $depth;
                $result[$v['id']]['_title'] = $l . (isset($v[$name]) ? $v[$name] : $v['name']);
                if ($v['id'] > 0) {
                    self::arrayDepthSort($arrays, $result, $v['id'], $child, $name, $icon, $depth + 1);
                }
            }
        }
    }

    public static function findChildIds($arrays, & $result = [], $parentId, $child = 'parent_id') {
        if (intval($parentId) <= 0) {
            return;
        }

        foreach ($arrays as $arr) {
            $pid = intval($arr[$child]);
            if ($pid === intval($parentId)) {
                $result[$arr['id']] = $arr;
                self::findChildIds($arrays, $result, $arr['id'], $child);
            }
        }
    }

    public static function removeChild($arrays, $parentId = 0, $childName = 'parent_id') {
        if (empty($arrays)) {
            return array();
        }
        static::findChildIds($arrays, $childIds, $parentId, $childName);
        //删除子节点
        if (!empty($childIds)) {
            foreach ($childIds as $childId) {
                $id = $childId['id'];
                if (isset($arrays[$id])) {
                    unset($arrays[$id]);
                }
            }
        }
        //删除本身
        if (isset($arrays[$parentId])) {
            unset($arrays[$parentId]);
        }
        return $arrays;
    }

    public static function arraySortByKey($array, $key, $asc = TRUE) {
        sort($array);
        $result = $values = [];
        // 整理出准备排序的数组
        foreach ($array as $k => $v) {
            $values[] = isset($v[$key]) ? $v[$key] : '';
        }
        unset($v);

        $asc ? asort($values) : arsort($values);

        // 重新排列原有数组
        foreach ($values as $k => $v) {
            $result[] = $array[$k];
        }
        unset($array);
        return $result;
    }
}