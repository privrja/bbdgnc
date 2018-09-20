<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Finder\Enum\ServerEnum;

class Finder {

    /**
     * Factory for finders, get right finder by database
     * @param int $intDatabase
     * @return IFinder
     */
    public function getFinder($intDatabase) {
        switch ($intDatabase) {
            case ServerEnum::PUBCHEM:
                return new PubChemFinder();
                /* TODO */
//            case ServerEnum::CHEMSPIDER:
//            case ServerEnum::NORINE:
//            case ServerEnum::PDB:
        }
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

    public function findByFormula($intDatabase, $strFormula, &$outArResult, &$outArNextResult) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findByFormula($strFormula, $outArResult, $outArNextResult);
    }

}