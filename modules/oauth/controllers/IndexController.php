<?php

namespace app\modules\oauth\controllers;

use app\controllers\BaseController;
use yii\helpers\Url;


class IndexController extends BaseController
{

    public $token = 'wrnf*$$$$)45454@#$^^1i54+-!%(*(';
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMakeoauthurl()
    {

        return $this->redirect(['oauth/oauth','sign'=>sha1(uniqid() . $this->token),'']);

    }




}