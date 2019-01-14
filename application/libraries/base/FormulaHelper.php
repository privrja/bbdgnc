<?php

namespace Bbdgnc\Base;

use Bbdgnc\Enum\PeriodicTableSingleton;

class FormulaHelper {

    /**
     * Compute mass
     * @param String $strFormula
     * @return float
     */
    public static function computeMass($strFormula) {
        if (!isset($strFormula) || empty($strFormula)) {
            throw new \InvalidArgumentException();
        }
        $intLength = strlen($strFormula);
        if ($intLength == 1) {
            throw new \InvalidArgumentException();
        }
        $mass = $intIndex = 0;
        while ($intIndex < $intLength) {
            $strName = self::readLiteral($strFormula, $intLength, $intIndex);
            $strCount = self::readNumber($strFormula, $intLength, $intIndex);
            try {
                $mass += (PeriodicTableSingleton::getInstance()->getAtoms())[$strName]->getMass() * $strCount;
            } catch (\Exception $exception) {
                throw new \InvalidArgumentException();
            }
        }
        return $mass;
    }

    private static function readNumber($strFormula, $intLength, &$intIndex) {
        if($strFormula[$intIndex] == "0") {
            throw new \InvalidArgumentException();
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
                throw new \InvalidArgumentException();
            }
        }
        return $strName;
    }
}