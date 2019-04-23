<?php

namespace Bbdgnc\Base;

use Bbdgnc\Enum\LoggerEnum;

class Logger {

    const LEVEL = LoggerEnum::DISABLE;

    const FILE = "./application/logs/log-";

    private static $prefix = "";

    /**
     * @param int $lvl
     * @param string $msg
     * @see LoggerEnum
     */
    public static function log(int $lvl, string $msg) {
        if (self::LEVEL === LoggerEnum::DISABLE || $lvl < self::LEVEL) {
            return;
        }
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        $str = LoggerEnum::$values[$lvl] . ' - ' . date('Y-m-d h:i:s', time())
            . " --> " . $msg . " in " . $caller['file'] . " on line: " . $caller['line'] . "\n";
        file_put_contents(self::$prefix . self::FILE . date("Y-m-d", time()) . ".php", $str, FILE_APPEND);
    }

    public static function setPrefix(string $prefix = '.') {
        self::$prefix = $prefix;
    }

    public static function clearPrefix() {
        self::$prefix = '';
}

}