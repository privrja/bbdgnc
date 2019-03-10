<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Database\ModificationDatabase;
use Bbdgnc\Smiles\Parser\Accept;
use Bbdgnc\Smiles\Parser\Reject;
use Bbdgnc\TransportObjects\ModificationTO;
use CI_Controller;

class ModificationCycloBranch extends AbstractCycloBranch {

    const FILE_NAME = './uploads/modifications.txt';

    const NAME = 0;
    const FORMULA = 1;
    const MASS = 2;
    const N_TERMINAL = 3;
    const C_TERMINAL = 4;
    const LENGTH = 5;

    /**
     * ModificationCycloBranch constructor.
     * @param CI_Controller $controller
     */
    public function __construct($controller) {
        parent::__construct($controller);
        $this->database = new ModificationDatabase($controller);
    }


    public function parse($line) {
        $arItems = $this->validateLine($line);
        if ($arItems === false) {
            return self::reject();
        }

        $modification = new ModificationTO($arItems[self::NAME], $arItems[self::FORMULA], $arItems[self::MASS], $arItems[self::C_TERMINAL], $arItems[self::N_TERMINAL]);
        return new Accept([$modification->asEntity()], '');
    }

    public static function reject() {
        return new Reject('Not match modification in right format');
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

    protected function getLineLength() {
        return self::LENGTH;
    }

}
