<?php

namespace Bbdgnc\CycloBranch\Enum;

class ResetTypeEnum {

    const EMPTY = 0;

    const AMINO_ACIDS = 1;

    const AMINO_ACIDS_WITH_MODIFICATION = 2;

    const DEFAULT_MODIFICATIONS = 3;

    /** @var array mapping int code to string */
    public static $values = [
        self::EMPTY => 'Empty the database',
        self::AMINO_ACIDS => 'Reset and import 20 base amino acids',
        self::AMINO_ACIDS_WITH_MODIFICATION => 'Reset and import 20 base amino acids and 4 default modifications',
        self::DEFAULT_MODIFICATIONS => 'Reset and import 4 default modifications',
    ];

}
