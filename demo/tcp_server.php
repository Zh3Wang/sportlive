<?php

declare(strict_types=1);

use Swoole\Server;

//创建server对象，监听9501端口
$server = new Server('0.0.0.0', 9501);

$server->set([
    'reactor_num' => 2,
    'worker_num'    => 4,     // worker process num
]);

//监听连接事件
$server->on('Connect', function ($serv, $fd, $reactor_id) {
    echo "Client: connected - rid:{$reactor_id} - fd:{$fd}!\n";
});

//监听数据接收事件
$server->on('Receive', function ($serv, $fd, $reactor_id, $data) {
    // $serv->send($fd,"Server: {$data}-{$fd}-{$reactor_id}-{$data}");
    $serv->send($fd, "Server: 收到!$fd  Msg:$data\n");
});

//监听连接关闭事件
$server->on('Close', function ($serv, $fd) {
    echo "Client: Closed.\n";
});

//启动服务器
echo "listening....\n";
$server->start();
