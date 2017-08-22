<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_graficos extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_graficos/m_g_encuesta');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('m_utils');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, null, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
    }
   
	public function index() {
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SENC, SENC_FOLDER);
	    ////Modal Popup Iconos///
	    $data['titleHeader'] = 'Gráficos';
	    $data['barraSec'] = '  <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                   <a href="#grafico1" class="mdl-layout__tab is-active" onclick="tabAction(1)">Preguntas por Encuesta</a>
                                   <a href="#grafico2" class="mdl-layout__tab" onclick="tabAction(2)">Satisfacci&oacute;n</a>
                                   <a href="#grafico3" class="mdl-layout__tab" onclick="tabAction(3)">Propuesta de Mejora</a>
                                   <a href="#grafico4" class="mdl-layout__tab" onclick="tabAction(4)">Reporte por Encuesta</a>
                                   <a href="#grafico5" class="mdl-layout__tab" onclick="tabAction(5)">Ranking de preguntas</a>
	                               <a href="#grafico6" class="mdl-layout__tab" onclick="tabAction(6)">Tutor&iacute;a</a>
                               </div>';
	    $data['ruta_logo']        = MENU_LOGO_SENC;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SENC;
	    $data['nombre_logo']      = NAME_MODULO_SENC;
	    //MENU
	    $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_SENC, $this->_idUserSess);
	    $data['apps']               = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $data['menu']             = $this->load->view('v_menu', $data, true);
	    //NECESARIO
	    if(_validate_metodo_rol(_getSesion(SENC_ROL_SESS))){
	        $data['tipo_encuesta'] = __buildComboTipoEncuesta();
	    }else{
	        $tipo_encuestas = array(TIPO_ENCUESTA_LIBRE);
	        $data['tipo_encuesta'] = __getOptionTipoEncuestaByIdSimpleDecrypt($tipo_encuestas,1);
	    }
	    $data['tipo_encuestados'] = __buildComboTipoEncuestado();
	    ///////////
	    $this->session->set_userdata(array('tab_active_config' => null));
	    $this->load->view('vf_grafico/v_grafico',$data);
	}
	
	function logout() {
	    $this->session->set_userdata(array("logout" => true));
	    unset($_COOKIE['smiledu']);
	    $cookie_name2 = "smiledu";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	    Redirect(RUTA_SMILEDU, true);
	}
	
	function cambioRol() {
	    $idRolEnc = $this->input->post('id_rol');
	    $idRol = $this->lib_utils->simple_decrypt($idRolEnc,CLAVE_ENCRYPT);
	    $nombreRol = $this->m_utils->getById("senc.rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
	    $dataUser = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $idRol     = $this->session->userdata('nombre_rol');
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = $this->session->userdata('id_persona');
	    $idRol     = $this->session->userdata('id_rol');
	    $roles  = $this->m_usuario->getRolesByUsuario($idPersona,$idRol);
	    $return = null;
	    foreach ($roles as $var){
	        $check = null;
	        $class = null;
	        if($var->check == 1){
	            $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
	            $class = 'active';
	        }
	        $idRol = $this->lib_utils->simple_encrypt($var->nid_rol,CLAVE_ENCRYPT);
	        $return  .= "<li class='".$class."'>";
	        $return .= '<a href="javascript:void(0)" onclick="cambioRol(\''.$idRol.'\')"><span class="title">'.$var->desc_rol.$check.'</span></a>';
	        $return .= "</li>";
	    }
	    $dataUser = array("roles_menu" => $return);
	    $this->session->set_userdata($dataUser);
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
    
	function enviarFeedBack(){
	    $nombre = $this->session->userdata('nombre_completo');
	    $mensaje = $this->input->post('feedbackMsj');
	    $url = $this->input->post('url');
	    __enviarFeedBack($mensaje,$url,$nombre);
	}
}