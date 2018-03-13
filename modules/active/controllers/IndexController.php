<?php

namespace app\modules\active\controllers;

use app\controllers\BaseController;


class IndexController extends BaseController
{

    const YII_DEBUG = false;
    public $layout = '//layui';

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


}