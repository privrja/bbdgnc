<?php

namespace Bbdgnc\Database;

use Bbdgnc\base\Query;
use Bbdgnc\Base\Sortable;
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

    public function findAll(Query $query) {
        return $this->controller->block_model->findAll($query);
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

    public function findByAcronym($acronym) {
        return $this->controller->block_model->findByAcronym($acronym);
    }

    public function update($id, $to) {
        $this->controller->block_model->update($id, $to);
    }

    public function insert($blockTO) {
        return $this->controller->block_model->insert($blockTO);
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

    public function findAllSelect() {
        $query = new Query();
        $query->addSortable(new Sortable('acronym'));
        $blocksAll = $this->findAll($query);
        $blocks = ['None'];
        foreach ($blocksAll as $block) {
            $blocks[$block['id']] = $block['acronym'];
        }
        return $blocks;
    }

}
