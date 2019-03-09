<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\ReferenceHelper;

class BlockWithoutFormulaCycloBranch extends BlockCycloBranch {

    public function download() {
        $start = 0;
        $arResult = $this->database->findAllPaging($start);
        while (!empty($arResult)) {
            foreach ($arResult as $modification) {
                $strData = $modification['name'] . "\t";
                $strData .= $modification['acronym'] . "\t";
                $strData .= $modification['residue'] . "\t";
                $strData .= $modification['mass'] . "\t";
                $strData .= $modification['losses'] . "\t";
                $strData .= ReferenceHelper::reference($modification['database'], $modification['identifier'], $modification['smiles']);
                $strData .= PHP_EOL;
                file_put_contents(self::FILE_NAME, $strData, FILE_APPEND);
            }
            $start += CommonConstants::PAGING;
            $arResult = $this->database->findAllPaging($start);
        }
    }

}
