<?php

namespace Bbdgnc\Base;

abstract class Filterable {

    /** @var string $item */
    protected $item;

    /**
     * Filterable constructor.
     * @param string $item
     */
    public function __construct(string $item) {
        assert($item !== "");
        $this->item = $item;
    }

    public abstract function query($model);

}
