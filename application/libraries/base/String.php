<?php

namespace Bbdgnc\Base;

class StringObject {

    public $value = "";

    /**
     * StringObject constructor.
     * @param string $value
     */
    public function __construct(string $value) {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function toString(): string {
        return $this->value;
    }

}
