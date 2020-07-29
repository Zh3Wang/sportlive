<?php

declare(strict_types=1);

//同步阻塞客户端模式 UDP
use Swoole\Client;

// $client = new Client(SWOOLE_SOCK_UDP | SWOOLE_KEEP);

// $client->sendto('127.0.0.1', 9501,"你好!在吗?\n");
// echo $client->recv();

// $client->close();


//一键协程化
Co\run(function(){
    $client = new Client(SWOOLE_SOCK_UDP | SWOOLE_KEEP);

    $client->sendto('127.0.0.1', 9501,"你好!在吗?\n");
    echo $client->recv();

    $client->close();
});