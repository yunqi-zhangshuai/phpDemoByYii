<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-1-2
 * Time: 下午5:00
 */

use yii\web\Response;

if (!function_exists('app')) {
    /**
     * App或App的定义组件
     *
     * @param null $component Yii组件名称
     * @param bool $throwException 获取未定义组件是否报错
     * @return null|object|\yii\console\Application|\yii\web\Application
     * @throws \yii\base\InvalidConfigException
     */
    function app($component = null, $throwException = true)
    {
        if ($component === null) {
            return Yii::$app;
        }
        return Yii::$app->get($component, $throwException);
    }
}
if (!function_exists('t')) {
    /**
     * i18n 国际化
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    function t($category, $message, $params = [], $language = null)
    {
        return Yii::t($category, $message, $params, $language);
    }
}

if (!function_exists('user')) {


    /**
     * @param null $attribute
     * @return \yii\web\User
     * @throws Throwable
     */
    function user($attribute = null)
    {
        if ($attribute === null) {
            return Yii::$app->getUser();
        }
        if (is_array($attribute)) {
            return Yii::$app->getUser()->getIdentity()->setAttributes($attribute);
        }
        return Yii::$app->getUser()->getIdentity()->{$attribute};
    }
}
if (!function_exists('request')) {
    /**
     * Request组件或者通过Request组件获取GET值
     *
     * @param string $key
     * @param mixed $default
     * @return \yii\web\Request|string|array
     */
    function request($key = null, $default = null)
    {
        if ($key === null) {
            return Yii::$app->getRequest();
        }
        return Yii::$app->getRequest()->getQueryParam($key, $default);
    }
}
if (!function_exists('response')) {
    /**
     * Response组件或者通过Response组织内容
     *
     * @param string $content 响应内容
     * @param string $format 响应格式
     * @param null $status
     * @return Response
     */
    function response($content = '', $format = Response::FORMAT_HTML, $status = null)
    {
        $response = Yii::$app->getResponse();
        if (func_num_args() !== 0) {
            $response->format = $format;
            if ($status !== null) {
                $response->setStatusCode($status);
            }
            if ($format === Response::FORMAT_HTML) {
                $response->content = $content;
            } else {
                $response->data = $content;
            }
        }
        return $response;
    }
}

if (!function_exists('params')) {
    /**
     * params 组件或者通过 params 组件获取GET值
     * @param $key
     * @return mixed|\yii\web\Session
     */
    function params($key)
    {
        return Yii::$app->params[$key];
    }
}


if (!function_exists('session')) {
    /**
     * Session组件或者通过Session组件获取GET值
     * @param null $key
     * @return mixed|\yii\web\Session
     */
    function session($key = null)
    {
        if ($key === null) {
            return Yii::$app->session;
        }
        return Yii::$app->getSession()->get($key);
    }
}

if (!function_exists('cache')) {
    /**
     * Cache组件或者通过Cache组件获取GET值
     * @param null $key
     * @return mixed|\yii\caching\Cache
     */
    function cache($key = null)
    {
        if ($key === null) {
            return Yii::$app->cache;
        }
        return Yii::$app->getCache()->get($key);
    }
}


if (!function_exists('pr')) {
    /**
     * 调试专用
     * @param $message
     * @param bool|true $debug
     */
    function pr($message, $debug = true)
    {
        echo '<pre>';
        print_r($message);
        echo '</pre>';
        if ($debug) {
            die;
        }
    }
}


if (!function_exists('redis')) {
    /**
     * 返回redis实例
     * @param int $type 0时返回 cache
     * @return  \yii\redis\Cache|\yii\redis\Connection
     */
    function redis($type = 1)
    {
        if ($type !== 1) {
            return new yii\redis\Cache();
        }
        return (new yii\redis\Cache())->redis;
    }
}

if(!function_exists('sortSetArr')) {
    /**
     * 对redis 有序集合的数据重建数组
     * @param array $arr
     * @return array 重建的数组
     */
   function sortSetArr(array $arr)
   {
       $length = count($arr);
       $result = [];
       for ($i=0;$i<$length;$i++){
           if($i % 2 !== 0) {
              continue;
           }
           $result[$arr[$i]] = $arr[$i+1];
       }
       return $result;
   }
}