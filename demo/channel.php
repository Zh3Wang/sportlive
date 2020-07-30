<?php
/*
 * @Description: 协程通道demo
 * @Date: 2020-07-30 10:49:44
 */
$chan = new Swoole\Coroutine\Channel(8);
go(function () use ($chan) {
    for ($i = 0; $i < 100000; $i++) {
        co::sleep(1.0);
        $chan->push(['rand' => rand(1000, 9999), 'index' => $i]); // 通道push（pop）操作也是一个IO，会自动yield当前协程
        echo "1:$i\n";
    }
});
go(function () use ($chan) {
    while (1) {
        echo "2:go\n";
        $data = $chan->pop(); // 通道push（pop）操作也是一个IO，会自动yield当前协程
        var_dump($data);
    }
});

/**
 * 执行过程
 * 
 * 1. co::sleep, IO操作,触发yield协程调度，切换到另一个协程
 * 2. echo "2:go\n"; 此时首先打印出一个字符串
 * 3. 继续顺序执行，遇到$data = $chan->pop() , channel的pop方法也是一个IO操作，触发yield协程调度,回到第一个协程中，等待sleep.
 * 4. sleep 1秒后,执行channel->push操作,触发yield协程调度,回到第二个协程,此时channel->pop返回数据，顺序执行到var_dump打印出数据
 * 5. while循环执行echo "2:go\n"; 再次遇到channel->pop操作，触发yield操作，回到第一个协程，因为刚才第一个协程是在push操作后切换的，此时继续执行push后面的echo操作
 * 6. for循环又一次遇到co::sleep,重复1-5的步骤操作.
 */
