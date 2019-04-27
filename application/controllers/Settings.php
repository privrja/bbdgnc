<?php

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\CycloBranch\Enum\ResetTypeEnum;
use Bbdgnc\Database\BlockDatabase;
use Bbdgnc\Enum\Front;
use Bbdgnc\Exception\IllegalArgumentException;

class Settings extends CI_Controller {
    const PERMISSIONS_ERROR = 'You need to set permissions 777 for bbdgnc and application folders for installation process';
    private $errors = '';

    /**
     * Settings constructor.
     */
    public function __construct() {
        parent::__construct();
        try {
            if (!file_exists(CommonConstants::DB)) {
                @mkdir(CommonConstants::DB, CommonConstants::PERMISSIONS, true);
            }
            if (!file_exists(CommonConstants::UPLOADS_DIR)) {
                @mkdir(CommonConstants::UPLOADS_DIR, CommonConstants::PERMISSIONS, true);
            }
            $this->load->helper("form");
            $this->load->library("form_validation");
            $this->load->helper('url');
            $this->load->model(ModelEnum::BLOCK_MODEL);
            $this->load->model(ModelEnum::SEQUENCE_MODEL);
            $this->load->model(ModelEnum::MODIFICATION_MODEL);
            $this->load->model(ModelEnum::BLOCK_TO_SEQUENCE_MODEL);
            $this->load->dbforge();
        } catch (\Error $exception) {
            $this->errors = self::PERMISSIONS_ERROR;
        }
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('settings/main', [Front::ERRORS => $this->errors]);
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

    public function remove() {
        $remove = $this->input->post('remove');
        $this->form_validation->set_rules('remove', 'Delete', Front::REQUIRED);
        if ($this->form_validation->run() === false || $remove !== 'remx') {
            $this->index();
            return;
        }

        try {
            $res = delete_files(CommonConstants::UPLOADS_DIR);
            if (!$res) {
                throw new IllegalArgumentException();
            }
            $this->dbforge->drop_database(CommonConstants::DB . CommonConstants::DATA_SQLITE);
            $this->db->close();
            $res = delete_files(CommonConstants::DB, true);
            if (!$res) {
                throw new IllegalArgumentException();
            }
        } catch (IllegalArgumentException $exception) {
            $this->errors = self::PERMISSIONS_ERROR;
            $this->index();
            return;
        } catch (\Error $exception) {
            $this->errors = 'Error';
            $this->index();
            return;
        }
        $this->errors = 'Removed OK';
        $this->index();
    }

    function deleteFiles($target) {
        if (!file_exists($target)) {
            return;
        }
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

            foreach ($files as $file) {
                $this->deleteFiles($file);
            }

            $res = @rmdir($target);
            if (!$res) {
                throw new IllegalArgumentException();
            }
        } elseif (is_file($target)) {
            $res = @unlink($target);
            if (!$res) {
                throw new IllegalArgumentException();
            }
        }
    }

}
