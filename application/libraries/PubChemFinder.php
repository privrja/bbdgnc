<?php

get_instance()->load->iface('IFinder'); // interface file name

class PubChemFinder implements IFinder {

    const REST_DEF_URI = "https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/";
    const REST_PROPERTIES = "IUPACName,MolecularFormula,MolecularWeight,CanonicalSmiles/";
    const REST_JSON_FORMAT = "json";
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
     * Find data by Identificator
     * @param string $strId
     * @return mixed
     */
    public function findById($strId) {
        $uri = PubChemFinder::REST_DEF_URI . "cid/" . $strId . "/property/" . PubChemFinder::REST_PROPERTIES . Finder::REST_FOMRAT_JSON;
        $curl = curl_init($uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
//            $info = curl_getinfo($curl);
            curl_close($curl);
            log_message("error", "Error occured during curl exec. Uri: " . $uri);
            return null;
        }
        curl_close($curl);

        $decoded = json_decode($curl_response, true);

        if (isset($decoded[PubChemFinder::REPLY_FAULT])) {
            log_message('error', "REST reply fault. Uri: " . $uri);
            return null;
        }

        $objMolecule = new MoleculeTO();
        foreach ($decoded[PubChemFinder::REPLY_TABLE_PROPERTIES][PubChemFinder::REPLY_PROPERTIES][0] as $property => $value) {
            switch ($property) {
                case PubChemFinder::IDENTIFIER:
                    $objMolecule->mixIdentifier = $value;
                    break;
                case PubChemFinder::IUPAC_NAME:
                    $objMolecule->strName = $value;
                    break;
                case PubChemFinder::FORMULA:
                    $objMolecule->strFormula = $value;
                    break;
                case PubChemFinder::MASS:
                    $objMolecule->decMass = $value;
                    break;
                case PubChemFinder::SMILE:
                    $objMolecule->strSmile = $value;
                    break;
            }
            $objMolecule->intServerEnum = ServerEnum::PUBCHEM;
        }

        log_message('info', "Response OK to URI: $uri");
        return $objMolecule;
    }
}