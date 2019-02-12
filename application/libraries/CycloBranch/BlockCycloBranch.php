<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Parser\ReferenceParser;
use Bbdgnc\TransportObjects\BlockTO;

class BlockCycloBranch extends AbstractCycloBranch {

    const NAME = 0;
    const ACRONYM = 1;
    const FORMULA = 2;
    const MASS = 3;
    const REFERENCE = 4;
    const LENGTH = 5;

    public function parseLine(string $line) {
        $arItems = preg_split('/\t/', $line);
        if (empty($arItems) || sizeof($arItems) !== self::LENGTH) {
            return [];
        }
        $arNames = explode('/', $arItems[self::NAME]);
        $length = sizeof($arNames);
        $arSmiles = [];
        $arAcronyms = explode('/', $arItems[self::ACRONYM]);
        // TODO v nove verzi CycloBranch přibude položka Neutral Losess a bude před references
        $arReference = explode('/', $arItems[self::REFERENCE]);
        $arDatabaseReference = [];
        if (sizeof($arAcronyms) !== $length || sizeof($arReference) !== $length) {
            return [];
        }
        for ($index = 0; $index < $length; ++$index) {
            $arTmp = explode('in', $arReference[$index]);
            if (empty($arTmp) || $arTmp[0] === $arReference[$index]) {
                $arSmiles[] = '';
            } else {
                $smiles = substr($arTmp[0], 0, -1);
                $arSmiles[] = FormulaHelper::genericSmiles($smiles);
            }
        }

        for ($index = 0; $index < $length; ++$index) {
            $arTmp = explode('in', $arReference[$index]);
            if (sizeof($arTmp) === 2) {
                $strReference = $arTmp[1];
            } else {
                $strReference = $arTmp[0];
            }
            $referenceParser = new ReferenceParser();
            $referenceResult = $referenceParser->parse($strReference);
            if ($referenceResult->isAccepted()) {
                $arDatabaseReference[] = $referenceResult->getResult();
            } else {
               throw new IllegalArgumentException();
            }
        }

        $arBlocks = [];
        for ($index = 0; $index < $length; ++$index) {
            $blockTO = new BlockTO(0, $arNames[$index], $arAcronyms[$index], $arSmiles[$index], ComputeEnum::UNIQUE_SMILES);
            $blockTO->formula = $arItems[self::FORMULA];
            $blockTO->mass = $arItems[self::MASS];
            $blockTO->reference = $arDatabaseReference[$index];
            $arBlocks[] = $blockTO->asBlock();
        }
        return $arBlocks;
    }

    public function export() {
        // TODO: Implement export() method.
    }
}