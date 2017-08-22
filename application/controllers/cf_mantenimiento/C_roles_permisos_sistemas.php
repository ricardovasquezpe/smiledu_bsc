<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_roles_permisos_sistemas extends CI_Controller {

    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('mf_mantenimiento/m_roles_permisos_sistemas');
        $this->load->library('table');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_ROL_PERM_SIST);
        $this->_idUserSess = _getSesion('nid_persona');
    }
    
    function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);

        $data['titleHeader']      = 'Permisos';
        $data['ruta_logo']        = MENU_LOGO_SIST_AV;
        $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
        $data['rutaSalto']        = 'SI';

        $rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
        $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        
        //MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	
    	$data['menu']      = $menu;
        $data['font_size'] = _getSesion('font_size');
        
        $data['tabRolesSist'] =  $this->buildTablaRolesSistHTML(null);
        $data['tabSistPerm']  =  $this->buildTablaSistPermHTML(null,null);
        $data['optRol']       = __buildComboRoles();
        
        $this->load->view('vf_mantenimiento/v_roles_permisos_sistemas', $data);
    }
    
    function buildTablaRolesSistHTML($idRol){        
        $listaRolesSist = ($idRol != null ) ? $this->m_roles_permisos_sistemas->getAllSistemas($idRol) : array();  
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" id="tb_rolessist">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#');
        $head_2 = array('data' => 'Sistema');
        $head_3 = array('data' => 'Asignar', 'class' => 'text-center');
        $val = 0;
        $this->table->set_heading($head_1, $head_2, $head_3);
     
        foreach($listaRolesSist as $row){
            $idCryptSist= $this->encrypt->encode($row->nid_sistema);
            $idCryptRol = $this->encrypt->encode($idRol);
      
             $val++;
            $row_cell_1  = array('data' => $val);
            $row_cell_2  = array('data' => $row -> desc_sist);
             
            $check_sist = ($row->flg_acti == FLG_ACTIVO)   ? 'checked' : 0;
            $row_cell_3  = array('data' => '<label for="sist'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                                                <input type="checkbox" id="sist'.$val.'" '.$check_sist.' onclick="cambioCheckSist(this);"  class="mdl-checkbox__input"
	                                                   attr-idSist="'.$idCryptSist.'"  attr-idRol="'.$idCryptRol.'"  attr-cambio="false" attr-bd="'.$check_sist.'">
    											<span class="mdl-checkbox__label"></span>
    										</label>', 'class' => 'text-center');        
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function buildTablaSistPermHTML($idRol,$idSist){
        $listaRolesSistPerm = ($idRol != null && $idSist != null ) ? $this->m_roles_permisos_sistemas->getAllPermisos($idRol,$idSist) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar1" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" id="tb_sistperm">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#');
        $head_2 = array('data' => 'Permisos');
        $head_3 = array('data' => 'Asignar' , 'class' => 'text-center');
        $val = 0;
        $this->table->set_heading($head_1, $head_2, $head_3);
        foreach($listaRolesSistPerm as $row){
            $idCryptPerm = $this->encrypt->encode($row->nid_permiso);
            $idCryptSist= $this->encrypt->encode($idSist);
            $idCryptRol = $this->encrypt->encode($idRol);
            $val++;
            $row_cell_1  = array('data' => $val);
            $row_cell_2  = array('data' => $row -> desc_permiso);
             
            $check_perm = ($row->nid_rol != null)   ? 'checked' : null;
            $row_cell_3  = array('data' => '<label for="perm'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                                                <input type="checkbox" id="perm'.$val.'" '.$check_perm.' onclick="cambioCheckPerm(this);" class="mdl-checkbox__input"
	                                                   attr-idSist="'.$idCryptSist.'" attr-idRol="'.$idCryptRol.'"  attr-idPerm="'.$idCryptPerm.'" attr-cambio="false" attr-bd="'.$check_perm.'">
    											<span class="mdl-checkbox__label"></span>
    										</label>', 'class' => 'text-center');
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
  
    function getRolesFromSistema() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idRol = $this->encrypt->decode(_post('idRol'));
            if($idRol == null) {
                throw new Exception(ANP);
                $data['tabRolesSist'] = $this->buildTablaRolesSistHTML(null);
            }           
            $data['tabRolesSist'] = $this->buildTablaRolesSistHTML($idRol);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getSistemaFromPermiso() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idRol  = $this->encrypt->decode(_post('idRol'));
            $idSist = $this->encrypt->decode(_post('idSist'));      
            if($idRol == null || $idSist == null) {
                throw new Exception(ANP);
                $data['tabSistPerm'] = $this->buildTablaSistPermHTML(null,null);
            }
            $data['tabSistPerm'] = $this->buildTablaSistPermHTML($idRol,$idSist);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function grabarRolesSistema() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $myPostData = json_decode(_post('rolsistem'), TRUE);
      
            $arrayGeneral = array();
            foreach($myPostData['rolsist'] as $key => $rolsist){
                $idSist  = $this->encrypt->decode($rolsist['idSist']);
                $idRol  = $this->encrypt->decode($rolsist['idRol']);                
                $estado = $this->m_roles_permisos_sistemas->getflgActiRolSist($idRol,$idSist);
                $newEstado = ($estado == FLG_ACTIVO) ? FLG_INACTIVO : FLG_ACTIVO; 
              if($estado==null){                       
                $arrayDatos = array(
                    "flg_acti"    => $newEstado,
                    "nid_rol"     => $idRol,
                    "nid_sistema" => $idSist,
                    "ACCION"      => 'I'
                );                
               }
               else {    
                   $arrayDatos = array(
                        "flg_acti"    => $newEstado,
                        "nid_rol"     => $idRol,
                        "nid_sistema" => $idSist,
                        "ACCION"      => 'U'                           
                   );
               }
            
               array_push($arrayGeneral, $arrayDatos);
            }
            if(isset($arrayDatos)) {
                $data = $this->m_roles_permisos_sistemas->InsertupdateRolSistPerm($arrayGeneral);
                if($data['error'] == EXIT_SUCCESS) {
                      $data['tabRolesSist'] = $this->buildTablaRolesSistHTML($idRol);
                }
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function grabarSistemaPermiso() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $myPostData = json_decode(_post('sistperm'), TRUE);
            $arrayGeneral = array();
            foreach($myPostData['sistper'] as $key => $sistperm){
                $idSist  = $this->encrypt->decode($sistperm['idSist']);
                $idRol  = $this->encrypt->decode($sistperm['idRol']);
                $idPerm = $this->encrypt->decode($sistperm['idPerm']);
                $estado = $this->m_roles_permisos_sistemas->getflgActiSistPerm($idRol,$idSist,$idPerm);
                $newEstado = ($estado == FLG_ACTIVO) ? FLG_INACTIVO : FLG_ACTIVO;
    
                if($estado==null){
                    $arrayDatos = array(
                        "flg_acti"    => $newEstado,
                        "nid_rol"     => $idRol,
                        "nid_sistema" => $idSist,
                        "nid_permiso" => $idPerm,           
                        "ACCION"      => 'I'
                    );
                }
                else{
                    $arrayDatos = array(
                        "flg_acti"    => $newEstado,
                        "nid_rol"     => $idRol,
                        "nid_sistema" => $idSist,
                        "nid_permiso" => $idPerm,
                        "ACCION"      => 'U'
                    );
                }
                array_push($arrayGeneral, $arrayDatos);
            }

            if(isset($arrayDatos)) {
               $data = $this->m_roles_permisos_sistemas->InsertupdateRolSistPermv2($arrayGeneral);
              
                if($data['error'] == EXIT_SUCCESS) {
                   $data['tabSistPerm'] = $this->buildTablaSistPermHTML($idRol,$idSist);
                }
            }

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function logOut(){
        $logedUser = _getSesion('usuario');
        $this->session->sess_destroy();
        redirect('','refresh');
    }
    
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}