<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_txt_download extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('download');
    }
    
    public function index() {
        $fileName = $_POST['filename'];
        force_download('./uploads/modulos/pagos/txt/'.$fileName,NULL);
    }
}