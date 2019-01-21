<?php

namespace Bbdgnc\Enum;

class SequenceTypeEnum {

    const LINEAR = 0;

    const CYCLIC = 1;

    const BRANCH = 2;

    const BRANCH_CYCLIC = 3;

    const LINEAR_POLYKETIDE = 4;

    const CYCLIC_POLYKETIDE = 5;

    const OTHER = "other";

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

}