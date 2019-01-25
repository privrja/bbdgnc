<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Exception\IllegalArgumentException;

class Charge {

    /** @var string sign of charge '+' | '-' | '' */
    private $sign;

    /** @var int size of charge */
    private $chargeSize;

    /**
     * Charge constructor.
     * @param string $sign
     * @param int $charge charge size positive int
     */
    public function __construct(string $sign = "", int $charge = 0) {
        if (($sign !== '+' && $sign !== '-' && !empty($sign)) || $charge < 0) {
            throw new IllegalArgumentException();
        }
        $this->sign = $sign;
        $this->chargeSize = $charge;
    }

    /**
     * @return string
     */
    public function getSign(): string {
        return $this->sign;
    }

    /**
     * @return int
     */
    public function getChargeSize(): int {
        return $this->chargeSize;
    }

    public function getCharge() {
        if ($this->chargeSize === 0) {
            return "";
        }
        return $this->sign . $this->chargeSize;
    }


}
