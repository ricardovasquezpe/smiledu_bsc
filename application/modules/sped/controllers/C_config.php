<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_config extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
    function __construct(){
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('m_config');
        $this->load->model('m_utils');
        _validate_uso_controladorModulos(ID_SISTEMA_SPED, ID_PERMISO_GONFIG_SPED, SPED_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SPED_ROL_SESS);
    }
    
    public function index() {
        $data = $this->m_config->getValoresInit();
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SPED, SPED_FOLDER);
        ////Modal Popup Iconos///
        $rolSistemas   = $this->m_utils->getSistemasByRol(ID_SISTEMA_SPED, $this->_idUserSess);
        $data['apps']    = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        //MENU
        $data['main'] = true;
        $data['titleHeader'] = 'Configuraci&oacute;n';
	    $data['ruta_logo'] = MENU_LOGO_SPED;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SPED;
	    $data['nombre_logo'] = NAME_MODULO_SPED;
        $menu         = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        ////Modal Popup Iconos///
        $this->load->view('v_config', $data);
    }
    
    function cambioRol() {
        $idRolEnc = $this->input->post('id_rol');
        $idRol = _simple_decrypt($idRolEnc);
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'schoowl');
        $dataUser = array("id_rol"     => $idRol,
            "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $idRol     = $this->session->userdata('nombre_rol');
        $result['url'] = base_url()."c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }
    
    function grabarConfig_CTRL() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $val1 = $this->input->post('val1');
            $val2 = $this->input->post('val2');
            $val3 = $this->input->post('val3');
            $val4 = $this->input->post('val4');
            if($val1 == null || $val2 == null || $val3 == null || $val4 == null) {
                throw new Exception(ANP);
            }
            if(!ctype_digit((string) $val1) || !ctype_digit((string) $val2) || 
               !ctype_digit((string) $val3) || !ctype_digit((string) $val4) ) {
                throw new Exception(ANP);
            }
            if(($val1 > $val2) || ($val3 > $val4)) {
                throw new Exception(ANP);
            }
            $data = $this->m_config->guardarConfig($val1, $val2, $val3, $val4);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
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
    
    function logOut() {
        $this->session->sess_destroy();
        unset($_COOKIE['schoowl']);
        $cookie_name2 = "schoowl";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        redirect(RUTA_SMILEDU, 'refresh');
    }
}