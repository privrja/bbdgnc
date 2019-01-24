<?php

namespace Bbdgnc\Smiles;


use Bbdgnc\Enum\PeriodicTableSingleton;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Enum\LossesEnum;
use Bbdgnc\Smiles\Parser\SmilesParser;

class Graph {

    /** @var Node[] */
    private $arNodes = array();

    /**
     * Graph constructor.
     * @param string $strText
     */
    public function __construct(string $strText) {
        $this->buildGraph($strText);
    }

    /**
     * Add node to graph, in graph stored as @see \Bbdgnc\Smiles\Node
     * @param string $elementName
     */
    public function addNode(string $elementName) {
        $this->arNodes[] = new Node(PeriodicTableSingleton::getInstance()->getAtoms()[$elementName]);
    }

    /**
     * Add bond (edge) to graph
     * @param int $nodeIndex index of source node
     * @param Bond $bond -> index of target node and type of bond
     */
    public function addBond(int $nodeIndex, Bond $bond) {
        $this->arNodes[$nodeIndex]->addBond($bond);
    }

    /**
     * Add to bonds to graph source <-> target
     * @param int $sourceIndex
     * @param int $targetIndex
     * @param string $bondType
     */
    public function addBidirectionalBond(int $sourceIndex, int $targetIndex, string $bondType) {
        $this->addBond($sourceIndex, new Bond($targetIndex, $bondType));
        $this->addBond($targetIndex, new Bond($sourceIndex, $bondType));
    }

    /**
     * Parse input SMILES and build graph from it
     * @param string $strText
     */
    private function buildGraph($strText) {
        $strText = SmilesBuilder::removeUnnecessaryParentheses($strText);
        $smilesParser = new SmilesParser($this);
        $result = $smilesParser->parse($strText);
        if (!$result->isAccepted()) {
            throw new IllegalArgumentException();
        }
    }

    /**
     * Get formula from graph
     * @param int $losses losses to formula, this value will by subtracted from formula
     * @see LossesEnum
     * @return string formula
     */
    public function getFormula(int $losses) {
        $arMapNodesAndCount = [];
        foreach ($this->arNodes as $node) {
            if (isset($arMapNodesAndCount[PeriodicTableSingleton::H])) {
                $arMapNodesAndCount[PeriodicTableSingleton::H] += $node->hydrogensCount();
            } else {
                $arMapNodesAndCount[PeriodicTableSingleton::H] = $node->hydrogensCount();
            }

            if (isset($arMapNodesAndCount[$node->getAtom()->getName()])) {
                $arMapNodesAndCount[$node->getAtom()->getName()]++;
            } else {
                $arMapNodesAndCount[$node->getAtom()->getName()] = 1;
            }
        }
        $arMapNodesAndCount = LossesEnum::subtractLosses($losses, $arMapNodesAndCount);
        ksort($arMapNodesAndCount);
        $strFormula = "";
        foreach ($arMapNodesAndCount as $key => $value) {
            if ($value === 1) {
                $strFormula .= $key;
            } else {
                $strFormula .= $key . $value;
            }
        }
        return $strFormula;
    }

    /**
     * Return SMILES, now only the argument passed in constructor
     * @return string
     */
    public function getSmiles() {

    }

    public function getUniqueSmiles() {

    }

    public function toString() {
        $str = "";
        $intIndex = 0;
        /** @var Node $node */
        foreach ($this->arNodes as $node) {
            $str .= '[' . $intIndex . '] ' . $node->getAtom()->getName() . ' H' . $node->hydrogensCount() . ' => ';
            /** @var Bond $bond */
            foreach ($node->getBonds() as $bond) {
                $str .= $bond->getBondTypeString() . $bond->getNodeNumber() . ' ';
            }
            $str .= PHP_EOL;
            $intIndex++;
        }
        return $str;
    }

}
