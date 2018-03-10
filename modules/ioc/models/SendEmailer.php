<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-2-24
 * Time: 上午10:49
 */

namespace app\modules\ioc\models;


interface SendEmailer
{
    /**
     * 邮件发送着
     * @return mixed
     */
    public function send();
}