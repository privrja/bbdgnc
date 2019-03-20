<?php

namespace Bbdgnc\Base;

class BetweenFilter extends Filterable {

    private $valueFrom;

    private $valueTo;

    /**
     * BetweenFilter constructor.
     * @param $item
     * @param $valueFrom
     * @param $valueTo
     */
    public function __construct($item, $valueFrom, $valueTo) {
        parent::__construct($item);
        $this->valueFrom = $valueFrom;
        $this->valueTo = $valueTo;
    }

    public function query($model) {
        $model->db->where($this->item . ' >=', $this->valueFrom);
        $model->db->where($this->item . ' <=', $this->valueTo);
    }

}
