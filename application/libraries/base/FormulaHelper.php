<?php

namespace Bbdgnc\Base;

use Bbdgnc\Enum\PeriodicTableSingleton;
use Bbdgnc\Exception\IllegalArgumentException;

class FormulaHelper {

    /**
     * Compute mass
     * @param string $strFormula
     * @return float
     */
    public static function computeMass($strFormula) {
        if (!isset($strFormula) || empty($strFormula)) {
            throw new IllegalArgumentException();
        }
        $intLength = strlen($strFormula);
        if ($intLength == 1) {
            throw new IllegalArgumentException();
        }
        $mass = $intIndex = 0;
        while ($intIndex < $intLength) {
            $strName = self::readLiteral($strFormula, $intLength, $intIndex);
            $strCount = self::readNumber($strFormula, $intLength, $intIndex);
            try {
                $mass += (PeriodicTableSingleton::getInstance()->getAtoms())[$strName]->getMass() * $strCount;
            } catch (\Exception $exception) {
                throw new IllegalArgumentException();
            }
        }
        return $mass;
    }

    public static function formulaFromSmiles($strSmiles) {
        $arMap = [];



    }

    private static function readNumber($strFormula, $intLength, &$intIndex) {
        if ($strFormula[$intIndex] == "0") {
            throw new IllegalArgumentException();
        }
        $strCount = "";
        while (is_numeric($strFormula[$intIndex])) {
            $strCount .= $strFormula[$intIndex];
            $intIndex++;
            if ($intIndex >= $intLength) {
                break;
            }
        }
        return $strCount;
    }

    private static function readLiteral($strFormula, $intLength, &$intIndex) {
        $strName = "";
        while (!is_numeric($strFormula[$intIndex])) {
            $strName .= $strFormula[$intIndex];
            $intIndex++;
            if ($intIndex >= $intLength) {
                throw new IllegalArgumentException();
            }
        }
        return $strName;
    }
}