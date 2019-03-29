<?php

namespace Bbdgnc\TransportObjects;

class IdOrder {

    /** @var int $id */
    public $id;

    /** @var int[] $order*/
    public $order;

    /**
     * IdOrder constructor.
     * @param int $id
     * @param int[] $order
     */
    public function __construct(int $id, array $order) {
        $this->id = $id;
        $this->order = $order;
    }

}
