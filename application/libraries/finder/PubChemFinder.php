<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Enum\Constants;
use Bbdgnc\Finder\Enum\ResultEnum;

class PubChemFinder implements IFinder {

    /** Components of uri */
    const REST_DEF_URI = "https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/";
    const REST_PROPERTY_VALUES = "IUPACName,MolecularFormula,MolecularWeight,CanonicalSmiles/";
    const REST_PROPERTY = "/property/";

    /** Properties in JSON reply */
    const REPLY_TABLE_PROPERTIES = "PropertyTable";
    const REPLY_PROPERTIES = "Properties";
    const REPLY_FAULT = "Fault";
    const IDENTIFIER = "CID";
    const IUPAC_NAME = "IUPACName";
    const FORMULA = "MolecularFormula";
    const MASS = "MolecularWeight";
    const SMILE = "CanonicalSMILES";

    /**
     * Find data on PubChem by name
     * @param string $strName
     * @param array $outArResult
     * @return int result code
     */
    public function findByName($strName, &$outArResult = array()) {
        $uri = PubChemFinder::REST_DEF_URI . "name/" . $strName . PubChemFinder::REST_PROPERTY . PubChemFinder::REST_PROPERTY_VALUES . IFinder::REST_FORMAT_JSON;
        $decoded = $this->getJsonFromUri($uri);
        if ($decoded === false) {
            return ResultEnum::REPLY_NONE;
        }

        if (sizeof($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES]) == 1) {
            return  $this->resultOne($decoded, $outArResult);
        } else {
            foreach ($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES] as $intKey => $objItem) {
                $arMolecule = array();
                foreach ($objItem as $strProperty => $mixValue) {
                    $arMolecule[$this->getArrayKeyFromReplyProperty($strProperty)] = $mixValue;
                }
                $arMolecule[Constants::CANVAS_INPUT_DATABASE] = ServerEnum::PUBCHEM;
                $outArResult[$intKey] = $arMolecule;
            }
            return ResultEnum::REPLY_OK_MORE;
        }
    }

    /**
     * Find data by SMILES
     * @param string $strSmile
     * @return mixed
     */
    public function findBySmile($strSmile) {
        // TODO: Implement findBySmiles() method.
    }

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @return mixed
     */
    public function findByFormula($strFormula) {
        // TODO: Implement findByFormula() method.
    }

    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @return mixed
     */
    public function findByMass($decMass, $decTolerance) {
        // TODO: Implement findByMass() method.
    }

    /**
     * Find data by Identifier
     * @param string $strId
     * @param array $outArResult
     * @return int
     */
    public function findById($strId, &$outArResult) {
        $uri = PubChemFinder::REST_DEF_URI . "cid/" . $strId . PubChemFinder::REST_PROPERTY . PubChemFinder::REST_PROPERTY_VALUES . IFinder::REST_FORMAT_JSON;
        $decoded = $this->getJsonFromUri($uri);
        if ($decoded === false) {
            return ResultEnum::REPLY_NONE;
        }
        return $this->resultOne($decoded, $outArResult);
    }

    private function getJsonFromUri($strUri) {
        $objJson = @file_get_contents($strUri);

        /* Bad URI*/
        if ($objJson === false) {
            log_message('error', "REST Bad uri. Uri: " . $strUri);
            return false;
        }

        $decoded = json_decode($objJson, true);
        /* Bad reply */
        if (isset($decoded[PubChemFinder::REPLY_FAULT])) {
            log_message('error', "REST reply fault. Uri: " . $strUri);
            return false;
        }

        log_message('info', "Response OK to URI: $strUri");
        return $decoded;
    }

    private function getArrayKeyFromReplyProperty($strProperty) {
        switch ($strProperty) {
            case PubChemFinder::IDENTIFIER:
                return Constants::CANVAS_INPUT_IDENTIFIER;
            case PubChemFinder::IUPAC_NAME:
                return Constants::CANVAS_INPUT_NAME;
            case PubChemFinder::FORMULA:
                return Constants::CANVAS_INPUT_FORMULA;
            case PubChemFinder::MASS:
                return Constants::CANVAS_INPUT_MASS;
            case PubChemFinder::SMILE:
                return Constants::CANVAS_INPUT_SMILE;
        }
    }

    private function resultOne($decoded, &$outArResult) {
        foreach ($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES][0] as $strProperty => $mixValue) {
            $outArResult[$this->getArrayKeyFromReplyProperty($strProperty)] = $mixValue;
        }
        $outArResult[Constants::CANVAS_INPUT_DATABASE] = ServerEnum::PUBCHEM;
        return ResultEnum::REPLY_OK_ONE;
    }
}
