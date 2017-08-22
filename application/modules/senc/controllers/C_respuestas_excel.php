<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_respuestas_excel extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Classes/PHPExcel.php');
        $this->load->model('mf_encuesta/m_encuesta');
    }
    
    public function index() {
//         $cantEncuestados = $_POST["cantEncuestados"];
//         $idEncuesta = $_POST["id_encu"];
//         $data['idEncuesta']  = _simpleDecryptInt($idEncuesta);
//         $data['cantEncuestados'] = $cantEncuestados;
        $data['objPHPExcel'] = new PHPExcel();
        $data['inputFileName'] = APPPATH.'\\controllers\\Plantilla_encuesta_manual.xlsm';
        $this->load->view('v_download_respuestas_excel', $data);
	    //}
    }
}