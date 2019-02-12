<?php

namespace Bbdgnc\CycloBranch;

abstract class AbstractCycloBranch implements ICycloBranch {
    /**
     * @var CI_Controller
     */
    protected $controller;

    /**
     * AbstractCycloBranch constructor.
     * @param CI_Controller $controller
     */
    public function __construct(CI_Controller $controller) {
        $this->controller = $controller;
    }

    public function import(string $filePath) {
        ini_set('max_execution_time', 120);
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return;
        }
        while (($line = fgets($handle)) !== false) {
            $arBlocks = $this->parseLine($line);
            $this->save($arBlocks);
        }
        fclose($handle);
        unlink($filePath);
        ini_set('max_execution_time', 30);
    }

    public abstract function export();

    public abstract function parseLine(string $line);

    private function save($arBlocks) {
        // TODO save to DB mozna by se hodilo vytvorit transakci kvuli chybe na radku asi neukladat ty co budou dobre
        $this->controller->block_model->insertBlocks($arBlocks);
    }

}
