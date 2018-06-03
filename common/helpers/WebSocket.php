<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-5-21
 * Time: 下午10:06
 */

namespace app\common\helpers;

use \Swoole\WebSocket\Server;
class WebSocket
{
    private $server;
   public function __construct()
   {
       $this->server = new Server('127.0.0.1',9903);
       $this->server->on('Open',function(Server $server,$request){

       });
   }
}