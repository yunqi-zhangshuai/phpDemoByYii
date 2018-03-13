<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 17-12-11
 * Time: 上午10:09
 */

namespace app\common;



class Redis extends \Redis
{

   public function __construct()
   {
       $this->connect('localhost');
   }

   public function __destruct()
   {
       $this->close();
   }

}