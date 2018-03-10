<?php

namespace app\modules\demo\controllers;

use app\controllers\BaseController;


class IndexController extends BaseController
{


    public function actionIndex()
    {
    return $this->render('index');
    }




}