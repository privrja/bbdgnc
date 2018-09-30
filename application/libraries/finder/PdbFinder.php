<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

class PdbFinder implements IFinder {

    const REST_BASE_URI = "http://www.ebi.ac.uk/pdbe/api/pdb/compound/";
    const REST_SUMMARY = "summary/";

    const REPLY_NAME = "name";
    const REPLY_WEIGHT = "weight";
    const REPLY_FORMULA = "formula";
    const REPLY_SMILES = "smiles";

    /**
     * Find data on some server by name
     * @param string $strName
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     */
    public function findByName($strName, &$outArResult, &$outArNextResult) {
        // TODO: Implement findByName() method.
    }

    /**
     * Find data by SMILES
     * @param string $strSmile
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     */
    public function findBySmile($strSmile, &$outArResult, &$outArNextResult) {
        // TODO: Implement findBySmile() method.
    }

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     */
    public function findByFormula($strFormula, &$outArResult, &$outArNextResult) {
        // TODO: Implement findByFormula() method.
    }

    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @param array $outArResult
     * @return int
     */
    public function findByMass($decMass, $decTolerance, &$outArResult) {
        // TODO: Implement findByMass() method.
    }

    /**
     * Find data by Identifier
     * @param string $strId
     * @param array $outArResult
     * @return int
     */
    public function findByIdentifier($strId, &$outArResult) {
        $strUri = self::REST_BASE_URI . self::REST_SUMMARY . $strId;
        $mixDecoded = JsonDownloader::getJsonFromUri($strUri);
        if ($mixDecoded === false) {
            return ResultEnum::REPLY_NONE;
        }

        $outArResult[Front::CANVAS_INPUT_IDENTIFIER] = $strId;
        return $this->resultOne($mixDecoded[$strId][0], $outArResult);
    }

    /**
     * Find by identifiers
     * @param array $arIds ids in array
     * @param array $outArResult result
     * @return int
     */
    public function findByIdentifiers($arIds, &$outArResult) {
        // TODO: Implement findByIdentifiers() method.
    }

    private function resultOne($arItems, &$outArResult) {
        $this->setDataFromReplyToResult($arItems, $outArResult);
        return ResultEnum::REPLY_OK_ONE;
    }

    private function setDataFromReplyToResult($arPeptide, &$outArResult) {
        $outArResult[Front::CANVAS_INPUT_NAME] = @$arPeptide[self::REPLY_NAME];
        $outArResult[Front::CANVAS_INPUT_FORMULA] = Front::urlText(@$arPeptide[self::REPLY_FORMULA]);
        $outArResult[Front::CANVAS_INPUT_MASS] = @$arPeptide[self::REPLY_WEIGHT];
        $outArResult[Front::CANVAS_INPUT_SMILE] = @$arPeptide[self::REPLY_SMILES][0][self::REPLY_NAME];
        $outArResult[Front::CANVAS_HIDDEN_DATABASE] = ServerEnum::PDB;
    }
}