<?php

namespace Bbdgnc\CycloBranch\Enum;

class ResetTypeEnum {

    const EMPTY = 0;

    const AMINO_ACIDS = 1;

    /** @var array mapping int code to string */
    public static $values = [
        self::EMPTY => 'Empty the database',
        self::AMINO_ACIDS => 'Reset and import 20 base amino acids',
    ];

}
