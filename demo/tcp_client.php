<?php

declare(strict_types=1);

//同步阻塞客户端模式
// use Swoole\Client;

// $client = new Client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);

// if(!$client->connect('127.0.0.1', 9501)){
//     echo "连接失败:{$client->errCode}\n";
// }

// $client->send("你好!在吗?\n");
// echo $client->recv();

// $client->close();


//协程模式
Co\run(function () {
    $client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
    if (!$client->connect('127.0.0.1', 9501, 0.5)) {
        echo "connect failed. Error: {$client->errCode}\n";
    }
    $client->send("hello world\n");
    echo $client->recv();
    $client->close();
});
