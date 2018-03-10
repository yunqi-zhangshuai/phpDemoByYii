<?php

namespace app\modules\ioc\controllers;

use app\controllers\BaseController;

use app\modules\ioc\models\Container;
use app\modules\ioc\models\Email163;

use yii\helpers\VarDumper;


class IndexController extends BaseController
{

    public function actionIndex()
    {
        VarDumper::dump(['helloword!'], 1, true);
    }


    public static function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }


    public function actionSend()
    {

        $container = new Container;
        $container->set('Email163',function ($name = ''){
            return new Email163($name);
        });

        try{
            $aa = $container->get('Email163','555');
            $aa->send();
        }
        catch (\Exception $exception){
            echo $exception->getMessage();
        }
    }




}