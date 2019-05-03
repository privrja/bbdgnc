<?php

namespace Bbdgnc\Enum;

/**
 * Class ModificationHelperTypeEnum
 * Modification helper for helping proccesing modification in while cycle
 * @package Bbdgnc\Enum
 */
class ModificationHelperTypeEnum {

    const S = 's';
    const N = 'n';
    const C = 'c';
    const B = 'b';
    const E = 'e';

    public static function startModification(int $sequenceType) {
        return self::changeBranchChar(self::S, $sequenceType);
    }

    public static function isEnd(string $branchChar) {
        return self::E === $branchChar;
    }

    public static function changeBranchChar(string $branchChar, int $sequenceType) {
        if ($branchChar === self::S && ($sequenceType === SequenceTypeEnum::LINEAR || $sequenceType === SequenceTypeEnum::LINEAR_POLYKETIDE)) {
            return self::N;
        } else if ($branchChar === self::N && ($sequenceType === SequenceTypeEnum::LINEAR || $sequenceType === SequenceTypeEnum::LINEAR_POLYKETIDE)) {
            return self::C;
        } else if ($branchChar === self::S && ($sequenceType === SequenceTypeEnum::BRANCH) || $sequenceType === SequenceTypeEnum::OTHER) {
            return self::N;
        } else if ($branchChar === self::N && ($sequenceType === SequenceTypeEnum::BRANCH) || $sequenceType === SequenceTypeEnum::OTHER) {
            return self::C;
        } else if ($branchChar === self::C && ($sequenceType === SequenceTypeEnum::BRANCH) || $sequenceType === SequenceTypeEnum::OTHER) {
            return self::B;
        } else if ($branchChar === self::S && $sequenceType === SequenceTypeEnum::BRANCH_CYCLIC) {
            return self::B;
        }
        return self::E;
    }

}
