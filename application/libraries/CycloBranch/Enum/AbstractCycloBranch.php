<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Base\Logger;
use Bbdgnc\Database\AbstractDatabase;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Smiles\Parser\IParser;
use CI_Controller;

abstract class AbstractCycloBranch implements ICycloBranch, IParser {
    /**
     * @var CI_Controller
     */
    protected $controller;

    /** @var AbstractDatabase $database */
    protected $database;

    /**
     * AbstractCycloBranch constructor.
     * @param CI_Controller $controller
     */
    public function __construct($controller) {
        $this->controller = $controller;
    }

    public final function import(string $filePath) {
        ini_set('max_execution_time', 120);
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return;
        }
        while (($line = fgets($handle)) !== false) {
            $arBlocksResult = $this->parse($line);
            if ($arBlocksResult->isAccepted()) {
                $this->save($arBlocksResult->getResult());
            } else {
                Logger::log(LoggerEnum::WARNING, "Line not parsed correctly" . PHP_EOL . $line . $arBlocksResult->getErrorMessage());
            }
        }
        fclose($handle);
        unlink($filePath);
        ini_set('max_execution_time', 30);
    }

    public abstract function parse($strText);

    public abstract static function reject();

    public abstract function download();

    public final function export() {
        if (file_exists($this->getFileName())) {
            unlink($this->getFileName());
        }
        $this->download();
        force_download($this->getFileName(), null);
    }

    private function save(array $arTos) {
        $this->database->startTransaction();
        $this->database->insertMore($arTos);
        $this->database->endTransaction();
    }

    protected abstract function getFileName();

    protected abstract function getLineLength();

    protected function validateLine($line) {
        $arItems = preg_split('/\t/', $line);
        if (empty($arItems) || sizeof($arItems) !== $this->getLineLength()) {
            return false;
        }

        for ($index = 0; $index < $this->getLineLength(); ++$index) {
            if ($arItems[$index] === "") {
                return false;
            }
        }
        return $arItems;
    }
}
