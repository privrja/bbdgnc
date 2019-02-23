<?php

use Bbdgnc\Enum\ModificationTypeEnum;
use Bbdgnc\Exception\IllegalArgumentException;
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

    public function save(SequenceTO $sequenceTO, SplObjectStorage $blocks, array $modifications = []) {
        $this->sequenceTO = $sequenceTO;
        $this->blocks = $blocks;
        $this->modifications = $modifications;
        $this->controller->block_model->startTransaction();
        $this->saveBlocks();
        $this->saveModifications();
        $this->saveSequence();
        $this->saveBlocksToSequence();
        $this->controller->block_model->endTransaction();
    }

    private function saveBlocks() {
        /** @var BlockTO $blockTO */
        foreach ($this->blocks as $blockTO) {
            if (isset($blockTO->databaseId)) {
                $this->blockIds[] = $blockTO->databaseId;
            } else {
                $this->blockIds[] = $this->controller->block_model->insert($blockTO);
            }
        }
    }

    private function saveModifications(): void {
        foreach ($this->modifications as $key => $modificationTO) {
            $id = $this->controller->modification->insertModification($modificationTO);
            $this->setupModifications($key, $id);
        }
    }

    private function saveSequence(): void {
        $this->sequenceId = $this->controller->sequence_model->insert($this->sequenceTO);
    }

    private function saveBlocksToSequence(): void {
        foreach ($this->blockIds as $blockId) {
            $blockToSequence = new BlockToSequenceTO($blockId, $this->sequenceId);
            $this->controller->blockToSequence_model->insert($blockToSequence);
        }
    }

    private function setupModifications(int $key, $id) {
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