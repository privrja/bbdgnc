<?php

namespace Bbdgnc\Finder\Enum;

abstract class ServerEnum {
    const PUBCHEM = 0;
    const CHEMSPIDER = 1;
    const NORINE = 2;
    const PDB = 3;

    public static $values = array(
        self::PUBCHEM => "PubChem",
        self::CHEMSPIDER => "ChemSpider",
        self::NORINE => "Norine",
        self::PDB => "PDB"
    );

    public static function getLink($intServerEnum) {

    }
}