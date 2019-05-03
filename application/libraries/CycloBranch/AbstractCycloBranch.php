<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Base\Logger;
use Bbdgnc\Database\AbstractDatabase;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Smiles\Parser\IParser;
use CI_Controller;

/**
 * Class AbstractCycloBranch
 * Abstract class for import/export data from CycloBranch
 * @package Bbdgnc\CycloBranch
 */
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

    /**
     * @see ICycloBranch::import()
     */
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

    /**
     * Parse one line of an uploaded file
     * @see IParser::parse()
     * @param string $strText line of file
     * @return \Bbdgnc\Smiles\Parser\Accept|\Bbdgnc\Smiles\Parser\Reject
     */
    public abstract function parse($strText);

    /**
     * @see IParser::reject()
     */
    public abstract static function reject();

    /**
     * Exporting data to a file
     */
    public abstract function download();

    /**
     * @see ICycloBranch::export()
     */
    public final function export() {
        if (file_exists($this->getFileName())) {
            unlink($this->getFileName());
        }
        $this->download();
        force_download($this->getFileName(), null);
    }

    /**
     * Save data to database
     * @param array $arTos
     */
    protected function save(array $arTos) {
        $this->database->startTransaction();
        $this->database->insertMore($arTos);
        $this->database->endTransaction();
    }

    /**
     * Get name of a file to download
     * @return string
     */
    protected abstract function getFileName();

    /**
     * Get count of item on a line
     * @return int
     */
    protected abstract function getLineLength();

    protected function validateLine($line, $allSet = true) {
        $arItems = preg_split('/\t/', $line);
        if (empty($arItems) || sizeof($arItems) !== $this->getLineLength()) {
            return false;
        }

        if ($allSet) {
            for ($index = 0; $index < $this->getLineLength(); ++$index) {
                if ($arItems[$index] === "") {
                    return false;
                }
            }
        }
        return $arItems;
    }

}
