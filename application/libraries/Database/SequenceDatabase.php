<?php

namespace Bbdgnc\Database;

use Bbdgnc\Base\IDatabase;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\Query;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Enum\ModificationTypeEnum;
use Bbdgnc\Exception\BlockToSequenceInDatabaseException;
use Bbdgnc\Exception\DatabaseException;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Exception\SequenceInDatabaseException;
use Bbdgnc\Exception\UniqueConstraintException;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\BlockToSequenceTO;
use Bbdgnc\TransportObjects\IdOrder;
use Bbdgnc\TransportObjects\ModificationTO;
use Bbdgnc\TransportObjects\SequenceTO;
use SplObjectStorage;

/**
 * Class SequenceDatabase
 * @see IDatabase
 * @package Bbdgnc\Database
 */
class SequenceDatabase extends AbstractDatabase {

    /** @var SequenceTO $sequenceTO */
    private $sequenceTO;

    /** @var SplObjectStorage $blocks */
    private $blocks;

    /** @var ModificationTO[] */
    private $modifications;

    /** @var IdOrder[] $blockIdsAndSort */
    private $blockIdsAndSort = [];

    private $sequenceId;

    /**
     * Save sequence with modifications and building blocks
     * @param SequenceTO $sequenceTO
     * @param SplObjectStorage $blocks
     * @param array $modifications
     * @throws DatabaseException
     */
    public function save(SequenceTO $sequenceTO, SplObjectStorage $blocks, array $modifications = []) {
        try {
            $this->sequenceTO = $sequenceTO;
            $this->blocks = $blocks;
            $this->modifications = $modifications;
            $this->controller->block_model->startTransaction();
            $this->saveBlocks();
            $this->saveModifications();
            $this->saveSequence();
            $this->saveBlocksToSequence();
            $this->controller->block_model->endTransaction();
        } catch (BlockToSequenceInDatabaseException $e) {
            $this->controller->block_model->commit();
        } catch (DatabaseException $e) {
            Logger::log(LoggerEnum::ERROR, "Database exception: " . $e->getMessage() . " Trace: " . $e->getTraceAsString());
            $this->controller->block_model->endTransaction();
            throw $e;
        } catch (\Exception $e) {
            Logger::log(LoggerEnum::ERROR, $e->getMessage() . " Trace: " . $e->getTraceAsString());
            $this->controller->block_model->endTransaction();
        }
    }

    private function saveBlocks() {
        /** @var BlockTO $blockTO */
        foreach ($this->blocks as $key => $blockTO) {
            if (isset($blockTO->databaseId) && "" !== $blockTO->databaseId) {
                $this->blockIdsAndSort[] = new IdOrder($blockTO->databaseId, $this->blocks[$blockTO]);
            } else {
                $id = $this->controller->block_model->insert($blockTO);
                $this->blockIdsAndSort[] = new IdOrder($id, $this->blocks[$blockTO]);
            }
        }
    }

    private function saveModifications(): void {
        foreach ($this->modifications as $key => $modificationTO) {
            if (isset($modificationTO->databaseId)) {
                $id = $modificationTO->databaseId;
            } else {
                $id = $this->controller->modification_model->insert($modificationTO);
            }
            $this->setupModifications($key, $id);
        }
    }

    /**
     * @throws SequenceInDatabaseException
     */
    private function saveSequence(): void {
        try {
            $this->sequenceId = $this->controller->sequence_model->insert($this->sequenceTO);
        } catch (UniqueConstraintException $exception) {
            Logger::log(LoggerEnum::ERROR, "Sequence already in database. Sequence id: " . $this->sequenceId);
            throw new SequenceInDatabaseException();
        }
    }

    /**
     * @throws BlockToSequenceInDatabaseException
     */
    private function saveBlocksToSequence(): void {
        $exThrown = false;
        foreach ($this->blockIdsAndSort as $idOrder) {
            foreach ($idOrder->order as $order) {
                $blockToSequence = new BlockToSequenceTO($idOrder->id, $this->sequenceId);
                $blockToSequence->sort = $order;
                try {
                    $this->controller->blockToSequence_model->insert($blockToSequence);
                } catch (UniqueConstraintException $e) {
                    Logger::log(LoggerEnum::WARNING, "Block to sequence already in database. Sequence id: " . $this->sequenceId . " block id: " . $idOrder->id);
                    $exThrown = true;
                }
            }
        }
        if ($exThrown) {
            throw new BlockToSequenceInDatabaseException();
        }
    }

    private function setupModifications(string $key, $id) {
        switch ($key) {
            case ModificationTypeEnum::N_MODIFICATION:
                $this->sequenceTO->nModification = $id;
                break;
            case ModificationTypeEnum::C_MODIFICATION:
                $this->sequenceTO->cModification = $id;
                break;
            case ModificationTypeEnum::BRANCH_MODIFICATION:
                $this->sequenceTO->bModification = $id;
                break;
            default:
                throw new IllegalArgumentException();
        }
    }

    public function findSequenceDetail($id) {
        $sequence = $this->controller->sequence_model->findById($id);
        $detail = [];
        $detail['sequence'] = $sequence;
        if (isset($sequence['n_modification_id'])) {
            $detail['nModification'] = $this->controller->modification_model->findById($sequence['n_modification_id']);
        } else {
            $detail['nModification'] = null;
        }

        if (isset($sequence['c_modification_id'])) {
            $detail['cModification'] = $this->controller->modification_model->findById($sequence['c_modification_id']);
        } else {
            $detail['cModification'] = null;
        }

        if (isset($sequence['b_modification_id'])) {
            $detail['bModification'] = $this->controller->modification_model->findById($sequence['b_modification_id']);
        } else {
            $detail['bModification'] = null;
        }

        $detail['blocks'] = $this->controller->block_model->findBlocksBySequenceId($sequence['id']);
        return $detail;
    }

    public function findSequenceWithModificationNamesPaging($start, Query $query) {
        return $this->controller->sequence_model->findSequenceWithModificationNames($start, $query);
    }

    public function findSequenceWithModificationNamesPagingCount(Query $query) {
        return $this->controller->sequence_model->findSequenceWithModificationNamesCount($query);
    }

    public function findAllPaging($start, Query $query) {
        return $this->controller->sequence_model->findAllPaging($start, $query);
    }


    public function findAllPagingCount(Query $query) {
        return $this->controller->sequence_model->findAllPaging($query);
    }

    public function findById($id) {
        return $this->controller->sequence_model->findById($id);
    }

    public function update($id, $to) {
        $this->controller->sequence_model->update($id, $to);
    }

    /**
     * @param $sequenceId
     * @param $modificationId
     * @param $modification
     * @param $terminal
     */
    public function insertNewModification($sequenceId, $modification, $terminal) {
        $modificationDatabase = new ModificationDatabase($this->controller);
        $modificationId = $modificationDatabase->insert($modification);
        $this->updateModification($sequenceId, $modificationId, $terminal);
    }

    public function updateModification($sequenceId, $modificationId, $terminal) {
        $this->controller->sequence_model->updateModification($sequenceId, $modificationId, $terminal);
    }

    public function insert($to) {
        return $this->controller->sequence_model->insert($to);
    }

    public function insertMore(array $tos) {
        $this->controller->sequence_model->insertMore($tos);
    }

    public function startTransaction() {
        $this->controller->sequence_model->startTransaction();
    }

    public function endTransaction() {
        $this->controller->sequence_model->endTransaction();
    }

    public function commit() {
        $this->controller->sequence_model->commit();
    }

    public function rollback() {
        $this->controller->sequence_model->rollback();
    }

    public function delete($id, $database = null) {
        $this->controller->blockToSequence_model->deleteWithSequenceId($id);
        $this->controller->sequence_model->delete($id);
    }

    public function findSequenceWithModificationUsage($modificationId) {
        return $this->controller->sequence_model->findSequenceWithModificationUsage($modificationId);
    }

}
