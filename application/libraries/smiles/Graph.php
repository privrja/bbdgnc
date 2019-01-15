<?php

namespace Bbdgnc\Smiles;


use Bbdgnc\Enum\PeriodicTableSingleton;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Parser\SmilesParser;

class Graph {

    private $arNodes = array();

    /**
     * Graph constructor.
     * @param string $strText
     */
    public function __construct(string $strText) {
        $this->buildGraph($strText);
    }

    public function addNode(string $elementName) {
        $this->arNodes[] = new Node(PeriodicTableSingleton::getInstance()->getAtoms()[$elementName]);
    }

    public function addBond(int $nodeIndex, Bond $bond) {
        $this->arNodes[$nodeIndex]->addBond($bond);
    }

    private function buildGraph($strText) {
        $smilesParser = new SmilesParser($this);
        $result = $smilesParser->parse($strText);
        if (!$result->isAccepted()) {
            throw new IllegalArgumentException();
        }
    }

    public function toString() {
        $str = "";
        $intIndex = 0;
        /** @var Node $node */
        foreach ($this->arNodes as $node) {
            $str .= '[' . $intIndex . '] ' . $node->getAtom()->getName() . ' => ';
            /** @var Bond $bond */
            foreach ($node->getBonds() as $bond) {
                $str .= $bond->getNodeNumber() . ' ';
            }
            $str .= PHP_EOL;
            $intIndex++;
        }
        return $str;
    }

    /**
     * @return array
     */
    public function getArNodes(): array {
        return $this->arNodes;
    }

}
