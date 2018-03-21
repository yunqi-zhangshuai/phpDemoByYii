<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-3-4
 * Time: 上午10:39
 */

namespace app\controllers;


use yii\base\Exception;
use yii\web\Controller;

/**
 * 基础控制器
 * Class BaseController
 * @package app\controllers
 */
class BaseController extends Controller
{
    /**
     * 默认布局模板
     * @var string
     */
    public $layout = '//layui';

    /**
     * 登录缓存的key
     * @var
     */
    public $login_key;


    /**
     * 设置登录信息
     * @param string $key
     * @param array $value
     * @throws Exception
     */
    public function login(string $key, array $value)
    {
        if (!$key || !is_array($value)) {
            throw new Exception('key或value值不合法!');
        }
        return \Yii::$app->session->set($key, $value);
    }

    /**
     * 获取缓存信息
     * @param $key string
     * @return mixed
     */
    public function loginInfo(string $key)
    {
        return \Yii::$app->session->get($key);
    }

    /**
     * 缓存加锁
     * @param string $key
     * @return bool
     */
    public function lock(string  $key)
    {
        return redis(0)->add($key,'locked',300);
    }

    /**
     * 删除锁机制
     * @param string $key
     * @return bool
     */
    public function unlock( string $key)
    {
        return redis(0)->delete($key);
    }


}