<?php

use Bbdgnc\Base\ModelEnum;
use Bbdgnc\CycloBranch\Enum\ResetTypeEnum;
use Bbdgnc\Database\BlockDatabase;
use Bbdgnc\Enum\Front;

class Settings extends CI_Controller {
    const DB = "application/db";
    const DATA_SQLITE = '/data.sqlite';
    const DATABASE_FILE = self::DB . self::DATA_SQLITE;

    /**
     * Settings constructor.
     */
    public function __construct() {
        parent::__construct();
        if (!file_exists(self::DB)) {
            mkdir(self::DB, 0755, true);
        }

        $h = fopen(self::DATABASE_FILE, 'w');
        fclose($h);
        $this->load->helper("form");
        $this->load->library("form_validation");
        $this->load->helper('url');
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->model(ModelEnum::BLOCK_TO_SEQUENCE_MODEL);
        $this->load->dbforge();
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('settings/main');
        $this->load->view('templates/footer');
    }

    public function reset() {
        $delete = $this->input->post('delete');
        $this->form_validation->set_rules('delete', 'Delete', Front::REQUIRED);
        if ($this->form_validation->run() === false || $delete !== 'delx') {
            $this->index();
            return;
        }

        $blockDatabase = new BlockDatabase($this);
        $type = $this->input->post('resetType');
        switch ($type) {
            case ResetTypeEnum::EMPTY:
                $blockDatabase->deleteAll();
                break;
            case ResetTypeEnum::AMINO_ACIDS:
                $blockDatabase->resetWithAminoAcids();
                break;
            case ResetTypeEnum::AMINO_ACIDS_WITH_MODIFICATION:
                $blockDatabase->resetAminoAcidsWithModifications();
                break;
            case ResetTypeEnum::DEFAULT_MODIFICATIONS:
                $blockDatabase->resetWithModifications();
                break;
            default:
                break;
        }
        $this->index();
    }

}
