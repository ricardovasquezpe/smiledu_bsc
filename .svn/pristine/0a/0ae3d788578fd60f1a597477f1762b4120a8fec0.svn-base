<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_confirm_asist_bypass extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
    }
   
	public function index() {
	    $data = array();
	    if(isset($_GET['recevento']) && isset($_GET['persona']) && isset($_GET['opcion'])){
	        $idRecEvento = _simple_decrypt(str_replace(" ","+",$_GET['recevento']));
	        $idPersona   = _simple_decrypt(str_replace(" ","+",$_GET['persona']));
	        $opc         = _simple_decrypt(str_replace(" ","+",$_GET['opcion']));
	        $arraySession = array("recursoEventoConfirmar" => $idRecEvento,
	                              "personaConfirmar"       => $idPersona,
	                              "opcionConfirmar"        => $opc);
	        $this->session->set_userdata($arraySession);
	        redirect('admision/c_confirmar_evento', 'location');
	    }else{
	        redirect('c_login', 'location');
	    }
	}
}