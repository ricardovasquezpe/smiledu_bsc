<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_simulacro extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('mf_mantenimiento/m_simulacro');
        $this->load->library('table');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_ADMINISION);
        $this->_idUserSess = _getSesion('nid_persona');
    }

	public function index(){  
	    $data['titleHeader']      = 'Simulacros';
	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
	    $data['rutaSalto']        = 'SI';
        
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);
        
    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        
    	//MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
  	    $data['font_size'] = _getSesion('font_size');
  	    
  	    $data['optUniv'] = __buildComboUniversidades();
  	    $data['optSede'] = __buildComboSedes();
  	    $data['tablaAlumnosAula'] = $this->buildTablaSimulacroHTML(0, 0,false, null);
  	    
        $this->load->view('vf_mantenimiento/v_simulacro', $data);
	}
		
	function buildTablaSimulacroHTML($idUniv, $idAula, $isPUCP, $nroSimulacro){
        $listaSimulacro = ($idUniv == 0 || $idAula == 0) ? array() : $this->m_simulacro->getAlumnosPostulantesByAula($idUniv, $idAula, $nroSimulacro);
	    $tmpl = array('table_open'  => '<table data-toggle="table" data-toolbar="#custom-toolbar" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_simulacro">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_1 = array('data' => '#');
        $head_2 = array('data' => 'Alumno', 'style' => 'text-align:left', 'data-sortable' => 'true');
	    $head_3 = array('data' => ($isPUCP) ? 'Apto / No Apto' : 'Puntaje', 'class' => 'col-sm-2');
	    $head_4 = array('data' => '&#191;Particip&oacute;?','class' => 'text-center');
	    $val = 0;
	    $this->table->set_heading($head_1, $head_2, $head_3, $head_4);
	    foreach($listaSimulacro as $row){
	        $idCryptAlumno = _encodeCI($row->__id_persona);
	        $idCryptSimu   = _encodeCI($row->id_simulacro);
	        $val++;
	        $row_cell_1  = array('data' => $val);
	        $row_cell_2  = array('data' => $row->nombres);
	        $row_cell_3  = array('data' => '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                            <input type="text" onchange="onChangePuntaje(this);" class="mdl-textfield__input" maxlength="7" value="'.$row->puntaje.'" name="puntaje" id="ptje_apto_'.$val.'" attr-bd="'.$row->puntaje.'" attr-idalumno="'.$idCryptAlumno.'"
	                                                   attr-id_simu="'.$idCryptSimu.'" attr-cambio="false" >
	                                             <label class="mdl-textfield__label" for="ptje_apto_'.$val.'"></label> 
	                                        </div>');
	        
	        $valAttrPtjeAptop = $row->puntaje;
	        if($isPUCP) { // Pinta el checkbox de apto/no apto
	            $check = ($row->flg_apto == 'S') ? 'checked' : null;
	            $valAttrPtjeAptop = ($check == 'checked') ? 'true' : 'false';
	            $row_cell_3  = array('data' => '<div class="checkbox checkbox-inline checkbox-styled">
    										    <label class="text-center">
    											    <input type="checkbox" id="ptje_apto_'.$val.'" '.$check.' onclick="cambioCheckApto(this);" attr-idalumno="'.$idCryptAlumno.'" attr-id_simu="'.$idCryptSimu.'" attr-bd="'.$check.'" attr-cambio="false">
    											    <span></span>
    										    </label>
    									    </div>');
	        }
	        $checkParticipo = ($row->id_simulacro != null) ? 'checked' : null;
	  
	        $row_cell_4  = array('data' => '<label for="check'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect text-center">
											    <input type="checkbox" class="mdl-checkbox__input" id="check'.$val.'" '.$checkParticipo.' attr-ptje_apto="'.$valAttrPtjeAptop.'" onclick="cambioCheckSimu(this);" attr-fila="'.$val.'"
                                                       attr-idalumno="'.$idCryptAlumno.'" attr-id_simu="'.$idCryptSimu.'" attr-cambio="false" attr-foco="false" attr-bd="'.$checkParticipo.'">
											    <span class="mdl-checkbox__label"></span>
										    </label>');
	        $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3,$row_cell_4);
	    }
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	function getPostulantesByUniv_CTRL() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idAula  = _decodeCI(_post('idAula'));
	        $idUniv  = _decodeCI(_post('idUniv'));
	        $idGrado = _decodeCI(_post('idGrado'));
	        $idSede  = _decodeCI(_post('idSede'));
	        $nroSimu = _decodeCI(_post('nroSimu'));
	        if($idUniv == null) {
	            $data['error'] = EXIT_WARM;
	            $data['optSede'] = null;
	            $data['tablaAlumnosAula'] = $this->buildTablaSimulacroHTML(0, 0, 0, $nroSimu);
	            throw new Exception(null);
	        }
	        $idAula = ($idAula == null) ? null : $idAula;
	        $apto = ($idUniv == ID_PUCP) ? true : false;
	        $data['optSede'] = __buildComboSedes();
	        $data['tablaAlumnosAula'] = $this->buildTablaSimulacroHTML($idAula, $idUniv, $apto, $nroSimu);
	        if($idGrado != null && $idSede != null) {
	            $data['comboNroSimu'] = __buildComboNrosSimulacro($idSede, $idGrado, $idUniv);
	        } else {
	            $data['comboNroSimu'] = EXIT_ERROR;
	        }
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
	            $data['tablaAlumnosAula'] = $this->buildTablaSimulacroHTML(0, 0, 0, null);
	            throw new Exception(null);
	        }
	        $data['tablaAlumnosAula'] = $this->buildTablaSimulacroHTML(0, 0, 0, null);
	        $data['optGrado']  = __buildComboGrados($idSede);
	        $data['error']     = EXIT_SUCCESS;
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
	        $idUniv  = _decodeCI(_post('idUniv'));
	        if($idGrado == null) {
	            $data['error'] = EXIT_WARM;
	            $data['optGrado'] = null;
	            $data['tablaAlumnosAula'] = $this->buildTablaSimulacroHTML(0, 0, 0, null);
	            throw new Exception(null);
	        }
	        $data['tablaAlumnosAula'] = $this->buildTablaSimulacroHTML(0, 0, 0, null);
	        $data['optAula'] = __buildComboAulas($idGrado, $idSede);
	        if($idUniv != null) {
	            $data['comboNroSimu'] = __buildComboNrosSimulacro($idSede, $idGrado, $idUniv);
	        } else {
	            $data['comboNroSimu'] = EXIT_ERROR;
	        }
	        $data['error']     = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAlumnosFromAula_CTRL() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idAula   = _decodeCI(_post('idAula'));
	        $idUniv   = _decodeCI(_post('idUniv'));
	        $nroSimu = (_post('nroSimu') == null || _post('nroSimu') == '') ? null : _simple_decrypt(_post('nroSimu'));
	        if($idAula == null ) {
	            $data['error'] = EXIT_WARM;
	            $data['$optAula'] = null;
	            $data['tablaAlumnosAula'] = $this->buildTablaSimulacroHTML(0, 0, 0, $nroSimu);
	            throw new Exception(null);
	        }
	        $idAula = ($idAula == null) ? null : $idAula;
	        $apto = ($idUniv == ID_PUCP) ? true : false;
	        $data['tablaAlumnosAula'] = $this->buildTablaSimulacroHTML($idUniv, $idAula, $apto, $nroSimu);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}

	function guardarSimulacro_CTRL(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idUniv  = _decodeCI(_post('idUniv'));
	        $idAula  = _decodeCI(_post('idAula'));
	        $idGrado = _decodeCI(_post('idGrado'));
	        $idSede  = _decodeCI(_post('idSede'));
	        $nroSimu = (_post('nroSimu') == null || _post('nroSimu') == '') ? null : _simple_decrypt(_post('nroSimu'));
	        if($idUniv == null || $idAula == null || $idGrado == null || $idSede == null) {
	            throw new Exception(ANP);
	        }
	        if($idUniv == ID_PUCP) {
	            $columna = "flg_apto";
	        } else {
	            $columna = "puntaje";
	        }
	        if($nroSimu == null) {//Nueva medicion ELSE usar la seleccionada
	            /*$cantAlumnos = $this->m_simulacro->getCountAlumnosSimulacro($idSede, $idGrado, $idUniv);
	            if($cantAlumnos == 0) {
	                throw new Exception('Para crear un nuevo simulacro debe ');
	            }*/
	            $nroSimu = $this->m_simulacro->getNextNroSimulacro($idSede, $idGrado, $idUniv);
	            if($nroSimu == null) {
	                throw new Exception(ANP);
	            }
	        }
	        $myPostData = json_decode(_post('alumnos'), TRUE);
	        $strgConcatIdPersonas = null;
	        $arrayGeneral = array();
	        foreach($myPostData['alumno'] as $key => $alumno){	            
	            $ptje_apto      = isset($alumno['ptje_apto'])      ? $alumno['ptje_apto']      : false;
	            $checkParticipo = isset($alumno['checkParticipo']) ? $alumno['checkParticipo'] : false;
	            $idAlumno       = _decodeCI($alumno['idAlumno']);
	            $idSimu         = _decodeCI($alumno['idSimu']);
	           if($idUniv == ID_PUCP) {
	                $ptje_apto = ($ptje_apto == 'true') ? 'S' : 'N';
	            } else {
	                $ptje_apto = ($ptje_apto == null || trim($ptje_apto) == "" || $ptje_apto < 0) ? null : $ptje_apto;
	                if($ptje_apto == null && $checkParticipo == true) {
	                    throw new Exception("Escribir un puntaje en la fila: ".$alumno['fila']);
	                }
	            }
	            if($idSimu == null) {
	                $arrayDatos = array("__id_universidad" => $idUniv,
            	                        "year_academico"   => _getYear(),
            	                        "id_alumno"        => $idAlumno,
            	                        "id_aula"          => $idAula,
            	                        "id_grado"         => $idGrado,
            	                        "id_sede"          => $idSede,
            	                        $columna           => $ptje_apto,
            	                        "fec_simulacro"    => date('d-m-Y'),
	                                    "nro_simulacro"    => $nroSimu,
            	                        "id_simulacro"     => $idSimu);
	            } else if($idSimu != null && $checkParticipo == true) {
	                $arrayDatos = array("checkParticipo"   => $checkParticipo,
            	                        $columna           => $ptje_apto,
            	                        "id_simulacro"     => $idSimu);
	            } else if($idSimu != null && $checkParticipo == false) {
	                $arrayDatos = array("checkParticipo"   => $checkParticipo,
	                                    "id_simulacro"     => $idSimu);
	            }	                
	            array_push($arrayGeneral, $arrayDatos);        
	        }
	        if(count($arrayGeneral) > 0) {
	           $data = $this->m_simulacro->insertUpdateSimulacro($arrayGeneral);
	        }
	        if($data['error'] == EXIT_SUCCESS) {
	            $apto = ($idUniv == ID_PUCP) ? true : false;
	            $data['tablaAlumnosAula'] = $this->buildTablaSimulacroHTML($idUniv, $idAula, $apto, $nroSimu);
	            $data['comboNroSimu']     = __buildComboNrosSimulacro($idSede, $idGrado, $idUniv);
	            $data['id_nro_simu']      = _simple_encrypt($nroSimu);
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
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}