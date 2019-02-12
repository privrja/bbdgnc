<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Finder\Exception\BadTransferException;
use Bbdgnc\Finder\FinderFactory;
use Bbdgnc\Smiles\Parser\Accept;
use Bbdgnc\Smiles\Parser\ReferenceParser;
use Bbdgnc\Smiles\Parser\Reject;
use Bbdgnc\TransportObjects\BlockTO;

class BlockCycloBranch extends AbstractCycloBranch {

    const NAME = 0;
    const ACRONYM = 1;
    const FORMULA = 2;
    const MASS = 3;
    const REFERENCE = 4;
    const LENGTH = 5;

    public function parse($line) {
        $arItems = preg_split('/\t/', $line);
        if (empty($arItems) || sizeof($arItems) !== self::LENGTH) {
            return self::reject();
        }

        for ($index = 0; $index < self::LENGTH; ++$index) {
            if ($arItems[$index] === "") {
                return self::reject();
            }
        }

        $arNames = explode('/', $arItems[self::NAME]);
        $length = sizeof($arNames);
        $arSmiles = [];
        $arAcronyms = explode('/', $arItems[self::ACRONYM]);
        // TODO v nove verzi CycloBranch přibude položka Neutral Losess a bude před references
        $arReference = explode('/', $arItems[self::REFERENCE]);
        $arDatabaseReference = [];
        if (sizeof($arAcronyms) !== $length || sizeof($arReference) !== $length) {
            return self::reject();
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
                if ($arSmiles[$index] === "") {
                    if ($referenceResult->getResult()->server === ServerEnum::PUBCHEM || $referenceResult->getResult()->server === ServerEnum::CHEBI) {
                        $finder = FinderFactory::getFinder($referenceResult->getResult()->server);
                        $findResult = null;
                        try {
                            $findResult = $finder->findByIdentifier($referenceResult->getResult()->identifier, $outArResult);
                        } catch (BadTransferException $e) {
                        }
                        if ($findResult === ResultEnum::REPLY_OK_ONE) {
                            $arSmiles[$index] = $outArResult[Front::CANVAS_INPUT_SMILE];
                        }
                    }
                }
            } else {
                return self::reject();
            }
        }

        $arBlocks = [];
        for ($index = 0; $index < $length; ++$index) {
            $blockTO = new BlockTO(0, $arNames[$index], $arAcronyms[$index], $arSmiles[$index], ComputeEnum::UNIQUE_SMILES);
            $blockTO->formula = $arItems[self::FORMULA];
            $blockTO->mass = (float)$arItems[self::MASS];
            $blockTO->reference = $arDatabaseReference[$index];
            $arBlocks[] = $blockTO->asBlock();
        }
        return new Accept($arBlocks, '');
    }

    public function export() {
        // TODO: Implement export() method.
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match blocks in right format');
    }
}