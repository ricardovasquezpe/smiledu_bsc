<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_persona_rol extends CI_Controller {
    
    private $_idUserSess = null;

    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('mf_mantenimiento/m_persona_rol');
        $this->load->model('mf_usuario/m_usuario');
        $this->load->library('table');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_PERSONA_ROL);
        $this->_idUserSess = _getSesion('nid_persona');
    }

    public function index() {
        $roles = _getSesion('roles');
	    $data['titleHeader']      = 'Persona Rol';
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
        
        $data['comboRoles']        = __buildComboRoles();
        $data['personaRolesTable'] = $this->buildTablePersonaRolesHTML(null);
        
        $this->load->view('vf_mantenimiento/v_persona_rol', $data);
    }
    //Tabla ROL para el modal
    function buildTablePersonaRolesHTML($idRol){
        $listaPersonas = ($idRol != null ) ? $this->m_persona_rol->getAllPersonasByRol($idRol) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_persona_rol">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#'       , 'class' => 'text-left');
        $head_2 = array('data' => 'Nombres' , 'class' => 'text-left');
        $head_3 = array('data' => 'Roles'   , 'class' => 'text-left');
        $head_4 = array('data' => 'Acciones', 'class' => 'text-center');
        
        $this->table->set_heading($head_1,$head_2,$head_3,$head_4);
        $val = 0; 
        foreach($listaPersonas as $row) {
            $idCryptPersona = _encodeCI($row->nid_persona);
            $val++;
            $row_cell_1  = array('data' => $val                 , 'class' => 'text-left');
            $row_cell_2  = array('data' => $row->nombrecompleto , 'class' => 'text-left');
            $row_cell_3  = array('data' => $row->roles          , 'class' => 'text-left');
            $row_cell_4  = array('data' => '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="abrirAsignarRoles(this);" attr-idpersona="'.$idCryptPersona.'" id="modificar'.$val.'" data-toggle="tooltip" data-placement="top" data-original-title="Edit row"><i class="mdi mdi-edit"></i></button>
                                            <button class="mdl-button mdl-js-button mdl-button--icon" onclick="abrirAsignarPermisos(\''.$idCryptPersona.'\'  );" data-toggle="tooltip" data-placement="top" data-original-title="Edit row"><i class="mdi mdi-subject"></i></button>
                                ', 'class' => 'text-center');
            $this->table->add_row($row_cell_1,$row_cell_2,$row_cell_3,$row_cell_4);
        }
        
        $tabla = $this->table->generate();
        return $tabla;
    }
    //Tabla Rol x Persona llamado al GetPersonaByRol
    function getAllPersonaByRol(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idRol = _decodeCI(_post('idRol'));
            $data['personaRolesTable'] = $this->buildTablePersonaRolesHTML($idRol);
            if($idRol != null) {
                $data['fabOpcNuevoPers'] = '<li>
                                                <a href="javascript:void(0);" data-mfb-label="Seleccionar Rol" class="mfb-component__button--child mdl-color--indigo">
                                                    <i class="mfb-component__child-icon md md-edit" onclick="abrirCerrarModal(\'modalFiltro\')" style="font-size: 20px;padding-top: 1px;color:white;margin-top: -6px;"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" data-mfb-label="Agregar Nuevo" class="mfb-component__button--child mdl-color--indigo">
                                                    <i class="mfb-component__child-icon md md-edit" onclick="openModalBuscarPersona();" style="font-size: 20px;padding-top: 1px;color:white;margin-top: -6px;"></i>
                                                </a>
                                            </li>';
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablePersonaRolesAllHTML($idPersona) {
        $listaPersonaRol =($idPersona != null) ? $this->m_persona_rol->getPersonasRolAll($idPersona) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_roles_all_persona">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#');
        $head_2 = array('data' => 'Rol');
        $head_3 = array('data' => '');
        $this->table->set_heading($head_1,$head_2,$head_3);
        $val = 0;
        foreach($listaPersonaRol as $row) {
            $val++;
            $idPersonaCryp = _encodeCI($idPersona);
            $idRolCryp     = _encodeCI($row->nid_rol);
            $check_rol = ($row->flg_acti == 1)   ? 'checked' : null;
            $row_1 = array('data' => $val);
            $row_2 = array('data' => $row->desc_rol);
            $row_3 = array('data' => '<label for="rol'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
										  <input type="checkbox" '.$check_rol.' class="mdl-checkbox__input" id="rol'.$val.'" attr-bd="'.$check_rol.'" attr-idpersona="'.$idPersonaCryp.'" attr-cambio="false" attr-idrol="'.$idRolCryp.'" onchange="cambioCheckRol(this);">
										  <span class="mdl-checkbox__label"></span>
									  </label>');
            
            $this->table->add_row($row_1,$row_2,$row_3);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    //
    function buildTablePermisosPersonaMantenimiento($listaPermisos, $idPersona){
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_persona_permiso">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        
        $head_1 = array('data' => 'Permiso');
        $head_2 = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
        
        $this->table->set_heading($head_1, $head_2);
        $val = 0;
        foreach($listaPermisos as $row){
            $val++;
            $idPersonaCryp = _encodeCI($idPersona);
            $idPermCryp    = _encodeCI($row->nid_permiso);
            $check_permiso = $row->flg_acti;
            $row_1 = array('data' => $row->desc_permiso);
            $row_2 = array('data' => '<label for="rol'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
										  <input type="checkbox" '.$check_permiso.' class="mdl-checkbox__input" id="rol'.$val.'" attr-bd="'.$check_permiso.'" attr-idpersona="'.$idPersonaCryp.'" attr-cambio="false" attr-idpermiso="'.$idPermCryp.'" onchange="cambioCheckPermiso(this);">
										  <span class="mdl-checkbox__label"></span>
									  </label>', 'class' => 'text-center');
        
            $this->table->add_row($row_1, $row_2);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getPermisosByPersona(){
        $idPersona = _decodeCI(_post('idPersona'));
        $listaPermisos = $this->m_usuario->getListaPermisosMantByPersona($idPersona);
        $table = $this->buildTablePermisosPersonaMantenimiento($listaPermisos, $idPersona);
        $data['tabla'] = $table;
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getAllRolesByPersona(){
        $idPersona = _decodeCI(_post('idPersona'));
        $data['rolesByPersonaTable'] = $this->buildTablePersonaRolesAllHTML($idPersona);        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function grabarRolesPersona(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $myPostData = json_decode(_post('personas'), TRUE);
            $strgConcatIdPersonas = null;
            $arrayGeneral = array();
            foreach($myPostData['persona'] as $key => $persona){
                $idPersona  = _decodeCI($persona['idPersona']);
                $newVal = ($persona['valor'] == null) ? '0' : '1';
                $idRol = _decodeCI($persona['idRol']);
                $condicion = $this->m_persona_rol->evaluaInsertUpdate($idPersona,$idRol);
                $arrayDatos = array();
                $arrayDatos = array("flg_acti"       => $newVal,
                                    "nid_persona"    => $idPersona,
                                    "nid_rol"        => $idRol,
                                    "condicion"      => $condicion
                );
                array_push($arrayGeneral, $arrayDatos);
            }
            $data = $this->m_persona_rol->updateInsertRolesPersona($arrayGeneral);
            if($data['error'] == EXIT_SUCCESS){
                $idRol = _decodeCI(_post('idRolCombo'));
                $data['rolesByPersonaTable'] = $this->buildTablePersonaRolesAllHTML($idPersona);
                $data['personaRolesTable'] = $this->buildTablePersonaRolesHTML($idRol);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function grabarPermisosPersona(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $myPostData = json_decode(_post('permisos'), TRUE);
            $strgConcatIdPersonas = null;
            $arrayGeneral = array();
            foreach($myPostData['persona'] as $key => $persona){
                $idPersona  = _decodeCI($persona['idPersona']);
                $newVal = ($persona['valor'] == null) ? '0' : '1';
                $idPermiso  = _decodeCI($persona['idPermiso']);
                $condicion = $this->m_usuario->evaluaInsertUpdatePersPermMant($idPersona,$idPermiso);
                $arrayDatos = array();
                $arrayDatos = array("_id_persona"    => $idPersona,
                                    "_id_permiso"    => $idPermiso,
                                    "condicion"      => $condicion
                );
                array_push($arrayGeneral, $arrayDatos);
            }
            $data = $this->m_usuario->updateInsertPermisosPersonaMant($arrayGeneral);
            if($data['error'] == EXIT_SUCCESS){
                $idRol = _decodeCI(_post('idRolCombo'));
                $data['rolesByPersonaTable'] = $this->buildTablePersonaRolesAllHTML($idPersona);
                $data['personaRolesTable'] = $this->buildTablePersonaRolesHTML($idRol);
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
        $nombre = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }

	function buscarUsuarioForRol() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $persBusq    = _post('persBusq');
	        $rolSelected = _decodeCI(_post('idRolSel'));
	        if($rolSelected == null) {
	            throw new Exception(ANP);
	        }
	        $arryPersonasBusq = $this->m_usuario->getBusquedaPersonaRol($rolSelected, $persBusq);
	        $data['tbPersBusq'] = $this->buildTablePersonasBusquedaHTML($arryPersonasBusq);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	//
	function buildTablePersonasBusquedaHTML($arryPersonasBusq){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_pers_busq">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	
	    $head_1 = array('data' => 'Nombres');
	    $head_2 = array('data' => 'Acci&oacute;n');
	
	    $this->table->set_heading($head_1, $head_2);
	    $val = 0;
	    foreach($arryPersonasBusq as $row){
	        $val++;
	        $idPersonaCryp = _encodeCI($row->nid_persona);
	        $row_1 = array('data' => $row->nombres);
	        $row_2 = array('data' => '<label for="pers_'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
									      <input type="checkbox" class="mdl-checkbox__input" id="pers_'.$val.'" data-id_persona="'.$idPersonaCryp.'" attr-cambio="false" onchange="cambioCheckPersRol($(this));">
									      <span class="mdl-checkbox__label"></span>
								      </label>');
	       
	        $this->table->add_row($row_1, $row_2);
	    }
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	function grabarPersonaRol() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $arryPersGlobal = _post('arryPersGlobal');
	        $rolSelected    = _decodeCI(_post('idRolSel'));
	        if($rolSelected == null) {
	            throw new Exception(ANP);
	        }
	        if(!is_array($arryPersGlobal)) {
	            throw new Exception(ANP);
	        }
	        if(count($arryPersGlobal) == 0) {
	            throw new Exception(ANP);
	        }
	        //
	        $arryPersonasBusq    = $this->m_usuario->insertarPersonasRol($arryPersGlobal, $rolSelected);
	        $data['tbPrincipal'] = $this->buildTablePersonaRolesHTML($rolSelected);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
}