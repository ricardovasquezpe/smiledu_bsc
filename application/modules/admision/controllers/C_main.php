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
        $this->load->model('mf_evento/m_evento');
        $this->load->library('table');
    
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(ADMISION_ROL_SESS);
        
        
        if(!isset($_COOKIE[$this->config->item('sess_cookie_name')])) {
            $this->session->sess_destroy();
            Redirect(RUTA_SMILEDU, 'refresh');
        }
        if($this->_idUserSess == null || $this->_idRol == null) {
            $this->session->sess_destroy();
            Redirect(RUTA_SMILEDU, 'refresh');
        }
    }
   
	public function index() {
        $data = _searchInputHTML('Busca tus eventos',"onchange='buscarGeneral()'");
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_ADMISION, ADMISION_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_ADMISION, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['main'] = true;
	    $data['ruta_logo'] = MENU_LOGO_ADMISION;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_ADMISION;
	    $data['nombre_logo'] = NAME_MODULO_ADMISION;
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
        $this->load->view('v_main', $data);
	}
    function logOut() {
        $this->session->sess_destroy();
        unset($_COOKIE['schoowl']);
        $cookie_name2 = "schoowl";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        redirect(RUTA_SMILEDU, 'refresh');
    }
	
	function cambioRol() {
	    $idRol = _simple_decrypt(_post('id_rol'));
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, null);
	    $dataUser = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = _getSesion('id_persona');
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

    function setIdSistemaInSession(){
	    $idSistema = _decodeCI(_post('id_sis'));
	    $idRol     = _decodeCI(_post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }	    
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
    
	function enviarFeedBack(){
	    $nombre = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}	
	function mostrarRolesSistema(){
	    $idSistema = _decodeCI(_post('sistema'));
	    $roles = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'),$idSistema);
	    $result = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	function buscarGeneral() {
	    $texto = _post("texto");
        /*$partes = explode(" ", $texto);
	    if($partes > 2){
               
	    }
        echo $partes['texto'];
        */     
	    $eventos = null;	    
	    //$this->m_evento->buscarEvento($texto);
	    $tarjetas = null;
	    $date = null;   
	    if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING){ 
	           $eventos  = $this->m_evento->buscarEventoMain($texto);
	    }else if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR){
	           $eventos  = $this->m_evento->buscarEventoBySedeMain($this->_idUserSess, _getSesion("id_sede_trabajo"), $texto);
	    }else{
	           $eventos  = $this->m_evento->getEventosByPersonaFiltro($this->_idUserSess, $texto);
	    }
	    foreach ($eventos as $eve){
	        $idEventoEnc = _simple_encrypt($eve->id_evento);
	        //$esTour = () ?  : "";
	        $esTour = null;
	        if( $eve->tipo_evento == TIPO_EVENTO_TOUR){
	           $esTour .= '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="verEventosEnlazados(\''.$idEventoEnc.'\')">
                            <i class="glyphicon glyphicon-menu-down"></i> 
                           </button>';
	        }
	        $past = ($eve->fecha_pasada==1) ? "mdl-tour-past" : "";
	        $tarjetas .= '<div class="mdl-card mdl-tour '.$past.'">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text" >'.$eve->desc_evento.'</h2>
                                </div>
                                    <div class="mdl-card__supporting-text">
                                        <h2 class="mdl-card__title-text">'.date('d/m/Y', strtotime($eve->fecha_realizar)).'</h2>
                                        <h2 class="mdl-card__title-text">'.date('H:i', strtotime($eve->hora_inicio)).' - '.date('H:i', strtotime($eve->hora_fin)).'</h2>
                                            <div class="list-images">
                                            <img onclick="verColaboradoresEvento(\''.$idEventoEnc.'\')" alt="" src="<?php echo base_url()?>public/general/img/profile-default.png">
                                            <img onclick="verColaboradoresEvento(\''.$idEventoEnc.'\')" alt="" src="<?php echo base_url()?>public/general/img/profile-default.png">
                                            <img onclick="verColaboradoresEvento(\''.$idEventoEnc.'\')" alt="" src="<?php echo base_url()?>public/general/img/profile-default.png">
                                            <div onclick="verColaboradoresEvento(\''.$idEventoEnc.'\')" class="mdi mdi-people"></div>
                                    </div>
                                    <small class="link-dotted" onclick="verColaboradoresEvento(\''.$idEventoEnc.'\')">'.$eve->cant_colab.' Colaboradores</small><br>
                                    <small class="link-dotted" onclick="verInvitadosEvento(\''.$idEventoEnc.'\')">'.$eve->cant_invitados.' Invitados</small>    
                                </div>
                                <div class="mdl-card__actions" onclick="irDetalleEvento(\''.$idEventoEnc.'\')" >
                                    <a class="mdl-button mdl-js-button mdl-js-ripple-effect">
                                        Ir al evento
                                        <i class="mdi mdi-event"></i>
                                    </a>
                                </div>
                                <div class="mdl-card__menu">
                                 '.$esTour.'
                                </div>
                         </div>';
	    }
	    $data['eventos'] = $tarjetas;
	    echo json_encode(array_map('utf8_encode', $data));       
	}
	
	function detalleEvento(){
	    $idEvento = _simpleDecryptInt(_post('idevento'));
	    $dataUser = array("id_evento_detalle"   => $idEvento,
	                      "accionDetalleEvento" => 3,
	                      "tipo_evento_detalle" => $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", $idEvento));
	    $this->session->set_userdata($dataUser);
	}  
	function eventosEnlazados(){
	    $idEvento = _simpleDecryptInt(_post('idevento'));
	    $EventosEnlazados = $this->m_evento->getInfoDetalleEvento($idEvento);
	    $data['eventos'] = _createTableEventosEnlazados($EventosEnlazados);
	    $data['count']   = count($EventosEnlazados);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	function colaboradoresEvento(){
	    $idEvento = _post('idevento') != null ? _simpleDecryptInt(_post('idevento')) : null;
	    if($idEvento == null ){
	        throw new Exception(ANP);
	    }
	    $Colaboradores = $this->m_evento->getdetalleColaborador($idEvento);
	    $data['evento'] = _createTableColaboradores($Colaboradores);
	    $data['count']   = count($Colaboradores);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	function InvitadosEvento(){
	    $idEvento = _post('idevento') != null ? _simpleDecryptInt(_post('idevento')) : null;
	    if($idEvento == null ){
	        throw new Exception(ANP);
	    }
	    $Invitados = $this->m_evento->getdetalleInvitado($idEvento);
	    $data['evento'] = _createTableInvitados($Invitados);
	    $data['count']   = count($Invitados);
	    echo json_encode(array_map('utf8_encode', $data));
	}
		
}