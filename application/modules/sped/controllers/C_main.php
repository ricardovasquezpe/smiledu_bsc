<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_main extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->library('table');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SPED_ROL_SESS);
        if(!isset($_COOKIE[__getCookieName()])) {
            Redirect(RUTA_SMILEDU, 'refresh');
        }
        if($this->_idUserSess == null || $this->_idRol == null) {
            Redirect(RUTA_SMILEDU, 'refresh');
        }
    }

	public function index() {
	    $data = _searchInputHTML('Buscar docentes');
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SPED, SPED_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_SPED, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['main'] = true;
	    $data['ruta_logo']        = MENU_LOGO_SPED;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SPED;
	    $data['nombre_logo']      = NAME_MODULO_SPED;
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $this->load->view('v_main', $data);
	}
	
	/*function logout() {
	    $this->session->set_userdata(array("logout" => true));
	    unset($_COOKIE[$this->config->item('sess_cookie_name')]);
	    $cookie_name2 = $this->config->item('sess_cookie_name');
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        $this->session->sess_destroy();
	    Redirect(RUTA_SMILEDU, true);
	}*/
	
	function cambioRol() {
	    $idRolEnc  = _post('id_rol');
	    $idRol     = _simple_decrypt($idRolEnc);
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'schoowl');
	    $dataUser  = array("id_rol"     => $idRol,
	                       "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $idRol     = $this->session->userdata('nombre_rol');
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}

    /*function setIdSistemaInSession() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSistema = _decodeCI(_post('id_sis'));
            $idRol     = _decodeCI(_post('rol'));
            if($idSistema == null || $idRol == null) {
                throw new Exception(ANP);
            }
            $data = $this->lib_utils->setIdSistemaInSession($idSistema, $idRol);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
	    echo json_encode(array_map('utf8_encode', $data));
	}*/
    
	function enviarFeedBack(){
	    $nombre = $this->session->userdata('nombre_completo');
	    $mensaje = $this->input->post('feedbackMsj');
	    $url = $this->input->post('url');
	    __enviarFeedBack($mensaje,$url,$nombre);
	}
}