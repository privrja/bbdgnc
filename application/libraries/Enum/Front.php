<?php

namespace Bbdgnc\Enum;

use Bbdgnc\Base\BetweenFilter;
use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\LikeFilter;
use Bbdgnc\Base\Query;
use Bbdgnc\Base\SameFilter;
use Bbdgnc\Base\Sortable;
use Bbdgnc\Base\SortDirectionEnum;

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
    const BLOCK_REFERENCE_SERVER = "blockReferenceServer";
    const BLOCK_DATABASE_ID = "blockDatabaseId";
    const BLOCK_SELECT = "blockSelect";
    const SEQUENCE_TYPE = "sequenceType";
    const SEQUENCE = "sequence";
    const MODIFICATION_SELECT = "Select";
    const MODIFICATION_NAME = "Modification";
    const MODIFICATION_FORMULA = "Formula";
    const MODIFICATION_MASS = "Mass";
    const MODIFICATION_TERMINAL_N = "TerminalN";
    const MODIFICATION_TERMINAL_C = "TerminalC";
    const N_MODIFICATION_SELECT = "nSelect";
    const N_MODIFICATION_NAME = "nModification";
    const N_MODIFICATION_FORMULA = "nFormula";
    const N_MODIFICATION_MASS = "nMass";
    const N_MODIFICATION_TERMINAL_N = "nTerminalN";
    const N_MODIFICATION_TERMINAL_C = "nTerminalC";
    const C_MODIFICATION_SELECT = "cSelect";
    const C_MODIFICATION_NAME = "cModification";
    const C_MODIFICATION_FORMULA = "cFormula";
    const C_MODIFICATION_MASS = "cMass";
    const C_MODIFICATION_TERMINAL_N = "cTerminalN";
    const C_MODIFICATION_TERMINAL_C = "cTerminalC";
    const B_MODIFICATION_SELECT = "bSelect";
    const B_MODIFICATION_NAME = "bModification";
    const B_MODIFICATION_FORMULA = "bFormula";
    const B_MODIFICATION_MASS = "bMass";
    const B_MODIFICATION_TERMINAL_N = "bTerminalN";
    const B_MODIFICATION_TERMINAL_C = "bTerminalC";

    const PAGES_MAIN = "pages/main";
    const PAGES_CANVAS = "pages/canvas";
    const PAGES_BLOCKS = "pages/blocks";
    const PAGES_SELECT = "pages/select";
    const TEMPLATES_FOOTER = "templates/footer";
    const TEMPLATES_HEADER = "templates/header";


    const ERRORS = "errors";
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

    public static function checked($checked) {
        return ($checked === "true" || $checked === "on" || $checked == 1) ? "checked" : "";
    }

    public static function toBoolean($checked) {
        return $checked != "false";
    }

    public static function removeWhiteSpace(string $str) {
        return preg_replace('/\s+/', '', $str);
    }

    public static function isEmpty($value) {
        return isset($value) && $value !== "";
    }

    public static function addLikeFilter(string $key, string $tableName, Query $query, $controller) {
        $filter = $controller->input->get($key, true);
        if (Front::isEmpty($filter)) {
            $query->addFilterable(new LikeFilter($tableName . CommonConstants::DOT . $key, $filter));
        }
    }

    public static function addBetweenFilter(string $key, string $tableName, Query $query, $controller) {
        $filterFrom = $controller->input->get($key . 'From', true);
        $filterTo = $controller->input->get($key . "To", true);
        if (Front::isEmpty($filterFrom) && Front::isEmpty($filterTo) && $filterFrom <= $filterTo) {
            $query->addFilterable(new BetweenFilter($tableName . CommonConstants::DOT . $key, $filterFrom, $filterTo));
        }
    }

    public static function addSameFilter(string $key, string $tableName, Query $query, $controller) {
        $filter = $controller->input->get($key, true);
        if (Front::isEmpty($filter)) {
            $query->addFilterable(new SameFilter($tableName . CommonConstants::DOT . $key, $filter));
        }
    }

    public static function addSortable(string $key, string $tableName, Query $query, $controller) {
        $sort = $controller->input->get($key . 'Sort', true);
        if (Front::isEmpty($sort) && $sort === SortDirectionEnum::DESC || $sort === SortDirectionEnum::ASC) {
            $query->addSortable(new Sortable($tableName . CommonConstants::DOT . $key, $sort));
            return $sort;
        }
        return '';
    }

    public static function setValue($field, $default = '', $html_escape = true) {
        $CI =& get_instance();

        $value = (isset($CI->form_validation) && is_object($CI->form_validation) && $CI->form_validation->has_rule($field))
            ? $CI->form_validation->set_value($field, $default)
            : $CI->input->post($field, FALSE);

        isset($value) || $value = ($CI->input->get($field) === null ? $default : $CI->input->get($field));
        return ($html_escape) ? html_escape($value) : $value;
    }

    public static function getSortDirection(array $sort) {
        foreach ($sort as $direction) {
            if ($direction === SortDirectionEnum::ASC) {
                return SortDirectionEnum::DESC;
            } else if ($direction === SortDirectionEnum::DESC) {
                return SortDirectionEnum::ASC;
            }
        }
        return SortDirectionEnum::ASC;
    }

}
