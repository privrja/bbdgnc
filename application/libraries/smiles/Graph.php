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
    private $arNodes = [];

    /** @var string $uniqueSmiles */
    private $uniqueSmiles = "";

    /** @var bool $isCyclic */
    private $isCyclic = false;

    /** @var bool $isSecondPass */
    private $isSecondPass = false;

    /** @var int $digit */
    private $digit = 1;

    /** @var OpenNumbersSort $openNumbersSort */
    private $openNumbersSort;

    /** @var int 0 */
    private const ZERO = 0;

    /** @var int 1 */
    private const ONE = 1;

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
    public function addNode(Element $element): void {
        $this->arNodes[] = new Node($element);
    }

    /**
     * Add bond (edge) to graph
     * @param int $nodeIndex index of source node
     * @param Bond $bond -> index of target node and type of bond
     */
    public function addBond(int $nodeIndex, Bond $bond): void {
        $this->arNodes[$nodeIndex]->addBond($bond);
    }

    /**
     * Add to bonds to graph source <-> target
     * @param int $sourceIndex
     * @param int $targetIndex
     * @param string $bondType
     */
    public function addBidirectionalBond(int $sourceIndex, int $targetIndex, string $bondType): void {
        $this->addBond($sourceIndex, new Bond($targetIndex, $bondType));
        $this->addBond($targetIndex, new Bond($sourceIndex, $bondType));
    }

    /**
     * Parse input SMILES and build graph from it
     * @param string $strText
     */
    private function buildGraph($strText): void {
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
    public function getFormula(int $losses): string {
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

    public function getUniqueSmiles(): string {
        $this->cangen();
        $this->genes();
        return $this->uniqueSmiles;
    }

    public function genes(): void {
        $this->openNumbersSort = new OpenNumbersSort();
        $startVertexIndex = $this->dfsInitialization();
        $this->dfs($startVertexIndex);
        if ($this->isCyclic) {
            $this->isSecondPass = true;
            $this->dfsInitialization();

            foreach ($this->openNumbersSort->getNodes() as $number) {
                if ($number->isInPair()) {
                    for ($index = $number->getLength() - 1; $index == 0; --$index) {
                        $this->arNodes[$number->getNodeNumber()]->addDigit(new Digit($number->getNexts()[$index]->getFirst()));
                    }
                    $this->arNodes[$number->getNodeNumber()]->addDigit(new Digit($number->getNumber()));
                }
            }
            $this->dfs($startVertexIndex);
        }
    }

    public function cangen(): void {
        $nodesLength = sizeof($this->arNodes);
        $this->computeInvariants();
        $this->rankInvariants();
        while (true) {
            while (true) {
                $this->rankToPrimes();
                $this->productPrimes();
                $this->rankByPrimes();
                if ($this->ranksEquals()) {
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
    public function rankInvariants(): void {
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

    public function rankToPrimes(): void {
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

    public function productPrimes(): void {
        foreach ($this->arNodes as $node) {
            $product = 1;
            foreach ($node->getBonds() as $bond) {
                $product *= $this->arNodes[$bond->getNodeNumber()]->getCangenStructure()->getRank();
            }
            $node->getCangenStructure()->setProductPrime($product);
        }
    }

    public function rankByPrimes(): void {
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
     * Return true when ranks are same for all nodes
     * otherwise return false
     * @return bool
     */
    public function ranksEquals(): bool {
        foreach ($this->arNodes as $node) {
            if (!$node->getCangenStructure()->isRankSameAsLastRank()) {
                return false;
            }
        }
        return true;
    }

    public function computeInvariants(): void {
        foreach ($this->arNodes as $node) {
            $node->computeInvariants();
        }
    }

    public function maxRank(): int {
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

    public function minRankIndex(): int {
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

    private function breakTies(): void {
        foreach ($this->arNodes as $node) {
            $node->setInvariant($node->getCangenStructure()->getRank() * 2);
        }
        $minIndex = $this->minRankIndex();
        $rank = $this->arNodes[$minIndex]->getCangenStructure()->getRank() * 2 - 1;
        $this->arNodes[$minIndex]->setInvariant($rank);
        $this->rankInvariants();
    }

    public function getNodes(): array {
        return $this->arNodes;
    }

    public function toString(): string {
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
    private function dfsInitialization(): int {
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
    private function dfs(int $nodeNumber, $branch = false, $bond = '', $lastNodeNumber = -1): void {
        $node = $this->arNodes[$nodeNumber];
        if ($node->getVertexState() === VertexStateEnum::OPEN) {
            if (!$this->isSecondPass) {
                $this->isCyclic = true;
//                $node->addDigit(new Digit($this->digit));
//                $this->arNodes[$lastNodeNumber]->addDigit(new Digit($this->digit));
//                $this->digit++;
                $this->openNumbersSort->addDigit($nodeNumber, $lastNodeNumber);
            }
        }
        if ($node->getVertexState() !== VertexStateEnum::NOT_FOUND) {
            return;
        }

        $this->openNumbersSort->addOpenNode($nodeNumber);
        $node->setVertexState(VertexStateEnum::OPEN);
        $this->printBracket($branch, '(');
        $this->printBondAndAtom($bond, $node);
        $printedDigits = 0;
        if ($this->isSecondPass) {
            $printedDigits = $this->ringClosures($nodeNumber);
        }

        $heap = $this->initializeHeap($node);
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
        $this->printBracket($branch, ')');
        $node->setVertexState(VertexStateEnum::CLOSED);
    }

    /**
     * Add bracket to Unique SMILES, if $branch is true
     * @param bool $branch
     * @param string $bracket
     */
    private function printBracket(bool $branch, string $bracket): void {
        if ($branch) {
            $this->uniqueSmiles .= $bracket;
        }
    }

    /**
     * Find rings and set nodes on ring to isInRing to true, then add digits on actual node to Unique SMILES
     * @param int $nodeNumber number of actual node
     * @return int number of printed numbers
     */
    private function ringClosures($nodeNumber): int {
        $printedDigits = 0;
        $node = $this->arNodes[$nodeNumber];
        if (!$node->isDigitsEmpty()) {
            foreach ($node->getDigits() as $digit) {
                foreach ($node->getBonds() as $bond) {
                    if ($this->isDigitIn($digit->getDigit(), $this->arNodes[$bond->getNodeNumber()]->getDigits())) {
                        $this->findRings($nodeNumber, $bond->getNodeNumber(), $digit);
                        try {
                            $digit = $this->findDigit($digit->getDigit(), $node->getDigits());
                        } catch (NotFoundException $exception) {
                            throw new IllegalStateException();
                        }
                    }
                }
            }
            foreach ($node->getDigits() as $digit) {
                $this->uniqueSmiles .= $digit->printDigit();
                $printedDigits++;
            }
        }
        return $printedDigits;
    }

    /**
     * Return right type of heap
     * when node in ring return RankBondMinHeap
     * otherwise return RankMinHeap
     * @param Node $node
     * @return RankBondMinHeap|RankMinHeap
     */
    private function initializeHeap(Node $node): \SplMinHeap {
        if ($this->isSecondPass && $node->isInRing()) {
            return new RankBondMinHeap();
        } else {
            return new RankMinHeap();
        }
    }

    /**
     * @param int $digit
     * @param Digit[] $digits
     * @return Digit
     * @throws NotFoundException
     */
    private function findDigit(int $digit, array $digits): Digit {
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
    private function isDigitIn(int $digit, array $digits): bool {
        foreach ($digits as $aDigit) {
            if ($aDigit->getDigit() === $digit) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return true when all nodes are aromatic
     * otherwise false
     * @param int[] $path node numbers
     * @return bool
     */
    public function isAromaticRing($path): bool {
        foreach ($path as $nodeNumber) {
            if (!$this->arNodes[$nodeNumber]->getAtom()->isAromatic()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $from node number from
     * @param int $to node number to
     * @return Bond
     * @throws NotFoundException
     */
    private function findBondTo(int $from, int $to): Bond {
        foreach ($this->arNodes[$from]->getBonds() as $bond) {
            if ($bond->getNodeNumber() === $to) {
                return $bond;
            }
        }
        throw new NotFoundException();
    }

    /**
     * Set rings closure
     * @param int $start node number start
     * @param int $finish node number finish
     * @param Digit $digit
     */
    public function findRings(int $start, int $finish, Digit $digit): void {
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
                $this->aromaticRing($path);
                $this->multipleBindingRing($start, $finish, $path, $digit->getDigit());
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

    /**
     * Add bond and atom to Unique SMILES
     * @param string $bond
     * @param Node $node
     */
    private function printBondAndAtom(string $bond, Node $node) {
        $this->uniqueSmiles .= $bond;
        $this->uniqueSmiles .= $node->getAtom()->elementSmiles();
    }

    /**
     * Replace syntax in SMILES c1ccccc1 to C1=CC=CC=C1 in graph notation
     * @param array $path
     */
    private function aromaticRing(array $path) {
        if ($this->isAromaticRing($path)) {
            $pathLength = sizeof($path);
            for ($index = 0; $index < $pathLength; ++$index) {
                $this->arNodes[$path[$index]]->getAtom()->asNonAromatic();
                if ($index % 2 === 0) {
                    try {
                        $bond = $this->findBondTo($path[$index], $path[$index + 1]);
                        $bondBack = $this->findBondTo($path[$index + 1], $path[$index]);
                    } catch (NotFoundException $exception) {
                        throw new IllegalStateException();
                    }
                    $bond->setBondType(BondTypeEnum::DOUBLE);
                    $bondBack->setBondType(BondTypeEnum::DOUBLE);
                }
            }
        }
    }

    /**
     * @param int $nodeNumber
     * @param int $digit
     * @param string $bondType
     */
    private function setNewDigit(int $nodeNumber, int $digit, string $bondType) {
        try {
            if (!$this->findDigit($digit, $this->arNodes[$nodeNumber]->getDigits())->isAccepted()) {
                $this->deleteAndAddDigit($nodeNumber, $digit, $bondType);
            }
        } catch (NotFoundException $exception) {
            throw new IllegalStateException();
        }
    }

    /**
     * @param int $nodeNumber
     * @param int $digit
     * @param string $bondType
     */
    private function deleteAndAddDigit(int $nodeNumber, int $digit, string $bondType) {
        $this->arNodes[$nodeNumber]->deleteDigit($digit);
        $this->arNodes[$nodeNumber]->addDigit(new Digit($digit, true, $bondType));
    }

    /**
     * @param int $start
     * @param int $finish
     * @param array $path
     * @param int $digit
     */
    private function multipleBindingRing(int $start, int $finish, array $path, int $digit) {
        $nodeStart = $this->arNodes[$start];
        foreach ($nodeStart->getBonds() as $bond) {
            if ($bond->getNodeNumber() === $finish) {
                if (BondTypeEnum::isMultipleBinding($bond->getBondTypeString())) {
                    $this->arNodes[$finish]->deleteDigit($digit);
                    $setNumber = false;
                    foreach ($this->arNodes[$path[self::ZERO]]->getBonds() as $nextBond) {
                        if ($nextBond->getNodeNumber() === $path[self::ONE] && BondTypeEnum::isSimple($nextBond->getBondTypeString())) {
                            $this->deleteAndAddDigit($start, $digit, BondTypeEnum::$values[BondTypeEnum::SIMPLE]);
                            $this->arNodes[$path[self::ONE]]->addDigit(new Digit($digit, true));
                            $setNumber = true;
                            break;
                        }
                    }
                    if (!$setNumber) {
                        $this->deleteAndAddDigit($start, $digit, $bond->getBondTypeString());
                        $this->arNodes[$finish]->addDigit(new Digit($digit, true, $bond->getBondTypeString()));
                    }
                } else {
                    $this->setNewDigit($start, $digit, $bond->getBondTypeString());
                    $this->setNewDigit($finish, $digit, $bond->getBondTypeString());
                }
                break;
            }
        }
    }


}
