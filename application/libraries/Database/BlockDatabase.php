<?php

namespace Bbdgnc\Database;

use Bbdgnc\Base\AminoAcidsHelper;
use Bbdgnc\Base\Query;
use Bbdgnc\Base\Sortable;
use Bbdgnc\Exception\DeleteException;
use Bbdgnc\Exception\IllegalArgumentException;
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

    public function findAllPaging($start, Query $query) {
        return $this->controller->block_model->findAllPaging($start, $query);
    }

    public function findAllPagingCount(Query $query) {
        return $this->controller->block_model->findAllPagingCount($query);
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
        try {
            $graph = new Graph($smiles);
            return $this->controller->block_model->getBlockByUniqueSmiles($graph->getUniqueSmiles());
        } catch (IllegalArgumentException $e) {
            return $this->controller->block_model->findBlockBySmiles($smiles);
        }
    }

    private function findBlockBySmiles($smiles) {
        return $this->controller->block_model->findBlockBySmiles($smiles);
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

    public function delete($id, $database = null) {
        $result = $this->controller->blockToSequence_model->findBlockUsage($id);
        if (empty($result)) {
            $this->controller->block_model->delete($id);
        } else {
            throw new DeleteException("Block is used!");
        }
    }

    public function deleteAll() {
        $this->controller->sequence_model->deleteAll();
        $this->controller->blockToSequence_model->deleteAll();
        $this->controller->modification_model->deleteAll();
        $this->controller->block_model->deleteAll();
    }

    public function resetWithAminoAcids() {
       $this->deleteAll();
       $aminoAcids = AminoAcidsHelper::getAminoAcids();
       $this->controller->block_model->insertMore($aminoAcids);
    }

    public function resetAminoAcidsWithModifications() {
        $this->deleteAll();
        $aminoAcids = AminoAcidsHelper::getAminoAcids();
        $modifications = AminoAcidsHelper::getDefaultModifications();
        $this->controller->block_model->insertMore($aminoAcids);
        $this->controller->modification_model->insertMore($modifications);
    }

    public function resetWithModifications() {
        $this->deleteAll();
        $modifications = AminoAcidsHelper::getDefaultModifications();
        $this->controller->modification_model->insertMore($modifications);
    }

}
