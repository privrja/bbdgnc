<?php

class Finder {

    const REST_FOMRAT_JSON = "json";

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

    public function findByIdentifier($intDatabase, $identifier) {
        $finder = $this->getFinder($intDatabase);
        return $finder->findById($identifier);
    }

}