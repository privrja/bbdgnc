<?php

use Bbdgnc\Base\Logger;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Enum\ModificationTypeEnum;
use Bbdgnc\Exception\BlockToSequenceInDatabaseException;
use Bbdgnc\Exception\DatabaseException;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Exception\SequenceInDatabaseException;
use Bbdgnc\Exception\UniqueConstraintException;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\BlockToSequenceTO;
use Bbdgnc\TransportObjects\ModificationTO;
use Bbdgnc\TransportObjects\SequenceTO;

class SequenceDatabase {

    private $controller;

    /** @var SequenceTO $sequenceTO */
    private $sequenceTO;

    /** @var SplObjectStorage $blocks */
    private $blocks;

    /** @var ModificationTO[] */
    private $modifications;

    /** @var int[] */
    private $blockIds = [];

    private $sequenceId;

    /**
     * SequenceDatabase constructor.
     * @param Land $controller
     */
    public function __construct($controller) {
        $this->controller = $controller;
    }

    /**
     * @param SequenceTO $sequenceTO
     * @param SplObjectStorage $blocks
     * @param array $modifications
     * @throws Exception
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
        } catch (Exception $e) {
            Logger::log(LoggerEnum::ERROR, $e->getMessage() . " Trace: " . $e->getTraceAsString());
            $this->controller->block_model->endTransaction();
        }
    }

    private function saveBlocks() {
        /** @var BlockTO $blockTO */
        foreach ($this->blocks as $blockTO) {
            if (isset($blockTO->databaseId) && "" !== $blockTO->databaseId) {
                $this->blockIds[] = $blockTO->databaseId;
            } else {
                $this->blockIds[] = $this->controller->block_model->insert($blockTO);
            }
        }
    }

    private function saveModifications(): void {
        foreach ($this->modifications as $key => $modificationTO) {
            $id = $this->controller->modification_model->insert($modificationTO);
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
        foreach ($this->blockIds as $blockId) {
            $blockToSequence = new BlockToSequenceTO($blockId, $this->sequenceId);
            try {
                $this->controller->blockToSequence_model->insert($blockToSequence);
            } catch (UniqueConstraintException $e) {
                Logger::log(LoggerEnum::WARNING, "Block to sequence already in database. Sequence id: " . $this->sequenceId . " block id: " . $blockId);
                $exThrown = true;
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

}