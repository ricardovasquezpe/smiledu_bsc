<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_configuracion extends CI_Controller {

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
		$this->load->model('mf_alumno/m_alumno');
		$this->load->model('mf_aula/m_aula');
		$this->load->model('mf_matricula/m_matricula');
		$this->load->model('m_traslado');
		_validate_uso_controladorModulos(ID_SISTEMA_MATRICULA, ID_PERMISO_CONFIGURACION_MATRICULA, MATRICULA_ROL_SESS);
		$this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(MATRICULA_ROL_SESS);
	}
	
	public function index(){
		$data['titleHeader'] =  'Configuración';
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_MATRICULA, MATRICULA_FOLDER);
	    
	    $data['ruta_logo']        = MENU_LOGO_MATRICULA;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_MATRICULA;
	    $data['nombre_logo']      = NAME_MODULO_MATRICULA;
	    $fechasAll = $this->m_matricula->getFechasReferencia(array('M','T','R'));
	    $fechas    = null;
	    $fechasMat = null;
	    $fechasRat = null;
	    if($fechasAll != null){
    	    foreach ($fechasAll as $fec){
    	        switch ($fec->tipo) {
    	            case CONFIG_MATRICULA:           $fechasMat = (array)$fec;        break;
    	            case CONFIG_RATIFICACION:        $fechasRat = (array)$fec;        break;
    	            case CONFIG_TRASLADOS:           $fechas    = (array)$fec;        break;
    	        }
    	    }
	    }
	    
	    if($fechas != null){
	        /*$date1 = _fecha_tabla($fechas['fec_inicio'], "d/m");
	        $date3 = DateTime::createFromFormat("d/m", date("d/m"));
	        $data['disabled'] = null;
	        if ($date3 > $date1) {
	            $data['disabled'] = 'disabled';
	        }*/
	        $data['fechaInicio'] = _fecha_tabla($fechas['fec_inicio'], "d/m");
	        $data['fechaFin']    = _fecha_tabla($fechas['fec_fin'], "d/m");;
	        
	        $dataUser = array("id_config_ses" => $fechas['id_config']);
	        $this->session->set_userdata($dataUser);
	    }else{
	        $dataUser = array("id_config_ses" => null);
	        $this->session->set_userdata($dataUser);
	    }
	    if($fechasMat != null){
	        $data['fechaInicioMatricula'] = _fecha_tabla($fechasMat['fec_inicio'], "d/m");
	        $dataUser = array("id_config_matricula_ses" => $fechasMat['id_config']);
	        $this->session->set_userdata($dataUser);
	    }else{
	        $dataUser = array("id_config_matricula_ses" => null);
	        $this->session->set_userdata($dataUser);
	    }
	    if($fechasRat != null){
	        $data['fechaInicioRatificacion'] = _fecha_tabla($fechasRat['fec_inicio'], "d/m");
	        $dataUser = array("id_config_ratificacion_ses" => $fechasRat['id_config']);
	        $this->session->set_userdata($dataUser);
	    }else{
	        $dataUser = array("id_config_ratificacion_ses" => null);
	        $this->session->set_userdata($dataUser);
	    }
	    
	    $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_MATRICULA, $this->_idUserSess);
	    $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $menu = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;

		$this->load->view('v_configuracion',$data);
	}
	
	function guardarFechas(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $fechaInicio = _post('fechaInicio');
	        $fechaFin    = _post('fechaFin');
	        if(strlen($fechaInicio) != 5 || strlen($fechaFin) != 5){
	            throw new Exception("Ingrese fechas validas");
	        }
	        $date1 = DateTime::createFromFormat("d/m", $fechaInicio);
	        $date2 = DateTime::createFromFormat("d/m", $fechaFin);
	        if ($date1 >= $date2) {
	          throw new Exception('La fecha de inicio debe ser menor a la fin');
	        }
	        if($fechaInicio == null || $fechaFin == null){
	            throw new Exception("Ingrese las 2 fechas");
	        }
	        $sesConfig = _getSesion("id_config_ses");
	        if($sesConfig != null){ //UPDATE
	            $date3 = DateTime::createFromFormat("d/m", date("d/m"));
	            /*$dataUpdate = null;
	            if ($date3 > $date1) {
	                $dataUpdate = array('fec_fin'    => $fechaFin."/".date("Y"));
	            }else{*/
	                $dataUpdate = array('fec_inicio' => $fechaInicio."/".date("Y"),
	                                    'fec_fin'    => $fechaFin."/".date("Y"));
	            /*}*/
	            $data = $this->m_matricula->updateFechasReferencia($dataUpdate, $sesConfig);
	        }else{//INSERT
	            $dataInsert = array('fec_inicio' => $fechaInicio."/".date("Y"),
	                                'fec_fin'    => $fechaFin."/".date("Y"),
	                                'tipo'       => 'T');
	            $data = $this->m_matricula->insertFechasReferencia($dataInsert);
	            $dataUser = array("id_config_ses" => $data['id']);
	            $this->session->set_userdata($dataUser);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function guardarFechaMatricula(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $fechaInicio = _post('fechaInicio');
	        if(strlen($fechaInicio) != 5){
	            throw new Exception("Ingrese fechas validas");
	        }
	        $date1 = DateTime::createFromFormat("d/m", $fechaInicio);

	        $sesConfig = _getSesion("id_config_matricula_ses");
	        if($sesConfig != null){ //UPDATE
	            $dataUpdate = array('fec_inicio' => $fechaInicio."/".date("Y"),);
	            $data = $this->m_matricula->updateFechasReferencia($dataUpdate, $sesConfig);
	        }else{//INSERT
	            $dataInsert = array('fec_inicio' => $fechaInicio."/".date("Y"),
	                                'tipo'       => 'M');
	            $data = $this->m_matricula->insertFechasReferencia($dataInsert);
	            $dataUser = array("id_config_matricula_ses" => $data['id']);
	            $this->session->set_userdata($dataUser);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function guardarFechaRatificacion(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $fechaInicio = _post('fechaInicio');
	        if(strlen($fechaInicio) != 5){
	            throw new Exception("Ingrese fechas validas");
	        }
	        $date1 = DateTime::createFromFormat("d/m", $fechaInicio);

	        $sesConfig = _getSesion("id_config_ratificacion_ses");
	        if($sesConfig != null){ //UPDATE
	            $dataUpdate = array('fec_inicio' => $fechaInicio."/".date("Y"),);
	            $data = $this->m_matricula->updateFechasReferencia($dataUpdate, $sesConfig);
	        }else{//INSERT
	            $dataInsert = array('fec_inicio' => $fechaInicio."/".date("Y"),
	                                'tipo'       => 'R');
	            $data = $this->m_matricula->insertFechasReferencia($dataInsert);
	            $dataUser = array("id_config_ratificacion_ses" => $data['id']);
	            $this->session->set_userdata($dataUser);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
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