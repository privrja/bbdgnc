<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\ReferenceHelper;
use Bbdgnc\Database\BlockDatabase;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
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

    const FILE_NAME = './uploads/blocks.txt';

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
            if ($strReference[0] === " ") {
                $strReference = substr($strReference, 1);
            }
            $referenceParser = new ReferenceParser();
            $referenceResult = $referenceParser->parse($strReference);
            if ($referenceResult->isAccepted()) {
                $arDatabaseReference[] = $referenceResult->getResult();
                if ($arSmiles[$index] === "") {
                    if ($referenceResult->getResult()->database === ServerEnum::PUBCHEM || $referenceResult->getResult()->database === ServerEnum::CHEBI) {
                        $finder = FinderFactory::getFinder($referenceResult->getResult()->database);
                        $findResult = null;
                        $outArResult = [];
                        try {
                            $findResult = $finder->findByIdentifier($referenceResult->getResult()->identifier, $outArResult);
                        } catch (BadTransferException $e) {
                            Logger::log(LoggerEnum::WARNING, "Block not found");
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
            $blockTO->database = $arDatabaseReference[$index]->database;
            $blockTO->identifier = $arDatabaseReference[$index]->identifier;
            $arBlocks[] = $blockTO->asEntity();
        }
        return new Accept($arBlocks, '');
    }

    public function download() {
        $blockDatabase = new BlockDatabase($this->controller);
        $start = 0;
        $arResult = $blockDatabase->findMergeBlocks($start);
        while (!empty($arResult)) {
            foreach ($arResult as $formula) {
                $strData = "";
                $blockCount = sizeof($formula);
                $strData = $this->setNames($strData, $formula, $blockCount);
                $strData = $this->setAcronyms($strData, $formula, $blockCount);
                $strData .= $formula[0]['residue'] . "\t";
                $strData .= $formula[0]['mass'] . "\t";
                $strData = $this->setReferences($strData, $formula, $blockCount);
                file_put_contents(self::FILE_NAME, $strData, FILE_APPEND);
            }
            $start += CommonConstants::PAGING;
            $arResult = $blockDatabase->findMergeBlocks($start);
        }
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match blocks in right format');
    }

    protected function getFileName() {
        return self::FILE_NAME;
    }

    private function setNames($strData, $formula, $blockCount) {
        return $this->setData($strData, $formula, $blockCount, 'name');
    }

    private function setAcronyms(string $strData, $formula, int $blockCount) {
        return $this->setData($strData, $formula, $blockCount, 'acronym');
    }

    private function setData(string $strData, $formula, int $blockCount, string $type) {
        $index = 0;
        $strData .= $formula[$index][$type];
        for ($index = 1; $index < $blockCount; ++$index) {
            $strData .= '/' . $formula[$index][$type];
        }
        $strData .= "\t";
        return $strData;
    }

    private function setReferences(string $strData, $formula, int $blockCount) {
        $index = 0;
        $strData .= ReferenceHelper::reference($formula[$index]['database'], $formula[$index]['identifier'], $formula[$index]['smiles']);
        for ($index = 1; $index < $blockCount; ++$index) {
            $strData .= '/' . ReferenceHelper::reference($formula[$index]['database'], $formula[$index]['identifier'], $formula[$index]['smiles']);
        }
        $strData .= PHP_EOL;
        return $strData;
    }

}
