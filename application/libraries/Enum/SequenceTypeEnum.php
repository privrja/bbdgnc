<?php

namespace Bbdgnc\Enum;

class SequenceTypeEnum {

    const LINEAR = 0;

    const CYCLIC = 1;

    const BRANCH = 2;

    const BRANCH_CYCLIC = 3;

    const LINEAR_POLYKETIDE = 4;

    const CYCLIC_POLYKETIDE = 5;

    const OTHER = 6;

    /** @var array mapping int code to string */
    public static $values = array(
        self::LINEAR => "linear",
        self::CYCLIC => "cyclic",
        self::BRANCH => "branched",
        self::BRANCH_CYCLIC => "branch-cyclic",
        self::LINEAR_POLYKETIDE => "linear-polyketide",
        self::CYCLIC_POLYKETIDE => "cyclic-polyketide",
        self::OTHER => "other",
    );

    /** @var array mapping int code to string */
    public static $backValues = array(
        "linear" => self::LINEAR,
        "cyclic" => self::CYCLIC,
        "branched" => self::BRANCH,
        "branch-cyclic" => self::BRANCH_CYCLIC,
        "linear-polyketide" => self::LINEAR_POLYKETIDE,
        "cyclic-polyketide" => self::CYCLIC_POLYKETIDE,
        "other" => self::OTHER,
    );

}
