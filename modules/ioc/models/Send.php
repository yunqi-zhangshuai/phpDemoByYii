<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-2-24
 * Time: 上午10:54
 */

namespace app\modules\ioc\models;


class Send
{
    private $_email = null;
    public function __construct(SendEmailer $sendEmailer)
    {
        $this->_email =  $sendEmailer;

    }

   public function send(){
        $this->_email->send();
   }
}