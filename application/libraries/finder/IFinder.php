<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Finder\Enum\ResultEnum;

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
     * @param array $outArNextResult id with next results
     * @return int
     * @see ResultEnum
     * @throws Exception\BadTransferException
     */
    public function findByName($strName, &$outArResult, &$outArNextResult);

    /**
     * Find data by SMILES
     * @param string $strSmile
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     * @see ResultEnum
     * @throws Exception\BadTransferException
     */
    public function findBySmile($strSmile, &$outArResult, &$outArNextResult);

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     * @see ResultEnum
     * @throws Exception\BadTransferException
     */
    public function findByFormula($strFormula, &$outArResult, &$outArNextResult);

    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @param array $outArResult
     * @param $outArNextResult
     * @return int
     * @see ResultEnum
     * @throws Exception\BadTransferException
     */
    public function findByMass($decMass, $decTolerance, &$outArResult, &$outArNextResult);

    /**
     * Find data by Identifier
     * @param string $strId
     * @param array $outArResult
     * @return int
     * @see ResultEnum
     * @throws Exception\BadTransferException
     */
    public function findByIdentifier($strId, &$outArResult);

    /**
     * Find by identifiers
     * @param array $arIds ids in array
     * @param array $outArResult result
     * @return int
     * @see ResultEnum
     * @throws Exception\BadTransferException
     */
    public function findByIdentifiers($arIds, &$outArResult);

}
