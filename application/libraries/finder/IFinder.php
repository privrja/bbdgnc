<?php

namespace Bbdgnc\Finder;

interface IFinder {

    /** FORMAT of REST API output */
    const REST_FORMAT_JSON = "json";
    const REST_QUESTION_MARK = "?";
    const REST_AMPERSAND = "&";
    const REST_SLASH = "/";
    const REST_EQUALS = "=";

    const FIRST_X_RESULTS = 10;

    /* better false, with true is too slow */
    const FIND_NAMES = false;

    /**
     * Find data on some server by name
     * @param string $strName
     * @param array $outArResult
     * @return int
     */
    public function findByName($strName, &$outArResult, &$outArNextResult);

    /**
     * Find data by SMILES
     * @param string $strSmile
     * @return int
     */
    public function findBySmile($strSmile, &$outArResult, &$outArNextResult);

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @return int
     */
    public function findByFormula($strFormula, &$outArResult, &$outArNextResult);

    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @return int
     */
    public function findByMass($decMass, $decTolerance, &$outArResult);

    /**
     * Find data by Identifier
     * @param string $strId
     * @param array $outArResult
     * @return int
     */
    public function findById($strId, &$outArResult);

    /**
     * Find by identifiers
     * @param array $arIds ids in array
     * @param array $outArResult result
     * @return int
     */
    public function findByIdentifiers($arIds, &$outArResult);

}