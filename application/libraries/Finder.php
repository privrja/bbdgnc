<?php

class Finder {

    /**
     * Finder constructor.
     */
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library("ServerEnum");
        $this->CI->load->library("FindByEnum");
        $this->CI->load->library("PubChemFinder");
        $this->CI->load->library("MoleculeTO");
    }

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