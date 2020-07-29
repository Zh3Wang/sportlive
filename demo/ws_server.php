<?php

declare(strict_types=1);

use Swoole\WebSocket\Server;

//创建websocket服务器对象，监听0.0.0.0:9502端口
$ws = new Server("0.0.0.0", 9502);

$ws->set([
    'worker_num' => 2,
    'task_worker_num' => 10,
]);

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    echo $request->fd . " - 连接 \n";
    // var_dump($request->get);
    $ws->push($request->fd, "hello, welcome\n");
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    echo "Message: {$frame->data}\n";
    /**
     * 执行一个task异步任务
     */
    // $ws->task('睡觉');

    /**
     * 收到消息后，执行一个定时器
     */
    // swoole_timer_after(2000, function () use ($ws, $frame) {
    //     $ws->push($frame->fd, "定时器执行中...");
    // });
    swoole_timer_tick(1000, function ($timer_id) use ($ws, $frame){
        $ws->push($frame->fd, "定时器执行中...");
        swoole_timer_clear($timer_id);
    });
    $ws->push($frame->fd, "server: {$frame->data}");
});

$ws->on('task', function ($ws, $task_id, $worker_id, $data) {
    echo "Task进程开始处理数据:";
    echo "#{$ws->worker_id}\tonTask: [PID={$ws->worker_pid}]: task_id=$task_id, data=" . $data . "." . PHP_EOL;
    sleep(5);
    return 'finish';
});

$ws->on('finish', function ($ws, $task_id, $data) {
    echo "Task完成任务：tid->{$task_id}, data->{$data}\n";
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();
