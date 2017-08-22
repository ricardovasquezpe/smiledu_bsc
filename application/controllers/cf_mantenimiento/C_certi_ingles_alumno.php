<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_certi_ingles_alumno extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_mantenimiento/m_certi_ingles_alumno');
        $this->load->model('m_utils');
        $this->load->library('table');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_CERTI_INGLES_ALUMN);
        $this->_idUserSess = _getSesion('nid_persona');
    }
    

	public function index(){
  	    $data['titleHeader']      = 'Certificado Ingl&eacute;s Alumno';
  	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
  	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
  	    $data['rutaSalto']        = 'SI';
    	$data['comboSedes'] =  __buildComboSedes();
    	$data['tabAlumnos'] =  $this->buildTablaAlumnosHTML(array());
    	$data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);
    	
    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
    	
    	//MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
    	
        $this->load->view('vf_mantenimiento/v_certi_ingles_alumno', $data);
	}	
	
	function buildTablaAlumnosHTML($listaAlumnos) {
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_alumnos">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
		$head_0 = array('data' => '#');
		$head_1 = array('data' => 'Alumno');			
		$head_2 = array('data' => '&#191;Realiz&oacute; examen?', 'class' => 'text-center');
		$head_3 = array('data' => '&#191;Aprob&oacute; examen?', 'class' => 'text-center');	
		$val = 0;
		$this->table->set_heading($head_0, $head_1, $head_2, $head_3);
		foreach($listaAlumnos as $row) {
			if($row->nid_alumno_certificacion != null) {
				$idCryptAlumno = _encodeCI($row->nid_alumno_certificacion);
			}else {
				$idCryptAlumno = _encodeCI($row->__id_persona);
			}			
			$val++;
			$row_col0  = array('data' => $val);
			$row_col1  = array('data' => $row->nombrecompleto);
			$realizo   = ($row->estado == null) ?  null 	: 'checked';						
			$aprobo	   = ($row->estado == 'A')    ? 'checked' : null;
			$bd		   = $row->estado;
			if($row->estado == null){
				$bd = 'N';
			}
			
			$row_col2  = array('data' => '<label for="check'.$val.'1" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
											    <input type="checkbox" class="mdl-checkbox__input" id="check'.$val.'1"'.$realizo.' onclick="cambioCheckPostulo(this);" attr-idalumno="'.$idCryptAlumno.'" attr-bd="'.$bd.'" >
											    <span class="mdl-checkbox__label"></span>
										    </label>', 'class' => 'text-center');
			
			$row_col3  = array('data' => '<label for="check'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
											   <input type="checkbox" class="mdl-checkbox__input" '.$aprobo.' id="check'.$val.'" onclick="cambioCheckAprobo(this);" attr-idalumno="'.$idCryptAlumno.'" attr-cambio="false" attr-bd="'.$bd.'">
											   <span class="mdl-checkbox__label"></span>
										   </label>', 'class' => 'text-center');		
			$this->table->add_row($row_col0, $row_col1, $row_col2, $row_col3);
		}
		$tabla = $this->table->generate();
		return $tabla;
	}
	
	function getGradosBySede(){		
		$result  = '<select id="selectGrado" name="selectGrados" data-live-search="true" title="Grado" class="form-control pickerButn">
				    <option value="">Selec. Grado</option>';		
		$result .= __buildComboGradosBySedeAll(_decodeCI(_post('val')));
		$result .= '</select>';
		echo $result;
	}
	
	function getAulasBySedeGrado(){
		$result  = '<select id="selectAula" name="selectAulas" data-live-search="true" title="Aula" class="form-control pickerButn">
				    <option value="">Selec. Aula</option>';
		$result .= __buildComboAulas(_decodeCI(_post('valGra')),_decodeCI(_post('valSed')));
		$result .= '</select>';
		echo $result;
	}
	
	function getTableAlumnos(){
		$data['tbAlumnos']	=	$this->buildTablaAlumnosHTML($this->m_certi_ingles_alumno->getAlumnosCertificados(_decodeCI(_post('val'))));
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function saveAlumnosCertificados(){
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
			$myPostData = json_decode(_post('alumnos'), TRUE);
			$idAula     = _decodeCI(_post('idAula'));
			$idSede     = _decodeCI(_post('idSede'));
			$idGrado    = _decodeCI(_post('idGrado'));
			if($idAula == null || $idSede == null || $idGrado == null) {
			    throw new Exception('Seleccione las opciones');
			}
			$idNivel = $this->m_utils->getById('grado', 'id_nivel', 'nid_grado', $idGrado);
			if($idNivel == null) {
			    throw new Exception(ANP);
			}
			$arrayUpdate = array();
			$arrayDelete = array();
			$arrayInsert = array();
			foreach($myPostData['alumno'] as $key => $alumno) {
				$idAlumno = _decodeCI($alumno['idAlumno']);
				$estado	  = $alumno['estado'];
				$bd		  = $alumno['bd'];
				if($idAlumno != null && ($estado == APROBO_EXAMEN_CERTIFICADO || $estado == POSTULO_EXAMEN_CERTIFICADO || $estado == NO_DIO_EXAMEN_CERTIFICADO)){//Para que no tome en cuenta si es que algun ID viene mal
					if($bd != NO_DIO_EXAMEN_CERTIFICADO){//UPDATE O DELETE
						if($estado == NO_DIO_EXAMEN_CERTIFICADO){//DELETE
							array_push($arrayDelete, $idAlumno);
						}else if($estado != NO_DIO_EXAMEN_CERTIFICADO){//UPDATE
							$arrayDatos = array("estado"                   => $estado,
												"nid_alumno_certificacion" => $idAlumno);
							array_push($arrayUpdate, $arrayDatos);
						}
					}else if($bd == 'N'){//INSERT
						$arrayDatos = array("__id_alumno" => $idAlumno,
											"estado"      => $estado,
						                    "id_aula"     => $idAula,
						                    "id_sede"     => $idSede,
						                    "id_grado"    => $idGrado,
						                    "id_nivel"    => $idNivel,
											"year" 		  => _getYear());
						array_push($arrayInsert, $arrayDatos);
					}
				}
			}
			$data = $this->m_certi_ingles_alumno->updateDataCertiInglesAlumnos($arrayInsert,$arrayDelete,$arrayUpdate);
			if($data['error'] == EXIT_SUCCESS){
			   $data['tablaAlumnos'] = $this->buildTablaAlumnosHTML($this->m_certi_ingles_alumno->getAlumnosCertificados($idAula));
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