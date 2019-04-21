<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Finder\Enum\ServerEnum;

abstract class FinderFactory {

    const OPTION_EXACT_MATCH = 'exact';

    /**
     * @param string $strOption
     * @param array $arOptions
     * @return string|null
     */
    private static function isOptionSet($strOption, $arOptions) {
        if (isset($arOptions[$strOption])) {
            return $arOptions[$strOption];
        } else {
            return null;
        }
    }

    /**
     * Factory for finders, get right finder by database
     * @param int $intDatabase
     * @param array $arOptions options for finder, default empty array
     * @return IFinder
     */
    public static function getFinder($intDatabase, $arOptions = array()) {
        switch ($intDatabase) {
            default:
            case ServerEnum::PUBCHEM:
                return new PubChemFinder(self::isOptionSet(self::OPTION_EXACT_MATCH, $arOptions));
            case ServerEnum::CHEBI:
                return new ChebiFinder();
            case ServerEnum::NORINE:
                return new NorineFinder();
        }
    }

}