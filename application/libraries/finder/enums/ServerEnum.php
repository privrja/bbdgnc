<?php

namespace Bbdgnc\Finder\Enum;

abstract class ServerEnum {

    /** @var int servers */
    const PUBCHEM = 0;
    const CHEBI = 4;

    /** @var array mapping int code to string */
    public static $values = array(
        self::PUBCHEM => "PubChem",
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
            default:
            case self::PUBCHEM:
                return "https://pubchem.ncbi.nlm.nih.gov/compound/" . $strIdentifier;
            case self::CHEBI:
                return "https://www.ebi.ac.uk/chebi/searchId.do?chebiId=" . $strIdentifier;
        }
    }
}