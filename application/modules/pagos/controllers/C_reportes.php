<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_reportes extends CI_Controller{
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
	public function __construct(){
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->model('m_reportes');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_REPORTES, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }

    public function index(){
        $data['titleHeader']      = 'Reportes';
        $data['ruta_logo']        = MENU_LOGO_PAGOS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
        $data['nombre_logo']      = NAME_MODULO_PAGOS;
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
        $data['barraSec'] = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                <a href="#tab-vencimiento" class="mdl-layout__tab is-active" >Pensiones Vencidas</a>
        	                    <a href="#tab-puntual"     class="mdl-layout__tab" >Pagos Puntuales</a>
                                <a href="#tab-pagados"     class="mdl-layout__tab" >Pensiones Pagadas</a>
                                <a href="#tab-verano"      class="mdl-layout__tab" >Verano</a>
                                <a href="#tab-auditoria"   class="mdl-layout__tab" >Auditoria Del Sistema</a>
        					</div>';
        $rolSistemas           = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
        $data['apps']            = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $menu                  = $this->load->view('v_menu', $data, true);
        $data['menu']          = $menu;
        $data['cmbBanco']      = __buildComboBancos();
        $data['optSede']       = __buildComboSedes();
        $data['optCronograma'] = __buildComboCronograma();
        $data['optTablas']     = __buildComboByGrupo(COMBO_AUDI_PAGOS);
        $data['optYear']       = __buildComboYearByCompromisos();
        $arraySerie = array('name' => 'John' , 'data' => array(4,5,6,7,8));
        $data['serie']  = json_encode($arraySerie); 
        $this->load->view('v_reportes', $data);
    }

    function logout() {
       $this->session->set_userdata(array("logout" => true));
       unset($_COOKIE[__getCookieName()]);
       $cookie_name2 = __getCookieName();
       $cookie_value2 = "";
       setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
       Redirect(RUTA_SMILEDU, true);
    }
	
	function cambioRol() {
	    $idRol = _simple_decrypt(_post('id_rol'));
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
	    $dataUser = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = $this->_idUserSess;
	    $idRol     = _getSesion('id_rol');
	    $roles  = $this->m_usuario->getRolesByUsuario($idPersona,$idRol);
	    $return = null;
	    foreach ($roles as $var){
	        $check = null;
	        $class = null;
	        if($var->check == 1){
	            $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
	            $class = 'active';
	        }
	        $idRol = _simple_encrypt($var->nid_rol);
	        $return  .= "<li class='".$class."'>";
	        $return .= '<a href="javascript:void(0)" onclick="cambioRol(\''.$idRol.'\')"><span class="title">'.$var->desc_rol.$check.'</span></a>';
	        $return .= "</li>";
	    }
	    $dataUser = array("roles_menu" => $return);
	    $this->session->set_userdata($dataUser);
	}

    /*function setIdSistemaInSession(){
	    $idSistema = _decodeCI(_post('id_sis'));
	    $idRol     = _decodeCI(_post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }	    
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);	    
	    echo json_encode(array_map('utf8_encode', $data));
	}*/
    
	function enviarFeedBack(){
	    $nombre = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    __enviarFeedBack($mensaje,$url,$nombre);
	}
	
	function mostrarRolesSistema(){
	    $idSistema = _decodeCI(_post('sistema'));
	    $roles = $this->m_usuario->getRolesOnlySistem($this->_idUserSess,$idSistema);
	    $result = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}

    function createFab(){
    	$tab = _post('tab');
    	$menu = null;
    	if($tab == 'tab1'){
//     	    throw new Exception('Esta opci&oacute;n est&aacute; habilitada en el siguiente paquete');
    		$menu = '<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
    					<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarPagados" data-mfb-label="Filtrar">
                            <i class="mfb-component__main-icon--resting mdi mdi-filter_list" style="transform: rotate(0deg); top: 11px;"></i>
    						<i class="mfb-component__main-icon--active mdi mdi-filter_list" style="top: 11px;"></i>
    					</button>
		            </li>';
    	} else if($tab == 'tab2'){
    		$this->session->set_userdata(array('arrayAlumnos' => null));
    		$menu = '<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
    					<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarVencidos" data-mfb-label="Filtrar">
                            <i class="mfb-component__main-icon--resting mdi mdi-filter_list" style="transform: rotate(0deg); top: 11px;"></i>
    						<i class="mfb-component__main-icon--active mdi mdi-filter_list" style="top: 11px;"></i>
    					</button>
		            </li>';
    	}else if($tab == 'tab3'){
    		$menu = '<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
    					<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarCuota" data-mfb-label="Filtrar">
                            <i class="mfb-component__main-icon--resting mdi mdi-filter_list" style="transform: rotate(0deg); top: 11px;"></i>
    						<i class="mfb-component__main-icon--active mdi mdi-filter_list" style="top: 11px;"></i>
    					</button>
		            </li>';
    	}else if($tab == 'tab4'){
//     	    throw new Exception('Esta opci&oacute;n est&aacute; habilitada en el siguiente paquete');
    		$menu = '<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
    					<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarCuota" data-mfb-label="Filtrar">
                            <i class="mfb-component__main-icon--resting mdi mdi-filter_list" style="transform: rotate(0deg); top: 11px;"></i>
    						<i class="mfb-component__main-icon--active mdi mdi-filter_list" style="top: 11px;"></i>
    					</button>
		            </li>';
    	}
    	$img = '<img src="'.RUTA_IMG.'smiledu_faces/filter_fab.png">
                    <p>Primero debemos filtrar para</p>
                    <p>visualizar los reportes</p>';
    	$data['img'] = $img;
    	$data['menu'] = $menu;
    	echo json_encode(array_map('utf8_encode', $data));
    }    
}