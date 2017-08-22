<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_admision extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('m_main');
        $this->load->model('mf_mantenimiento/m_admision');
        $this->load->library('table');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_ADMINISION);
        $this->_idUserSess = _getSesion('nid_persona');
    }

	public function index() {
  	    $data['titleHeader']      = 'Admisi&oacute;n';
  	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
  	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
  	    $data['rutaSalto']        = 'SI';
        $data['optUniv'] = __buildComboUniversidades();
        $data['optSede'] = __buildComboSedes();
        $data['tablaAlumnosAula'] = $this->buildTablaAdmisionHTML(0,0);
    	$data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);

    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
    	
    	//MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
    	
        $this->load->view('vf_mantenimiento/v_admision', $data);
	}
		
	function buildTablaAdmisionHTML($idUniv, $idAula) {
	    $listaAdmision = ($idUniv == 0 || $idAula == 0) ? array() : $this->m_admision->getAlumnosPostulantesByAula($idUniv, $idAula);
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_admision">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_1 = array('data' => '#', 'class' => 'text-center');
	    $head_2 = array('data' => 'Alumno', 'style' => 'text-align:left', 'data-sortable' => 'true');
	    $head_3 = array('data' => 'Puntaje');
	    $head_4 = array('data' => '&#191;Particip&oacute;?'         , 'class' => 'text-center');
	    $head_5 = array('data' => '&#191;Ingres&oacute;?', 'class' => 'text-center');
	    $val = 0;
	    $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5);
	    foreach($listaAdmision as $row) {
	        $idCryptAlumno = _encodeCI($row->__id_persona);
	        $idCryptAdmin   = _encodeCI($row->id_admision);
	        $val++;
	        $row_cell_1  = array('data' => $val);
	        $row_cell_2  = array('data' => ucwords(strtolower($row->nombres)));
	        $row_cell_3  = array('data' => '<div class="mdl-textfield mdl-js-textfield">
                                                <input class="mdl-textfield__input" type="text" value="'.$row->puntaje.'" maxlength="5" name="puntaje" id="pjte_ingre_'.$val.'"
	                                                   attr-bd="'.$row->puntaje.'" attr-idalumno="'.$idCryptAlumno.'" attr-id_admin="'.$idCryptAdmin.'" attr-cambio="false" onchange="onChangePuntaje(this);" maxlength="7">
                                                <label class="mdl-textfield__label" for="pjte_ingre_'.$val.'"></label>
                                             </div>');
            $check = ($row->flg_ingreso == 'S') ? 'checked' : null;
            $checkParticipo = ($row->id_admision != null) ? 'checked' : null;
            $row_cell_4  = array('data' => '<label for="check'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" id="cont_cb_'.$val.'">
                                              <input type="checkbox" class="mdl-checkbox__input" id="check'.$val.'" '.$checkParticipo.' attr-ptje_ingre="'.$row->puntaje.'" attr-flg_ingre="'.$check.'" onclick="cambioCheckAdmin(this);"
	                                                       attr-fila="'.$val.'" attr-idalumno="'.$idCryptAlumno.'" attr-id_admin="'.$idCryptAdmin.'" attr-cambio="false" attr-foco="false" attr-bd="'.$checkParticipo.'">
                                              <span class="mdl-checkbox__label"></span>
                                            </label>', 'class' => 'text-center');
            $row_cell_5  = array('data' => '<label for="ingre_'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
											    <input type="checkbox" class="mdl-checkbox__input" id="ingre_'.$val.'" '.$check.' onclick="cambioCheckIngreso(this);" attr-idalumno="'.$idCryptAlumno.'" 
                                                       attr-id_admin="'.$idCryptAdmin.'" attr-bd="'.$check.'" attr-cambio="false">
											    <span class="mdl-checkbox__label"></span>
									        </label>', 'class' => 'text-center');
	      
	        $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5);
	    }
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	function getPostulantesByUniv_CTRL() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idAula = _decodeCI(_post('idAula'));
	        $idUniv = _decodeCI(_post('idUniv'));
	        if($idUniv == null) {
	            $data['error'] = EXIT_WARM;
	            $data['optSede'] = null;
	            $data['tablaAlumnosAula'] = $this->buildTablaAdmisionHTML(0, 0);
	            throw new Exception(null);
	        }
	        $idAula = ($idAula == null) ? 0 : $idAula;
	        $data['tablaAlumnosAula'] = $this->buildTablaAdmisionHTML($idUniv, $idAula);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboGradosSede() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede = _decodeCI(_post('idSede'));
	        if($idSede == null) {	           
	            $data['error'] = EXIT_WARM;
	            $data['optSede'] = null;
	            $data['tablaAlumnosAula'] = $this->buildTablaAdmisionHTML(0, 0);
	            throw new Exception(null);
	        }
	        $data['optGrado'] = __buildComboGrados($idSede);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboAulasGrado() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idGrado = _decodeCI(_post('idGrado'));
	        $idSede  = _decodeCI(_post('idSede'));
	        if($idGrado == null) {
	            $data['error'] = EXIT_WARM;	      
	            $data['optGrado'] = null;
	            $data['tablaAlumnosAula'] = $this->buildTablaAdmisionHTML(0, 0);
	            throw new Exception(null);
	        }
	        $data['optAula'] = __buildComboAulas($idGrado, $idSede);
	        $data['tablaAlumnosAula'] = $this->buildTablaAdmisionHTML(0, 0);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAlumnosFromAula_CTRL() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idAula = _decodeCI(_post('idAula'));
	        $idUniv = _decodeCI(_post('idUniv'));
	         if($idAula == null ) {
	             $data['error']    = EXIT_WARM;
	             $data['$optAula'] = null;
	             $data['tablaAlumnosAula'] = $this->buildTablaAdmisionHTML(0, 0);
	             throw new Exception(null);
	        }
	        $idAula = ($idAula == null) ? null : $idAula;
	        $data['tablaAlumnosAula'] = $this->buildTablaAdmisionHTML($idUniv, $idAula);
	      
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function guardarAdmision_CTRL() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idUniv  = _decodeCI(_post('idUniv'));
	        $idAula  = _decodeCI(_post('idAula'));
	        $idGrado = _decodeCI(_post('idGrado'));
	        $idSede  = _decodeCI(_post('idSede'));
	        if($idUniv == null || $idAula == null || $idGrado == null || $idSede == null) {
	            throw new Exception(ANP);
	        }
	        $myPostData = json_decode(_post('alumnos'), TRUE);
	        $strgConcatIdPersonas = null;
	        $arrayGeneral = array();
	        foreach($myPostData['alumno'] as $key => $alumno) {
	            $ptje_ingre     = isset($alumno['ptje_ingre'])  ? $alumno['ptje_ingre']        : false;
	            $ingreso        = isset($alumno['ingreso'])     ? $alumno['ingreso']           : false;
	            $checkParticipo = isset($alumno['checkParticipo']) ? $alumno['checkParticipo'] : false;
	            $idAlumno       = _decodeCI($alumno['idAlumno']);
	            $idAdmin        = _decodeCI($alumno['idAdmin']);
	            
	            $arrayDatos = array();
                $ingreso = ($ingreso == 'true') ? ADMINISION_INGRESO : ADMINISION_NO_INGRESO;
                $ptje_ingre   = ($ptje_ingre == null || trim($ptje_ingre) == "" || $ptje_ingre < 0) ? null : $ptje_ingre;
                if($ptje_ingre == null && $checkParticipo == true) {
                    throw new Exception("Escribir un puntaje en la fila: ".$alumno['fila']);
                }
	            if($idAdmin == null) {
	                $arrayDatos = array("__id_universidad" => $idUniv,
            	                        "year_academico"   => date('Y'),
            	                        "id_alumno"        => $idAlumno,
            	                        "id_aula"          => $idAula,
            	                        "id_grado"         => $idGrado,
            	                        "id_sede"          => $idSede,
            	                        "puntaje"          => $ptje_ingre,
	                                    "flg_ingreso"      => $ingreso,
            	                        "fec_postulo"      => date('d-m-Y'),
            	                        "id_admision"      => $idAdmin);
	            } else if($idAdmin != null && $checkParticipo == true) {
	                $arrayDatos = array("checkParticipo"   => $checkParticipo,
            	                        "puntaje"          => $ptje_ingre,
	                                    "flg_ingreso"      => $ingreso,
            	                        "id_admision"      => $idAdmin);
	            } else if($idAdmin != null && $checkParticipo == false) {
	                $arrayDatos = array("checkParticipo"   => $checkParticipo,
	                                    "id_admision"      => $idAdmin);
	            }
	            array_push($arrayGeneral, $arrayDatos);
	        }
	        if(count($arrayGeneral) > 0){
	            $data = $this->m_admision->insertUpdateAdmision($arrayGeneral);
	        }
	        if($data['error'] == EXIT_SUCCESS) {
	            $data['tablaAlumnosAula'] = $this->buildTablaAdmisionHTML($idUniv, $idAula);
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode',$data));
	}
	
	function checkUniv(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idUniv = _decodeCI(_post('idUniv'));
	        if($idUniv == null) {
	            throw new Exception(ANP);
	        }
	        $data['flg_univ'] = ($idUniv == ID_PUCP) ? true : false;
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function logOut(){
	    $logedUser = _getSesion('usuario');
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}
	
    function enviarFeedBack(){
        $nombre = _getSesion('nombre_completo');
        $mensaje = utf8_decode(_post('feedbackMsj'));
        $url = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}