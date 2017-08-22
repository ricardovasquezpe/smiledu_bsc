<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_cursos extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('m_detalle_curso');
        $this->load->library('table');
        
        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_MIS_CURSOS, NOTAS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);
    }
   
	public function index() {
	    $data['titleHeader']      = 'Mis cursos';
	    $data['ruta_logo']        = MENU_LOGO_NOTAS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
	    $data['nombre_logo']      = NAME_MODULO_NOTAS;
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos(_getSesion(NOTAS_ROL_SESS), ID_SISTEMA_NOTAS, NOTAS_FOLDER);

	    $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
	    $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    
	    $data2 = _searchInputHTML('Busca tus cursos');
	    $data = array_merge($data, $data2);
	    
	    $menu = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    $data['card_cursos'] = $this->build_CardCursosDocente_HTML();
	    $this->load->view('v_cursos', $data);
	}
	
	function build_CardCursosDocente_HTML() {
	    $misCursos = $this->m_detalle_curso->getMisCursos_Docente(_getSesion('nid_persona'));
	    $htmlFinal = null;
	    $cont = 0;
	    foreach ($misCursos as $curs) {
	        $htmlFinal .= '    <div class="mdl-card mdl-classroom">
                                   <div class="mdl-card__title">
                                       <h2 class="mdl-card__title-text">'.$curs['desc_curso'].'</h2>
                                   </div>
                                   <div class="mdl-card__supporting-text">
                                       <div class="row-fluid p-0 m-0">
                                           <div class="col-xs-12 classroom-head m-b-20"><strong>Detalles de mi Aula</strong></div>                                            
                                           <div class="col-xs-5 classroom-item">Sede</div>
                                           <div class="col-xs-7 classroom-value">'.$curs['desc_sede'].'</div>
                                           <div class="col-xs-5 classroom-item">Nivel</div>
                                           <div class="col-xs-7 classroom-value">'.$curs['desc_nivel'].'</div>
                                           <div class="col-xs-5 classroom-item">Grado</div>
                                           <div class="col-xs-7 classroom-value">'.$curs['desc_grado'].'</div>
                                           <div class="col-xs-4 classroom-item">Aula</div>
                                           <div class="col-xs-8 classroom-value">'.$curs['desc_aula'].'</div>
                                           <div class="col-xs-9 classroom-item">A&ntilde;o</div>
                                           <div class="col-xs-3 classroom-value">'.$curs['year'].'</div>
                                           <div class="col-xs-7 classroom-item">Capacidad</div>
                                           <div class="col-xs-5 classroom-value classroom-link">'.$curs['cant_estu'].'/'.$curs['capa_max'].'</div>
                                           <div class="col-xs-7 classroom-item p-l-10">Varones</div>
                                           <div class="col-xs-5 classroom-value">'.$curs['cant_varones'].'</div>
                                           <div class="col-xs-7 classroom-item p-l-10">Mujeres</div>
                                           <div class="col-xs-5 classroom-value">'.$curs['cant_mujeres'].'</div>
                                       </div>
                                   </div>
                                   <div class="mdl-card__menu">
                                       <button id="curso_'.$cont.'" class="mdl-button mdl-js-button mdl-button--icon">
                                           <i class="mdi mdi-more_vert"></i>
                                       </button>
                                       <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="curso_'.$cont.'">
                                           <li class="mdl-menu__item"><i class="mdi mdi-delete"></i> Opci&oacute;n</li>
                                       </ul>
                                   </div>
                                   <div class="mdl-card__actions">
                                       <button class="mdl-button mdl-js-button mdl-button--colored-text">Ver</button>
                                       <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" data-id_main="'._encodeCI($curs['nid_main']).'" onclick="goDetalleCurso($(this));">Ingresar</button>
                                   </div>
                               </div>';
	        $cont++;
	    }
	    return $htmlFinal;
	}
	
	function go_detalleCurso() {
	    $idCurso = _decodeCI(_post('id_main'));
	    if($idCurso == null) {
	        redirect('', 'refresh');
	    } else {
	        _setSesion(array('id_main' => $idCurso));
	        redirect('notas/c_detalle_curso', 'refresh');
	    }
	}
	
	function logout() {
        $logedUser = _getSesion('usuario');
        $this->session->sess_destroy();
        redirect('','refresh');
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
    
	function enviarFeedBack(){
	    $nombre = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}
}