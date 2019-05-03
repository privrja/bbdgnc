<?php

namespace Bbdgnc\Base;

/**
 * Class Query
 * class for generating reducing query of filters or sort
 * @package Bbdgnc\Base
 */
class Query {

    /** @var Sortable[] */
    private $sortables = [];

    /** @var Filterable[] */
    private $filterables = [];

    public function addSortable(Sortable $sortable) {
        array_push($this->sortables, $sortable);
    }

    public function addFilterable(Filterable $filterable) {
        array_push($this->filterables, $filterable);
    }

    public function applyQuery($model) {
        foreach ($this->filterables as $filterable) {
            $filterable->query($model);
        }

        foreach ($this->sortables as $sortable) {
            $sortable->query($model);
        }
    }

}
