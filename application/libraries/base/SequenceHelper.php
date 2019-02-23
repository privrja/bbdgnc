<?php

namespace Bbdgnc\Base;

class SequenceHelper {

    /**
     * @param string $sequence
     * @param int $lastAcronym
     * @param string $acronym
     * @return string
     */
    public static function replaceSequence(string $sequence, int $lastAcronym, string $acronym): string {
        if ("" === $lastAcronym) {
            return $sequence;
        }
        return self::replace($sequence, $lastAcronym, $acronym);
    }

    private static function replace(string $sequence, string $lastAcronym, string $acronym) {
        $length = strlen($lastAcronym);
        $index = strpos($sequence, "[" . $lastAcronym . "]");
        if ($index === false) {
            return $sequence;
        }
        $index++;
        $left = substr($sequence, 0, $index);
        $right = substr($sequence, $index + $length);
        return $left . $acronym . $right;
    }

}
