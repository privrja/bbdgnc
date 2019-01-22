<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Smiles\Enum\BondTypeEnum;

class Bond {

    private $nodeNumber;

    private $bondType;

    /**
     * Bound constructor.
     * @param $nodeNumber
     * @param $bondType
     */
    public function __construct($nodeNumber, $bondType) {
        $this->nodeNumber = $nodeNumber;
        $this->bondType = BondTypeEnum::$values[$bondType];
    }

    /**
     * @return mixed
     */
    public function getNodeNumber() {
        return $this->nodeNumber;
    }

    /**
     * @return mixed
     */
    public function getBondType() {
        return $this->bondType;
    }

    public function getBondTypeString() {
        return BondTypeEnum::$backValues[$this->bondType];
    }

}
