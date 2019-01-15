<?php

namespace Bbdgnc\Smiles\Enum;

class BondTypeEnum {

    public static $values = array(
        '' => 1,
        '-' => 1,
        '=' => 2,
        '#' => 3,
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

}