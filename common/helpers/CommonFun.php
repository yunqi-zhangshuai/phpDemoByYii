<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-5-16
 * Time: 下午7:20
 */

namespace app\common\helpers;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class CommonFun
{


    /**
     * php模拟curl请求
     *
     * @param string $url 请求的url
     * @param string $method 请求的方法, 默认POST
     * @param array $data 请求传递的数据
     * @param array $header 请求设置的头信息
     * @param int $head 是否打印头信息
     * @param int $body 是否打印body信息
     * @param int $connect_timeout 设置连接前的超时时间
     * @param int $timeout 设置连接后的超时时间
     * @param string $error 错误信息
     *
     * @return array
     */
    static public function curl($url, $method = "POST", $data = array(), $header = array(), $head = 0, $body = 0, $connect_timeout = 30, $timeout = 30, &$error = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (strpos($url, "https") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            }
        }
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        switch ($method) {
            case 'POST':
                if (is_array($data)) {
                    $data = http_build_query($data);
                }
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'GET':
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_PUT, 1);
                curl_setopt($ch, CURLOPT_INFILE, '');
                curl_setopt($ch, CURLOPT_INFILESIZE, 10);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                break;
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, $head);
        curl_setopt($ch, CURLOPT_NOBODY, $body);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $rtn = curl_exec($ch); //获得返回
        if (curl_errno($ch)) {
            //echo 'Errno' . curl_error($ch);//捕抓异常
            return false;
        }
        curl_close($ch);
        return $rtn;
    }

    /**
     * php通过代理模拟curl请求
     *
     * @param string $url 请求的url
     * @param string $method 请求的方法, 默认POST
     * @param array $data 请求传递的数据
     * @param array $header 请求设置的头信息
     * @param int $head 是否打印头信息
     * @param int $body 是否打印body信息
     * @param int $timeout 设置超时时间
     * @param string $error 错误信息
     *
     * @return string | bool
     */
    static public function proxyCurl($url, $method = "POST", $data = array(), $header = array(), $head = 0, $body = 0, $timeout = 30, &$error = '')
    {
        $ch = curl_init();
        //设置代理
        if (strpos($url, 'https') === 0) {
            curl_setopt($ch, CURLOPT_PROXY, '10.26.115.166:443');
            $url = str_replace('https://', 'http://', $url);
        } else {
            curl_setopt($ch, CURLOPT_PROXY, '10.26.115.166:8080');
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        if (strpos($url, "https") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            }
        }
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        switch ($method) {
            case 'POST':
                if (is_array($data)) {
                    $data = http_build_query($data);
                }
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'GET':
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_PUT, 1);
                curl_setopt($ch, CURLOPT_INFILE, '');
                curl_setopt($ch, CURLOPT_INFILESIZE, 10);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                break;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, $head);
        curl_setopt($ch, CURLOPT_NOBODY, $body);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $rtn = curl_exec($ch); //获得返回
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            return false;
        }
        curl_close($ch);
        return $rtn;
    }

    /**
     * 隐藏手机号
     * @param $mobile
     * @return mixed
     */
    public static function hide_mobile($mobile)
    {
        return preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $mobile);
    }

    /**
     * 判断是否是手机号
     *
     * @param $mobile
     * @return int
     */
    public static function is_mobile($mobile)
    {
        return preg_match('/^1[3456789]\d{9}$/', $mobile);
    }

    /**
     * 获取请求ip
     *
     * @return ip地址
     */
    public static function getip()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : '';
    }

    /**
     * 生成随机字符串
     * @param int $length 长度
     * @return string 字符串
     */
    public static function createRandomstr($length = 6)
    {
        return self::random($length, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
    }

    /**
     * 产生随机字符串
     *
     * @param    int $length 输出长度
     * @param    string $chars 可选的 ，默认为 0123456789
     * @return   string     字符串
     */
    public static function random($length, $chars = '0123456789')
    {
        $hash = '';
        $max = strlen($chars) - 1;
        mt_srand();
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }

    /**
     * 简单加密
     * @param $string string
     * @return string
     */
    public static function easyEncrypt($string)
    {
        $encrypt = strtoupper(CommonFun::createRandomstr(3)) . base64_encode($string);
        return rtrim($encrypt, '=');
    }

    /**
     * 简单解密
     * @param $string
     * @return string
     */
    public static function easyDecrypt($string)
    {
        if (!$decode = base64_decode(substr($string, 3))) {
            return false;
        }
        if (!json_encode($decode)) {
            return false;
        }
        return $decode;
    }

    /**
     * 查询号码归属地
     *
     * @param $mobile
     * @return mixed
     */
    public static function getArea($mobile)
    {

        $url = "http://api.buslive.cn/mobile?phone={$mobile}";

        $_area = CommonFun::curl($url, 'GET');
        $area = array();
        if ($_area) {
            $area = json_decode($_area, true);
        }
        return $area;
    }

    /**
     * 十进制转三十四进制
     * @param $int
     * @return string
     */
    public static function getBase34($int)
    {
        $dic = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $arr = [];
        $loop = true;
        while ($loop)
        {
            $arr[] = $dic[bcmod($int, 34)];
            $int = floor(bcdiv($int, 34));
            if ($int == 0) {
                $loop = false;
            }
        }
        return implode('', array_reverse($arr));
    }

    /**
     * 获取毫秒级方法
     *
     * @return float
     */
    public static function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }

    /**
     * 过滤敏感字
     * @param $content
     * @return mixed
     */
    public static function filter($content)
    {
        $url = 'http://y.buslive.cn/tools/filter/filter.php';
        $res = self::curl($url, "POST", ['message' => $content]);
        $res = Json::decode($res, true);
        return ArrayHelper::getValue($res, 'message', '');
    }

    /**
     * 将数组转换成json,支持jsonp
     * @param $value
     * @return string
     */
    public static function toJson($value)
    {
        if (isset($_GET['callback'])) {
            return htmlentities(strip_tags($_GET['callback'])) . '(' . json_encode($value) . ')';
        } else {
            return json_encode($value);
        }
    }

    /**
     * 批量取参数
     * @param array $params
     * @param string $method
     * @return array|bool
     */
    public static function getParams(array $params, $method = 'get')
    {
        $method = strtolower($method);
        if (empty($params) || ($method != 'get' && $method != 'post')) return false;
        foreach ($params as $paramKey => $paramVal) {
            $params[$paramVal] = \Yii::$app->request->$method($paramVal, '');
            unset($params[$paramKey]);
        }
        return $params;
    }

    public static function checkHost()
    {
        if (in_array($_SERVER['HTTP_HOST'] , [
            '1892139.com',
            'y.buptinfo.com',
            'y.buslive.cn',
            'y.1892139.cn',
        ])) {
            return true;
        }

        header('HTTP/1.1 403 Forbidden'); exit;
    }
    /**
     * 最简单的XML转数组
     * @param string $xmlstring XML字符串
     * @return array XML数组
     */
    static public function simplest_xml_to_array($xmlstring) {
        return json_decode(json_encode((array) simplexml_load_string($xmlstring)), true);
    }

    /**
     * 判斷字符串是否全是中文
     * @param $text
     * @return false|int
     */
    public static function is_Zh($text)
    {
        return preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', self::trimAll($text));
    }

    /**
     * 校验邮箱
     * @param $email
     * @return false|int
     */
    public static function is_email($email)
    {
        $reg = '/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/';
        return preg_match($reg, trim($email));
    }

    /**
     * 校验身份证
     * @param $card
     * @return false|int
     */
    public static function is_idCard($card)
    {
        $reg = '/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/';
        return preg_match($reg, self::trimAll($card));

    }

    /**
     * 去除各种空格换行符等
     * @param $str
     * @return mixed
     */
    public static function trimAll($str)
    {
        $qian = array(" ", "　", "\t", "\n", "\r");
        return str_replace($qian, '', $str);
    }

}