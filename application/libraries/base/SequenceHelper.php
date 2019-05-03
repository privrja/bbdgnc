<?php

namespace Bbdgnc\Base;

use Bbdgnc\Exception\IllegalArgumentException;

/**
 * Class SequenceHelper
 * Helper for CycloBranch sequence notation
 * @package Bbdgnc\Base
 */
class SequenceHelper {

    const WRONG_SEQUENCE = 'Wrong sequence ';
    const LEFT_BRACKET = "[";
    const RIGHT_BRACKET = "]";

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
        $index = strpos($sequence, self::LEFT_BRACKET . $lastAcronym . self::RIGHT_BRACKET);
        if ($index === false) {
            return $sequence;
        }
        $index++;
        $left = substr($sequence, 0, $index);
        $right = substr($sequence, $index + $length);
        return $left . $acronym . $right;
    }

    /**
     * Get block names from sequence entry
     * @param $strSequence
     * @return array
     */
    public static function getBlockAcronyms(string $strSequence) {
        $sequence = str_split($strSequence);
        $boolMeasure = false;
        $blocks = [];
        $block = '';
        while($sequence != []) {
            $character = array_shift($sequence);
            switch ($character) {
                case self::LEFT_BRACKET:
                    if ($boolMeasure) {
                        throw new IllegalArgumentException(self::WRONG_SEQUENCE . $strSequence);
                    }
                    $boolMeasure = true;
                    $block = '';
                    break;
                case self::RIGHT_BRACKET:
                    if (!$boolMeasure) {
                        throw new IllegalArgumentException(self::WRONG_SEQUENCE . $strSequence);
                    }
                    $boolMeasure = false;
                    $blocks[] = $block;
                    break;
                default:
                    if ($boolMeasure) {
                        $block .= $character;
                    }
                    break;
            }
        }
        return $blocks;
    }

    private function arraySequence(string $sequence) {
        if ($sequence == "") {
            return "";
        }
        $arOut = [];
        $arSequenceInput = explode('', $sequence);
        $char = array_pop($arSequenceInput);
        while (empty($arSequenceInput)) {
            switch ($char) {
                case '\\':
                case '[':
                case ']':
                case '(':
                case ')':
                case '-':
                    break;
                default:
                    $arOut[] = $char;
                    break;
            }
            $char = array_pop($arSequenceInput);
        }
        return$arOut;
    }


}
