<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-5-21
 * Time: 下午10:06
 */

namespace app\common\server;

use \Swoole\WebSocket\Server;

class WsServer
{

    /**
     * @var Server webSocket
     */
    private $_ws;

    /**
     * WsServer constructor.
     * @param string $url
     * @param int $port
     * @param array $server_config
     */
    public function __construct($url = '', int $port = 9003, $server_config = [])
    {
        $this->_ws = new Server('127.0.0.1', $port);

        if (empty(!$server_config)) {
            $this->_ws->set($server_config);
        }
        //客户端连接时候的回调
        $this->_ws->on('Open', [$this, 'OnOpen']);

        //给客户端发送信息
        $this->_ws->on('message', [$this, 'OnMessage']);

        //关闭连接的回调
        $this->_ws->on('close', [$this, 'OnClose']);

        //异步回调
        $this->_ws->on('task', [$this, 'OnTask']);

        //worker 进程创建时候的回调,可用户热更新业务代码
        $this->_ws->on('WorkerStart', [$this, 'OnWorkerStart']);

        //注册异步完成时的回调
        $this->_ws->on('finish', [$this, 'OnFinish']);
        //运行服务
        $this->_ws->start();
    }

    //客户端连接时候的回调

    public function OnOpen(Server $server, \swoole_http_request $request)
    {

        echo '当前的请求连接ID是----', "{$request->fd}", PHP_EOL;

    }

    public function OnMessage(Server $server, \swoole\websocket\frame $frame)
    {
        /*
         * $frame->fd，客户端的socket id，使用$server->push推送数据时需要用到
         * $frame->data，数据内容，可以是文本内容也可以是二进制数据，可以通过opcode的值来判断
         * $frame->opcode，WebSocket的OpCode类型，可以参考WebSocket协议标准文档
         * $frame->finish，
         * 表示数据帧是否完整，一个WebSocket请求可能会分成多个数据帧进行发送（底层已经实现了自动合并数据帧，
         * 现在不用担心接收到的数据帧不完整）
          * */
        echo '当前客户端的id是--', $frame->fd, PHP_EOL, '接收的数据是---', $frame->data, PHP_EOL;
        $back_msg = '服务器已经收到!' . date('Y-m-d H:i:s');
        $server->push($frame->fd, $back_msg);

        //投递异步任务
        if(count($server->connections) > 2 ) {
            $server->task('aaa');

        }
    }


    /**
     * @param Server $server swoole_server对象
     * @param int $fd 是连接的文件描述符
     * @param int $reactorId reactor线程ID
     */
    public function OnClose(Server $server, int $fd, int $reactorId)
    {

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
        echo '当前的异步id是----', $task_id, '-----', $src_worker_id, PHP_EOL;
            foreach($server->connections as $fd)
            {
                $server->push($fd, "服务器向你问好!" . date('Y-m-d H:i:s') . '序号是 ' . mt_rand(1,3000));
            }

        /*var_dump($data);
        static $state = 0;
        swoole_timer_tick(5000,function($time)use ($data,$server,&$state){
            $message = '我是异步投递的任务---' . ($state++);
            $server->push($data['obj']->fd,$message);


            if($state >= 10) {
                swoole_timer_clear($time);
            }

            echo  $message . PHP_EOL;
        });*/
    }


    /**
     * @param Server $server
     * @param int $worker_id 是一个从0-$worker_num之间的数字，表示这个Worker进程的ID
     * $worker_id和进程PID没有任何关系，可使用posix_getpid函数获取PID
     */
    public function OnWorkerStart(Server $server, int $worker_id)
    {
       // new \app\common\helpers\Run();

    }

    public function onFinish(Server $server, $taskId, $data)
    {

    }

    public function updateCode($file_path)
    {

    }
}