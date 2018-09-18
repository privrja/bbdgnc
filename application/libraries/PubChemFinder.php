<?php

get_instance()->load->iface('IFinder'); // interface file name

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
     * Find data on some server by name
     * @param string $strName
     * @return mixed
     */
    public function findByName($strName) {
        // TODO: Implement findByName() method.
        $uri = PubChemFinder::REST_DEF_URI . "name/" . $strName . PubChemFinder::REST_PROPERTY . PubChemFinder::REST_PROPERTY_VALUES . IFinder::REST_FORMAT_JSON;
        $decoded = $this->getJsonFromUri($uri);
        if ($decoded === false) {
            return null;
        }

        $arResult = array();
        foreach ($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES] as $intKey => $objItem) {
            $arMolecule = array();
            foreach ($objItem as $strProperty => $mixValue) {
                $arMolecule[$this->getArrayKeyFromReplyProperty($strProperty)] = $mixValue;
            }
            $arResult[$intKey] = $arMolecule;
            $arMolecule[Land::INPUT_DATABASE] = ServerEnum::PUBCHEM;
        }
        return $arResult;
    }

    private function getArrayKeyFromReplyProperty($strProperty) {
        switch ($strProperty) {
            case PubChemFinder::IDENTIFIER:
                return Land::INPUT_IDENTIFIER;
            case PubChemFinder::IUPAC_NAME:
                return Land::INPUT_NAME;
            case PubChemFinder::FORMULA:
                return Land::INPUT_FORMULA;
            case PubChemFinder::MASS:
                return Land::INPUT_MASS;
            case PubChemFinder::SMILE:
                return Land::INPUT_SMILE;
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
     * @return mixed
     */
    public function findById($strId) {
        $uri = PubChemFinder::REST_DEF_URI . "cid/" . $strId . PubChemFinder::REST_PROPERTY . PubChemFinder::REST_PROPERTY_VALUES . IFinder::REST_FORMAT_JSON;

        $decoded = $this->getJsonFromUri($uri);

        if ($decoded === false) {
            return null;
        }

        /* setup moleculeTo object from reply */
        $objMolecule = new MoleculeTO();
        foreach ($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES][0] as $property => $value) {
            $this->setMolecule($property, $value, $objMolecule);
        }
        $objMolecule->intServerEnum = ServerEnum::PUBCHEM;

        return $objMolecule;
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

    private function setMolecule($strProperty, $mixValue, $objMolecule) {
        switch ($strProperty) {
            case PubChemFinder::IDENTIFIER:
                $objMolecule->mixIdentifier = $mixValue;
                break;
            case PubChemFinder::IUPAC_NAME:
                $objMolecule->strName = $mixValue;
                break;
            case PubChemFinder::FORMULA:
                $objMolecule->strFormula = $mixValue;
                break;
            case PubChemFinder::MASS:
                $objMolecule->decMass = $mixValue;
                break;
            case PubChemFinder::SMILE:
                $objMolecule->strSmile = $mixValue;
                break;
        }
    }
}
