<?php

class CycloBranch {

    public function import(string $filePath, int $type) {
        $cycloBranch = ImportTypeFactory::getCycloBranch($type);
        return $cycloBranch->import($filePath);
    }

    public function export() {

    }

}
