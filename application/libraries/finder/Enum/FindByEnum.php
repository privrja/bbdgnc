<?php

namespace Bbdgnc\Finder\Enum;

abstract class FindByEnum {
    const NAME = 0;
    const SMILE = 1;
    const FORMULA = 2;
    const MASS = 3;
    const IDENTIFIER = 4;

    /** @var array mapping int code to string */
    public static $values = array(
        self::NAME => "Name",
        self::SMILE => "SMILES",
        self::FORMULA => "Molecular Formula",
        self::MASS => "Monoisotopic Mass",
        self::IDENTIFIER => "Identifier"
    );
}