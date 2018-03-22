<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-3-22
 * Time: 上午11:06
 */

namespace app\modules\queue\controllers;


use app\controllers\BaseController;

/**
 * 消息队列demo
 * Class MessageController
 * @package app\modules\queue\controllers
 */
class MessageController extends BaseController
{

     public function actionIndex()
      {
          return $this->render('message');

      }

}