<?php

namespace Bbdgnc\Base;

use Bbdgnc\Enum\PeriodicTableSingleton;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Enum\LossesEnum;
use Bbdgnc\Smiles\Graph;

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

    /**
     * Get Formula from SMILES
     * @param string $strSmiles SMILES
     * @param int $losses
     * @return string formula
     */
    public static function formulaFromSmiles(string $strSmiles, int $losses = LossesEnum::NONE) {
        $graph = new Graph($strSmiles);
        return $graph->getFormula($losses);
    }

    private static function readNumber($strFormula, $intLength, &$intIndex) {
        if ($intIndex >= $intLength) {
            return 1;
        }
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
        if (empty($strCount)) {
            return 1;
        } else {
            return $strCount;
        }
    }

    private static function readLiteral($strFormula, $intLength, &$intIndex) {
        $strName = "";
        $intFirstIndex = $intIndex;
        while (!is_numeric($strFormula[$intIndex])) {
            if ($intIndex > $intFirstIndex && ctype_upper($strFormula[$intIndex])) {
                return $strName;
            }
            $strName .= $strFormula[$intIndex];
            $intIndex++;
            if ($intIndex >= $intLength) {
                break;
            }
        }
        if (empty($strName)) {
            throw new IllegalArgumentException();
        } else {
            return $strName;
        }
    }
}