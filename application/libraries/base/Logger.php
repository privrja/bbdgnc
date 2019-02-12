<?php

namespace Bbdgnc\Base;

use Bbdgnc\Enum\LoggerEnum;

class Logger {

    const LEVEL = LoggerEnum::INFO;

    const FILE = "./application/logs/log-";

    public static function log(int $lvl, string $msg) {
        if ($lvl === LoggerEnum::DISABLE || $lvl < self::LEVEL) {
            return;
        }
        $fd = fopen(self::FILE . date("Y-m-d", time()) . ".php", 'a');
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        $str = LoggerEnum::$values[$lvl] . ' - ' . date('Y-m-d h:i:s', time())
            . " --> " . $msg . " in " . $caller['file'] . " on line: " . $caller['line'] . "\n";
        fwrite($fd, $str);
        fclose($fd);
    }

}