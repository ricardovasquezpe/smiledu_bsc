<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_colaboradores extends CI_Controller {
    
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
        $this->load->model('mf_colaborador/m_colaborador');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_RRHH, ID_PERMISO_COLABORADORES, RRHH_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(RRHH_ROL_SESS);
    }
   
	public function index() {
	    $data = _searchInputHTML('Busca tus colaboradores', 'onkeyup="buscarColaborador()"');
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_RRHH, RRHH_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_RRHH, $this->_idUserSess);
	    $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['titleHeader'] = "Colaboradores";
	    $data['ruta_logo'] = MENU_LOGO_RRHH;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_RRHH;
	    $data['nombre_logo'] = NAME_MODULO_RRHH;
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;

	    $data['comboSexo']          = __buildComboByGrupo(COMBO_SEXO);
	    $data['comboTipoDocumento'] = __buildComboByGrupoNoEncryptId(COMBO_TIPO_DOC);
	    $data['colaboradores'] = _createVistaPersonal($this->m_colaborador->getAllColaboradores(1, NUMERO_COLABORADORES_CARGA));
	    
	    $this->load->view('v_colaboradores',$data);
	}
	
	function crearColaborador(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $apePaterno   = _post('apepaterno');
	        $apeMaterno   = _post('apematerno');
	        $nombres      = _post("nombres");
	        $fecNaci      = _post("fecnaci");
	        $sexo         = _simpleDecryptInt(_post('sexo'));
	        $correo       = _post("correo");
	        $telefono     = _post("telefono");
	        $tipoDoc      = _post("tipoDoc");
	        $numeroDoc    = _post("numeroDoc");
	    
	        if($apePaterno == null || $apeMaterno == null || $nombres == null || $fecNaci == null
	           || $sexo == null || $correo == null || $telefono == null || $numeroDoc == null || $tipoDoc == null){
	            throw new Exception("Faltan algunos campos");
	        }
	         
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Ingrese un correo v&aacute;lido");
            }
            
            if($this->m_utils->existeByCampoModelById("correo_pers", $correo, "rrhh.persona", 0) > 0){
                throw new Exception("El correo ingresado ya existe");
            }
	         
	        $arrayInsert = array("nom_persona"   => $nombres,
	                             "ape_pate_pers" => $apePaterno,
	                             "ape_mate_pers" => $apeMaterno,
	                             "fec_naci"      => $fecNaci,
	                             "correo_pers"        => $correo,
	                             "sexo"          => $sexo,
	                             "telf_pers"    => $telefono,
	                             "tipodoc"       => $tipoDoc,
	                             "numerodoc"     => $numeroDoc);
	         
	        $data = $this->m_colaborador->insertColaborador($arrayInsert);
	        if($data['error'] == EXIT_SUCCESS){
	            
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function onScrollGetColaboradores(){
	    $scroll = _post("countScroll");
	    $data['colaboradores'] = _createVistaPersonal($this->m_colaborador->getAllColaboradores((NUMERO_COLABORADORES_CARGA * $scroll) + 1, NUMERO_COLABORADORES_CARGA));
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buscarColaborador(){
	    $texto = _post("texto");
	    $data['colaboradores'] = _createVistaPersonal($this->m_colaborador->buscarColaborador($texto));
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function verRolesPorUsuario(){
	    $idPersona = _simpleDecryptInt(_post("persona"));
	    $roles = $this->m_colaborador->getRolesByPersona($idPersona);
	    $data['tabla'] = _buildTableRolesPorUsuario($roles);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function editarRolesPersona(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
    	    $idPersona = _simpleDecryptInt(_post("persona"));
    	    $myPostData = json_decode(_post('json'), TRUE);
    	    $arrayRolesInsert = array();
    	    foreach($myPostData['rol'] as $key => $rol) {
    	        $rol = _simpleDecryptInt($rol['rol']);
    	        array_push($arrayRolesInsert, array('nid_persona' => $idPersona, 'nid_rol' => $rol, 'flg_acti' => FLG_ACTIVO));
    	    }
    	    $data = $this->m_colaborador->updateRolesPersona($arrayRolesInsert, $idPersona);
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function logout() {
	    $this->session->set_userdata(array("logout" => true));
	    unset($_COOKIE['schoowl']);
	    $cookie_name2 = "schoowl";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	    Redirect(RUTA_SMILEDU, true);
	}
	
	function cambioRol() {
	    $idRol = _simple_decrypt(_post('id_rol'));
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'schoowl');
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
}