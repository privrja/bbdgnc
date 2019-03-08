<?php

namespace Bbdgnc\Base;

use Bbdgnc\Finder\Enum\ServerEnum;

class ReferenceHelper {

    const COLON = ": ";
    const SMILES = "SMILES: ";

    public static function reference(int $database, $reference, $smiles) {
        if ($reference == 0) {
            self::defaultValue($smiles);
        }
        switch ($database) {
            case ServerEnum::PUBCHEM:
                return ServerEnum::$allValues[ServerEnum::CHEMSPIDER] . self::COLON . $reference;
            case ServerEnum::CHEMSPIDER:
                return ServerEnum::$allValues[ServerEnum::PUBCHEM] . self::COLON . $reference;
            case ServerEnum::PDB:
                return ServerEnum::$allValues[ServerEnum::PDB] . self::COLON . $reference;
            case ServerEnum::NORINE:
                return ServerEnum::$allValues[ServerEnum::NORINE] . self::COLON . $reference;
            default:
                return self::defaultValue($smiles);
        }
    }

    private static function defaultValue($smiles) {
            return self::SMILES . $smiles;
    }

}
