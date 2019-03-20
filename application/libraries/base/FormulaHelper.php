<?php

namespace Bbdgnc\Base;

use Bbdgnc\Enum\PeriodicTableSingleton;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Enum\LossesEnum;
use Bbdgnc\Smiles\Graph;
use Bbdgnc\Smiles\Parser\AtomParser;
use Bbdgnc\Smiles\Parser\IntParser;
use Bbdgnc\TransportObjects\AtomCount;

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
            $atomCount = self::getAtomCount($strFormula);
            try {
                $mass += (PeriodicTableSingleton::getInstance()->getAtoms())[$atomCount->getAtom()]->getMass() * $atomCount->getCount();
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

    public static function formulaWithLosses(string $strFormula, int $losses = LossesEnum::NONE) {
        if (!isset($strFormula) || empty($strFormula)) {
            throw new IllegalArgumentException();
        }
        $arMap = [];
        while (!empty($strFormula)) {
            $atomCount = self::getAtomCount($strFormula);
            $arMap[$atomCount->getAtom()] = $atomCount->getCount();
        }
        return self::formulaExtractLosses($arMap, $losses);
    }

    private static function getAtomCount(string &$strFormula) {
        $atomParser = new AtomParser();
        $result = $atomParser->parse($strFormula);
        if (!$result->isAccepted()) {
            throw new IllegalArgumentException();
        }
        $strFormula = $result->getRemainder();
        $strName = $result->getResult();
        $strCount = 1;
        $numberParser = new IntParser();
        $numberResult = $numberParser->parse($strFormula);
        if ($numberResult->isAccepted()) {
            $strCount = $numberResult->getResult();
            $strFormula = $numberResult->getRemainder();
        }
        return new AtomCount($strName, $strCount);
    }

    public static function formulaExtractLosses($arMap, $losses) {
        $arMap = LossesEnum::subtractLosses($losses, $arMap);
        ksort($arMap);
        $strFormulaResult = "";
        foreach ($arMap as $key => $value) {
            if ($value === 1) {
                $strFormulaResult .= $key;
            } else {
                $strFormulaResult .= $key . $value;
            }
        }
        return $strFormulaResult;
    }

    public static function genericSmiles(string $smiles) {
        $stack = [];
        $smilesNext = str_split($smiles);
        foreach ($smilesNext as $smile) {
            switch ($smile) {
                case ']':
                    $stack = self::isoText($stack);
                    break;
                case '/':
                case '\\':
                    break;
                case ')':
                    $index = sizeof($stack) - 1;
                    if ($stack[$index] === '(') {
                        array_pop($stack);
                    } else {
                        array_push($stack, $smile);
                    }
                    break;
                default:
                    array_push($stack, $smile);
                    break;
            }
        }
        return implode('', $stack);
    }

    public static function isoText($stack) {
        $text = [];
        $c = ']';
        $last = '';
        while ($c != '[') {
            switch ($c) {
                case '@':
                    break;
                case 'H':
                    if ($last !== '@') {
                        array_unshift($text, $c);
                    }
                    break;
                default:
                    array_unshift($text, $c);
                    break;
            }
            $last = $c;
            $c = array_pop($stack);
        }
        array_unshift($text, '[');
        if (sizeof($text) === 3 && $text[1] === 'H') {
            $text = [];
        }
        if (sizeof($text) === 3) {
            $text = [$text[1]];
        }
        if (sizeof($text) === 4) {
            $text = [$text[1]];
        }
        return array_merge($stack, $text);
    }

}
