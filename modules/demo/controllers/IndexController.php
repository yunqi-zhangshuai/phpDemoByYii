<?php

namespace app\modules\demo\controllers;

use app\controllers\BaseController;


class IndexController extends BaseController
{


    public function actionIndex()
    {
        $arr = [23, 15, 43, 25, 54, 2, 6, 82, 11, 5, 21, 32, 65];
        dd($this->bubbleSort($arr));
        //return $this->render('index');
    }


    public function bubbleSort(array $input)
    {
        $length = count($input);
        for ($i = 0; $i < $length; $i++) {
            for ($j = $i + 1; $j < $length; $j++) {
                if ($input[$i] < $input[$j]) {
                    $tmp = $input[$i];
                    $input[$i] = $input[$j];
                    $input[$j] = $tmp;
                }
                echo '---' . "\r\n";

            }
        }
        return $input;
    }



}