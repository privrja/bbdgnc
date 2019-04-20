<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Base\Logger;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Exception\IllegalStateException;
use Bbdgnc\Finder\Enum\ChebiSearchCategoryEnum;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Exception\BadTransferException;

class ChebiFinder implements IFinder {

    /** @var string URI for wsdl file */
    const WSDL = 'https://www.ebi.ac.uk/webservices/chebi/2.0/webservice?wsdl';

    /** @var int max results in one query max 5000 */
    const MAX_RESULTS = 200;

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

    /** @var int max value of GetCompleteEntity */
    const TOTAL_RESULTS_VALUE = 50;

    /** @var float similarity cut off between 0 and 1 */
    const TANIMOTO_CUTOFF_VALUE = 0.25;

    /** @var string type of structure search SIMILARITY|IDENTITY|SUBSTRUCTURE */
    const STRUCTURE_SEARCH_CATEGORY_VALUE = "SIMILARITY";

    /** @var string type of search mol|smiles|spieces */
    const TYPE_VALUE = "SMILES";

    /** @var string star option */
    const STARS_ALL = "ALL";

    const ERROR_DURING_SOAP = "Error during Soap";
    const INFO_SOAP_OK = "Response OK to SOAP query.";

    /** @var array default options for query */
    private $options = array('exceptions' => true);

    /**
     * Find data on some server by name
     * @param string $strName
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     * @see ResultEnum
     * @throws BadTransferException
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
     * @see ResultEnum
     * @throws BadTransferException
     */
    public function findBySmile($strSmile, &$outArResult, &$outArNextResult) {
        $client = new \SoapClient(self::WSDL, $this->options);
        $this->setInputWithSmiles($strSmile, $arInput);
        try {
            $intCounter = 0;
            $response = $client->GetStructureSearch($arInput);
            if (!isset($response->return->ListElement)) {
                return ResultEnum::REPLY_NONE;
            }
            foreach ($response->return->ListElement as $ar) {
                $arIds[] = $ar->chebiId;
                $intCounter++;
            }
            if ($intCounter >= IFinder::FIRST_X_RESULTS) {
                $outArNextResult = array_splice($arIds, IFinder::FIRST_X_RESULTS + 1);
            } else {
                $outArNextResult = array();
            }
            $this->findByIdentifiers($arIds, $outArResult);
        } catch (\Exception $ex) {
            Logger::log(LoggerEnum::ERROR, self::ERROR_DURING_SOAP . "\n" . $ex->getMessage());
            throw new BadTransferException(self::ERROR_DURING_SOAP);
        }
        Logger::log(LoggerEnum::INFO, self::INFO_SOAP_OK);
        return ResultEnum::REPLY_OK_MORE;
    }

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     * @see ResultEnum
     * @throws BadTransferException
     */
    public function findByFormula($strFormula, &$outArResult, &$outArNextResult) {
        return $this->getLiteEntity($strFormula, ChebiSearchCategoryEnum::FORMULA, $outArResult, $outArNextResult);
//        $result = $this->getLiteEntity($strFormula, ChebiSearchCategoryEnum::FORMULA, $outArResult, $outArNextResult);
//        switch ($result) {
//            case ResultEnum::REPLY_NONE:
//            case ResultEnum::REPLY_OK_ONE:
//                return $result;
//            case ResultEnum::REPLY_OK_MORE;
//                $mass = FormulaHelper::computeMass($strFormula);
//                foreach ($outArResult as $molecule) {
//                    if ($molecule[Front::CANVAS_INPUT_MASS] > $mass + 4
//                        || $molecule[Front::CANVAS_INPUT_MASS] < $mass - 4) {
//                        $this->deleteElement($molecule, $outArResult);
//                    }
//                }
//                foreach ($outArNextResult as $molecule) {
//                    if ($molecule[Front::CANVAS_INPUT_MASS] > $mass + 4
//                        || $molecule[Front::CANVAS_INPUT_MASS] < $mass - 4) {
//                        $this->deleteElement($molecule, $outArNextResult);
//                    }
//                }
//                $length = sizeof($outArResult);
//                if ($length < IFinder::FIRST_X_RESULTS) {
//                    for ($index = 0; $index <= $length; ++$index) {
//                        $outArResult[] = array_pop($outArNextResult);
//                    }
//                }
//                return $result;
//            default:
//                throw new IllegalStateException();
//        }
    }

    function deleteElement($element, &$array){
        $index = array_search($element, $array);
        if($index !== false){
            unset($array[$index]);
        }
    }


    /**
     * Find data by Monoisotopic Mass
     * @param $decMass
     * @param $decTolerance
     * @param array $outArResult
     * @param $outArNextResult
     * @return int
     * @see ResultEnum
     * @throws BadTransferException
     */
    public function findByMass($decMass, $decTolerance, &$outArResult, &$outArNextResult) {
        return $this->getLiteEntity($decMass, ChebiSearchCategoryEnum::MONOISOTOPIC_MASS, $outArResult, $outArNextResult);
    }

    /**
     * Find data by Identifier
     * @param string $strId
     * @param array $outArResult
     * @return int
     * @see ResultEnum
     * @throws BadTransferException
     */
    public function findByIdentifier($strId, &$outArResult) {
        $client = new \SoapClient(self::WSDL, $this->options);
        $arInput['chebiId'] = $strId;
        $outArResult[Front::CANVAS_INPUT_IDENTIFIER] = $strId;
        try {
            $response = $client->GetCompleteEntity($arInput);
            $intCounter = 0;
            foreach ($response as $value) {
                $this->getDataFromResultWithoutIdentifier($value, $outArResult);
                $intCounter++;
            }
            if ($intCounter == 0) {
                $outArResult[Front::CANVAS_INPUT_NAME] = "";
                $outArResult[Front::CANVAS_INPUT_SMILE] = "";
                $outArResult[Front::CANVAS_INPUT_MASS] = "";
                $outArResult[Front::CANVAS_INPUT_FORMULA] = "";
                return ResultEnum::REPLY_NONE;
            }
        } catch (\Exception $ex) {
            Logger::log(LoggerEnum::ERROR, self::ERROR_DURING_SOAP . "\n" . $ex->getMessage());
            throw new BadTransferException(self::ERROR_DURING_SOAP);
        }
        Logger::log(LoggerEnum::INFO, self::INFO_SOAP_OK);
        return ResultEnum::REPLY_OK_ONE;
    }

    /**
     * Find by identifiers
     * @param array $arIds ids in array
     * @param array $outArResult result
     * @return int
     * @see ResultEnum
     * @throws BadTransferException
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
                if (!$this->isMoleculeJunk($arMolecule)) {
                    $outArResult[$intCounter] = $arMolecule;
                    $intCounter++;
                }
            }
        } catch (\Exception $ex) {
            Logger::log(LoggerEnum::ERROR, self::ERROR_DURING_SOAP . "\n" . $ex->getMessage());
            throw new BadTransferException(self::ERROR_DURING_SOAP);
        }
        Logger::log(LoggerEnum::INFO, self::INFO_SOAP_OK);
        return ResultEnum::REPLY_OK_MORE;
    }

    /**
     * Is molecule data set? return true when data is not set
     * @param array $arMolecule
     * @return bool
     */
    private function isMoleculeJunk($arMolecule) {
        return empty($arMolecule[Front::CANVAS_INPUT_SMILE]) && empty($arMolecule[Front::CANVAS_INPUT_FORMULA]) && empty($arMolecule[Front::CANVAS_INPUT_MASS]);
    }

    /**
     * Find using GetLiteEntity from SOAP
     * @param string $strSearchParam which to find (name, formula) ex.: "Cyclosporin" or "CH4"
     * @param string $strSearchCategory ChebiSearchCategoryEnum which to find category
     * @param array $outArResult
     * @param array $outArNextResult
     * @return int
     * @see ResultEnum
     * @throws BadTransferException
     */
    private function getLiteEntity($strSearchParam, $strSearchCategory, &$outArResult, &$outArNextResult) {
        $client = new \SoapClient(self::WSDL, $this->options);
        $this->setInput($strSearchParam, $strSearchCategory, $arInput);
        try {
            $intCounter = 0;
            $response = $client->GetLiteEntity($arInput);
            if (!isset($response->return->ListElement)) {
                return ResultEnum::REPLY_NONE;
            }
            $arIds = array();
            if (is_array($response->return->ListElement)) {
                foreach ($response->return->ListElement as $ar) {
                    $arIds[] = $ar->chebiId;
                    $intCounter++;
                }
                if ($intCounter >= IFinder::FIRST_X_RESULTS) {
                    $outArNextResult = array_splice($arIds, IFinder::FIRST_X_RESULTS);
                } else {
                    $outArNextResult = array();
                }
            } else {
                return $this->findByIdentifier(substr($response->return->ListElement->chebiId, self::IDENTIFIER_PREFIX_SIZE), $outArResult);
            }
            $this->findByIdentifiers($arIds, $outArResult);
        } catch (\Exception $ex) {
            Logger::log(LoggerEnum::ERROR, self::ERROR_DURING_SOAP . "\n" . $ex->getMessage());
            throw new BadTransferException(self::ERROR_DURING_SOAP);
        }
        Logger::log(LoggerEnum::INFO, self::INFO_SOAP_OK);
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
        $outArInput[self::SOAP_STARS] = self::STARS_ALL;
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
        $this->getDataFromResultWithoutIdentifier($arData, $outArMolecule);
    }

    /**
     * Setup data from result to right structure without identifier
     * @param array $arData
     * @param array $outArMolecule
     */
    private function getDataFromResultWithoutIdentifier($arData, &$outArMolecule) {
        $this->getName($arData, $outArMolecule);
        $this->getSmiles($arData, $outArMolecule);
        $this->getMass($arData, $outArMolecule);
        $this->getFormula($arData, $outArMolecule);
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
