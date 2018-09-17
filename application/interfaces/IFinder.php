<?php

interface IFinder {

    /**
     * Find data on some server by name
     * @param string $strName
     * @return mixed
     */
    public function findByName($strName);

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
     * @return mixed
     */
    public function findById($strId);

}