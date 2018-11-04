<?php

use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Finder\FinderFactory;
use Bbdgnc\Finder\PubChemFinder;

class Land extends CI_Controller {

    /** @var string cookie identifier for next results */
    const COOKIE_NEXT_RESULTS = 'find-next-results';

    /** @var int expire time of cookie 1 hour */
    const COOKIE_EXPIRE_HOUR = 3600;

    const ERRORS = "errors";

    private $errors = "";

    /**
     * Get Default data for view
     * @return array
     */
    private function getData() {
        return array(
            Front::CANVAS_INPUT_NAME => "", Front::CANVAS_INPUT_SMILE => "",
            Front::CANVAS_INPUT_FORMULA => "", Front::CANVAS_INPUT_MASS => "",
            Front::CANVAS_INPUT_DEFLECTION => "", Front::CANVAS_INPUT_IDENTIFIER => "",
            self::ERRORS => ""
        );
    }

    /**
     * Land constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper(array("form", "url"));
    }

    /**
     * Index - default view
     * @param array $viewData default null, data for view to print
     */
    public function index($viewData = null) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view(Front::PAGES_CANVAS);
        if (isset($viewData)) {
            $this->load->view(Front::PAGES_MAIN, $viewData);
        } else {
            $this->load->view(Front::PAGES_MAIN, $this->getData());
        }
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    private function renderSelect($viewSelectData, $viewData) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view(Front::PAGES_CANVAS);
        $this->load->view(Front::PAGES_MAIN, $viewData);
        $this->load->view(Front::PAGES_SELECT, $viewSelectData);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    /**
     * Form
     * Find in specific database by specific param or save data to database
     */
    public function form() {
        /* load form validation library */
        $this->load->library("form_validation");

        /* get important input data */
        $btnFind = $this->input->post("find");
        $btnSave = $this->input->post("save");
        $btnLoad = $this->input->post("load");
        $intDatabase = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $intFindBy = $this->input->post(Front::CANVAS_INPUT_SEARCH_BY);
        $blMatch = $this->input->post(Front::CANVAS_INPUT_MATCH);

        if (isset($btnFind)) {
            /* Find */
            $this->find($intDatabase, $intFindBy, $blMatch);
        } else if (isset($btnSave)) {
            /* Save to database */
        } else if (isset($btnLoad)) {
            /* Load from database */
        }
    }

    /**
     * Find data on selected server
     * @param int $intDatabase selected server
     * @param int $intFindBy find by seleceted input
     * @param boolean $blMatch if exact match selected
     */
    private function find($intDatabase, $intFindBy, $blMatch) {
        /* input check */
        $this->form_validation->set_rules(Front::CANVAS_INPUT_DATABASE, "Database", Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_SEARCH_BY, "Search by", Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            $this->index($this->getLastData());
            return;
        }

        /* search options */
        $arSearchOptions = array();
        if (isset($blMatch)) {
            $arSearchOptions[FinderFactory::OPTION_EXACT_MATCH] = true;
        } else {
            $arSearchOptions[FinderFactory::OPTION_EXACT_MATCH] = false;
        }

        try {
            $intResultCode = $this->findBy($intDatabase, $intFindBy, $outArResult, $outArNextResult, $arSearchOptions);
        } catch (Exception $ex) {
            $this->errors = $ex->getMessage();
            $this->index($this->getLastData());
            return;
        }

        switch ($intResultCode) {
            default:
            case ResultEnum::REPLY_NONE:
                $this->errors = "Not Found";
                $this->index($this->getLastData());
                break;
            case ResultEnum::REPLY_OK_ONE:
                if (empty($outArResult[Front::CANVAS_INPUT_NAME])) {
                    $outArResult[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
                }
                $outArResult[Front::CANVAS_INPUT_DEFLECTION] = "";
                $this->index($outArResult);
                break;
            case ResultEnum::REPLY_OK_MORE:
                /* form with list view and select the right one, next find by id the right one */
                $data = $this->getLastData();
                $data['molecules'] = $outArResult;
                if (!empty($outArNextResult)) {
                    $data[Front::CANVAS_HIDDEN_NEXT_RESULTS] = serialize($outArNextResult);
                    $data[Front::CANVAS_HIDDEN_SHOW_NEXT_RESULTS] = true;
                } else {
                    $data[Front::CANVAS_HIDDEN_SHOW_NEXT_RESULTS] = false;
                    log_message('debug', "Last page of results");
                }
                $this->renderSelect($data, $this->getLastData());
                break;
        }
    }

    /**
     * Render default view with canvas and form. Select data from list and set them to form
     */
    public function select() {
        $data = $this->getLastData();

        /* Problem with name */
        $strName = $this->input->post(Front::CANVAS_INPUT_NAME);
        if ($this->input->post(Front::CANVAS_INPUT_DATABASE) == ServerEnum::PUBCHEM) {
            $pubChemFinder = new PubChemFinder();
            $strIupacName = $strName;
            $intResultCode = $pubChemFinder->findName($this->input->post(Front::CANVAS_INPUT_IDENTIFIER), $strName);
            if ($intResultCode == ResultEnum::REPLY_NONE) {
                $data[Front::CANVAS_INPUT_NAME] = $strIupacName;
            } else {
                $data[Front::CANVAS_INPUT_NAME] = $strName;
            }
        }
        $this->index($data);
    }

    /**
     * Render view for next values from finding
     */
    public function next() {
        $arNext = @unserialize($this->input->post(Front::CANVAS_HIDDEN_NEXT_RESULTS));
        $intDatabase = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $data = $this->getLastData();

        if (isset($arNext) && !empty($arNext)) {
            $arResult = array_splice($arNext, 0, \Bbdgnc\Finder\IFinder::FIRST_X_RESULTS);

            $finder = FinderFactory::getFinder($intDatabase);
            $finder->findByIdentifiers($arResult, $outArResult);

            $data['molecules'] = $outArResult;
            if (!empty($arNext)) {
                $data[Front::CANVAS_HIDDEN_NEXT_RESULTS] = serialize($arNext);
                $data[Front::CANVAS_HIDDEN_SHOW_NEXT_RESULTS] = true;
            } else {
                log_message('debug', "Last page of results");
                $data[Front::CANVAS_HIDDEN_SHOW_NEXT_RESULTS] = false;
                // last page -> not show next results button
            }
            $this->renderSelect($data, $this->getLastData());
        } else {
            $this->index($this->getLastData());
        }
    }

    /**
     * Get last data from input and set it to array for view
     * @return array data for view
     */
    private function getLastData() {
        $arViewData = array();
        $arViewData[Front::CANVAS_INPUT_DATABASE] = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $arViewData[Front::CANVAS_INPUT_SEARCH_BY] = $this->input->post(Front::CANVAS_INPUT_SEARCH_BY);
        $arViewData[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
        $arViewData[Front::CANVAS_INPUT_SMILE] = $this->input->post(Front::CANVAS_INPUT_SMILE);
        $arViewData[Front::CANVAS_INPUT_FORMULA] = $this->input->post(Front::CANVAS_INPUT_FORMULA);
        $arViewData[Front::CANVAS_INPUT_MASS] = $this->input->post(Front::CANVAS_INPUT_MASS);
        $arViewData[Front::CANVAS_INPUT_DEFLECTION] = $this->input->post(Front::CANVAS_INPUT_DEFLECTION);
        $arViewData[Front::CANVAS_INPUT_IDENTIFIER] = $this->input->post(Front::CANVAS_INPUT_IDENTIFIER);
        $arViewData[self::ERRORS] = $this->errors;
        return $arViewData;
    }

    /**
     * Find by - specific param
     * @param int $intDatabase where to search
     * @param int $intFindBy find by this param
     * @param array $outArResult output param result only first X results, can be influenced by param IFinder::FIRST_X_RESULTS
     * @param array $outArNextResult next results identifiers
     * @return int result code 0 => find none, 1 => find 1, 2 => find more than 1
     */
    private function findBy($intDatabase, $intFindBy, &$outArResult = array(), &$outArNextResult = array(), $arSearchOptions = array()) {
        $finder = FinderFactory::getFinder($intDatabase, $arSearchOptions);
        switch ($intFindBy) {
            case FindByEnum::IDENTIFIER:
                $this->form_validation->set_rules(Front::CANVAS_INPUT_IDENTIFIER, "Identifier", Front::REQUIRED);
                if ($this->form_validation->run() === false) {
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findByIdentifier($this->input->post(Front::CANVAS_INPUT_IDENTIFIER), $outArResult);
            case FindByEnum::NAME:
                $this->form_validation->set_rules(Front::CANVAS_INPUT_NAME, "Name", Front::REQUIRED);
                if ($this->form_validation->run() === false) {
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findByName($this->input->post(Front::CANVAS_INPUT_NAME), $outArResult, $outArNextResult);
            case FindByEnum::FORMULA:
                $this->form_validation->set_rules(Front::CANVAS_INPUT_FORMULA, "Formula", Front::REQUIRED);
                if ($this->form_validation->run() === false) {
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findByFormula($this->input->post(Front::CANVAS_INPUT_FORMULA), $outArResult, $outArNextResult);
            case FindByEnum::SMILE:
                $this->form_validation->set_rules(Front::CANVAS_INPUT_SMILE, "SMILES", Front::REQUIRED);
                if ($this->form_validation->run() === false) {
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findBySmile($this->input->post(Front::CANVAS_INPUT_SMILE), $outArResult, $outArNextResult);
            case FindByEnum::MASS:
                $this->form_validation->set_rules(Front::CANVAS_INPUT_MASS, "Mass", Front::REQUIRED);
                if ($this->form_validation->run() === false) {
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findByMass($this->input->post(Front::CANVAS_INPUT_MASS), $this->input->post(Front::CANVAS_INPUT_DEFLECTION),$outArResult, $outArNextResult);
            default:
                return ResultEnum::REPLY_NONE;
        }
    }

}
