<?php

namespace Bbdgnc\Smiles;


use Bbdgnc\Enum\PeriodicTableSingleton;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Enum\LossesEnum;
use Bbdgnc\Smiles\Enum\VertexStateEnum;
use Bbdgnc\Smiles\Parser\SmilesParser;

class Graph {

    /** @var Node[] */
    private $arNodes = array();

    private $uniqueSmiles = "";

    /**
     * Graph constructor.
     * @param string $strText
     */
    public function __construct(string $strText) {
        $this->buildGraph($strText);
    }

    /**
     * Add node to graph, in graph stored as @see \Bbdgnc\Smiles\Node
     * @param Element $element
     */
    public function addNode(Element $element) {
        $this->arNodes[] = new Node($element);
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

    public function getUniqueSmiles() {
        $this->cangen();
        return $this->genes();
    }

    public function genes() {
        $startVertexIndex = $this->dfsInitialization();

        $this->dfs($this->arNodes[$startVertexIndex]);


    }

    public function cangen() {
        $nodesLength = sizeof($this->arNodes);
        $this->computeInvariants();
        $this->rankInvariants();
        while (true) {
            while (true) {
                $this->rankToPrimes();
                $this->productPrimes();
                $this->rankByPrimes();
                if ($this->rankEquals()) {
                    break;
                }
            }
            if ($this->maxRank() < $nodesLength) {
                $this->breakTies();
            } else {
                break;
            }
        }
    }

    /**
     * Rank invariants in nodes
     */
    public function rankInvariants() {
        $heap = new \SplMinHeap();
        foreach ($this->arNodes as $node) {
            $heap->insert($node->getInvariant());
        }

        $arMap = [];
        $index = 1;
        while (!$heap->isEmpty()) {
            $min = $heap->extract();
            if (!isset($arMap[$min])) {
                $arMap[$min] = $index;
                $index++;
            }
        }

        foreach ($this->arNodes as $node) {
            $node->getCangenStructure()->setRank($arMap[$node->getInvariant()]);
            $node->getCangenStructure()->setLastRank($arMap[$node->getInvariant()]);
        }
    }

    public function rankToPrimes() {
        $heap = new \SplMinHeap();
        foreach ($this->arNodes as $node) {
            $heap->insert($node->getCangenStructure()->getRank());
            $node->getCangenStructure()->setLastRank($node->getCangenStructure()->getRank());
        }

        $arMap = [];
        $index = 0;
        while (!$heap->isEmpty()) {
            $min = $heap->extract();
            if (!isset($arMap[$min])) {
                $arMap[$min] = Smiles::$primes[$index];
                $index++;
            }
        }

        foreach ($this->arNodes as $node) {
            $node->getCangenStructure()->setRank($arMap[$node->getCangenStructure()->getLastRank()]);
        }
    }

    public function productPrimes() {
        foreach ($this->arNodes as $node) {
            $product = 1;
            foreach ($node->getBonds() as $bond) {
                $product *= $this->arNodes[$bond->getNodeNumber()]->getCangenStructure()->getRank();
            }
            $node->getCangenStructure()->setProductPrime($product);
        }
    }

    public function rankByPrimes() {
        $heap = new CangenMinHeap();
        foreach ($this->arNodes as $node) {
            $heap->insert($node->getCangenStructure());
        }

        $index = 0;
        $lastMin = new CangenStructure();
        while (!$heap->isEmpty()) {
            $min = $heap->extract();
            if ($lastMin->getLastRank() !== $min->getLastRank() || $lastMin->getProductPrime() !== $min->getProductPrime()) {
                $index++;
                $lastMin = $min;
            }
            $min->setRank($index);
        }
    }

    /**
     * Return SMILES, now only the argument passed in constructor
     * @return string
     */
    public function getSmiles() {

    }

    /**
     * Return true when ranks are same for all nodes
     * otherwise return false
     * @return bool
     */
    public function rankEquals() {
        foreach ($this->arNodes as $node) {
            if (!$node->getCangenStructure()->isRankSameAsLastRank()) {
                return false;
            }
        }
        return true;
    }

    public function computeInvariants() {
        foreach ($this->arNodes as $node) {
            $node->computeInvariants();
        }
    }

    public function maxRank() {
        $index = 0;
        $max = $this->arNodes[$index]->getCangenStructure()->getRank();
        foreach ($this->arNodes as $node) {
            if ($node->getCangenStructure()->getRank() > $max) {
                $max = $node->getCangenStructure()->getRank();
            }
            $index++;
        }
        return $max;
    }

    public function minRankIndex() {
        $heap = new \SplMinHeap();
        foreach ($this->arNodes as $node) {
            $heap->insert($node->getCangenStructure()->getRank());
        }

        $lastMin = -1;
        while (!$heap->isEmpty()) {
            $min = $heap->extract();
            if ($lastMin === $min) {
                break;
            }
            $lastMin = $min;
        }

        $index = 0;
        foreach ($this->arNodes as $node) {
            if ($node->getCangenStructure()->getRank() === $lastMin) {
                break;
            }
            $index++;
        }
        return $index;
    }

    private function breakTies() {
        foreach ($this->arNodes as $node) {
            $node->setInvariant($node->getCangenStructure()->getRank() * 2);
        }

        $minIndex = $this->minRankIndex();
        $rank = $this->arNodes[$minIndex]->getCangenStructure()->getRank() * 2 - 1;
        $this->arNodes[$minIndex]->setInvariant($rank);
        $this->rankInvariants();
    }

    public function getNodes() {
        return $this->arNodes;
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

    /**
     * Initialize all nodes to VertexStateEnum::NOT_FOUND
     * and return index of the lowest rank, this point would be the starting point
     * @return int
     */
    private function dfsInitialization() {
        $min = $this->arNodes[0]->getCangenStructure()->getRank();
        $index = $minIndex = 0;
        foreach ($this->arNodes as $node) {
            if ($node->getCangenStructure()->getRank() < $min){
                $min = $node->getCangenStructure()->getRank();
                $minIndex = $index;
            }
            $node->setVertexState(VertexStateEnum::NOT_FOUND);
            $index++;
        }
        return $minIndex;
    }

    /**
     * DFS for UNIQUE SMILES
     * @param Node $node
     */
    private function dfs(Node $node) {
        if ($node->getVertexState() !== VertexStateEnum::NOT_FOUND) {
            return;
        }



    }

}
