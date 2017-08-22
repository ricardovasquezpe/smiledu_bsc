<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_cert_ing_doc extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_mantenimiento/m_cert_ing_doc');
        $this->load->model('m_utils');
        $this->load->library('table');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_CERTI_INGLES_DOC);
        $this->_idUserSess = _getSesion('nid_persona');
    }
    
    function index(){
  	    $data['titleHeader']      = 'Certificado Ingl&eacute;s Docente';
  	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
  	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
  	    $data['rutaSalto']        = 'SI';
        $data['tabDocentes']      =  $this->buildTablaDocentesHTML();
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);
        
    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        
    	//MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
    	
        $this->load->view('vf_mantenimiento/v_cert_ing_doc', $data);
    }
    
    function buildTablaDocentesHTML(){
        $listaDocentes = $this->m_cert_ing_doc->getAllDocentes();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_docentes">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#');
        $head_2 = array('data' => 'Docente');
        $head_3 = array('data' => 'Nro. Doc.', 'class' => 'text-center');
        $head_4 = array('data' => '&#191;Certificaci&oacute;n EFCE?', 'class' => 'text-center');
        $head_5 = array('data' => '&#191;Ingl&eacute;s Nativo?', 'class' => 'text-center');
        $val = 0;
        $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5);
        foreach($listaDocentes as $row) {
            $idCryptDocente = _encodeCI($row->nid_persona);
	        $val++;
	        $row_cell_1  = array('data' => $val);
	        $row_cell_2  = array('data' => $row->nombrecompleto);
	        $row_cell_3  = array('data' => $row->nro_documento);
	        $check = ($row->flg_certi_efce == TIENE_CERTIFICADO_ECFE) ? 'checked' : null;
	        $row_cell_4  = array('data' => '<label for="check'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
											    <input type="checkbox" '.$check.' class="mdl-checkbox__input" id="check'.$val.'" onclick="cambioCheckCert(this);" attr-iddocente="'.$idCryptDocente.'"
                                                       attr-cambio="false" attr-bd="'.$check.'">
											    <span class="mdl-checkbox__label"></span>
										    </label>', 'class' => 'text-center');
	        $check2 = ($row->flg_ingles_nativo == TIENE_CERTIFICADO_ECFE) ? 'checked' : null;
	        $row_cell_5  = array('data' => '<label for="nati'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
											    <input type="checkbox" '.$check2.' class="mdl-checkbox__input" id="nati'.$val.'" onclick="cambioCheckNativo(this);" attr-iddocente="'.$idCryptDocente.'"
                                                       attr-cambio="false" attr-bd="'.$check2.'" attr-efce="'.$check.'" attr-foco="false">
											    <span class="mdl-checkbox__label"></span>
										    </label>', 'class' => 'text-center');
	        $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function grabarDocentesIngles() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $myPostData = json_decode(_post('docentes'), TRUE);
            $strgConcatIdPersonas = null;
            $arrayGeneral = array();
            foreach($myPostData['docente'] as $key => $docente){
                $idDocente  = _decodeCI($docente['idDocente']);
                $chkCerti = ($docente['chkCerti'] == null) ? null : TIENE_CERTIFICADO_ECFE;
                $chkNati  = (isset($docente['chkNati']) ? ($docente['chkNati'] == null) ? null : TIENE_CERTIFICADO_ECFE : null);;
                $arrayDatos = array();
                $arrayDatos = array("flg_certi_efce"    => $chkCerti,
                                    "flg_ingles_nativo" => $chkNati,
                                    "nid_persona"       => $idDocente);
                array_push($arrayGeneral, $arrayDatos);
            }log_message('error', print_r($arrayGeneral, TRUE));
            if(count($arrayGeneral) > 0){
                $data = $this->m_cert_ing_doc->updateCertificacionDoc($arrayGeneral);
            }
            if($data['error'] == EXIT_SUCCESS){
                $data['msj']  = 'Se edit�';
                $data['tablaDocentes'] = $this->buildTablaDocentesHTML();
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function logOut(){
        $logedUser = _getSesion('usuario');
        $this->session->sess_destroy();
        redirect('','refresh');
    }
    
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}