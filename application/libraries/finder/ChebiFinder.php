<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Enum\Front;

class ChebiFinder implements IFinder {

    const WSDL = 'https://www.ebi.ac.uk/webservices/chebi/2.0/webservice?wsdl';
    const NAME_SPACE = 'https://www.ebi.ac.uk/webservices/chebi';


    /**
     * Find data on some server by name
     * @param string $strName
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     */
    public function findByName($strName, &$outArResult, &$outArNextResult) {
        // TODO: Implement findByName() method.
    }

    /**
     * Find data by SMILES
     * @param string $strSmile
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     */
    public function findBySmile($strSmile, &$outArResult, &$outArNextResult) {
        // TODO: Implement findBySmile() method.
    }

    /**
     * Find data by Molecular Formula
     * @param string $strFormula
     * @param array $outArResult
     * @param array $outArNextResult id with next results
     * @return int
     */
    public function findByFormula($strFormula, &$outArResult, &$outArNextResult) {
        // TODO: Implement findByFormula() method.
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
        $client = new \SoapClient(self::WSDL, array('exceptions' => true));
        $arInput['chebiId'] = $strId;
        $response = $client->GetCompleteEntity($arInput);
        foreach ($response as $value) {
            $outArResult[Front::CANVAS_INPUT_NAME] = $value->chebiAsciiName;
            $outArResult[Front::CANVAS_INPUT_SMILE] = $value->smiles;
            $outArResult[Front::CANVAS_INPUT_MASS] = $value->monoisotopicMass;
            $this->getFormulaFromFormulae($value->Formulae, $outArResult);
        }
        echo var_dump($outArResult);
    }

    /**
     * Find by identifiers
     * @param array $arIds ids in array
     * @param array $outArResult result
     * @return int
     */
    public function findByIdentifiers($arIds, &$outArResult) {
        // TODO: Implement findByIdentifiers() method.
    }

    private function getFormulaFromFormulae($formulae, &$outArResult) {
        foreach ($formulae as $key => $value) {
            if ($key == "data") {
                $outArResult[Front::CANVAS_INPUT_FORMULA] = $value;
            }
        }
    }

}
