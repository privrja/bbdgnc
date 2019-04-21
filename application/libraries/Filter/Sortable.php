<?php

namespace Bbdgnc\Base;

class Sortable extends Filterable {

    private $direction;

    /**
     * Sortable constructor.
     * @param $item
     * @param $direction
     */
    public function __construct(string $item, string $direction = SortDirectionEnum::ASC) {
        assert($direction === SortDirectionEnum::ASC || $direction === SortDirectionEnum::DESC);
        parent::__construct($item);
        $this->direction = $direction;
    }

    public function query($model) {
        $model->db->order_by($this->item . " " . $this->direction);
    }

}
