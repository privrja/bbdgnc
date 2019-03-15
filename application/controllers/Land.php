<?php

use Bbdgnc\Base\BlockSplObjectStorage;
use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\LibraryEnum;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Base\SequenceHelper;
use Bbdgnc\Database\BlockDatabase;
use Bbdgnc\Database\ModificationDatabase;
use Bbdgnc\Database\SequenceDatabase;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Enum\ModificationHelperTypeEnum;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Exception\SequenceInDatabaseException;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Finder\Exception\BadTransferException;
use Bbdgnc\Finder\FinderFactory;
use Bbdgnc\Finder\IFinder;
use Bbdgnc\Finder\PubChemFinder;
use Bbdgnc\Smiles\Graph;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\ModificationTO;
use Bbdgnc\TransportObjects\SequenceTO;

class Land extends CI_Controller {

    /** @var string cookie identifier for next results */
    const COOKIE_NEXT_RESULTS = 'find-next-results';

    /** @var int expire time of cookie 1 hour */
    const COOKIE_EXPIRE_HOUR = 360000;

    const COOKIE_BLOCKS = "cookie_blocks";

    private $errors = "";

    private $blockDatabase;

    /**
     * Land constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper(array(HelperEnum::HELPER_FORM, HelperEnum::HELPER_URL, HelperEnum::HELPER_COOKIE));
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->model(ModelEnum::BLOCK_TO_SEQUENCE_MODEL);
        $this->load->library(LibraryEnum::FORM_VALIDATION);
        $this->blockDatabase = new BlockDatabase($this);
    }

    private function getData() {
        return array(
            Front::CANVAS_INPUT_NAME => "", Front::CANVAS_INPUT_SMILE => "",
            Front::CANVAS_INPUT_FORMULA => "", Front::CANVAS_INPUT_MASS => "",
            Front::CANVAS_INPUT_DEFLECTION => "", Front::CANVAS_INPUT_IDENTIFIER => "",
            Front::ERRORS => ""
        );
    }

    private function getModificationEmptyData($data) {
        $data[Front::N_MODIFICATION_NAME] = "";
        $data[Front::N_MODIFICATION_FORMULA] = "";
        $data[Front::N_MODIFICATION_MASS] = "";
        $data[Front::N_MODIFICATION_TERMINAL_N] = "";
        $data[Front::N_MODIFICATION_TERMINAL_C] = "";
        $data[Front::C_MODIFICATION_NAME] = "";
        $data[Front::C_MODIFICATION_FORMULA] = "";
        $data[Front::C_MODIFICATION_MASS] = "";
        $data[Front::C_MODIFICATION_TERMINAL_N] = "";
        $data[Front::C_MODIFICATION_TERMINAL_C] = "";
        $data[Front::B_MODIFICATION_NAME] = "";
        $data[Front::B_MODIFICATION_FORMULA] = "";
        $data[Front::B_MODIFICATION_MASS] = "";
        $data[Front::B_MODIFICATION_TERMINAL_N] = "";
        $data[Front::B_MODIFICATION_TERMINAL_C] = "";
        return $data;
    }

    private function modifications() {
        $modificationDatabase = new ModificationDatabase($this);
        $modificationsAll = $modificationDatabase->findAll();
        $modifications = ['None'];
        foreach ($modificationsAll as $modification) {
            $modifications[$modification['id']] = $modification['name'];
        }
        return $modifications;
    }

    private function getModificationData($data) {
        $data['modifications'] = $this->modifications();
        $data[Front::N_MODIFICATION_NAME] = $this->input->post(Front::N_MODIFICATION_NAME);
        $data[Front::N_MODIFICATION_FORMULA] = $this->input->post(Front::N_MODIFICATION_FORMULA);
        $data[Front::N_MODIFICATION_MASS] = $this->input->post(Front::N_MODIFICATION_MASS);
        $data[Front::N_MODIFICATION_TERMINAL_N] = $this->input->post(Front::N_MODIFICATION_TERMINAL_N);
        $data[Front::N_MODIFICATION_TERMINAL_C] = $this->input->post(Front::N_MODIFICATION_TERMINAL_C);
        $data[Front::C_MODIFICATION_NAME] = $this->input->post(Front::C_MODIFICATION_NAME);
        $data[Front::C_MODIFICATION_FORMULA] = $this->input->post(Front::C_MODIFICATION_FORMULA);
        $data[Front::C_MODIFICATION_MASS] = $this->input->post(Front::C_MODIFICATION_MASS);
        $data[Front::C_MODIFICATION_TERMINAL_N] = $this->input->post(Front::C_MODIFICATION_TERMINAL_N);
        $data[Front::C_MODIFICATION_TERMINAL_C] = $this->input->post(Front::C_MODIFICATION_TERMINAL_C);
        $data[Front::B_MODIFICATION_NAME] = $this->input->post(Front::B_MODIFICATION_NAME);
        $data[Front::B_MODIFICATION_FORMULA] = $this->input->post(Front::B_MODIFICATION_FORMULA);
        $data[Front::B_MODIFICATION_MASS] = $this->input->post(Front::B_MODIFICATION_MASS);
        $data[Front::B_MODIFICATION_TERMINAL_N] = $this->input->post(Front::B_MODIFICATION_TERMINAL_N);
        $data[Front::B_MODIFICATION_TERMINAL_C] = $this->input->post(Front::B_MODIFICATION_TERMINAL_C);
        return $data;
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
        $block->databaseId = $this->input->post(Front::BLOCK_DATABASE_ID);
        $block->formula = $this->input->post(Front::BLOCK_FORMULA);
        $block->mass = $this->input->post(Front::BLOCK_MASS);
        $block->losses = $this->input->post(Front::BLOCK_NEUTRAL_LOSSES);
        $block->identifier = $this->input->post(Front::BLOCK_REFERENCE);
        $block->database = $this->input->post(Front::BLOCK_REFERENCE_SERVER);
        $data = $this->getLastData();
        $data[Front::BLOCK] = $block;
        $data[Front::BLOCK_COUNT] = $blockCount;
        $data[Front::SEQUENCE] = $sequence;
        $data[Front::SEQUENCE_TYPE] = $sequenceType;
        $data['blocks'] = $this->blockDatabase->findAllSelect();
        $data = $this->getModificationData($data);
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('editor/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    private function toBlockTO(int $blockIdentifier, array $arBlock) {
        $blockTO = new BlockTO(
            $blockIdentifier,
            $arBlock['name'],
            $arBlock['acronym'],
            $arBlock['smiles'],
            ComputeEnum::NO
        );
        $blockTO->formula = $arBlock['residue'];
        $blockTO->mass = $arBlock['mass'];
        $blockTO->losses = $arBlock['losses'];
        $blockTO->database = $arBlock['database'];
        $blockTO->identifier = $arBlock['identifier'];
        return $blockTO;
    }

    public function blocks() {
        $first = $this->input->post('first');
        $data = $this->getLastData();
        $cookieVal = get_cookie(self::COOKIE_BLOCKS . CommonConstants::ZERO);
        $data[Front::SEQUENCE] = $this->input->post(Front::SEQUENCE);
        $data[Front::SEQUENCE_TYPE] = $this->input->post(Front::SEQUENCE_TYPE);
        $data['modifications'] = $this->modifications();
        if (!isset($first) && $cookieVal !== null) {
            $data[Front::BLOCK_COUNT] = $this->input->post(Front::BLOCK_COUNT);
            $blocks = $this->loadCookies($data[Front::BLOCK_COUNT]);
            $blockIdentifier = $this->input->post(Front::BLOCK_IDENTIFIER);
            $databaseId = $this->input->post(Front::BLOCK_SELECT);
            if (!empty($databaseId)) {
                $arBlock = $this->blockDatabase->findById($databaseId);
                $blockTO = $this->toBlockTO($blockIdentifier, $arBlock);
            } else {
                $blockTO = new BlockTO($blockIdentifier, $this->input->post(Front::BLOCK_NAME), $this->input->post(Front::BLOCK_ACRONYM), $this->input->post(Front::BLOCK_SMILE), ComputeEnum::NO);
                $blockTO->databaseId = $this->input->post(Front::BLOCK_DATABASE_ID);
                $blockTO->formula = $this->input->post(Front::BLOCK_FORMULA);
                $blockTO->mass = $this->input->post(Front::BLOCK_MASS);
                $blockTO->losses = $this->input->post(Front::BLOCK_NEUTRAL_LOSSES);
                $blockTO->identifier = $this->input->post(Front::BLOCK_REFERENCE);
                $blockTO->database = $this->input->post(Front::BLOCK_REFERENCE_SERVER);
            }
            $blockTO->databaseId = empty($databaseId) ? "" : $databaseId;
            $blocks[$blockIdentifier] = $blockTO;
            $data = $this->getModificationData($data);
        } else {
            $blocks = [];
            $intCounter = 0;
            $inputSmiles = $this->input->post(Front::BLOCK_SMILES);
            $smiles = explode(",", $inputSmiles);
            foreach ($smiles as $smile) {
                $arResult = $this->blockDatabase->findBlockByUniqueSmiles($smile);

                if (!empty($arResult)) {
                    $blockTO = new BlockTO($intCounter, $arResult['name'], $arResult['acronym'], $arResult['smiles'], ComputeEnum::NO);
                    $blockTO->databaseId = $arResult['id'];
                    $blockTO->formula = $arResult['residue'];
                    $blockTO->mass = $arResult['mass'];
                    $blockTO->database = $arResult['database'];
                    $blockTO->identifier = $arResult['identifier'];
                    $data[Front::SEQUENCE] = SequenceHelper::replaceSequence($data[Front::SEQUENCE], $blockTO->id, $blockTO->acronym);
                } else {
                    $pubchemFinder = new PubChemFinder();
                    try {
                        $result = $pubchemFinder->findBySmile($smile, $outArResult, $outArExtResult);
                        switch ($result) {
                            case ResultEnum::REPLY_OK_ONE:
                                $blockTO = new BlockTO($intCounter, $outArResult[Front::CANVAS_INPUT_NAME], "", $smile, ComputeEnum::FORMULA_MASS);
                                $blockTO->identifier = $outArResult[Front::CANVAS_INPUT_IDENTIFIER];
                                $blockTO->database = ServerEnum::PUBCHEM;
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
            $data = $this->getModificationEmptyData($data);
        }
        $data[Front::BLOCKS] = $blocks;
        $this->saveCookies($blocks);
        $this->renderBlocks($data);
    }


    /**
     * @param BlockTO[] $blocks
     */
    private function saveCookies(array $blocks) {
        foreach ($blocks as $block) {
            set_cookie(self::COOKIE_BLOCKS . $block->id, json_encode($block), self::COOKIE_EXPIRE_HOUR);
        }
    }

    private function loadCookies($blocksCount) {
        $blocks = [];
        for ($index = 0; $index < $blocksCount; ++$index) {
            $cookieVal = get_cookie(self::COOKIE_BLOCKS . $index);
            $blocks[] = json_decode($cookieVal);
        }
        return $blocks;
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
            $this->uniqueSmiles();
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
        $arViewData[Front::ERRORS] = $this->errors;
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
        $this->form_validation->set_rules(Front::SEQUENCE_TYPE, 'Sequence Type', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_NAME, 'Sequence Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_FORMULA, 'Sequence Formula', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_MASS, 'Sequence Mass', Front::REQUIRED);
        $this->form_validation->set_rules(Front::SEQUENCE, 'Sequence', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_SMILE, 'Sequence SMILES', Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            throw new IllegalArgumentException();
        }
        $this->validateSequenceString();
    }

    private function validateSequenceString() {
        $sequence = $this->input->post(Front::SEQUENCE);
        if (preg_match('/\[\\d+\]/', $sequence)) {
            $this->errors = "Sequence problem, some block haven't acronym";
            throw new IllegalArgumentException();
        }
    }

    private function validateBlocks() {
        $cookieVal = get_cookie(self::COOKIE_BLOCKS . CommonConstants::ZERO);
        if ($cookieVal === null) {
            $this->errors = "Blocks data problem";
            throw new IllegalArgumentException();
        }
    }

    private function getLastBlocksData() {
        $data[Front::BLOCK_COUNT] = $this->input->post(Front::BLOCK_COUNT);
        $cookieVal = get_cookie(self::COOKIE_BLOCKS . CommonConstants::ZERO);
        if ($cookieVal !== null) {
            $blocks = $this->loadCookies($data[Front::BLOCK_COUNT]);
            $data[Front::BLOCKS] = $blocks;
        }
        $data[Front::SEQUENCE] = $this->input->post(Front::SEQUENCE);
        $data[Front::SEQUENCE_TYPE] = $this->input->post(Front::SEQUENCE_TYPE);
        return $data;
    }

    private function save() {
        try {
            $this->validateSequence();
            $this->validateBlocks();
        } catch (IllegalArgumentException $exception) {
            $this->renderBlocksError();
            return;
        }

        $sequenceType = $this->input->post(Front::SEQUENCE_TYPE);
        $sequenceName = $this->input->post(Front::CANVAS_INPUT_NAME);
        $sequenceFormula = $this->input->post(Front::CANVAS_INPUT_FORMULA);
        $sequenceMass = $this->input->post(Front::CANVAS_INPUT_MASS);
        $sequence = $this->input->post(Front::SEQUENCE);
        $sequenceSmiles = $this->input->post(Front::CANVAS_INPUT_SMILE);
        $sequenceDatabase = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $sequenceIdentifier = $this->input->post(Front::CANVAS_INPUT_IDENTIFIER);
        $lengthBlocks = $this->input->post(Front::BLOCK_COUNT);
        $blocks = $this->loadCookies($lengthBlocks);
        $mapBlocks = new BlockSplObjectStorage();
        for ($index = 0; $index < $lengthBlocks; ++$index) {
            if ($blocks[$index]->name === "") {
                $this->errors = "On block with acronym " . $blocks[$index]->acronym . " isn't set up name";
                $this->renderBlocksError();
                return;
            }
            $blockTO = new BlockTO($blocks[$index]->id, $blocks[$index]->name, $blocks[$index]->acronym, $blocks[$index]->smiles, ComputeEnum::UNIQUE_SMILES);
            $blockTO->databaseId = $blocks[$index]->databaseId;
            $blockTO->formula = $blocks[$index]->formula;
            $blockTO->mass = $blocks[$index]->mass;
            $blockTO->losses = $blocks[$index]->losses;
            $blockTO->database = $blocks[$index]->database;
            $blockTO->identifier = $blocks[$index]->identifier;
            $mapBlocks->attach($blockTO);
        }

        $modifications = [];
        $branchChar = ModificationHelperTypeEnum::startModification($sequenceType);
        for ($index = 0; $index < 3; ++$index) {
            $modificationNameSel = $this->input->post($branchChar . Front::MODIFICATION_SELECT);
            if ($modificationNameSel != 0) {
                $modification = new ModificationTO('', '', '', '', '');
                $modification->databaseId = $modificationNameSel;
                $modifications[$branchChar] = $modification;
            } else {
                $modificationName = $this->input->post($branchChar . Front::MODIFICATION_NAME);
                if (isset($modificationName) && $modificationName != '') {
                    $modificationFormula = $this->input->post($branchChar . Front::MODIFICATION_FORMULA);
                    if (!isset($modificationFormula) || $modificationFormula === "") {
                        $this->errors = "Formula for modification " . strtoupper($branchChar) . " is not defined";
                        $this->renderBlocksError();
                        return;
                    }
                    $modificationMass = $this->input->post($branchChar . Front::MODIFICATION_MASS);
                    if (!isset($modificationMass) || $modificationMass === "") {
                        $modificationMass = FormulaHelper::computeMass($modificationFormula);
                    }
                    $modificationTerminalN = Front::toBoolean($this->input->post($branchChar . Front::MODIFICATION_TERMINAL_N));
                    $modificationTerminalC = Front::toBoolean($this->input->post($branchChar . Front::MODIFICATION_TERMINAL_C));
                    $modification = new ModificationTO($modificationName, $modificationFormula, $modificationMass, $modificationTerminalN, $modificationTerminalC);
                    $modifications[$branchChar] = $modification;
                }
            }
            $branchChar = ModificationHelperTypeEnum::changeBranchChar($branchChar, $sequenceType);
            if (ModificationHelperTypeEnum::isEnd($branchChar)) {
                break;
            }
        }

        $sequenceTO = new SequenceTO($sequenceDatabase, $sequenceName, $sequenceSmiles, $sequenceFormula, $sequenceMass, $sequenceIdentifier, $sequence, $sequenceType);
        $sequenceTO->identifier = $sequenceIdentifier;
        $sequenceTO->database = $sequenceDatabase;
        $sequenceDatabase = new SequenceDatabase($this);

        try {
            $sequenceDatabase->save($sequenceTO, $mapBlocks, $modifications);
        } catch (SequenceInDatabaseException $e) {
            $this->errors = "Sequence is already in database";
            $this->renderBlocksError();
            return;
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
            $this->renderBlocksError();
            return;
        }

        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view(Front::PAGES_CANVAS);
        $this->load->view(Front::PAGES_MAIN, $this->getLastData());
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    private function renderBlocksError() {
        $data = $this->getLastBlocksData();
        $data = $this->getModificationData($data);
        $this->renderBlocks($data);
    }

    private function uniqueSmiles() {
        $graph = new Graph($this->input->post(Front::CANVAS_INPUT_SMILE));
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view(Front::PAGES_CANVAS);
        $data = $this->getLastData();
        $data[Front::CANVAS_INPUT_SMILE] = $graph->getUniqueSmiles();
        $this->load->view(Front::PAGES_MAIN, $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

}
