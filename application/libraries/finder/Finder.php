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
     * @return moleculeTO
     */
    public function findByIdentifier($intDatabase, $identifier) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findById($identifier);
    }

    public function findByName($intDatabase, $strName) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findByName($strName);
    }

}