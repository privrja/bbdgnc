<?php

namespace Bbdgnc\Enum;

abstract class Constants {

    const CANVAS_INPUT_DATABASE = "database";
    const CANVAS_INPUT_NAME = "name";
    const CANVAS_INPUT_SMILE = "smile";
    const CANVAS_INPUT_FORMULA = "formula";
    const CANVAS_INPUT_MASS = "mass";
    const CANVAS_INPUT_IDENTIFIER = "identifier";
    const CANVAS_HIDDEN_DATABASE = "hddatabase";
    const CANVAS_HIDDEN_NAME = "hdname";

    public static function formula($strFormula) {
        return preg_replace('/(\d+)/', '<sub>$1</sub>', $strFormula);
    }

    public  static function urlText($strText) {
        return str_replace(" ", "", $strText);
    }
}