<?php

namespace Bbdgnc\Smiles\Enum;

class BondTypeEnum {

    const SIMPLE = 1;

    const DOUBLE = 2;

    const TRIPLE = 3;

    public static $values = array(
        '' => self::SIMPLE,
        '-' => self::SIMPLE,
        '=' => self::DOUBLE,
        '#' => self::TRIPLE,
    );

    /**
     * @param string $strBond
     * @return bool
     */
    public static function isSimple(string $strBond) {
        return self::$values[$strBond] == 1;
    }

    public static function isMultipleBinding(string $strBond) {
        return self::$values[$strBond] > 1;
    }

    public static $backValues = array(
        self::SIMPLE => '',
        self::DOUBLE => '=',
        self::TRIPLE => '#',
    );
}