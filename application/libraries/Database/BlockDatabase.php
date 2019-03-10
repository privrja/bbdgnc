<?php

namespace Bbdgnc\Database;

use Bbdgnc\Base\Logger;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Smiles\Graph;

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

    public function insertMore(array $tos) {
        $this->controller->block_model->insertMore($tos);
    }

    public function findBlockByUniqueSmiles($smiles) {
        $graph = new Graph($smiles);
        return $this->controller->block_model->getBlockByUniqueSmiles($graph->getUniqueSmiles());
    }

    public function startTransaction() {
        $this->controller->block_model->startTransaction();
    }

    public function endTransaction() {
        $this->controller->block_model->endTransaction();
    }

    public function commit() {
        $this->controller->block_model->commit();
    }

    public function rollback() {
        $this->controller->block_model->rollback();
    }

}
