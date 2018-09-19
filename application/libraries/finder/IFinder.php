<?php

namespace Bbdgnc\Finder;

interface IFinder {

    /** FORMAT of REST API output */
    const REST_FORMAT_JSON = "json";

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
    public function findBySmile($strSmile);

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @return mixed
     */
    public function findByFormula($strFormula);

    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @return mixed
     */
    public function findByMass($decMass, $decTolerance);

    /**
     * Find data by Identificator
     * @param string $strId
     * @param array $outArResult
     * @return int
     */
    public function findById($strId, &$outArResult);

}