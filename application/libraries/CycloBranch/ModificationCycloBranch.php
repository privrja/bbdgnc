<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Database\ModificationDatabase;
use CI_Controller;

class ModificationCycloBranch extends AbstractCycloBranch {

    const FILE_NAME = './uploads/modifications.txt';

    private $database;

    /**
     * ModificationCycloBranch constructor.
     * @param CI_Controller $controller
     */
    public function __construct($controller) {
        parent::__construct($controller);
        $this->database = new ModificationDatabase($controller);
    }


    public function parse($strText) {
        // TODO: Implement parse() method.
    }

    public static function reject() {
        // TODO: Implement reject() method.
    }

    public function download() {
        $start = 0;
        $arResult = $this->database->findAllPaging($start);
        while (!empty($arResult)) {
            foreach ($arResult as $modification) {
                $strData = $modification['name'] . "\t";
                $strData .= $modification['formula'] . "\t";
                $strData .= $modification['mass'] . "\t";
                $strData .= $modification['nterminal'] . "\t";
                $strData .= $modification['cterminal'] . PHP_EOL;
                file_put_contents(self::FILE_NAME, $strData, FILE_APPEND);
            }
            $start += CommonConstants::PAGING;
            $arResult = $this->database->findAllPaging($start);
        }
    }

    protected function getFileName() {
        return self::FILE_NAME;
    }
}
