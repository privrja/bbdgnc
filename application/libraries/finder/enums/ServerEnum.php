<?php

namespace Bbdgnc\Finder\Enum;

abstract class ServerEnum {

    /** @var int servers */
    const PUBCHEM = 0;
    const CHEMSPIDER = 1;
    const NORINE = 2;
    const PDB = 3;
    const CHEBI = 4;

    /** @var array mapping int code to string */
    public static $values = array(
        self::PUBCHEM => "PubChem",
        self::CHEMSPIDER => "ChemSpider",
        self::NORINE => "Norine",
        self::PDB => "PDB",
        self::CHEBI => "ChEBI"
    );

    /**
     * Create link to web page to the molecule
     * @param int $intServerEnum enum code for server
     * @param string $strIdentifier molecule identifier
     * @return string link to molecule on web
     */
    public static function getLink($intServerEnum, $strIdentifier) {
        switch ($intServerEnum) {
            case self::PUBCHEM:
                return "https://pubchem.ncbi.nlm.nih.gov/compound/" . $strIdentifier;
                break;
            case self::CHEMSPIDER:
                return "http://www.chemspider.com/Chemical-Structure." . $strIdentifier . ".html";
                break;
        }
    }
}