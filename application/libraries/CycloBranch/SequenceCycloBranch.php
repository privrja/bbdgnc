<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\ReferenceHelper;
use Bbdgnc\Database\SequenceDatabase;
use Bbdgnc\Enum\SequenceTypeEnum;
use CI_Controller;

class SequenceCycloBranch extends AbstractCycloBranch {

    const FILE_NAME = './uploads/sequences.txt';

    private $database;

    /**
     * SequenceCycloBranch constructor.
     * @param CI_Controller $controller
     */
    public function __construct($controller) {
        parent::__construct($controller);
        $this->database = new SequenceDatabase($controller);
    }


    public function parse($strText) {
        // TODO: Implement parse() method.
    }

    public static function reject() {
        // TODO: Implement reject() method.
    }

    public function download() {
        $start = 0;
        $arResult = $this->database->findSequenceWithModificationNamesPaging($start);
        while (!empty($arResult)) {
            foreach ($arResult as $sequence) {
                $strData = SequenceTypeEnum::$values[$sequence['type']] . "\t";
                $strData .= $sequence['name'] . "\t";
                $strData .= $sequence['formula'] . "\t";
                $strData .= $sequence['mass'] . "\t";
                $strData .= $sequence['sequence'] . "\t";
                $strData .= $sequence['nname'] . "\t";
                $strData .= $sequence['cname'] . "\t";
                $strData .= $sequence['bname'] . "\t";
                $strData .= ReferenceHelper::reference($sequence['database'], $sequence['identifier'], $sequence['smiles']);
                $strData .= PHP_EOL;
                file_put_contents(self::FILE_NAME, $strData, FILE_APPEND);
            }
            $start += CommonConstants::PAGING;
            $arResult = $this->database->findSequenceWithModificationNamesPaging($start);
        }
    }

    protected function getFileName() {
        return self::FILE_NAME;
    }

}
