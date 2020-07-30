<?php

declare(strict_types=1);

use Swoole\Http\Server;

$http = new Server("0.0.0.0", 9501);

$http->set([
    'document_root' => '/var/www/chinahub/demo/static/',
    'enable_static_handler' => true,
]);

$http->on(
    "start",
    function ($http) {
        echo "Swoole HTTP server is started.\n";
    }
);
$http->on(
    "request",
    function ($request, $response) {
        var_dump("Request:" . $request->server['request_method'] . " " . $request->header['x-real-ip']);
        $response->cookie('wangzhe', 'abc', 86400);
        $response->end("Hello, World!\n");
    }
);

echo 'listening...';
$http->start();
