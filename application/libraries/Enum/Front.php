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
    const CANVAS_HIDDEN_NEXT_RESULTS = "nextResults";
    const CANVAS_HIDDEN_SHOW_NEXT_RESULTS = "showNextResults";
    const BLOCK_SMILES = "blockSmiles";
    const BLOCK_SMILE = "blockSmile";
    const BLOCK_ACRONYM = "blockAcronym";
    const BLOCK_COUNT = "blockCount";
    const BLOCK_IDENTIFIER = "blockIdentifier";
    const BLOCK = "block";
    const BLOCKS = "blocks";
    const BLOCK_NAME = "blockName";
    const BLOCK_FORMULA = "blockFormula";
    const BLOCK_NEUTRAL_LOSSES = "blockLosses";
    const BLOCK_MASS = "blockMass";
    const BLOCK_REFERENCE = "blockReference";
    const SEQUENCE_TYPE = "sequenceType";
    const SEQUENCE = "sequence";

    const PAGES_MAIN = "pages/main";
    const PAGES_CANVAS = "pages/canvas";
    const PAGES_BLOCKS = "pages/blocks";
    const PAGES_SELECT = "pages/select";
    const TEMPLATES_FOOTER = "templates/footer";
    const TEMPLATES_HEADER = "templates/header";

    const MAX_LENGTH_TEXT = 20;
    const STRING_TREE_DOTS = "...";
    const REQUIRED = "required";

    public static function formula($strFormula) {
        return preg_replace('/(\d+)/', '<sub>$1</sub>', $strFormula);
    }

    public static function urlText($strText) {
        return str_replace(" ", "", $strText);
    }

    public static function smallerText($strText) {
        if (strlen($strText) > self::MAX_LENGTH_TEXT) {
            return substr($strText, 0, self::MAX_LENGTH_TEXT) . self::STRING_TREE_DOTS;
        } else {
            return $strText;
        }
    }

    public static function defIndex($array, $strKey) {
        if (isset($array[$strKey])) {
            return $array[$strKey];
        } else {
            return "";
        }
    }
}
