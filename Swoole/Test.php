<?php

class Ws
{
    const HOST = "0.0.0.0";
    const PORT = 9501;
    const WS_PORT = 9502;
    public $ws = null;
    const CONFIG = [
        'document_root' => '/mnt/hgfs/yaf/swoole.com/tp5/public/static',
        'enable_static_handler' => true,
        'worker_num' => 4,
        'task_worker_num' => 4,
    ];

    public function __construct()
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->del('live_user');
        $this->ws = new swoole_websocket_server(Ws::HOST, Ws::PORT);
        $this->ws->set(Ws::CONFIG);
        $this->ws->listen('0.0.0.0', Ws::WS_PORT, SWOOLE_SOCK_TCP);
        $this->ws->on('start', [$this, 'onStart']);
        $this->ws->on('workerstart', [$this, 'onWorkerStart']);
        $this->ws->on('request', [$this, 'onRequest']);
        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('close', [$this, 'onClose']);
        $this->ws->on('task', [$this, 'onTask']);
        $this->ws->on('finish', [$this, 'onFinish']);
        $this->ws->start();
    }

    public function onWorkerStart(swoole_server $server, int $worker_id)
    {
        //Container::get('app')->run()->send(); 会直接执行框架，然而这步的意义是热加载，载入框架(容器注入)
//        require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../public/index.php';
    }
    public function onStart(swoole_server $server){
        //设置启动服务启动后的进程名
        swoole_set_process_name('live_master');
    }
    public function onRequest($request, $response)
    {
        //阻止favicon.ico 进入
        if ($request->server['request_uri']=='/favicon.ico'){
            $response->status(404);
            $request->end;
            return ;
        }
//    require_once __DIR__ . '/../thinkphp/base.php';
        //因为swoole是常驻内存的，所有有的数据会一直存在，需要覆盖
        foreach ($request->server as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
        foreach ($request->header as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
        //因为swoole是常驻内存的，所以获得到的数据要保证每次都是最新的
        $_GET = [];
        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }
        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }
        $this->writeLog();
        //将http服务传入到框架内部使用
        $_POST['http_server'] = $this->ws;
        //将获得到的数据写到缓存区，避免内存泄漏
        ob_start();
        try {
            //直接执行框架(执行容器)
            think\Container::get('app')->run()->send();
        } catch (\Exception $exception) {
            //TODO
        }
        $res = ob_get_contents();
        ob_end_clean();
//    $response->end('开始');
        //输出内容
        $response->end($res);
    }

    public function onClose($ser, $fd)
    {
        $redis = \app\common\lib\redis\Predis::getInstance();
        $redis->sRem(config('redis.live_user'), $fd);
//        echo "client {$fd} closed\n";
    }

    public function onTask($serv, $task_id, $src_worker_id, $data)
    {
        //将任务自动识别方法
        $taskobj = new app\common\lib\task\Task();
        $method = $data['method'];
        $taskobj->$method($data['data'], $serv);
    }

    public function onFinish($serv, $task_id, $data)
    {
        print_r($data);
    }

    public function onOpen(swoole_websocket_server $server, $request)
    {
        $redis = \app\common\lib\redis\Predis::getInstance();
        $redis->sAdd(config('redis.live_user'), $request->fd);
    }

    public function onMessage(swoole_websocket_server $server, $frame)
    {
    }

    public function writeLog()
    {
        $datas = array_merge(['date' => date('Y-m-d H:i:s')], $_GET, $_POST, $_SERVER);
        $logs = '';
        foreach ($datas as $key => $val) {
            if (!is_array($val)){
                $logs .= $key . ' : ' . $val . ' ';
            }
        }
        $logs.=PHP_EOL;
        swoole_async_writefile(__DIR__ . '/../runtime/log/' . date('Ym') . '/' . date('d') . '_access.log', $logs, function ($filename) {

        }, FILE_APPEND);
    }
}

$obj = new Ws();