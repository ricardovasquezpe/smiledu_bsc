<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Calendario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_usuario');
        $this->load->model('m_utils');
        $this->load->model('googlecalendar');
        $this->load->model('mf_formulario/m_formulario');
        $this->load->library('table');
    }
   
	public function index() {
        if(isset($_GET['code'])){
            $code = $_GET['code'];
            $this->googlecalendar->login($code);
            $datos = $this->m_formulario->getEventoRegistro();
            $fecha = _fecha_tabla($datos['fecha_realizar'], "Y-m-d");
            $event = array(
                'summary' 		=> utf8_encode($datos['desc_evento']),
                'start' 		=> $fecha.'T10:00:00.000-05:00',
                'end' 			=> $fecha.'T10:00:00.000-05:00',
                'description' 	=> utf8_encode($datos['desc_evento']));
            $this->googlecalendar->addEvent('primary',$event);
        }
        echo "<script>window.close();</script>";
	}
	
}