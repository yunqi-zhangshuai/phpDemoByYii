<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-2-24
 * Time: 上午10:50
 */

namespace app\modules\ioc\models;


class Email163 implements SendEmailer
{
    private $_name;

   public function __construct($name = '')
   {
       $this->_name = $name;
   }

    public function send()
    {
        echo '发送', '<span style="color: orangered">163邮件</span>';
    }
}