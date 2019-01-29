<?php

namespace Bbdgnc\Smiles;


use Bbdgnc\Enum\PeriodicTableSingleton;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Exception\IllegalStateException;
use Bbdgnc\Exception\NotFoundException;
use Bbdgnc\Smiles\Enum\BondTypeEnum;
use Bbdgnc\Smiles\Enum\LossesEnum;
use Bbdgnc\Smiles\Enum\VertexStateEnum;
use Bbdgnc\Smiles\Parser\SmilesParser;

class Graph {

    /** @var Node[] */
    private $arNodes = array();

    /** @var string $uniqueSmiles */
    private $uniqueSmiles = "";

    /** @var bool $isCyclic */
    private $isCyclic = false;

    /** @var bool $isSecondPass */
    private $isSecondPass = false;

    /** @var int $digit */
    private $digit = 1;

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
        $this->dfs($startVertexIndex);
        if ($this->isCyclic) {
            $this->isSecondPass = true;
            $this->dfsInitialization();
            $this->dfs($startVertexIndex);
        }
        return $this->uniqueSmiles;
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
        $this->uniqueSmiles = "";
        $this->isCyclic = false;
        $this->digit = 1;
        $min = $this->arNodes[0]->getCangenStructure()->getRank();
        $index = $minIndex = 0;
        foreach ($this->arNodes as $node) {
            if ($node->getCangenStructure()->getRank() < $min) {
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
     * @param int $nodeNumber
     * @param bool $branch
     * @param string $bond
     * @param int $lastNodeNumber
     */
    private function dfs(int $nodeNumber, $branch = false, $bond = '', $lastNodeNumber = -1) {
        $node = $this->arNodes[$nodeNumber];
        if ($node->getVertexState() === VertexStateEnum::OPEN) {
            $this->isCyclic = true;
            $node->addDigit(new Digit($this->digit));
            $this->arNodes[$lastNodeNumber]->addDigit(new Digit($this->digit));
            $this->digit++;
        }
        if ($node->getVertexState() !== VertexStateEnum::NOT_FOUND) {
            return;
        }

        $node->setVertexState(VertexStateEnum::OPEN);
        if ($branch) {
            $this->uniqueSmiles .= '(';
        }
        $this->uniqueSmiles .= $bond;
        $this->uniqueSmiles .= $node->getAtom()->elementSmiles();

        $printedDigits = 0;
        if (!$node->isDigitsEmpty()) {
            foreach ($node->getDigits() as $digit) {
                foreach ($node->getBonds() as $bond) {
                    if ($this->isDigitIn($digit->getDigit(), $this->arNodes[$bond->getNodeNumber()]->getDigits())) {
                        $this->findRings($nodeNumber, $bond->getNodeNumber(), $digit);
                    }
                }
                $newDigit = null;
                try {
                    $newDigit = $this->findDigit($digit->getDigit(), $this->arNodes[$nodeNumber]->getDigits());
                } catch (NotFoundException $exception) {
                    throw new IllegalStateException();
                }
                $this->uniqueSmiles .= $newDigit->printDigit();
                $printedDigits++;
            }
        }

        $heap = null;
        if ($this->isSecondPass && $node->isInRing()) {
            $heap = new RankBondMinHeap();
        } else {
            $heap = new RankMinHeap();
        }
        foreach ($node->getBonds() as $bond) {
            if ($lastNodeNumber == $bond->getNodeNumber()) {
                continue;
            }
            $heap->insert(new NextNode($bond->getNodeNumber(), $bond->getBondTypeString(),
                $this->arNodes[$bond->getNodeNumber()]->getCangenStructure()->getRank()));
        }

        while (!$heap->isEmpty()) {
            /** @var NextNode $nextNode */
            $heapCount = $heap->count();
            $nextNode = $heap->extract();
            $this->dfs($nextNode->getNodeIndex(), $heapCount - $printedDigits > 1, $nextNode->getBondType(), $nodeNumber);
        }
        if ($branch) {
            $this->uniqueSmiles .= ')';
        }
        $node->setVertexState(VertexStateEnum::CLOSED);
    }

    /**
     * @param int $digit
     * @param Digit[] $digits
     * @return Digit
     * @throws NotFoundException
     */
    private function findDigit(int $digit, array $digits) {
        foreach ($digits as $aDigit) {
            if ($aDigit->getDigit() === $digit) {
                return $aDigit;
            }
        }
        throw new NotFoundException();
    }

    /**
     * @param int $digit
     * @param Digit[] $digits
     * @return bool
     */
    private function isDigitIn(int $digit, array $digits) {
        foreach ($digits as $aDigit) {
            if ($aDigit->getDigit() === $digit) {
                return true;
            }
        }
        return false;
    }


    public function findRings(int $start, int $finish, Digit $digit) {
        if ($digit->isAccepted()) {
            return;
        }
        $queue = new \SplQueue();
        $firstPath = [$start];
        $queue->push($firstPath);
        $firstPass = true;

        while (!$queue->isEmpty()) {
            $path = $queue->pop();
            $last = end($path);
            if ($last === $finish) {
                $nodeStart = $this->arNodes[$start];
                foreach ($nodeStart->getBonds() as $bond) {
                    if ($bond->getNodeNumber() === $finish) {
                        if (BondTypeEnum::isMultipleBinding($bond->getBondTypeString())) {
                            $this->arNodes[$finish]->deleteDigit($digit->getDigit());
                            $index = 0;
                            $setNumber = false;
                            foreach ($this->arNodes[$path[$index]]->getBonds() as $nextBond) {
                                if ($nextBond->getNodeNumber() === $path[$index + 1] && BondTypeEnum::isSimple($nextBond->getBondTypeString())) {
                                    $this->arNodes[$path[$index + 1]]->addDigit(new Digit($digit->getDigit(), true));
                                    $setNumber = true;
                                    break;
                                }
                            }
                            if (!$setNumber) {
                                $this->arNodes[$start]->deleteDigit($digit->getDigit());
                                $newDigit = new Digit($digit->getDigit(), true, $bond->getBondTypeString());
                                $this->arNodes[$start]->addDigit($newDigit);
                                $this->arNodes[$finish]->addDigit($newDigit);
                            }
                        }
                        break;
                    }
                }
                foreach ($path as $nodeNumber) {
                    $this->arNodes[$nodeNumber]->setInRing(true);
                }
                continue;
            }
            $node = $this->arNodes[$last];
            foreach ($node->getBonds() as $bond) {
                if ($firstPass && $bond->getNodeNumber() === $finish) {
                    $firstPass = false;
                    continue;
                }
                if (!in_array($bond->getNodeNumber(), $path)) {
                    $newPath = $path;
                    $newPath[] = $bond->getNodeNumber();
                    $queue->push($newPath);
                }
            }
        }

    }

}
