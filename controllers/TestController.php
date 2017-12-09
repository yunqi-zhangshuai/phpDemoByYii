<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 下午 5:59
 */

namespace app\controllers;


use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $m =20;
        for ($i = 0; $i < $m; $i++) {
            //第一个和最后一个都为1
            $a[$i][0] = 1;
            $a[$i][$i] = 1;
        }
        //var_dump($a);die;
        for ($i = 2; $i < $m; $i++) {
            for ($j = 1; $j < $i; $j++) {
                $a[$i][$j] = $a[$i - 1][$j - 1] + $a[$i - 1][$j];
            }
        }
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j <= $i; $j++) {
                echo $a[$i][$j] . '&nbsp;';
            }
            echo '<br/>';
        }

    }


}