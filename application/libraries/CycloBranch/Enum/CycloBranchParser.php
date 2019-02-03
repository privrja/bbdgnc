<?php

abstract class CycloBranchParser implements ICycloBranch {

    public function import(string $filePath) {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return;
        }
        while (($line = fgets($handle)) !== false) {
            $this->parseLine($line);
        }
        fclose($handle);
        unlink($handle);
    }

    public abstract function export();

    protected abstract function parseLine(string $line);

}
