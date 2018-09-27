<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ResultEnum;

class PubChemFinder implements IFinder {

    /** Components of uri */
    const REST_DEF_URI = "https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/";
    const REST_PROPERTY_VALUES = "IUPACName,MolecularFormula,MonoisotopicMass,CanonicalSmiles/";
    const REST_PROPERTY = "/property/";
    const REST_CIDS = "/cids/";
    const REST_LIST_KEY = "listkey";
    const REST_LIST_RETURN = "list_return=";
    const REST_LIST_KEY_START = "listkey_start=";
    const REST_LIST_KEY_COUNT = "listkey_count=";
    const REST_NAME_SPECIFICATION = "name_type=word";
    const REST_COUNT = 200;

    /** Properties in JSON reply */
    const REPLY_TABLE_PROPERTIES = "PropertyTable";
    const REPLY_PROPERTIES = "Properties";
    const REPLY_FAULT = "Fault";
    const REPLY_IDENTIFIER_LIST = "IdentifierList";
    const REPLY_LIST_KEY = "ListKey";
    const REPLY_CID = "CID";
    const IDENTIFIER = self::REPLY_CID;
    const IUPAC_NAME = "IUPACName";
    const FORMULA = "MolecularFormula";
    const MASS = "MonoisotopicMass";
    const SMILE = "CanonicalSMILES";

    /** Search options */
    /** @var bool when find by name, find exact the same word? */
    const OPTION_EXACT_MATCH = true;

    private $blExactMatch;

    /**
     * PubChemFinder constructor.
     * @param bool $blExactMatch find exact match when find by name
     */
    public function __construct($blExactMatch = self::OPTION_EXACT_MATCH) {
        $this->blExactMatch = $blExactMatch == null ? $blExactMatch : self::OPTION_EXACT_MATCH;
    }

    /**
     * Url value for exact match
     * @return string url value for exact match
     */
    private function exactMatchUrlValue() {
        if ($this->blExactMatch) {
            return IFinder::REST_QUESTION_MARK;
        } else {
            return IFinder::REST_QUESTION_MARK . PubChemFinder::REST_NAME_SPECIFICATION . PubChemFinder::REST_AMPERSAND;
        }
    }

    /**
     * Find data on PubChem by name
     * @param string $strName find by this name
     * @param array $outArResult output param result, only first X results
     * @return int result code
     */
    public function findByName($strName, &$outArResult, &$outArNextResult) {
        $strBaseUri = PubChemFinder::REST_DEF_URI . "name/" . Front::urlText($strName) .
            PubChemFinder::REST_CIDS . IFinder::REST_FORMAT_JSON . $this->exactMatchUrlValue();
        $strUri = $strBaseUri . PubChemFinder::REST_LIST_RETURN .
            PubChemFinder::REST_LIST_KEY;

        $mixDecoded = $this->getJsonFromUri($strUri);
        if ($mixDecoded === false) {
            return ResultEnum::REPLY_NONE;
        }

        /* get list key */
        $listKey = $mixDecoded[PubChemFinder::REPLY_IDENTIFIER_LIST][PubChemFinder::REPLY_LIST_KEY];
        $strBaseUri .= PubChemFinder::REST_LIST_KEY .
            PubChemFinder::REST_EQUALS . $listKey . IFinder::REST_AMPERSAND .
            PubChemFinder::REST_LIST_KEY_START . "0" . IFinder::REST_AMPERSAND .
            PubChemFinder::REST_LIST_KEY_COUNT . PubChemFinder::REST_COUNT;

        /* request list key and get data of first molecules */
        return $this->getMoleculesFromListKey($strBaseUri, $outArResult, $outArNextResult);
    }

    /**
     * Find data by SMILES
     * @param string $strSmile
     * @return mixed
     */
    public function findBySmile($strSmile, &$outArResult, &$outArNextResult) {
        $strBaseUri = self::REST_DEF_URI . "smiles/" . $strSmile . self::REST_CIDS . IFinder::REST_FORMAT_JSON . IFinder::REST_QUESTION_MARK;
        $strUri = $strBaseUri . self::REST_LIST_RETURN . self::REST_LIST_KEY;

        $mixDecoded = $this->getJsonFromUri($strUri);
        if ($mixDecoded === false) {
            return ResultEnum::REPLY_NONE;
        }

        $listKey = $mixDecoded[PubChemFinder::REPLY_IDENTIFIER_LIST][PubChemFinder::REPLY_LIST_KEY];
        $strBaseUri .= PubChemFinder::REST_LIST_KEY .
            PubChemFinder::REST_EQUALS . $listKey . IFinder::REST_AMPERSAND .
            PubChemFinder::REST_LIST_KEY_START . "0" . IFinder::REST_AMPERSAND .
            PubChemFinder::REST_LIST_KEY_COUNT . PubChemFinder::REST_COUNT;
        return $this->getMoleculesFromListKey($strBaseUri, $outArResult, $outArNextResult);

    }

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @param array $outArResult output param result, only first X results
     * @param array $outArNextResult next results as array of identifiers
     * @return int ResultEnum
     */
    public function findByFormula($strFormula, &$outArResult, &$outArNextResult) {
        $uri = PubChemFinder::REST_DEF_URI . "fastformula/" . $strFormula . PubChemFinder::REST_CIDS . IFinder::REST_FORMAT_JSON;
        return $this->getMoleculesFromListKey($uri, $outArResult, $outArNextResult);
    }

    private function getMoleculesFromListKey($strUri, &$outArResult, &$outArNextResult) {
        $mixDecoded = $this->getJsonFromUri($strUri);
        if ($mixDecoded === false) {
            return ResultEnum::REPLY_NONE;
        }
        $intCounter = 0;
        foreach ($mixDecoded[PubChemFinder::REPLY_IDENTIFIER_LIST][PubChemFinder::REPLY_CID] as $intCid) {
            $outArNextResult[$intCounter] = $intCid;
            $intCounter++;
        }

        if ($intCounter >= IFinder::FIRST_X_RESULTS) {
            $outArResult = array_splice($outArNextResult, 0, IFinder::FIRST_X_RESULTS);
        } else {
            $outArResult = $outArNextResult;
            $outArNextResult = array();
        }
        $this->findByIdentifiers($outArResult, $outArResult);

        if ($intCounter == 1) {
            $outArResult[Front::CANVAS_INPUT_NAME] = $this->getNames($outArResult[Front::CANVAS_INPUT_IDENTIFIER], $outArResult[Front::CANVAS_INPUT_NAME]);
            return ResultEnum::REPLY_OK_ONE;
        } else {
            return ResultEnum::REPLY_OK_MORE;
        }
    }

    /**
     * Get string input of ids and get info about them from PubChem
     * @param string $arIds example: 45545,46546,8945 beware of too long for HTTTP GET request
     * @param array $outArResult output param info about molecules
     * @return int ResultEnum
     */
    public function findByIdentifiers($arIds, &$outArResult) {
        $strIds = $this->getStringIdsFromArray($arIds);
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

    private function getStringIdsFromArray($arIds) {
        $strIds = "";
        foreach ($arIds as $intId) {
            $strIds .= $intId . ",";
        }
        return rtrim($strIds, ",");
    }

    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @return mixed
     */
    public
    function findByMass($decMass, $decTolerance, &$outArResult) {
        // TODO: Implement findByMass() method.
    }

    /**
     * Find data by Identifier
     * @param string $strId
     * @param array $outArResult
     * @return int
     */
    public
    function findById($strId, &$outArResult) {
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
    public
    function findName($strId, &$outStrName) {
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
    private
    function getJsonFromUri($strUri) {
        $curl = curl_init($strUri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
//            $info = curl_getinfo($curl);
            curl_close($curl);
            log_message("error", "Error occured during curl exec. Uri: " . $strUri);
            return null;
        }
        curl_close($curl);
        $decoded = json_decode($curl_response, true);

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
    private
    function getArrayKeyFromReplyProperty($strProperty) {
        switch ($strProperty) {
            case PubChemFinder::IDENTIFIER:
                return Front::CANVAS_INPUT_IDENTIFIER;
            case PubChemFinder::IUPAC_NAME:
                return Front::CANVAS_INPUT_NAME;
            case PubChemFinder::FORMULA:
                return Front::CANVAS_INPUT_FORMULA;
            case PubChemFinder::MASS:
                return Front::CANVAS_INPUT_MASS;
            case PubChemFinder::SMILE:
                return Front::CANVAS_INPUT_SMILE;
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
    private
    function getNames($strId, $strDefaultName = "") {
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
    private
    function resultOne($objItem, &$outArResult, $blFindName = IFinder::FIND_NAMES) {
        foreach ($objItem as $strProperty => $mixValue) {
            if ($strProperty == PubChemFinder::IUPAC_NAME) {
                /* too slow with true */
                if ($blFindName) {
                    $mixValue = $this->getNames($outArResult[Front::CANVAS_INPUT_IDENTIFIER], $mixValue);
                }
            }
            $outArResult[$this->getArrayKeyFromReplyProperty($strProperty)] = $mixValue;
        }
        $outArResult[Front::CANVAS_HIDDEN_DATABASE] = ServerEnum::PUBCHEM;
        return ResultEnum::REPLY_OK_ONE;
    }

}
