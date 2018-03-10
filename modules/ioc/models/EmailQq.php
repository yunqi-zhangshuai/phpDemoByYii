<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-2-24
 * Time: 上午10:52
 */

namespace app\modules\ioc\models;


class EmailQq implements SendEmailer
{

    public function send()
    {
        echo '发送QQ邮件!';
    }
}