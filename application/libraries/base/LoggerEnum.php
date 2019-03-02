<?php

namespace Bbdgnc\Enum;

class LoggerEnum {

    const DISABLE = 0;
    const INFO = 1;
    const DEBUG = 2;
    const WARNING = 3;
    const ERROR = 4;

    public static $values = [
        self::INFO => 'INFO',
        self::DEBUG => 'DEBUG',
        self::WARNING => 'WARNING',
        self::ERROR => 'ERROR',
    ];

}
