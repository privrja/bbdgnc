<?php

use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\ModificationTO;
use Bbdgnc\TransportObjects\SequenceTO;

class SequenceDatabase {

    private $controller;

    /** @var SequenceTO $sequenceTO */
    private $sequenceTO;

    private $blocks;

    private $modifications;

    private $blockIds = [];

    private $modificationIds = [];

    private $sequenceId;

    /**
     * SequenceDatabase constructor.
     */
    public function __construct($controller) {
        $this->controller = $controller;
    }

    public function save(SequenceTO $sequenceTO, array $blocks, array $modifications = []) {
        $this->sequenceTO = $sequenceTO;
        $this->blocks = $blocks;
        $this->modifications = $modifications;
        $this->saveBlocks();
        $this->saveModifications();
        $this->saveSequence();
        $this->saveBlocksToSequence();
    }

    private function saveBlocks() {
        /** @var BlockTO $blockTO */
        foreach ($this->blocks as $blockTO) {
            $this->blockIds[] = $this->controller->block_model->insertBlock($blockTO->asBlock());
        }
    }

    private function saveModifications(): void {
        /** @var ModificationTO $modificationTO */
        foreach ($this->modifications as $key => $modificationTO) {
            $this->modificationIds[$key] = $this->controller->modification->insertModification($modificationTO->asModification());
        }
    }

    private function saveSequence(): void {
        $this->sequenceId = $this->controller->sequence_model->insert($this->sequenceTO->asSequence());
    }

    private function saveBlocksToSequence(): void {
    }

}