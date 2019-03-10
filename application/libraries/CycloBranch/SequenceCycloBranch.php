<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\ReferenceHelper;
use Bbdgnc\Database\SequenceDatabase;
use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Smiles\Parser\Reject;
use Bbdgnc\TransportObjects\SequenceTO;
use CI_Controller;

class SequenceCycloBranch extends AbstractCycloBranch {

    const FILE_NAME = './uploads/sequences.txt';

    const TYPE = 0;
    const NAME = 1;
    const FORMULA = 2;
    const MASS = 3;
    const SEQUENCE = 4;
    const N_TERMINAL_MODIFICATION = 5;
    const C_TERMINAL_MODIFICATION = 6;
    const B_TERMINAL_MODIFICATION = 7;
    const REFERENCE = 7;
    const LENGTH = 8;

    /**
     * SequenceCycloBranch constructor.
     * @param CI_Controller $controller
     */
    public function __construct($controller) {
        parent::__construct($controller);
        $this->database = new SequenceDatabase($controller);
    }


    public function parse($line) {
        $arResult = $this->validateLine($line);
        if ($arResult === false) {
            self::reject();
        }

        $type = $this->validateType($arResult[self::TYPE]);


        $sequenceTO = new SequenceTO();




    }

    private function validateType($type) {
        try {
            return SequenceTypeEnum::$backValues[$type];
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function reject() {
        return new Reject('Not match sequence in right format');
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

    protected function getLineLength() {
        return self::LENGTH;
    }

}
