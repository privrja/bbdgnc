<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

class NorineFinder implements IFinder {

    const REST_BASE_URI = "http://bioinfo.lifl.fr/norine/rest/";

    const REPLY_NORINE = "norine";
    const REPLY_PEPTIDE = "peptide";
    const REPLY_PEPTIDES = "peptides";
    const REPLY_GENERAL = "general";
    const REPLY_ID = "id";
    const REPLY_NAME = "name";
    const REPLY_FORMULA = "formula";
    const REPLY_STRUCTURE = "structure";
    const REPLY_SMILES = "smiles";
    const REPLY_TYPE = "type";
    const REPLY_LINK = "link";
    const REPLY_MOLECULAR_WEIGHT = "mw";

    /**
     * Find data on some server by name
     * @param string $strName
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     */
    public function findByName($strName, &$outArResult, &$outArNextResult) {
        $strUri = self::REST_BASE_URI . "name/" . IFinder::REST_FORMAT_JSON . IFinder::REST_SLASH . $strName;
        $mixDecoded = JsonDownloader::getJsonFromUri($strUri);
        if ($mixDecoded === false) {
            return ResultEnum::REPLY_NONE;
        }

        $intCounter = 0;
        foreach ($mixDecoded[self::REPLY_PEPTIDES] as $arPeptide) {
            $arMolecule = array();
            $this->setDataFromReplyToResult($arPeptide, $arMolecule);
            $outArResult[$intCounter] = $arMolecule;
            $intCounter++;
        }

        switch ($intCounter) {
            case 0:
                return ResultEnum::REPLY_NONE;
            case 1:
                return ResultEnum::REPLY_OK_ONE;
            default:
                return ResultEnum::REPLY_OK_MORE;
        }
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
    public function findById($strId, &$outArResult) {
        $strUri = self::REST_BASE_URI . "id/" . IFinder::REST_FORMAT_JSON . IFinder::REST_SLASH . $strId;
        $mixDecoded = JsonDownloader::getJsonFromUri($strUri);
        if ($mixDecoded === false) {
            return ResultEnum::REPLY_NONE;
        }
        return $this->resultOne($mixDecoded[self::REPLY_NORINE][self::REPLY_PEPTIDE], $outArResult);
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
        $this->setDataFromReplyToResult($arItems[0], $outArResult);
        return ResultEnum::REPLY_OK_ONE;
    }

    private function setDataFromReplyToResult($arPeptide, &$outArResult) {
        $outArResult[Front::CANVAS_INPUT_IDENTIFIER] = @$arPeptide[self::REPLY_GENERAL][self::REPLY_ID];
        $outArResult[Front::CANVAS_INPUT_NAME] = @$arPeptide[self::REPLY_GENERAL][self::REPLY_NAME];
        $outArResult[Front::CANVAS_INPUT_FORMULA] = @$arPeptide[self::REPLY_GENERAL][self::REPLY_FORMULA];
        $outArResult[Front::CANVAS_INPUT_MASS] = @$arPeptide[self::REPLY_GENERAL][self::REPLY_MOLECULAR_WEIGHT];
        $outArResult[Front::CANVAS_INPUT_SMILE] = @$arPeptide[self::REPLY_STRUCTURE][self::REPLY_SMILES];
        $outArResult[Front::CANVAS_HIDDEN_DATABASE] = ServerEnum::NORINE;
    }

}