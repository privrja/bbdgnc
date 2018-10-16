<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ChebiSearchCategoryEnum;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

class ChebiFinder implements IFinder {

    /** @var string URI for wsdl file */
    const WSDL = 'https://www.ebi.ac.uk/webservices/chebi/2.0/webservice?wsdl';

    /** @var int max results in one query */
    const MAX_RESULTS = 50;

    /** @var string search category option */
    const CATEGORY_ALL = "ALL";

    /** @var int CHEBI:12151 length of CHEBI: */
    const IDENTIFIER_PREFIX_SIZE = 6;

    /** @var string input keys for SOAP */
    const SOAP_SEARCH = "search";
    const SOAP_SEARCH_CATEGORY = "searchCategory";
    const SOAP_MAX_RESULTS = "maximumResults";
    const SOAP_STARS = "stars";
    const SOAP_STRUCTURE = "structure";
    const SOAP_TYPE = "type";
    const SOAP_STRUCTURE_SEARCH_CATEGORY = "structureSearchCategory";
    const SOAP_TOTAL_RESULTS = "totalResults";
    const SOAP_TANIMOTO_CUTOFF = "tanimotoCutoff";

    const TOTAL_RESULTS_VALUE = 50;
    const TANIMOTO_CUTOFF_VALUE = 0.25;
    const STRUCTURE_SEARCH_CATEGORY_VALUE = "SIMILARITY";
    const TYPE_VALUE = "SMILES";

    /** @var array default options for query */
    private $options = array('exceptions' => true);

    /**
     * Find data on some server by name
     * @param string $strName
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     */
    public function findByName($strName, &$outArResult, &$outArNextResult) {
        return $this->getLiteEntity($strName, ChebiSearchCategoryEnum::ALL, $outArResult, $outArNextResult);
    }

    /**
     * Find data by SMILES
     * @param string $strSmile
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     */
    public function findBySmile($strSmile, &$outArResult, &$outArNextResult) {
        $client = new \SoapClient(self::WSDL, $this->options);
        $this->setInputWithSmiles($strSmile, $arInput);
        // TODO next results
        try {
            $arIds = array();
            $response = $client->GetStructureSearch($arInput);
            foreach ($response->return->ListElement as $ar) {
                $arIds[] = $ar->chebiId;
            }
            $this->findByIdentifiers($arIds, $outArResult);
        } catch (\Exception $ex) {
            return ResultEnum::REPLY_NONE;
        }
        return ResultEnum::REPLY_OK_MORE;
    }

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     */
    public function findByFormula($strFormula, &$outArResult, &$outArNextResult) {
        return $this->getLiteEntity($strFormula, ChebiSearchCategoryEnum::FORMULA, $outArResult, $outArNextResult);
    }

    private function getLiteEntity($strSearchParam, $strSearchCategory, &$outArResult, &$outArNextResult) {
        $client = new \SoapClient(self::WSDL, $this->options);
        $this->setInput($strSearchParam, $strSearchCategory, $arInput);
        // TODO next results
        try {
            $response = $client->GetLiteEntity($arInput);
            if (is_array($response->return->ListElement)) {
                foreach ($response->return->ListElement as $ar) {
                    $arIds[] = $ar->chebiId;
                }
            } else {
                return $this->findByIdentifier(substr($response->return->ListElement->chebiId, self::IDENTIFIER_PREFIX_SIZE), $outArResult, $outArNextResult);
            }
            $this->findByIdentifiers($arIds, $outArResult);
        } catch (\Exception $ex) {
            return ResultEnum::REPLY_NONE;
        }
        return ResultEnum::REPLY_OK_MORE;
    }

    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @param array $outArResult
     * @return int
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
    public function findByIdentifier($strId, &$outArResult) {
        $client = new \SoapClient(self::WSDL, $this->options);
        $arInput['chebiId'] = $strId;
        $outArResult[Front::CANVAS_INPUT_IDENTIFIER] = $strId;
        $outArResult[Front::CANVAS_HIDDEN_DATABASE] = ServerEnum::CHEBI;
        try {
            $response = $client->GetCompleteEntity($arInput);
            foreach ($response as $value) {
                $outArResult[Front::CANVAS_INPUT_NAME] = $value->chebiAsciiName;
                $outArResult[Front::CANVAS_INPUT_SMILE] = $value->smiles;
                $outArResult[Front::CANVAS_INPUT_MASS] = $value->monoisotopicMass;
                $this->getFormulaFromFormulae($value->Formulae, $outArResult);
            }
        } catch (\Exception $ex) {
            return ResultEnum::REPLY_NONE;
        }
        return ResultEnum::REPLY_OK_ONE;
    }

    /**
     * Find by identifiers
     * @param array $arIds ids in array
     * @param array $outArResult result
     * @return int
     */
    public function findByIdentifiers($arIds, &$outArResult) {
        $client = new \SoapClient(self::WSDL, $this->options);
        $arInput['ListOfChEBIIds'] = $arIds;
        try {
            $intCounter = 0;
            $response = $client->GetCompleteEntityByList($arInput);
            foreach ($response->return as $arData) {
                $arMolecule = array();
                $this->getDataFromResult($arData, $arMolecule);
                $outArResult[$intCounter] = $arMolecule;
                $intCounter++;
            }
        } catch (\Exception $ex) {
            return ResultEnum::REPLY_NONE;
        }
        return ResultEnum::REPLY_OK_MORE;
    }

    /**
     * Setup input for SOAP
     * @param string $strSearchParam
     * @param string $strSearchCategory
     * @param array $outArInput
     */
    private function setInput($strSearchParam, $strSearchCategory, &$outArInput) {
        $outArInput[self::SOAP_SEARCH] = $strSearchParam;
        $outArInput[self::SOAP_SEARCH_CATEGORY] = $strSearchCategory;
        $outArInput[self::SOAP_MAX_RESULTS] = self::MAX_RESULTS;
        $outArInput[self::SOAP_STARS] = self::CATEGORY_ALL;
    }

    /**
     * Setup input for SOAP find by SMILES
     * @param string $strSmiles
     * @param array $arInput
     */
    private function setInputWithSmiles($strSmiles, &$arInput) {
        $arInput[self::SOAP_STRUCTURE] = $strSmiles;
        $arInput[self::SOAP_TYPE] = self::TYPE_VALUE;
        $arInput[self::SOAP_STRUCTURE_SEARCH_CATEGORY] = self::STRUCTURE_SEARCH_CATEGORY_VALUE;
        $arInput[self::SOAP_TOTAL_RESULTS] = self::TOTAL_RESULTS_VALUE;
        $arInput[self::SOAP_TANIMOTO_CUTOFF] = self::TANIMOTO_CUTOFF_VALUE;
    }

    /**
     * Setup data from result to right structure
     * @param array $arData
     * @param array $outArMolecule output param
     */
    private function getDataFromResult($arData, &$outArMolecule) {
        $outArMolecule[Front::CANVAS_INPUT_IDENTIFIER] = substr($arData->chebiId, self::IDENTIFIER_PREFIX_SIZE);
        $this->getName($arData, $outArMolecule);
        $this->getSmiles($arData, $outArMolecule);
        $this->getMass($arData, $outArMolecule);
        $this->getFormula($arData, $outArMolecule);
        $outArMolecule[Front::CANVAS_HIDDEN_DATABASE] = ServerEnum::CHEBI;
    }

    /**
     * Get name from result and set it to right structure
     * @param array $arData
     * @param array $outArMolecule
     */
    private function getName($arData, &$outArMolecule) {
        if (isset($arData->chebiAsciiName)) {
            $outArMolecule[Front::CANVAS_INPUT_NAME] = $arData->chebiAsciiName;
        } else {
            $outArMolecule[Front::CANVAS_INPUT_NAME] = "";
        }
    }

    /**
     * Get SMILES from result and set it to right structure
     * @param array $arData
     * @param array $outArMolecule
     */
    private function getSmiles($arData, &$outArMolecule) {
        if (isset($arData->smiles)) {
            $outArMolecule[Front::CANVAS_INPUT_SMILE] = $arData->smiles;
        } else {
            $outArMolecule[Front::CANVAS_INPUT_SMILE] = "";
        }
    }

    /**
     * Get monoisotopic mass from result and set it to right structure
     * @param array $arData
     * @param array $outArMolecule
     */
    private function getMass($arData, &$outArMolecule) {
        if (isset($arData->monoisotopicMass)) {
            $outArMolecule[Front::CANVAS_INPUT_MASS] = $arData->monoisotopicMass;
        } else {
            $outArMolecule[Front::CANVAS_INPUT_MASS] = "";
        }
    }

    /**
     * Get formula from result and set it to right structure
     * @param array $arData
     * @param array $outArMolecule
     */
    private function getFormula($arData, &$outArMolecule) {
        if (isset($arData->Formulae)) {
            $this->getFormulaFromFormulae($arData->Formulae, $outArMolecule);
        } else {
            $outArMolecule[Front::CANVAS_INPUT_FORMULA] = "";
        }
    }

    /**
     * @param $formulae object holds formula and source
     * @param array $outArResult output param for results
     */
    private function getFormulaFromFormulae($formulae, &$outArResult) {
        $outArResult[Front::CANVAS_INPUT_FORMULA] = "";
        foreach ($formulae as $key => $value) {
            if (is_numeric($key)) {
                $outArResult[Front::CANVAS_INPUT_FORMULA] = $value->data;
                break;
            }
            if ($key === "data") {
                $outArResult[Front::CANVAS_INPUT_FORMULA] = $value;
                break;
            }
        }
    }

}
