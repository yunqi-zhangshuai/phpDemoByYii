<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 下午 5:59
 */

namespace app\controllers;


use yii\web\Controller;
use app\common\Redis;
use PHPExcel_Chart_Legend;

class TestController extends Controller
{
    public function actionIndex()
    {
        $cacke = \Yii::$app->cache->set('index','aaa',26);
        var_dump($cacke);
        var_dump( \Yii::$app->cache->getOrSet('index','444'));die;

    }


    public static function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }

    public function actionFanshe(){
        $fs = new \Reflection();
    }
}


