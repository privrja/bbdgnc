<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Finder\Exception\BadTransferException;
use Bbdgnc\Finder\FinderFactory;
use Bbdgnc\Finder\IFinder;
use Bbdgnc\Finder\PubChemFinder;
use Bbdgnc\Smiles\Graph;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\ReferenceTO;
use Bbdgnc\TransportObjects\SequenceTO;

class Land extends CI_Controller {

    /** @var string cookie identifier for next results */
    const COOKIE_NEXT_RESULTS = 'find-next-results';

    /** @var int expire time of cookie 1 hour */
    const COOKIE_EXPIRE_HOUR = 3600;

    const ERRORS = "errors";


    const COOKIE_BLOCKS = "cookie_blocks";
    const BLOCK_MODEL = 'block_model';
    const SEQUENCE_MODEL = "sequence_model";
    const MODIFICATION_MODEL = "modification_model";

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
        $this->load->helper(array(HelperEnum::HELPER_FORM, HelperEnum::HELPER_URL, HelperEnum::HELPER_COOKIE));
        $this->load->model(self::BLOCK_MODEL);
        $this->load->model(self::SEQUENCE_MODEL);
        $this->load->model(self::MODIFICATION_MODEL);
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
     * Render editor or blocks
     */
    public function block() {
        $btnEditor = $this->input->post('editor');
        $btnAccept = $this->input->post('accept');
        if (isset($btnEditor)) {
            $this->editor();
        } else if (isset($btnAccept)) {
            $this->blocks();
        }
    }

    public function editor() {
        $blockIdentifier = $this->input->post(Front::BLOCK_IDENTIFIER);
        $blockSmile = $this->input->post(Front::BLOCK_SMILE);
        $blockAcronym = $this->input->post(Front::BLOCK_ACRONYM);
        $blockName = $this->input->post(Front::BLOCK_NAME);
        $blockCount = $this->input->post(Front::BLOCK_COUNT);
        $sequence = $this->input->post(Front::SEQUENCE);
        $sequenceType = $this->input->post(Front::SEQUENCE_TYPE);
        $block = new BlockTO($blockIdentifier, $blockName, $blockAcronym, $blockSmile, ComputeEnum::NO);
        $block->formula = $this->input->post(Front::BLOCK_FORMULA);
        $block->mass = $this->input->post(Front::BLOCK_MASS);
        $block->losses = $this->input->post(Front::BLOCK_NEUTRAL_LOSSES);
        $block->reference->identifier = $this->input->post(Front::BLOCK_REFERENCE);
        $block->reference->server = $this->input->post(Front::BLOCK_REFERENCE_SERVER);
        $data = $this->getLastData();
        $data[Front::BLOCK] = $block;
        $data[Front::BLOCK_COUNT] = $blockCount;
        $data[Front::SEQUENCE] = $sequence;
        $data[Front::SEQUENCE_TYPE] = $sequenceType;
        $this->load->view('templates/header');
        $this->load->view('editor/index', $data);
        $this->load->view('templates/footer');
    }

    public function blocks() {
        $first = $this->input->post('first');
        $data = $this->getLastData();
        $cookieVal = get_cookie(self::COOKIE_BLOCKS);
        if (!isset($first) && $cookieVal !== null) {
            $blocks = json_decode($cookieVal);
            $blockIdentifier = $this->input->post(Front::BLOCK_IDENTIFIER);
            $blockTO = new BlockTO($blockIdentifier, $this->input->post(Front::BLOCK_NAME), $this->input->post(Front::BLOCK_ACRONYM), $this->input->post(Front::BLOCK_SMILE), ComputeEnum::NO);
            $blockTO->formula = $this->input->post(Front::BLOCK_FORMULA);
            $blockTO->mass = $this->input->post(Front::BLOCK_MASS);
            $blockTO->losses = $this->input->post(Front::BLOCK_NEUTRAL_LOSSES);
            $blockTO->reference = new ReferenceTO();
            $blockTO->reference->identifier = $this->input->post(Front::BLOCK_REFERENCE);
            $blockTO->reference->server = $this->input->post(Front::BLOCK_REFERENCE_SERVER);
            $blocks[$blockIdentifier] = $blockTO;
            $data[Front::BLOCK_COUNT] = $this->input->post(Front::BLOCK_COUNT);
        } else {
            $blocks = [];
            $intCounter = 0;
            $inputSmiles = $this->input->post(Front::BLOCK_SMILES);
            $smiles = explode(",", $inputSmiles);
            foreach ($smiles as $smile) {
                $graph = new Graph($smile);
                $arResult = $this->block_model->getBlockByUniqueSmiles($graph->getUniqueSmiles());
                if (!empty($arResult)) {
                    $blockTO = new BlockTO($intCounter, $arResult['name'], $arResult['acronym'], $arResult['smiles'], ComputeEnum::NO);
                    $blockTO->formula = $arResult['residue'];
                    $blockTO->mass = $arResult['mass'];
                } else {
                    $pubchemFinder = new PubChemFinder();
                    try {
                        $result = $pubchemFinder->findBySmile($smile, $outArResult, $outArExtResult);
                        switch ($result) {
                            case ResultEnum::REPLY_OK_ONE:
                                $blockTO = new BlockTO($intCounter, $outArResult[Front::CANVAS_INPUT_NAME], "", $smile, ComputeEnum::FORMULA_MASS);
                                $blockTO->reference = new ReferenceTO();
                                $blockTO->reference->identifier = $outArResult[Front::CANVAS_INPUT_IDENTIFIER];
                                $blockTO->reference->server = ServerEnum::PUBCHEM;
                                break;
                            case ResultEnum::REPLY_OK_MORE:
                            case ResultEnum::REPLY_NONE:
                            default:
                                $blockTO = new BlockTO($intCounter, "", "", $smile);
                                break;
                        }
                    } catch (BadTransferException $e) {
                        $blockTO = new BlockTO($intCounter, "", "", $smile);
                    }
                }
                $blocks[] = $blockTO;
                $intCounter++;
            }
            $data[Front::BLOCK_COUNT] = $intCounter;
        }
        $data[Front::BLOCKS] = $blocks;
        $data[Front::SEQUENCE] = $this->input->post(Front::SEQUENCE);
        $data[Front::SEQUENCE_TYPE] = $this->input->post(Front::SEQUENCE_TYPE);
        set_cookie(self::COOKIE_BLOCKS, json_encode($blocks), 3600);
        $this->renderBlocks($data);
    }

    private function renderBlocks($data) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view(Front::PAGES_CANVAS);
        $this->load->view(Front::PAGES_MAIN, $this->getLastData());
        $this->load->view(Front::PAGES_BLOCKS, $data);
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
        $btnBlocks = $this->input->post("blocks");
        $intDatabase = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $intFindBy = $this->input->post(Front::CANVAS_INPUT_SEARCH_BY);
        $blMatch = $this->input->post(Front::CANVAS_INPUT_MATCH);

        if (isset($btnFind)) {
            /* Find */
            $this->find($intDatabase, $intFindBy, $blMatch);
        } else if (isset($btnSave)) {
            /* Save to database */
            $this->save();
        } else if (isset($btnLoad)) {
            /* Load from database */
        } else if (isset($btnBlocks)) {
            /* Building Blocks */
            $this->blocks();
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
                log_message(LoggerEnum::DEBUG, "Last page of results");
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
     * @param array $arSearchOptions
     * @return int result code 0 => find none, 1 => find 1, 2 => find more than 1
     * @throws BadTransferException
     */
    private function findBy($intDatabase, $intFindBy, &$outArResult = array(), &$outArNextResult = array(), $arSearchOptions = array()) {
        $finder = FinderFactory::getFinder($intDatabase, $arSearchOptions);
        switch ($intFindBy) {
            case FindByEnum::IDENTIFIER:
                return $this->validateFormAndSearchByIdentifier($finder, $outArResult);
            case FindByEnum::NAME:
                return $this->validateFormAndSearchByName($finder, $outArResult, $outArNextResult);
            case FindByEnum::FORMULA:
                return $this->validateFormAndSearchByFormula($finder, $outArResult, $outArNextResult);
            case FindByEnum::SMILE:
                return $this->validateFormAndSearchBySmiles($finder, $outArResult, $outArNextResult);
            case FindByEnum::MASS:
                return $this->validateFormAndSearchByMass($finder, $outArResult, $outArNextResult);
            default:
                return ResultEnum::REPLY_NONE;
        }
    }

    /**
     * Set form validation, when search by identifier, validate form and search by identifier
     * @param IFinder $finder
     * @param array $outArResult output param with result
     * @return int result code 0 => find none, 1 => find 1, 2 => find more than 1
     * @see ResultEnum
     * @throws BadTransferException
     */
    private function validateFormAndSearchByIdentifier($finder, &$outArResult) {
        $this->form_validation->set_rules(Front::CANVAS_INPUT_IDENTIFIER, "Identifier", Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            return ResultEnum::REPLY_NONE;
        }
        return $finder->findByIdentifier($this->input->post(Front::CANVAS_INPUT_IDENTIFIER), $outArResult);
    }

    /**
     * Set form validation, when search by name, validate form and search by name
     * @param IFinder $finder
     * @param array $outArResult output param with result
     * @param array $outArNextResult output param with integers as identifiers of next results
     * @return int result code 0 => find none, 1 => find 1, 2 => find more than 1
     * @see ResultEnum
     * @throws BadTransferException
     */
    private function validateFormAndSearchByName($finder, &$outArResult, &$outArNextResult) {
        $this->form_validation->set_rules(Front::CANVAS_INPUT_NAME, "Name", Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            return ResultEnum::REPLY_NONE;
        }
        return $finder->findByName($this->input->post(Front::CANVAS_INPUT_NAME), $outArResult, $outArNextResult);
    }

    /**
     * Set form validation, when search by formula, validate form and search by formula
     * @param IFinder $finder
     * @param array $outArResult output param with result
     * @param array $outArNextResult output param with integers as identifiers of next results
     * @return int result code 0 => find none, 1 => find 1, 2 => find more than 1
     * @see ResultEnum
     * @throws BadTransferException
     */
    private function validateFormAndSearchByFormula($finder, &$outArResult, &$outArNextResult) {
        $this->form_validation->set_rules(Front::CANVAS_INPUT_FORMULA, "Formula", Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            return ResultEnum::REPLY_NONE;
        }
        return $finder->findByFormula($this->input->post(Front::CANVAS_INPUT_FORMULA), $outArResult, $outArNextResult);
    }

    /**
     * Set form validation, when search by SMILES, validate form and search by SMILES
     * @param IFinder $finder
     * @param array $outArResult output param with result
     * @param array $outArNextResult output param with integers as identifiers of next results
     * @return int result code 0 => find none, 1 => find 1, 2 => find more than 1
     * @see ResultEnum
     * @throws BadTransferException
     */
    private function validateFormAndSearchBySmiles($finder, &$outArResult, &$outArNextResult) {
        $this->form_validation->set_rules(Front::CANVAS_INPUT_SMILE, "SMILES", Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            return ResultEnum::REPLY_NONE;
        }
        return $finder->findBySmile($this->input->post(Front::CANVAS_INPUT_SMILE), $outArResult, $outArNextResult);
    }

    /**
     * Set form validation, when search by mass, validate form and search by mass
     * @param IFinder $finder
     * @param array $outArResult output param with result
     * @param array $outArNextResult output param with integers as identifiers of next results
     * @return int result code 0 => find none, 1 => find 1, 2 => find more than 1
     * @see ResultEnum
     * @throws BadTransferException
     */
    private function validateFormAndSearchByMass($finder, &$outArResult, &$outArNextResult) {
        $this->form_validation->set_rules(Front::CANVAS_INPUT_MASS, "Mass", Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            return ResultEnum::REPLY_NONE;
        }
        return $finder->findByMass($this->input->post(Front::CANVAS_INPUT_MASS), $this->input->post(Front::CANVAS_INPUT_DEFLECTION), $outArResult, $outArNextResult);
    }

    private function validateSequence() {
        $this->form_validation->set_rules(Front::SEQUENCE_TYPE, 'Sequence Type', 'required');
        $this->form_validation->set_rules(Front::CANVAS_INPUT_NAME, 'Sequence Name', 'required');
        $this->form_validation->set_rules(Front::CANVAS_INPUT_FORMULA, 'Sequence Formula', 'required');
        $this->form_validation->set_rules(Front::CANVAS_INPUT_MASS, 'Sequence Mass', 'required');
        $this->form_validation->set_rules(Front::SEQUENCE, 'Sequence', 'required');
        $this->form_validation->set_rules(Front::CANVAS_INPUT_SMILE, 'Sequence SMILES', 'required');
        if ($this->form_validation->run() === false) {
            throw new IllegalArgumentException();
        }
        $this->validateSequenceString();
    }

    private function validateSequenceString() {
        $sequence = $this->input->post(Front::SEQUENCE);
        if (!preg_match('/\[\\d+\]/', $sequence)) {
            throw new IllegalArgumentException();
        }
    }

    private function validateBlocks() {
        $cookieVal = get_cookie(self::COOKIE_BLOCKS);
        if ($cookieVal === null) {
            $this->errors = "Blocks data problem";
            throw new IllegalArgumentException();
        }
    }

    private function getLastBlocksData() {
        $cookieVal = get_cookie(self::COOKIE_BLOCKS);
        if ($cookieVal !== null) {
            $blocks = json_decode($cookieVal);
            $data[Front::BLOCKS] = $blocks;
        }
        $data[Front::BLOCK_COUNT] = sizeof($blocks);
        $data[Front::SEQUENCE] = $this->input->post(Front::SEQUENCE);
        $data[Front::SEQUENCE_TYPE] = SequenceTypeEnum::$values[$this->input->post(Front::SEQUENCE_TYPE)];
        return $data;
    }

    private function save() {
        try {
            $this->validateSequence();
            $this->validateBlocks();
        } catch (IllegalArgumentException $exception) {
            $this->renderBlocks($this->getLastBlocksData());
            return;
        }

        $sequenceType = $this->input->post(Front::SEQUENCE_TYPE);
        $sequenceName = $this->input->post(Front::CANVAS_INPUT_NAME);
        $sequenceFormula = $this->input->post(Front::CANVAS_INPUT_FORMULA);
        $sequenceMass = $this->input->post(Front::CANVAS_INPUT_MASS);
        $sequence = $this->input->post(Front::SEQUENCE);
        $sequenceSmiles = $this->input->post(Front::CANVAS_INPUT_SMILE);
        $sequenceDatabase = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $sequenceIdentifier = $this->input->post(Front::CANVAS_INPUT_NAME);
        $cookieVal = get_cookie(self::COOKIE_BLOCKS);
        $blocks = json_decode($cookieVal);
        $sequenceTO = new SequenceTO($sequenceDatabase, $sequenceName, $sequenceSmiles, $sequenceFormula, $sequenceMass, $sequenceIdentifier, $sequence, $sequenceType);


        // TODO save

        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view(Front::PAGES_CANVAS);
        $this->load->view(Front::PAGES_MAIN, $this->getLastData());
        $this->load->view(Front::PAGES_BLOCKS, []);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

}
