<?php

class BlockCycloBranch implements ICycloBranch {

    public function import(string $filePath) {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return;
        }
        while (($line = fgets($handle)) !== false) {
            $arItems = preg_split('/\t/', $line);
            var_dump($arItems);
            // TODO in name check for \
        }
        fclose($handle);
    }

    public function export() {
        // TODO: Implement export() method.
    }

}