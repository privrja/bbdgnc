<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Finder\Enum\ServerEnum;

abstract class FinderFactory {

    const OPTION_EXACT_MATCH = 'exact';

    private static function isOptionSet($strOption, $arOptions) {
        if (isset($arOptions[$strOption])) {
            return $arOptions[$strOption];
        } else return null;
    }

    /**
     * Factory for finders, get right finder by database
     * @param int $intDatabase
     * @return IFinder
     */
    public static function getFinder($intDatabase, $arOptions = array()) {
        switch ($intDatabase) {
            case ServerEnum::PUBCHEM:
                return new PubChemFinder(self::isOptionSet(self::OPTION_EXACT_MATCH, $arOptions));
            /* TODO */
//            case ServerEnum::CHEMSPIDER:
            case ServerEnum::NORINE:
                return new NorineFinder();
            case ServerEnum::PDB:
                return new PdbFinder();
        }
    }

}