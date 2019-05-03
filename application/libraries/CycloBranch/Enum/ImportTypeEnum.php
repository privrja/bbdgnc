<?php

namespace Bbdgnc\CycloBranch\Enum;

/**
 * Class ImportTypeEnum
 * Enum options for import type
 * @package Bbdgnc\CycloBranch\Enum
 */
class ImportTypeEnum {

    const SEQUENCE = 0;

    const BLOCK = 1;

    const MODIFICATION = 2;

    /** @var array mapping int code to string */
    public static $values = [
        self::SEQUENCE => 'Sequence',
        self::BLOCK => 'Block',
        self::MODIFICATION => 'Modification'
    ];

}
