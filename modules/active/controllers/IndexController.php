<?php

namespace app\modules\active\controllers;

use app\controllers\BaseController;


class IndexController extends BaseController
{

    /**
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index');
    }


}