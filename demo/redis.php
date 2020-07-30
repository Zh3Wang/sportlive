<?php

/*
 * redis协程客户端
 * 
 * @Author: 
 * @Date: 2020/07/30 15:45
*/

Co::set(['hook_flags' => SWOOLE_HOOK_TCP]);

$s = microsecond();
// $redis = new Redis();
// $redis->connect('120.24.24.191', 6380);
// Co\run(function () use ($redis) {
//     for ($i = 0; $i < 10; $i++) {
//         go(function () use ($redis, $i) {
//             echo $i . " ";
//             $data = $redis->get('count');
//             echo $data . " " . $i  . PHP_EOL;
//         });
//     }
// });

$redis = new Redis();
$redis->connect('120.24.24.191', 6380);
Co\run(function () use ($redis) {
    for ($c = 3; $c--;) {
        //创建多个协程
        go(function () use ($c, $redis) {
            echo $c . " " . PHP_EOL;
            $data = $redis->get('count'); 
            // $data = 72;
            echo $data . " " . PHP_EOL;
        });
    }
});

$e = microsecond();
echo "耗时: " . ($e - $s) . "ms" . PHP_EOL;



function microsecond()
{
    $t = explode(" ", microtime());
    $microsecond = round(round($t[1] . substr($t[0], 2, 3)));
    return $microsecond;
}
