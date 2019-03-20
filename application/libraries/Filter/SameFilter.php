<?php

namespace Bbdgnc\Base;

class SameFilter extends Filterable {

    private $value;

    /**
     * SameFilter constructor.
     * @param $value
     */
    public function __construct($item, $value) {
        parent::__construct($item);
        $this->value = $value;
    }

    public function query($model) {
        $model->db->where($this->item, $this->value);
    }

}
