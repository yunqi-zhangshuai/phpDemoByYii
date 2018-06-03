<?php

namespace app\modules\active\controllers;

use app\controllers\BaseController;


class IndexController extends BaseController
{
//public $layout = '/main';
    /**
     * @return string
     */
    public function actionIndex()
    {
        var_dump($_SERVER);die;
       /* $client = new \swoole_client(SWOOLE_SOCK_TCP,SWOOLE_SOCK_SYNC);
        $client->connect('127.0.0.1',9002) || exit('链接错误!');
        $client->send('我是从modules进入的');
        $message = $client->recv();
        $client->close();
        echo $message;
        //return $this->render('index');*/
    }


}