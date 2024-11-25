<?php
/**
 * 内部接口基类
 * Created by PhpStorm.
 * User: ATom
 * Date: 2021/02/23
 * Time: 01:15
 */

namespace Controller\Sdk\Model;

use Library\Logger;
use ZScript\Library\Precondition;

class HttpBaseModel
{
    // 接口默认请求超时时间[单位:秒]
    public static $timeout = 10;

    //请求代理开关
    public static $proxy_switch = false;

    // 请求URI配置[固定配置]
    public static $request_uri = [];

    // 字段映射关系[固定配置]
    public static $fields_map = [];

    // 字段值映射关系[固定配置]
    public static $field_values_map = [];

    // 输入信息映射转换
    public static function encodeDataKey(array $data = [], array $fields_map = [])
    {
        $fields_map = empty($fields_map) ? static::$fields_map : $fields_map;
        foreach ($fields_map as $key => $value) {
            if (isset($data[$key])) {
                $data[$value] = $data[$key];
                if ($key !== $value) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }

    // 输出信息映射转换
    public static function decodeDataKey(array $response_data = [], array $fields_map = [])
    {
        $fields_map = empty($fields_map) ? static::$fields_map : $fields_map;
        foreach ($fields_map as $key => $value) {
            if (isset($response_data[$value])) {
                $response_data[$key] = $response_data[$value];
                if ($key !== $value) {
                    unset($response_data[$value]);
                }
            }
        }

        return $response_data;
    }

    // 输入键值信息映射转换
    public static function encodeDataValue(array $data = [], array $field_values_map = [])
    {
        $field_values_map = empty($field_values_map) ? static::$field_values_map : $field_values_map;
        foreach ($field_values_map as $key => $value) {
            if (isset($data[$key])) {
                $ori_value = $data[$key];
                $data[$key] = $value[$ori_value];
            }
        }

        return $data;
    }

    // 输出键值信息映射转换
    public static function decodeDataValue(array $response_data = [], array $field_values_map = [])
    {
        $field_values_map = empty($field_values_map) ? static::$field_values_map : $field_values_map;
        foreach ($field_values_map as $key => $value) {
            if (isset($response_data[$key])) {
                $ori_value = $response_data[$key];
                $response_data[$key] = array_search($ori_value, $value);
            }
        }

        return $response_data;
    }

    // 获取指定keys的数组
    public static function getAssignData(array $arr, array $keys = [])
    {
        // 检测指定keys是否存在
        $assign_data = [];
        foreach ($keys as $field) {
            if (isset($arr[$field])) {
                $assign_data[$field] = $arr[$field];
            }
        }

        return $assign_data;
    }

    // 获取格式化后的响应结果
    public static function getFormatResponseData(string $response_str)
    {
        return @json_decode($response_str, true);
    }
}