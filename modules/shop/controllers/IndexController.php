<?php

namespace app\modules\shop\controllers;

use app\controllers\BaseController;


class IndexController extends BaseController
{


    public function actionIndex()


    {
        return $this->render('index');
    }


}