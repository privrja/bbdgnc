<?php

namespace Bbdgnc\Database;

use Bbdgnc\Base\AminoAcidsHelper;
use Bbdgnc\Base\Query;
use Bbdgnc\Base\Sortable;
use Bbdgnc\Exception\DeleteException;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Graph;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\ModificationTO;
use Bbdgnc\TransportObjects\SequenceTO;

class BlockDatabase extends AbstractDatabase {

    const INTEGER = 'INTEGER';
    const TEXT = 'TEXT';
    const REAL = 'REAL';

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

        $this->resetDatabase();
    }

    public function resetDatabase() {
//        $this->controller->dbforge->drop_table(BlockTO::TABLE_NAME, true);
//        $this->controller->dbforge->drop_table(SequenceTO::TABLE_NAME, true);
//        $this->controller->dbforge->drop_table(ModificationTO::TABLE_NAME, true);
//        $this->controller->dbforge->drop_table('b2s', true);

        $this->controller->dbforge->add_field("id INTEGER PRIMARY KEY");
        $this->controller->dbforge->add_field("name TEXT NOT NULL CHECK(length(name) > 0)");
        $this->controller->dbforge->add_field("acronym TEXT NOT NULL CHECK(length(acronym) > 0)");
        $this->controller->dbforge->add_field("residue TEXT NOT NULL CHECK(length(residue) > 0)");
        $this->controller->dbforge->add_field("mass REAL");
        $this->controller->dbforge->add_field("losses TEXT");
        $this->controller->dbforge->add_field("smiles TEXT");
        $this->controller->dbforge->add_field("usmiles TEXT");
        $this->controller->dbforge->add_field("database INTEGER");
        $this->controller->dbforge->add_field("identifier TEXT");
        $this->controller->dbforge->create_table(BlockTO::TABLE_NAME, true);

        $this->controller->dbforge->add_field("id INTEGER PRIMARY KEY");
        $this->controller->dbforge->add_field("type TEXT NOT NULL DEFAULT 'other'");
        $this->controller->dbforge->add_field("name TEXT NOT NULL CHECK(length(name) > 0)");
        $this->controller->dbforge->add_field("formula TEXT NOT NULL CHECK(length(formula) > 0)");
        $this->controller->dbforge->add_field("mass REAL");
        $this->controller->dbforge->add_field("sequence TEXT");
        $this->controller->dbforge->add_field("smiles TEXT");
        $this->controller->dbforge->add_field("database INTEGER");
        $this->controller->dbforge->add_field("identifier TEXT");
        $this->controller->dbforge->add_field("decays TEXT");
        $this->controller->dbforge->add_field("n_modification_id INTEGER");
        $this->controller->dbforge->add_field("c_modification_id INTEGER");
        $this->controller->dbforge->add_field("b_modification_id INTEGER");
        $this->controller->dbforge->add_field("FOREIGN KEY (n_modification_id) REFERENCES modification(id)");
        $this->controller->dbforge->add_field("FOREIGN KEY (c_modification_id) REFERENCES modification(id)");
        $this->controller->dbforge->add_field("FOREIGN KEY (b_modification_id) REFERENCES modification(id)");
        $this->controller->dbforge->create_table(SequenceTO::TABLE_NAME, true);

        $this->controller->dbforge->add_field("id INTEGER PRIMARY KEY");
        $this->controller->dbforge->add_field("name TEXT NOT NULL CHECK(length(name) > 0)");
        $this->controller->dbforge->add_field("formula TEXT NOT NULL CHECK(length(formula) > 0)");
        $this->controller->dbforge->add_field("mass REAL");
        $this->controller->dbforge->add_field("nterminal INTEGER NOT NULL DEFAULT 0");
        $this->controller->dbforge->add_field("cterminal INTEGER NOT NULL DEFAULT 0");
        $this->controller->dbforge->create_table(ModificationTO::TABLE_NAME, true);

        $this->controller->dbforge->add_field("block_id INTEGER");
        $this->controller->dbforge->add_field("sequence_id INTEGER");
        $this->controller->dbforge->add_field("sort INTEGER");
        $this->controller->dbforge->add_field("FOREIGN KEY (block_id) REFERENCES block(id)");
        $this->controller->dbforge->add_field("FOREIGN KEY (sequence_id) REFERENCES sequence(id)");
        $this->controller->dbforge->create_table('b2s', true);

        $this->controller->db->query("CREATE UNIQUE INDEX UX_BLOCK_ACRONYM ON block(acronym)");
        $this->controller->db->query("CREATE INDEX IX_BLOCK_NAME ON block(name)");
        $this->controller->db->query("CREATE INDEX IX_BLOCK_RESIDUE ON block(residue)");
        $this->controller->db->query("CREATE INDEX IX_BLOCK_USMILE ON block(usmiles)");
        $this->controller->db->query("CREATE UNIQUE INDEX UX_SEQUENCE_NAME ON sequence(name)");
        $this->controller->db->query("CREATE UNIQUE INDEX UX_MODIFICATION_NAME ON modification(name)");
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
