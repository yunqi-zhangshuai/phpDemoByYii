<?php

namespace app\modules\oauth\controllers;

use app\controllers\BaseController;


class IndexController extends BaseController
{


    public function actionIndex()
    {
        return $this->render('index');
    }




}