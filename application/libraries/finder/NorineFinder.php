<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

class NorineFinder implements IFinder {

    const REST_BASE_URI = "http://bioinfo.lifl.fr/norine/rest/";
    const REST_SMILES = "smiles";
    const REST_PEPTIDES = "peptides";

    const REPLY_NORINE = "norine";
    const REPLY_PEPTIDE = "peptide";
    const REPLY_PEPTIDES = self::REST_PEPTIDES;
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
     * @throws Exception\BadTransferException
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

        return $this->returnCode($intCounter, $outArResult);
    }

    /**
     * Find data by SMILES
     * @param string $strSmile
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @throws \Exception
     */
    public function findBySmile($strSmile, &$outArResult, &$outArNextResult) {
        throw new \Exception('Norine does not support SMILES search!');
    }

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     * @throws Exception\BadTransferException
     */
    public function findByFormula($strFormula, &$outArResult, &$outArNextResult) {
        // !!! too slow
        $strUri = self::REST_BASE_URI . self::REPLY_PEPTIDES . IFinder::REST_SLASH . IFinder::REST_FORMAT_JSON . "/smiles";
        $mixDecoded = JsonDownloader::getJsonFromUri($strUri);
        if ($mixDecoded === false) {
            return ResultEnum::REPLY_NONE;
        }

        $intCounter = 0;
        foreach ($mixDecoded[self::REPLY_PEPTIDES] as $arPeptide) {
            if (isset($arPeptide[self::REPLY_GENERAL][self::REPLY_FORMULA])
                && $arPeptide[self::REPLY_GENERAL][self::REPLY_FORMULA] == $strFormula) {
                $arMolecule = array();
                $this->setDataFromReplyToResult($arPeptide, $arMolecule);
                $outArResult[$intCounter] = $arMolecule;
                $intCounter++;
            }
        }

        return $this->returnCode($intCounter, $outArResult);
    }

    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @param array $outArResult
     * @param $outArNextResult
     * @throws \Exception
     */
    public function findByMass($decMass, $decTolerance, &$outArResult, &$outArNextResult) {
        throw new \Exception('Norine does not support mass search!');
    }

    /**
     * Find data by Identifier
     * @param string $strId
     * @param array $outArResult
     * @return int
     * @throws Exception\BadTransferException
     */
    public function findByIdentifier($strId, &$outArResult) {
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
        return ResultEnum::REPLY_NONE;
    }

    /**
     * Setup all values to result array, only when one result
     * @param array $arItems
     * @param array $outArResult
     * @return int ResultEnum
     */
    private function resultOne($arItems, &$outArResult) {
        $this->setDataFromReplyToResult($arItems[0], $outArResult);
        return ResultEnum::REPLY_OK_ONE;
    }

    /**
     * Set values to result array
     * @param array $arPeptide
     * @param array $outArResult
     */
    private function setDataFromReplyToResult($arPeptide, &$outArResult) {
        $outArResult[Front::CANVAS_INPUT_IDENTIFIER] = @$arPeptide[self::REPLY_GENERAL][self::REPLY_ID];
        $outArResult[Front::CANVAS_INPUT_NAME] = @$arPeptide[self::REPLY_GENERAL][self::REPLY_NAME];
        $outArResult[Front::CANVAS_INPUT_FORMULA] = @$arPeptide[self::REPLY_GENERAL][self::REPLY_FORMULA];
        $outArResult[Front::CANVAS_INPUT_MASS] = @$arPeptide[self::REPLY_GENERAL][self::REPLY_MOLECULAR_WEIGHT];
        $outArResult[Front::CANVAS_INPUT_SMILE] = @$arPeptide[self::REPLY_STRUCTURE][self::REPLY_SMILES];
        $outArResult[Front::CANVAS_INPUT_DATABASE] = ServerEnum::NORINE;
    }

    /**
     * Set result code define by counter value
     * @param int $intCounter
     * @param array $outArResult
     * @return int ResultEnum
     */
    private function returnCode($intCounter, &$outArResult) {
        switch ($intCounter) {
            case 0:
                unset($outArResult);
                return ResultEnum::REPLY_NONE;
            case 1:
                $outArResult = $outArResult[0];
                return ResultEnum::REPLY_OK_ONE;
            default:
                return ResultEnum::REPLY_OK_MORE;
        }
    }

}
