<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Finder\Enum\ServerEnum;

class Finder {

    const OPTION_EXACT_MATCH = 'exact';

    private $arOptions;

    private function isOptionSet($strOption) {
        if (isset($this->arOptions[$strOption])) {
            return $this->arOptions[$strOption];
        } else return null;
    }

    /**
     * Factory for finders, get right finder by database
     * @param int $intDatabase
     * @return IFinder
     */
    public function getFinder($intDatabase) {
        switch ($intDatabase) {
            case ServerEnum::PUBCHEM:
                return new PubChemFinder($this->isOptionSet(self::OPTION_EXACT_MATCH));
            /* TODO */
//            case ServerEnum::CHEMSPIDER:
            case ServerEnum::NORINE:
                return new NorineFinder();
            case ServerEnum::PDB:
                return new PdbFinder();
        }
    }

    public function setOptions($arOptions) {
        $this->arOptions = $arOptions;
    }

    /**
     * Find by Identifier
     * @param int $intDatabase
     * @param mixed $identifier
     * @return int
     */
    public function findByIdentifier($intDatabase, $identifier, &$outArResult) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findById($identifier, $outArResult);
    }

    /**
     * Find by Name
     * @param int $intDatabase ServerEnum selected server
     * @param string $strName param for finding
     * @param array $outArResult first X results
     * @param array $outArNextResult ids of next results
     * @return int ResultEnum result dode
     */
    public function findByName($intDatabase, $strName, &$outArResult, &$outArNextResult) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findByName($strName, $outArResult, $outArNextResult);
    }

    /**
     * Find by SMILES
     * @param int $intDatabase
     * @param string $strSmile
     * @param array $outArResult
     * @param array $outArNextResult
     * @return int ResultEnum
     */
    public function findBySmile($intDatabase, $strSmile, &$outArResult, &$outArNextResult) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findBySmile($strSmile, $outArResult, $outArNextResult);
    }

    /**
     * Find by Formula
     * @param int $intDatabase ServerEnum selected server
     * @param string $strFormula param for finding
     * @param array $outArResult first X results
     * @param array $outArNextResult ids of next results
     * @return int
     */
    public function findByFormula($intDatabase, $strFormula, &$outArResult, &$outArNextResult) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findByFormula($strFormula, $outArResult, $outArNextResult);
    }

    /**
     * Find by Identifiers
     * @param int $intDatabase ServerEnum selected database
     * @param array $arIds identifiers
     * @param array $outArResult result
     * @return int
     */
    public function findByIdentifiers($intDatabase, $arIds, &$outArResult) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findByIdentifiers($arIds, $outArResult);
    }

}