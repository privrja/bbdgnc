<?php

namespace Bbdgnc\Base;

use Bbdgnc\Finder\Enum\ServerEnum;

class ReferenceHelper {

    const SMILES = "SMILES: ";

    public static function reference($database, $reference, $smiles) {
        if ($reference == 0) {
            self::defaultValue($smiles);
        }
        switch ($database) {
            case ServerEnum::PUBCHEM:
                return ServerEnum::$cycloBranchValues[ServerEnum::CHEMSPIDER] . $reference;
            case ServerEnum::CHEMSPIDER:
                return ServerEnum::$cycloBranchValues[ServerEnum::PUBCHEM] . $reference;
            case ServerEnum::PDB:
                return ServerEnum::$cycloBranchValues[ServerEnum::PDB] . $reference;
            case ServerEnum::NORINE:
                return $reference;
            default:
                return self::defaultValue($smiles);
        }
    }

    private static function defaultValue($smiles) {
            return self::SMILES . $smiles;
    }

}
