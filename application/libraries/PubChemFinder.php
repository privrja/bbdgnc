<?php

get_instance()->load->iface('IFinder'); // interface file name

class PubChemFinder implements IFinder {

    const REST_DEF_URI = "https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/";
    const REST_PROPERTIES = "IUPACName,MolecularFormula,MolecularWeight,CanonicalSmiles/";
    const REST_JSON_FORMAT = "json";

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
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);

        $decoded = json_decode($curl_response, true);

        $molecule = new MoleculeTO();
        foreach ($decoded['PropertyTable']['Properties'][0] as $property => $value) {
            switch ($property) {
                case PubChemFinder::IDENTIFIER:
                    $molecule->mixIdentifier = $value;
                    break;
                case PubChemFinder::IUPAC_NAME:
                    $molecule->strName = $value;
                    break;
                case PubChemFinder::FORMULA:
                    $molecule->strFormula = $value;
                    break;
                case PubChemFinder::MASS:
                    $molecule->decMass = $value;
                    break;
                case PubChemFinder::SMILE:
                    $molecule->strSmile = $value;
                    break;
            }
            $molecule->intServerEnum = ServerEnum::PUBCHEM;
        }

        /* TODO wrong connection */

        log_message('info', "Response OK to URI: $uri");
        return $molecule;
    }
}