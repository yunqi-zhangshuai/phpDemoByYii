<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-5-20
 * Time: 下午12:04
 */

namespace app\common\server;

use \Swoole\Server;

/**
 * tcp 服务
 * Class TcpServer
 * @package app\common\server
 */
class TcpServer
{
    private $_server;

    private $_run;

    /**
     * BackendServer constructor.
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host = '127.0.0.1', int $port = 9002)
    {
        $this->_server = new Server($host, $port);
        $this->_server->set([
            'worker_num' => 4,
//            'daemonize' => true, //守护进程
//            'log_file' => __DIR__ . '/server.log',
            'task_worker_num' => 2,
            'max_request' => 5000,
//            'task_max_request' => 5000,
//            'open_eof_check' => true, //打开EOF检测
//            'package_eof' => "\r\n", //设置EOF
//            'open_eof_split' => true, // 自动分包*/
        ]);
        $this->_server->on('Connect', [$this, 'OnConnect']);
        $this->_server->on('Receive', [$this, 'OnReceive']);
        $this->_server->on('Task', [$this, 'OnTask']);
//        $this->_server->on('WorkerStart', [$this, 'OnWorkerStart']);
//        $this->_server->on('ManagerStart', [$this, 'OnManagerStart']);
        $this->_server->on('Finish', [$this, 'onFinish']);
        //$this->_server->on('Start',[$this,'OnStart']);
        $this->_server->on('Close', [$this, 'OnClose']);


    }

    //开启服务
    public function OnStart()
    {
        swoole_set_process_name('server-process: master');
    }

    public function start()
    {
        $this->_server->start();
    }

    public function OnConnect(Server $server, $fd, $reactor_id)
    {
        echo "Client : {$reactor_id} , {$fd}";
    }

    //接收数据
    public function OnReceive(Server $server, $fd, $fromId, $data)
    {
        //echo  $this->_run->run($data);
        ECHO '服务器输出' . $data . PHP_EOL;
        $server->send($fd, '已经收到信息' . PHP_EOL);
        /* $i = 0;
         $server->tick(1000,function($time_id,$parm)use(&$i,$server) {
             $i++;
             echo '当前是' . $i . '秒' . PHP_EOL;
             if($i >= 15) {
                 echo "当前定时器id 是 $time_id ..参数是$parm. 正在清除";
                $server->clearTimer($time_id);
             }
         },'world');*/

    }


    /**
     * @param Server $server
     * @param int $task_id 任务ID，由swoole扩展内自动生成，用于区分不同的任务。$task_id和$src_worker_id组合起来才是全局唯一的，
     * 不同的worker进程投递的任务ID可能会有相同
     * @param int $src_worker_id 来自于哪个worker进程
     * @param mixed $data 是任务的内容
     */
    public function OnTask(Server $server, int $task_id, int $src_worker_id, $data)
    {

    }

    public function OnManagerStart()
    {
        swoole_set_process_name('server-process: manager');

    }

    public function OnClose(Server $server, $fd)
    {
    }

    public function onFinish(Server $server, $taskId, $data)
    {

    }

    public function OnWorkerStart(Server $server, $workerId)
    {
        //require __DIR__ .'/Run.php';
        //$this->_run = new Run;
        $this->_run = new \app\common\helpers\Run;

        if ($workerId >= $server->setting['worker_num']) {
            swoole_set_process_name("server-process: task");
        } else {
            swoole_set_process_name("server-process: worker");
        }
    }
}