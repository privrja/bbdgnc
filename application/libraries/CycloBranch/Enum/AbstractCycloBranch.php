<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Smiles\Parser\IParser;
use CI_Controller;

abstract class AbstractCycloBranch implements ICycloBranch, IParser {
    /**
     * @var CI_Controller
     */
    protected $controller;

    /**
     * AbstractCycloBranch constructor.
     * @param CI_Controller $controller
     */
    public function __construct($controller) {
        $this->controller = $controller;
    }

    public function import(string $filePath) {
        ini_set('max_execution_time', 120);
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return;
        }
        while (($line = fgets($handle)) !== false) {
            $arBlocks = $this->parse($line);
            $this->save($arBlocks->getResult());
        }
        fclose($handle);
        unlink($filePath);
        ini_set('max_execution_time', 30);
    }

    public abstract function parse($strText);

    public abstract static function reject();

    public abstract function export();

    private function save(array $arBlocks) {
        $this->controller->block_model->startTransaction();
        $this->controller->block_model->insertMore($arBlocks);
        $this->controller->block_model->endTransaction();
    }

}
