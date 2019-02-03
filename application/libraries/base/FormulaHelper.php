<?php

namespace Bbdgnc\Base;

use Bbdgnc\Enum\PeriodicTableSingleton;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Enum\LossesEnum;
use Bbdgnc\Smiles\Graph;
use Bbdgnc\Smiles\Parser\AtomParser;
use Bbdgnc\Smiles\Parser\NatParser;

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
        $mass = 0;
        while (!empty($strFormula)) {
            $atomParser = new AtomParser();
            $result = $atomParser->parse($strFormula);
            if (!$result->isAccepted()) {
                throw new IllegalArgumentException();
            }
            $strFormula = $result->getRemainder();
            $strName = $result->getResult();
            $strCount = 1;
            $numberParser = new NatParser();
            $numberResult = $numberParser->parse($strFormula);
            if ($numberResult->isAccepted()) {
                $strCount = $numberResult->getResult();
                $strFormula = $numberResult->getRemainder();
            }
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

}