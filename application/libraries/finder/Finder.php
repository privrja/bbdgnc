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
//            case ServerEnum::NORINE:
//            case ServerEnum::PDB:
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

    public function findByName($intDatabase, $strName, &$outArResult, &$outArNextResult) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findByName($strName, $outArResult, $outArNextResult);
    }

    public function findBySmile($intDatabase, $strSmile, &$outArResult, &$outArNextResult) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findBySmile($strSmile, $outArResult, $outArNextResult);
    }

    public function findByFormula($intDatabase, $strFormula, &$outArResult, &$outArNextResult) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findByFormula($strFormula, $outArResult, $outArNextResult);
    }

    public function findByIdentifiers($intDatabase, $arIds, &$outArResult) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findByIdentifiers($arIds, $outArResult);
    }

}