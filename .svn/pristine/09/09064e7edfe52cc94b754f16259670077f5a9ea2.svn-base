<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_calendario extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('mf_mantenimiento/m_calendario');
        _validate_usuario_controlador(ID_PERMISO_CALENDARIO);
        $this->_idUserSess = _getSesion('nid_persona');
    }

	public function index() {       
  	    $data['titleHeader']      = 'Calendario';
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
    	
    	$data['calendarData'] = $this->populateFullCalendar();
    	
        $this->load->view('vf_mantenimiento/v_calendario', $data);
	}
	
	function populateFullCalendar() {
	    $data = $this->m_calendario->getDiasNoLaborables();
	    $idx = 0;
	    foreach ($data as $d) {
	        $data[$idx]['id'] = _encodeCI($data[$idx]['id']);
	        $idx++;
	    }
	    return $data;
	}
	
	function agregarNuevoDiaNoLaborable() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $actualYear    = _getYear();
	    try {
	        $fecha = _post('fecha');
	        $descr = utf8_decode(_post('descripcion'));
	        $yearCapacitacion = date('Y',strtotime($fecha));
	        if($actualYear != $yearCapacitacion){
	            throw new Exception('Solo pueden ser del año actual');
	        }
	        $data  = $this->m_calendario->crearDiaNoLaborable($fecha, $descr);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function checkDiaNoLaborable() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $fecha = _post('fecha');
	        if($this->m_calendario->esDiaNoLaborable($fecha) == 0) {
	            $data['error'] = EXIT_SUCCESS;
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function editarDiaNoLaborable() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $fecha           = _decodeCI(_post('fecha'));
	        $borrarLaborable = _post('borrarLaborable');
	        $descripcion     = _post('descripcion');
	        $data = $this->m_calendario->editarBorrarDiaNoLaborable($fecha, $borrarLaborable, $descripcion);
	        $data['calendarioData'] = json_encode($this->populateFullCalendar(), JSON_NUMERIC_CHECK);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = utf8_decode(_post('feedbackMsj'));
        $url = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
    
    function logOut(){
        $logedUser = _getSesion('usuario');
        $this->session->sess_destroy();
        redirect('','refresh');
    }
}