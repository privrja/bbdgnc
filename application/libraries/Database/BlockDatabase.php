<?php

namespace Bbdgnc\Database;

class BlockDatabase extends AbstractDatabase {

    public function findMergeBlocks($page) {
        $data = [];
        $results = $this->controller->block_model->findGroupByFormula($page);
        foreach ($results as $formula) {
            $data[] = $this->controller->block_model->findByFormula($formula['residue']);
        }
        return $data;
    }

    public function findAllPaging($start) {
        return $this->controller->block_model->findAllPaging($start);
    }

    public function findAllPagingCount() {
        return $this->controller->block_model->findAllPagingCount();
    }

    public function findGroupByFormulaCount() {
        return $this->controller->block_model->findGroupByFormulaCount();
    }

    public function findById($id) {
        return $this->controller->block_model->findById($id);
    }

    public function update($id, $to) {
        $this->controller->block_model->update($id, $to);
    }

    public function insert($blockTO) {
        $this->controller->block_model->insert($blockTO);
    }


}
