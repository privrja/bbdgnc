<?php

namespace Bbdgnc\Finder;

interface IFinder {

    /** FORMAT of REST API output */
    const REST_FORMAT_JSON = "json";

    const FIRST_X_RESULTS = 10;

    /* better false, with true is too slow */
    const FIND_NAMES = false;

    /**
     * Find data on some server by name
     * @param string $strName
     * @param array $outArResult
     * @return int
     */
    public function findByName($strName, &$outArResult);

    /**
     * Find data by SMILES
     * @param string $strSmile
     * @return mixed
     */
    public function findBySmile($strSmile, &$outArResult);

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @return mixed
     */
    public function findByFormula($strFormula, &$outArResult, &$outArNextResults);

    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @return mixed
     */
    public function findByMass($decMass, $decTolerance, &$outArResult);

    /**
     * Find data by Identifier
     * @param string $strId
     * @param array $outArResult
     * @return int
     */
    public function findById($strId, &$outArResult);

}