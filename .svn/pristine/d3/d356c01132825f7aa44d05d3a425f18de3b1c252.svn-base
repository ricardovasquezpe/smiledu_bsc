<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_solicitud_traslado extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
	function __construct() {
		parent::__construct();
		$this->output->set_header(CHARSET_ISO_8859_1);
		$this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->load->library('table');
		$this->load->model('../mf_usuario/m_usuario');
		$this->load->model('../m_utils');
		$this->load->model('m_traslado');
		$this->load->model('mf_aula/m_aula');
		$this->load->model('mf_matricula/m_matricula');
		
		_validate_uso_controladorModulos(ID_SISTEMA_MATRICULA, ID_PERMISO_SOLICITUD_TRASLADO, MATRICULA_ROL_SESS);
		$this->_idUserSess = _getSesion('nid_persona');
		$this->_idRol      = _getSesion(MATRICULA_ROL_SESS);
	}
	
	public function index() {
	    $data['titleHeader'] =  'Traslados';
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_MATRICULA, MATRICULA_FOLDER);
	     
	    $data['ruta_logo']        = MENU_LOGO_MATRICULA;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_MATRICULA;
	    $data['nombre_logo']      = NAME_MODULO_MATRICULA;
	     
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_MATRICULA, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $menu = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $idSede = null;
	    if($this->_idRol != ID_ROL_ADMINISTRADOR){
	        $idSede = $this->m_utils->getById("rrhh.personal_detalle", "id_sede_control", "id_persona", $this->_idUserSess);
	    }
	    $resultado   = $this->m_traslado->getAllTrasladosByPersonaSede($this->_idUserSess, $idSede);
	    $data['tablaSolicitudes'] = _createTableTraslados($resultado);
	    
        $data['cmbTipoTraslado'] = __buildComboByGrupo(COMBO_TIPO_TRASLADO);
        $data['cmbSede']         = __buildComboSedes();
        $data['botonAceptar']    = _simple_encrypt(SOLICITUD_ACEPTADA);
        $data['botonRechazar']   = _simple_encrypt(SOLICITUD_RECHAZADA);
        //
		$this->load->view('v_solicitud_traslado',$data);
	}

	function cambiarEstadoTraslado(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idTraslado        = _simpleDecryptInt(_post("idtraslado"));
	        $estado            = _simple_decrypt(_post("estadotraslado"));
	        $motivoTraslado    = _post('motivoTraslado');
	        $idAula            = (strlen(_post("idaula")) != 0) ? _simpleDecryptInt(_post("idaula")) : null;
	        $logeoUsario       = $this->_idUserSess;
	        $nombreLogeoUsario = $this->session->userdata('nombre_persona_completo');
	        
	        if($idTraslado == null || $estado == null){
	            throw new Exception(ANP);
	        }
	        
	        if($estado == SOLICITUD_RECHAZADA && strlen($motivoTraslado) == 0){
	            throw new Exception("Ingrese un motivo de rechazo");
	        }
	        
	        if($estado == SOLICITUD_ACEPTADA && $idAula == null){
	            throw new Exception("Elija un aula donde matricular");
	        }
	        
	        $arrayUpdate = array("estado"                   => $estado,
	                             "id_usuario_confirmacion"  => $logeoUsario,
	                             "nombres_usuario_confirma" => $nombreLogeoUsario,
	                             "fecha_hora_confirmacion"  => _fecha_tabla(date('Y-m-d'), "d/m/Y"),
	                             "motivo_rechazo"           => utf8_decode(__only1whitespace($motivoTraslado)),
	                             "id_aula_destino"          => $idAula
	        );
	        $data = $this->m_traslado->updateTraslado($idTraslado, $arrayUpdate);
	        if($data['error'] == EXIT_SUCCESS && $estado == SOLICITUD_ACEPTADA){
	            $idAulmno     = $this->m_utils->getById("sima.traslado_alumno", "id_alumno", "id_traslado", $idTraslado);
	            $idAulaOrigen = $this->m_utils->getById("sima.traslado_alumno", "id_aula_origen", "id_traslado", $idTraslado);
	            $year         = $this->m_utils->getById("aula", "year", "nid_aula", $idAulaOrigen);
	            $arrayDelete = array("__id_persona" => $idAulmno,
	                                 "__id_aula"    => $idAulaOrigen);
	            $data = $this->m_matricula->eliminarAlumnoDeAula($arrayDelete);
	            if($data['error'] == EXIT_SUCCESS){
	                $arrayInsert = array("__id_persona"   => $idAulmno,
        	                             "__id_aula"      => $idAula,
        	                             "year_academico" => $year);
	                $data = $this->m_matricula->asignarAlumnoEnAula($arrayInsert);
	                if($data['error'] == EXIT_SUCCESS){
	                    $idSede = null;
	                    if($this->_idRol != ID_ROL_ADMINISTRADOR){
	                        $idSede = $this->m_utils->getById("rrhh.personal_detalle", "id_sede_control", "id_persona", $this->_idUserSess);
	                    }
	                    $resultado   = $this->m_traslado->getAllTrasladosByPersonaSede($this->_idUserSess, $idSede);
	                    $data['tablaSolicitudes'] = _createTableTraslados($resultado);
	                }
	            }
	        }else if($data['error'] == EXIT_SUCCESS && $estado == SOLICITUD_RECHAZADA){
	            $idSede = null;
	            if($this->_idRol != ID_ROL_ADMINISTRADOR){
                    $idSede = $this->m_utils->getById("rrhh.personal_detalle", "id_sede_control", "id_persona", $this->_idUserSess);
	            }
	            $resultado   = $this->m_traslado->getAllTrasladosByPersonaSede($this->_idUserSess, $idSede);
	            $data['tablaSolicitudes'] = _createTableTraslados($resultado);
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function aulasDestinoTraslado(){
	    $idTraslado = _simpleDecryptInt(_post("idtraslado"));
	    
	    $idSedeDestino = $this->m_utils->getById("sima.traslado_alumno", "id_sede_destino", "id_traslado", $idTraslado);
	    $estOrigen     = $this->m_traslado->getEstructuraTraslado($idTraslado);
	    
	    $data['comboAulas'] = $this->buildComboAulasByGradoYearCapacidad(/*, */$estOrigen['nid_nivel'], $idSedeDestino, $estOrigen['nid_grado'], $estOrigen['year'], $estOrigen['nid_aula']);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildComboAulasByGradoYearCapacidad($idNivel,$idSede, $idGrado, $year, $idaula = null){
	    $aulas = $this->m_matricula->getAulasByGradoYearCapcidad($idNivel, $idSede, $idGrado, $year, $idaula);
	    $opcion = '';
	    foreach ($aulas as $aul){
	        $opcion .= '<option value="'._simple_encrypt($aul->nid_aula).'">'.strtoupper($aul->desc_aula).' ('.$aul->count_est.'/'.$aul->capa_max.')</option>';
	    }
	    return $opcion;
	}

	function setIdSistemaInSession(){
	    $idSistema = $this->encrypt->decode($this->input->post('id_sis'));
	    $idRol     = $this->encrypt->decode($this->input->post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE[__getCookieName()]);
        $cookie_name2 = __getCookieName();
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
    
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        $html = '<p>'.$url.'</p>';
        $html .= '<p>'.$mensaje.'</p>';
        $html .= '<p>'.$nombre.'</p>';
        $arrayInsertCorreo = array('correos_destino'         => CORREO_BASE,
            'asunto'                  => utf8_encode("¡Sugerencias a Smiledu!"),
            'body'                    => $html,
            'estado_correo'           => CORREO_PENDIENTE,
            'sistema'                 => 'SMILEDU');
        $dataCorreo = $this->m_utils->insertarEnviarCorreo($arrayInsertCorreo);
    }
}