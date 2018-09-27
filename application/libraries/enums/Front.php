<?php

namespace Bbdgnc\Enum;

abstract class Front {

    /** @var string name of inputs */
    const CANVAS_INPUT_SEARCH_BY = "search";
    const CANVAS_INPUT_DATABASE = "database";
    const CANVAS_INPUT_NAME = "name";
    const CANVAS_INPUT_MATCH = "match";
    const CANVAS_INPUT_SMILE = "smile";
    const CANVAS_INPUT_FORMULA = "formula";
    const CANVAS_INPUT_MASS = "mass";
    const CANVAS_INPUT_DEFLECTION = "deflection";
    const CANVAS_INPUT_IDENTIFIER = "identifier";

    /** @var string name of hidden inputs */
    const CANVAS_HIDDEN_DATABASE = "hdDatabase";
    const CANVAS_HIDDEN_NAME = "hdName";
    const CANVAS_HIDDEN_SMILE = "hdSmile";
    const CANVAS_HIDDEN_FORMULA = "hdFormula";
    const CANVAS_HIDDEN_MASS = "hdMass";
    const CANVAS_HIDDEN_DEFLECTION = "hdDeflection";
    const CANVAS_HIDDEN_IDENTIFIER = "hdIdentifier";

    const MAX_LENGTH_TEXT = 20;
    const STRING_TREE_DOTS = "...";

    public static function formula($strFormula) {
        return preg_replace('/(\d+)/', '<sub>$1</sub>', $strFormula);
    }

    public static function urlText($strText) {
        return str_replace(" ", "", $strText);
    }

    public static function smallerText($strText) {
//        if (strlen($strText) > self::MAX_LENGTH_TEXT) {
            return substr($strText, 0, self::MAX_LENGTH_TEXT) . self::STRING_TREE_DOTS;
//        } else {
//            return $strText;
//        }
    }

    public static function defIndex($array, $strKey) {
        if (isset($array[$strKey])) {
            return $array[$strKey];
        } else {
            return "";
        }
    }
}
