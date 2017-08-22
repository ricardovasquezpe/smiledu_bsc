<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_excel_plantilla extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('m_utils');
        $this->load->library('Classes/PHPExcel.php');
    }
    
    public function index() {
        $cantEncuestados = $_POST["cantEncuestados"];
        $idEncuesta              = $_POST["id_encu"];
        $data['idEncuesta']      = _simpleDecryptInt($idEncuesta);
        $data['tipoEncuesta']    = $this->m_utils->getById('senc.encuesta', '_id_tipo_encuesta', 'id_encuesta', $data['idEncuesta']);
        $data['cantEncuestados'] = $cantEncuestados;
        $data['objPHPExcel']     = new PHPExcel();
        $data['inputFileName']   = APPPATH.'\\controllers\\Plantilla_encuesta_manual.xlsm';
        $this->load->view('v_download_excel_plantilla', $data);
    }
}
