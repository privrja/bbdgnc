<?php

namespace Bbdgnc\CycloBranch;

class SequenceCycloBranch extends AbstractCycloBranch {

    const FILE_NAME = './uploads/sequences.txt';

    public function parse($strText) {
        // TODO: Implement parse() method.
    }

    public static function reject() {
        // TODO: Implement reject() method.
    }

    public function download() {
        // TODO: Implement download() method.
    }

    protected function getFileName() {
        return self::FILE_NAME;
    }

}
