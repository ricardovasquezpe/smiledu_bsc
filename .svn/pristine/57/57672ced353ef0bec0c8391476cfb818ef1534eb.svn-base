<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_download_excel extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_reportes');
        $this->load->library('Classes/PHPExcel.php');    
    }
    
    public function index(){
        $id_sede = _decodeCI($_POST['idSede']);
        $year    = _decodeCI($_POST['year']);
        $data['inputFileName'] = APPPATH.'\\controllers\\Plantilla_excel.xlsm';
        $data['objPHPExcel']   = new PHPExcel();
        $data['textos']        = array('Total Mes','Total Acumulado:','Monto de Cobranza:',utf8_encode('Índice de Cobranza(%):'),'Monto de Morosidad:',utf8_encode('Índice de Morosidad:'),'Monto por Cobrar:','Monto por Cobrar Acumulado:',utf8_encode('Índice por Cobrar:'),'Cartera Pesada:','Cartera Pesada Acumulada:',utf8_encode('Índice de Cartera Pesada(%):'));
        $data['alpha']         = $alphas = range('A', 'Z');
        $data['datos']         = $this->m_reportes->getPersonasBySedeYear($id_sede , $year);
        $data['dataFin']       = $this->m_reportes->getDatosFinalesExcel($id_sede  , $year);
        $this->load->view('v_download_excel',$data);
    }
    
    function buildExcel(){
        $id_sede = _decodeCI(_post('id_sede'));
        if($id_sede != null){
            $data['inputFileName'] = APPPATH.'\\controllers\\Plantilla_excel.xlsm';
            $data['objPHPExcel']   = new PHPExcel();
            $data['alpha']         = $alphas = range('A', 'Z');
            $data['datos']         = $this->m_reportes->getPersonasBySedeYear(6,2016);
            $this->m_reportes->getDatosFinalesExcel(6,2016);
            $this->load->view('v_download_excel',$data);
        }
    }
}
