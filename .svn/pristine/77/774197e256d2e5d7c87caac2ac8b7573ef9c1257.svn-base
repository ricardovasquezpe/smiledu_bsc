<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_configuracion extends CI_Controller {

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
        $this->load->model('m_utils');
        $this->load->model('m_becas');
        $this->load->model('m_pensiones');
        $this->load->model('m_cronograma');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_CONFIGURACION, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
   
	public function index() {
	    $idRol     = $this->_idRol;
	    $tabSess   = _getSesion('tab_active_config');
	    $tabSess   = (($tabSess == null && ($idRol == ID_ROL_RESP_COBRANZAS || $idRol == ID_ROL_ADMINISTRADOR || $idRol == ID_ROL_PROMOTOR) ) || $tabSess == 'tab-1' && ($idRol == ID_ROL_RESP_COBRANZAS || $idRol == ID_ROL_ADMINISTRADOR || $idRol == ID_ROL_PROMOTOR) ) ? 'tab-1' : ((($tabSess == null && $idRol == ID_ROL_SECRETARIA) ? 'tab-2' : $tabSess));
	    $data['tabActive']   = $tabSess;
	    $data['barraSec']    = $this->buildTabsByRol($tabSess,$idRol);
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
	    ////Modal Popup Iconos///
	    $data['titleHeader']      = 'Configuraci&oacute;n';
	    $data['ruta_logo']        = MENU_LOGO_PAGOS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
	    $data['nombre_logo']      = NAME_MODULO_PAGOS;
	    //MENU
	    $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
	    $data['apps']             = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $data['menu']             = $this->load->view('v_menu', $data, true);
	    //NECESARIO
	    $data['optTipoBeca']      = $this->createComboByBecas();
	    $data['optYear']          = __buildComboYearFromNowByCompromisos();
	    $data['optSede']          = __buildComboSedes();
	    $data['optCronograma']    = $this->buildComboCronograma();
	    $arrayBecas               = $this->m_becas->getBecas();
	    $data['tableBecas']       = __buildTablaBecasHTML($arrayBecas);
	    $data['optTiposCrono']    = __buildComboTiposCronograma();//$this->buildComboTiposCronograma();
	    $arrayPromociones         = $this->m_becas->getPromociones();
	    $data['tablePromociones'] = __buildTablaPromocionesHTML($arrayPromociones);
// 	    $sedes                    = $this->m_pensiones->getAllSedes();
// 	    $data['tableSede']        = __buildTablaSedesHTML($sedes, _getYear(),1);
	    $yearActual               = _getYear();
	    $data['flechasNav']       = __getFlechasByYear($yearActual,1);
	    $arraySedes               = $this->m_utils->getSedes();
	    $data['tableCronograma']  = __buildTablaCronogramaHTML($arraySedes);
	    $data['optConceptos']     = __buildComboConceptosByTipo(MOV_INGRESO);
	    $data['plantillaCronograma'] = __buildComboCronograma();
	    /////////// 
	    $data['flgShow']          = (count($arrayBecas) != 0 || count($arrayPromociones) != 0)   ?  'block' : 'none';
	    $data['flgShow1']         = ($data['flgShow'] == 'block') ?  'none'  : 'block';
	    $this->session->set_userdata(array('tab_active_config' => null));
	    $this->load->view('v_configuracion', $data);
	}
	
	function buildTabsByRol($activo,$rol) {
	    $tabs = null;
	    if($rol == ID_ROL_SECRETARIA ) {
	        $tabs = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
	                     <a href="#tab-1" class="mdl-layout__tab is-active">Pensiones</a>
                         <a href="#tab-2" class="mdl-layout__tab ">Cronograma</a>
                         <a href="#tab-3" class="mdl-layout__tab ">Descuentos</a>
	                     <a href="#tab-4" class="mdl-layout__tab">Compromisos extras</a>
                     </div>';
	    } else if($rol == ID_ROL_ADMINISTRADOR || $rol == ID_ROL_PROMOTOR) {
	        $tabs = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                         <a href="#tab-1" class="mdl-layout__tab is-active">Pensiones</a>
                         <a href="#tab-2" class="mdl-layout__tab ">Cronograma</a>
                         <a href="#tab-3" class="mdl-layout__tab ">Descuentos</a>
	                     <a href="#tab-4" class="mdl-layout__tab">Compromisos extras</a>
                     </div>';
	    } else{
	        $tabs = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                         <a href="#tab-1" class="mdl-layout__tab is-active">Pensiones</a>
                         <a href="#tab-2" class="mdl-layout__tab ">Cronograma</a>
                         <a href="#tab-3" class="mdl-layout__tab ">Descuentos</a>
	                     <a href="#tab-4" class="mdl-layout__tab">Compromisos extras</a>
                     </div>';
	    }
	    return $tabs;
	}
	
	function createComboByBecas() {
		$combo  = $this->m_becas->getComboBecas();
		$opcion = '';
		foreach ($combo as $row){
			$opcion  .= '<option value="'._simple_encrypt($row->id_condicion).'">'.$row->desc_condicion.'</option>';
		}
		return $opcion;
	}
	
	function buildComboCronograma() {
		$combo  = $this->m_cronograma->getComboCronograma();
		$opcion = '';
		foreach ($combo as $row){
			$opcion .= '<option value="'._simple_encrypt($row->id_cronograma).'">'.$row->desc_cronograma.'</option>';
		}
		return $opcion;
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
	    $idRol     = _simple_decrypt(_post('id_rol'));
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
	    $dataUser  = array("id_rol"     => $idRol,
	                       "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = _getSesion('id_persona');
	    $idRol     = _getSesion('id_rol');
	    $roles     = $this->m_usuario->getRolesByUsuario($idPersona,$idRol);
	    $return    = null;
	    foreach ($roles as $var){
	        $check = null;
	        $class = null;
	        if($var->check == 1){
	            $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
	            $class = 'active';
	        }
	        $idRol   = _simple_encrypt($var->nid_rol);
	        $return .= "<li class='".$class."'>";
	        $return .= '<a href="javascript:void(0)" onclick="cambioRol(\''.$idRol.'\')"><span class="title">'.$var->desc_rol.$check.'</span></a>';
	        $return .= "</li>";
	    }
	    $dataUser = array("roles_menu" => $return);
	    $this->session->set_userdata($dataUser);
	}

    function setIdSistemaInSession() {
	    $idSistema = _decodeCI(_post('id_sis'));
	    $idRol     = _decodeCI(_post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }	    
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
    
	function enviarFeedBack() {
	    $nombre  = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url     = _post('url');
	    __enviarFeedBack($mensaje,$url,$nombre);
	}
	
	function mostrarRolesSistema() {
	    $idSistema = _decodeCI(_post('sistema'));
	    $roles     = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'),$idSistema);
	    $result    = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;
	    echo json_encode(array_map('utf8_encode', $data));
	}    
	
	function createFab() {
	    $tab  = _post('tab');
	    $this->session->set_userdata(array('tab_active_config' => $tab));
	    $menu = null;
	    if($tab == 'tab-1'){
            $menu = '<li class=" mfb-only-btn mdl-only-btn__animation" id="pensiones_pago_fg">
                         <button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltroPensiones" data-mfb-label="Filtrar Pensiones">
                             <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                             <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                         </button>
                     </li>'; 
	    } else if($tab == 'tab-2') {
	        $menu = '<li class="mfb-only-btn" id="cronograma_pago_fg">
                         <button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltroAlumnoCompromiso" data-mfb-label="Generar Compromisos">
                             <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                             <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                         </button>
                     </li>';
	    }else if($tab == 'tab-3') {
	        $menu = '<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
	                      <li>
    			             <button class="mfb-component__button--main" id="main_button" >
    			             	<i class="mfb-component__main-icon--resting mdi mdi-add" ></i>
    			             </button>
    			             <a class="mfb-component__button--main" id="main_button" data-mfb-label="Asignar becas para estudiante" onclick="openModalAsignarBeca();">
    			             	<i class="mfb-component__main-icon--active mdi mdi-new_student" ></i>
    			             </a>
    				         <ul class="mfb-component__list">
    					         <li>
    						         <button class="mfb-component__button--child " id="main_save_multi"  onclick="openModalcrearBeca();" data-mfb-label="Agregar beca">
    						         	<i class="mdi mdi-mode_edit"></i>
    						         </button>
    					         </li>
    		                   </ul>
            		      </li>
	                 </ul>';
	    }
	    else if($tab == 'tab-4') {
// 	        throw new Exception('Esta opci&oacute;n est&aacute; habilitada en el siguiente paquete');
	        $menu = '<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
	                    <li>
			               <button class="mfb-component__button--main" id="main_button" >
			                   <i class="mfb-component__main-icon--resting mdi mdi-add" ></i>
			               </button>
			               <button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltroCompromiso" data-mfb-label="Asignar compromisos">
			             	   <i class="mfb-component__main-icon--active mdi mdi-assignment_turned_in" ></i>
			               </button>
				           <ul class="mfb-component__list">
					           <li>
						          <button class="mfb-component__button--child " id="main_save_multi" data-toggle="modal" data-target="#modalSaveCompromisos" onclick="loadCompromisosModal(\'modalSaveCompromisos\',\'conceptosCompromisos\')" data-mfb-label="Guardar Compromisos de aulas">
						         	 <i class="mfb-component__child-icon mdi mdi-save"></i>
						          </button>
					           </li>
					           <li>
						          <button class="mfb-component__button--child " id="main_save_multiDelete" data-toggle="modal" data-target="#modalFiltroCompromisoDelete" onclick="loadComboCompromisosGlobales();" data-mfb-label="Eliminar Compromisos extras">
						         	 <i class="mfb-component__child-icon mdi mdi-delete"></i>
						          </button>
			                 </li>
		                 </ul>
            		 </li>
	            </ul>';
	    }
	    $data['menu'] = $menu;
        echo json_encode(array_map('utf8_encode', $data));
	}
    
    function buildComboTiposCronograma() {
        $tipos = $this->m_cronograma->getAllTiposCronograma();
        $opt   = null;
        foreach($tipos as $tip){
            $idCrypt = _encodeCI($tip->id_tipo_cronograma);
            $opt    .= '<option value="'.$idCrypt.'">'.$tip->desc_tipo_cronograma.'</option>';
        }
        return $opt;
    }
}