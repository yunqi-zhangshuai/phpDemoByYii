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
use app\common\Redis;

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
     * @var Redis;
     */
    public $redis;



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
     * @param $key
     * @return mixed
     */
    public function loginInfo($key)
    {
        return \Yii::$app->session->get($key);
    }


}