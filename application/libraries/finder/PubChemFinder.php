<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Enum\Constants;
use Bbdgnc\Finder\Enum\ResultEnum;

class PubChemFinder implements IFinder {

    /** Components of uri */
    const REST_DEF_URI = "https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/";
    const REST_PROPERTY_VALUES = "IUPACName,MolecularFormula,MonoisotopicMass,CanonicalSmiles/";
    const REST_PROPERTY = "/property/";
    const REST_NAME_SPECIFICATION = "?name_type=word";

    /** Properties in JSON reply */
    const REPLY_TABLE_PROPERTIES = "PropertyTable";
    const REPLY_PROPERTIES = "Properties";
    const REPLY_FAULT = "Fault";
    const REPLY_IDENTIFIER_LIST = "IdentifierList";
    const REPLY_CID = "CID";
    const IDENTIFIER = "CID";
    const IUPAC_NAME = "IUPACName";
    const FORMULA = "MolecularFormula";
    const MASS = "MonoisotopicMass";
    const SMILE = "CanonicalSMILES";

    /**
     * Find data on PubChem by name
     * @param string $strName find by this name
     * @param array $outArResult output param result, only first X results
     * @return int result code
     */
    public function findByName($strName, &$outArResult) {
        $uri = PubChemFinder::REST_DEF_URI . "name/" . Constants::urlText($strName) . PubChemFinder::REST_PROPERTY . PubChemFinder::REST_PROPERTY_VALUES . IFinder::REST_FORMAT_JSON . PubChemFinder::REST_NAME_SPECIFICATION;
        $decoded = $this->getJsonFromUri($uri);
        if ($decoded === false) {
            return ResultEnum::REPLY_NONE;
        }

        if (sizeof($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES]) == 1) {
            return $this->resultOne($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES][0], $outArResult, true);
        } else {
            foreach ($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES] as $intKey => $objItem) {
                $arMolecule = array();
                $this->resultOne($objItem, $arMolecule);
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
    public function findBySmile($strSmile, &$outArResult) {
        // TODO: Implement findBySmiles() method.
    }

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @param array $outArResult output param result, only first X results
     * @param array $outArNextResults next results as array of identifiers
     * @return int ResultEnum
     */
    public function findByFormula($strFormula, &$outArResult, &$outArNextResults) {
        $uri = PubChemFinder::REST_DEF_URI . "fastformula/" . $strFormula . "/cids/" . IFinder::REST_FORMAT_JSON;
        $decoded = $this->getJsonFromUri($uri);
        if ($decoded === false) {
            return ResultEnum::REPLY_NONE;
        }

        $intCounter = 1;
        $strIds = "";
        $blNextResults = false;
        foreach ($decoded[PubChemFinder::REPLY_IDENTIFIER_LIST][PubChemFinder::REPLY_CID] as $intCid) {
            if ($blNextResults) {
                $outArNextResults[$intCounter] = $intCid;
            } else {
                $strIds .= $intCid . ",";
                if ($intCounter == IFinder::FIRST_X_RESULTS) {
                    $strIds = rtrim($strIds, ",");
                    $this->findByIds($strIds, $outArResult);
                    $intCounter = 0;
                    $blNextResults = true;
                }
            }
            $intCounter++;
        }

        if (!$blNextResults) {
            $strIds = rtrim($strIds, ",");
            $this->findByIds($strIds, $outArResult);
        }

        if (!$blNextResults && $intCounter == 2) {
            return ResultEnum::REPLY_OK_ONE;
        } else {
            return ResultEnum::REPLY_OK_MORE;
        }
    }


    /**
     * Get string input of ids and get info about them from PubChem
     * @param string $strIds example: 45545,46546,8945 beware of too long for HTTTP GET request
     * @param array $outArResult output param info about molecules
     * @return int ResultEnum
     */
    private function findByIds($strIds, &$outArResult) {
        $uri = PubChemFinder::REST_DEF_URI . "cid/" . $strIds . PubChemFinder::REST_PROPERTY . PubChemFinder::REST_PROPERTY_VALUES . IFinder::REST_FORMAT_JSON;
        $decoded = $this->getJsonFromUri($uri);
        if ($decoded === false) {
            return ResultEnum::REPLY_NONE;
        }

        if (sizeof($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES]) == 1) {
            return $this->resultOne($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES][0], $outArResult);
        } else {
            foreach ($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES] as $intKey => $objItem) {
                $arMolecule = array();
                $this->resultOne($objItem, $arMolecule);
                $outArResult[$intKey] = $arMolecule;
            }
            return ResultEnum::REPLY_OK_MORE;
        }
    }
    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @return mixed
     */
    public function findByMass($decMass, $decTolerance, &$outArResult) {
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
        return $this->resultOne($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES][0], $outArResult, true);
    }

    /**
     * Find name in synonyms on PubChem
     * @param string $strId identifier
     * @param string $outStrName output param find name
     * @return int ResultEnum
     */
    public function findName($strId, &$outStrName) {
        $outStrName = $this->getNames($strId);
        if (empty($outStrName)) {
            return ResultEnum::REPLY_NONE;
        }
        return ResultEnum::REPLY_OK_ONE;
    }

    /**
     * Get decoded JSON from URI to array
     * @param string $strUri url address to call
     * @return bool|mixed something goes wrong => false, elsewhere the result
     */
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

    /**
     * From PubChem keys in array to keys used in view
     * @param string $strProperty PubChem key
     * @return string key used in view
     */
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

    /**
     * Get name from identifier on PubChem synonyms
     * !!! too slow !!! when call to many request
     * get all names and than get only the first name
     * @param string $strId identifier
     * @param string $strDefaultName default name if name not found on PubChem
     * @return string name from PubChem or default name
     */
    private function getNames($strId, $strDefaultName = "") {
        $strUri = PubChemFinder::REST_DEF_URI . "cid/" . $strId . "/synonyms/" . IFinder::REST_FORMAT_JSON;
        $decoded = $this->getJsonFromUri($strUri);
        if ($decoded === false) {
            return $strDefaultName;
        }
        foreach ($decoded['InformationList']['Information'][0]['Synonym'] as $strSynonym) {
            return $strSynonym;
        }
        return $strDefaultName;
    }

    /**
     * Transform PubChem object in array to array used in view with right keys
     * @param array $objItem PubChem object in array
     * @param array $outArResult output param array used in view
     * @param bool $blFindName true => find name on PubChem (new request to REST API), false => don't find name
     * @return int ResultEnum
     */
    private function resultOne($objItem, &$outArResult, $blFindName = IFinder::FIND_NAMES) {
        foreach ($objItem as $strProperty => $mixValue) {
            if ($strProperty == PubChemFinder::IUPAC_NAME) {
                /* too slow with true */
                if ($blFindName) {
                    $mixValue = $this->getNames($outArResult[Constants::CANVAS_INPUT_IDENTIFIER]);
                }
            }
            $outArResult[$this->getArrayKeyFromReplyProperty($strProperty)] = $mixValue;
        }
        $outArResult[Constants::CANVAS_HIDDEN_DATABASE] = ServerEnum::PUBCHEM;
        return ResultEnum::REPLY_OK_ONE;
    }
}
