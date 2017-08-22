<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_movimientos extends CI_Controller {

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
        $this->load->model('m_movimientos');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_MOVIMIENTOS, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
    
	public function index() {
// 	    $data                = _searchInputHTML('Busca tus alumnos o aulas');
	    $tabActive           = (_getSesion('tab_active_movi') == null || _getSesion('tab_active_movi') == '' || _getSesion('tab_active_movi') == 'tab-1') ? 'tab-1' : 'tab-2';
	    $data['tabActive']   = $tabActive;
	    if($this->_idRol == ID_ROL_DOCENTE){
	        redirect('pagos/c_egresos','refresh');
	    }
	    $data['barraSec']    = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                    <a href="#tab-1" class="mdl-layout__tab is-active">Ingresos</a>
                                    <a href="#tab-2" class="mdl-layout__tab">Egresos</a>
                                </div>';
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
	    ////Modal Popup Iconos///
	    $data['titleHeader']      = 'Movimientos';
	    $data['ruta_logo']        = MENU_LOGO_PAGOS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
	    $data['nombre_logo']      = NAME_MODULO_PAGOS;
	    //MENU
	    $rolSistemas         = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
	    $data['apps']          = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $data['menu']        = $this->load->view('v_menu', $data, true);
	    //NECESARIO
	    $data['optAreas']    =  __buildComboAreasEspecificas();
	    $data['optSede']     = __buildComboSedes(null,_getSesion('id_sede_trabajo'));
	    $data['optNivel']    = __buildComboNivelesBySede(_getSesion('id_sede_trabajo'));
	    $data['sedeDesc']    = $this->m_utils->getById('sede', 'desc_sede', 'nid_sede', _getSesion('id_sede_trabajo')); 
	    ///////////
	    $this->session->set_userdata(array('tab_active_config' => null));
	    $this->load->view('v_movimiento', $data);
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

	//BEGIN FILTRO ALUMNO
	function comboSedesNivel() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede        = empty($this->input->post('idSede')) ? null : _decodeCI($this->input->post('idSede'));
//             $idSede        = $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
	        $nombre        = $this->input->post('nombre');
    	    $apellidos     = $this->input->post('apellidos');
    	    $codigoAlumno  = $this->input->post('codigoAlumno');
    	    $codigoFamilia = $this->input->post('codigoFamilia');
    	    $searchMagic   = utf8_decode(trim(_post('searchMagic')));
    	    $offset        = (NUMERO_CARGA*_post('count'));
    	    $data['optNivel'] = null;
    	    $data['cards']    = null;
	        if($idSede == null) {
	            $data['optNivel'] = null;
// 	            throw new Excepstion(ANP);
	        }
	        $personas = $this->m_movimientos->getAlumnosByFiltro($idSede,null,null,null,$searchMagic);
	        $data['cards'] = __buildCardsAlumnosPagosHTML($personas);
	        if($data['cards'] == null || $data['cards'] == ""){
	            $data['cards'] = '<div class="img-search">
	                              <img src="'.base_url().'public/general/img/smiledu_faces/magic_not_found.png">
	                              <p><strong>&#161;Ups!</strong></p>
                                  <p>Tu filtro no ha sido</p>
                                  <p>encontrado.</p>
	                          </div>';
	            $data['error'] = EXIT_ERROR;
	        }
	        $data['optNivel'] = __buildComboNivelesBySede($idSede);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getComboGradoByNivel_Ctrl() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede        = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
// 	        $idSede        = $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
	        $idNivel       = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
	        $nombre        = $this->input->post('nombre');
    	    $apellidos     = $this->input->post('apellidos');
    	    $codigoAlumno  = $this->input->post('codigoAlumno');
    	    $codigoFamilia = $this->input->post('codigoFamilia');
    	    $searchMagic   = utf8_decode(trim(_post('searchMagic')));
    	    $offset        = (NUMERO_CARGA*_post('count'));
	        if($idNivel == null || $idSede == null) {
	            $data['optGrado'] = null;
	        }
            $personas = $this->m_movimientos->getAlumnosByFiltro($idSede,$idNivel,null,null,$searchMagic);
            $data['cards'] = __buildCardsAlumnosPagosHTML($personas);
            if($data['cards'] == null || $data['cards'] == ""){
                $data['cards'] = '<div class="img-search">
                              <img src="'.base_url().'public/general/img/smiledu_faces/magic_not_found.png">
                              <p><strong>&#161;Ups!</strong></p>
                              <p>Tu filtro no ha sido</p>
                              <p>encontrado.</p>
                          </div>';
                $data['error'] = EXIT_ERROR;
            }
	        $data['optGrado'] = __buildComboGradosByNivel($idNivel, $idSede);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboAulasByGradoUtils() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede        = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
// 	        $idSede        = $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
	        $idNivel       = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
	        $idGrado       = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
	        $nombre        = $this->input->post('nombre');
    	    $apellidos     = $this->input->post('apellidos');
    	    $codigoAlumno  = $this->input->post('codigoAlumno');
    	    $codigoFamilia = $this->input->post('codigoFamilia');
    	    $searchMagic   = utf8_decode(trim(_post('searchMagic')));
    	    $offset        = (NUMERO_CARGA*_post('count'));
	        if($idGrado == null || $idSede == null) {
	            $data['optAula']  = null;
	        }
	        $personas = $this->m_movimientos->getAlumnosByFiltro($idSede,$idNivel,$idGrado,null,$searchMagic);
	        $data['cards'] = __buildCardsAlumnosPagosHTML($personas);
	        if($data['cards'] == null || $data['cards'] == ""){
	            $data['cards'] = '<div class="img-search">
	                                  <img src="'.base_url().'public/general/img/smiledu_faces/magic_not_found.png">
                                      <p><strong>&#161;Ups!</strong></p>
                                      <p>Tu filtro no ha sido</p>
                                      <p>encontrado.</p>
	                              </div>';
	            $data['error'] = EXIT_ERROR;
	        }
	        $data['optAula'] = __buildComboAulas($idGrado,$idSede);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAlumnosFromAula() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede        = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
// 	        $idSede        = $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
	        $idNivel       = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
	        $idGrado       = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
	        $idAula        = empty($this->input->post('idAula'))  ? null : _decodeCI($this->input->post('idAula'));
	        $nombre        = $this->input->post('nombre');
	        $apellidos     = $this->input->post('apellidos');
	        $codigoAlumno  = $this->input->post('codigoAlumno');
	        $codigoFamilia = $this->input->post('codigoFamilia');
	        $searchMagic   = utf8_decode(trim(_post('searchMagic')));
	        $offset        = (NUMERO_CARGA*_post('count'));
	        if($idAula == null) {
	            $data['optAula']  = null;
	        }
	        $personas      = $this->m_movimientos->getAlumnosByFiltro($idSede,$idNivel,$idGrado,$idAula,$searchMagic);
	        $data['cards'] = __buildCardsAlumnosPagosHTML($personas);
	        if($data['cards'] == null || $data['cards'] == ""){
	            $data['cards'] = '<div class="img-search">
    	                              <img src="'.base_url().'public/general/img/smiledu_faces/magic_not_found.png">
    	                              <p>No se encontraron</p>
                                      <p>resultados.</p>
	                              </div>';
	            $data['error'] = EXIT_ERROR;
	        }
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAlumnosByFiltro() {
	    $data = null;
	    $nombre        = trim(_post('nombre'));
	    $apellidos     = trim(_post('apellidos'));
	    $codigoAlumno  = trim(_post('codigoAlumno'));
	    $codigoFamilia = trim(_post('codigoFamilia'));
	    $searchMagic   = utf8_decode(trim(_post('searchMagic')));
	    $searchMagic1  = trim(_post('searchMagic1'));
	    $idSede        = empty(_decodeCI(_post('idSede')))  ? null : _decodeCI(_post('idSede'));
// 	    $idSede        = NULL;//_getSesion('id_sede_trabajo');//$this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
	    $idNivel       = empty(_decodeCI(_post('idNivel'))) ? null : _decodeCI(_post('idNivel'));
	    $idGrado       = empty(_decodeCI(_post('idGrado'))) ? null : _decodeCI(_post('idGrado'));
	    $idAula        = empty(_decodeCI(_post('idAula'))) ? null : _decodeCI(_post('idAula'));
	    $offset        = (NUMERO_CARGA*_post('count'));
	    $personas = $this->m_movimientos->getAlumnosByFiltro($idSede,$idNivel,$idGrado,$idAula,$searchMagic);
	    $data['cards'] = __buildCardsAlumnosPagosHTML($personas);
	    if($data['cards'] == null || $data['cards'] == ""){
	        $data['cards'] = '<div class="img-search">
	                              <img src="'.base_url().'public/general/img/smiledu_faces/magic_not_found.png">
	                              <p>No se encontraron</p>
                                  <p>resultados.</p>
	                          </div>';
	        $data['error'] = EXIT_ERROR;
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function goToDetallePagoPersona() {
	    $data = null;
	    $idPersona  = _decodeCI(_post('idPersona'));
	    $currentTab = _post('current_tab');
        $this->session->set_userdata(array('tab_active_movi' => $currentTab));
	    $data['url'] = ($idPersona != null) ? RUTA_SMILEDU.'pagos/c_ingresos?persona='._encodeCI($idPersona) : null;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAllColaboradores() {
	    try {
	        $nombre    = trim(_post('nombre'));
	        $apellidos = trim(_post('apellidos'));
	        $dni       = trim(_post('dni'));
	        $area      = empty(_decodeCI(_post('area'))) ? null : _decodeCI(_post('area'));
	        $searchMagic = trim(_post('searchMagic'));
	        $personas = $this->m_movimientos->getColaboradoresByFiltro($nombre,$apellidos,$dni,$area,$searchMagic);
	        $data['cards'] = $this->buildCardsColaboradoresHTML($personas);
	        if($data['cards'] == null || $data['cards'] == ""){
	            $data['cards'] = '<div class="img-search">
    	                              <img src="'.base_url().'public/general/img/smiledu_faces/not_filter_fab.png">
    	                              <p><strong>&#161;Ups!</strong></p>
                                      <p>Tu filtro no ha sido</p>
                                      <p>encontrado.</p>
    	                          </div>';
	        }
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildCardsColaboradoresHTML($personas) {
	    $val = 0;
	    $cards = null;
	    foreach($personas as $row){
	        $val++;
	        $cards .= ' <div class="mdl-card mdl-student mdl-shadow--2dp">
                            <div class="mdl-card__title">
                                <img alt="Student" src="'.RUTA_SMILEDU.'public/general/img/profile/nouser.svg">
                            </div>
                            <div class="mdl-card__supporting-text pago puntual">
                                <div class="row p-0 m-0">
                                    <div class="col-xs-12 student-name">'.$row->apellidos.'</div>
                                    <div class="col-xs-12 student-name">'.$row->nombres.'</div>
                                    <div class="col-xs-12 student-state">'.$row->desc_rol.'</div>
                                    <div class="col-xs-12 student-head"><strong>Detalles del Colaborador:</strong></div>
                                    <div class="col-xs-7  student-item">Sede</div>
                                    <div class="col-xs-5  student-value">'.$row->desc_sede.'</div>
                                    <div class="col-xs-7  student-item">Nivel</div>
                                    <div class="col-xs-5  student-value">'.$row->desc_nivel.'</div>
                                    <div class="col-xs-3  student-item">&Aacute;rea</div>
                                    <div class="col-xs-9  student-value">'.$row->desc_area.'</div>
                                    <div class="col-xs-3  student-item">DNI</div>
                                    <div class="col-xs-9  student-value">'.$row->nro_documento.'</div>
                                    <div class="col-xs-3  student-item">Tel&eacute;f.</div>
                                    <div class="col-xs-9  student-value">'.$row->telf_pers.'</div>
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored btn_modal_card" onclick="goToEgresosColaborador(\''._encodeCI($row->nid_persona).'\')">Retiro</button>
                            </div>
                            <div class="mdl-card__menu">
                                <button id="pago'.$val.'" class="mdl-button mdl-js-button mdl-button--icon" onclick="";>
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="pago'.$val.'">
                                    <li class="mdl-menu__item"><i class="mdi mdi-edit"></i>Opcion</li>
                                </ul>
                            </div>
                        </div>
                    ';
	    }
	    return $cards;
	}
	
	function goToDetalleEgresosPersona() {
	    $url = null;
	    try{
	        $idPersona  = _decodeCI(_post('persona'));
	        if($idPersona == null){
	            throw new Exception(ANP);
	        }
	        $array = array(
	                       'id_persona_egreso' => $idPersona,
	                       'flg_egreso'     => 1
	                      );
	        $this->session->set_userdata($array);
	        $url = base_url().'pagos/c_egresos';
	    } catch(Exception $e){
	        $url = null;
	    }
	    echo $url;
	}
	
	function onScrollGetAlumnos(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede        = empty(_decodeCI(_post('idSede')))  ? null : _decodeCI($this->input->post('idSede'));
// 	        $idSede        = $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
	        $idNivel       = empty(_decodeCI(_post('idNivel'))) ? null : _decodeCI($this->input->post('idNivel'));
	        $idGrado       = empty(_decodeCI(_post('idGrado'))) ? null : _decodeCI($this->input->post('idGrado'));
	        $idAula        = empty(_decodeCI(_post('idAula')))  ? null : _decodeCI($this->input->post('idAula'));
	        $nombre        = $this->input->post('nombre');
	        $apellidos     = $this->input->post('apellidos');
	        $codigoAlumno  = $this->input->post('codigoAlumno');
	        $codigoFamilia = $this->input->post('codigoFamilia');
	        $searchMagic   = utf8_decode(trim(_post('searchMagic')));
	        $offset        = (NUMERO_CARGA*_post('count'));
	        if($idAula == null) {
	            $data['error']    = EXIT_ERROR;
	            $data['optAula']  = null;
	        }
	        $personas      = $this->m_movimientos->getAlumnosByFiltro($idSede,$idNivel,$idGrado,$idAula,$searchMagic,$offset);
	        $data['cards'] = __buildCardsAlumnosPagosHTML($personas);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAllProveedores(){
	    $nombreProveedor = _post('searchMagic');
	    $proveedores = $this->m_movimientos->getAllProveedores($nombreProveedor);
	    $data['cardProveedores'] = $this->buildCardsProveedoresHTML($proveedores);
	    if($data['cardProveedores'] == null || $data['cardProveedores'] == ""){
	        $data['cardProveedores'] = '<div class="img-search">
    	                                    <img src="'.base_url().'public/general/img/smiledu_faces/not_filter_fab.png">
    	                                    <p><strong>&#161;Ups!</strong></p>
                                            <p>Tu filtro no ha sido</p>
                                            <p>encontrado.</p>
    	                                </div>';
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildCardsProveedoresHTML($proveedores){
	    $val = 0;
	    $cards = null;
	    foreach($proveedores as $row){
	        $val++;
	        $cards .= ' <div class="mdl-card mdl-student mdl-shadow--2dp">
                            <div class="mdl-card__title">
                                <img alt="Student" src="'.RUTA_SMILEDU.'public/general/img/profile/nouser.svg">
                            </div>
                            <div class="mdl-card__supporting-text pago puntual">
                                <div class="row p-0 m-0">
                                    <div class="col-xs-12 student-name">'.$row->nombre_proveedor.'</div>
                                     <div class="col-xs-12 student-name">'.'</div>
                                    <div class="col-xs-12 student-state">'.'</div>
                                    <div class="col-xs-12 student-head"><strong>Detalles del Colaborador:</strong></div>
                                    <div class="col-xs-7  student-item">Responsable</div>
                                    <div class="col-xs-5  student-value">'.$row->responsable.'</div>
                                    <!-- <div class="col-xs-7  student-item">Nivel</div>
                                    <div class="col-xs-5  student-value">'.'</div>
                                    <div class="col-xs-3  student-item">&Aacute;rea</div>
                                    <div class="col-xs-9  student-value">'.'</div>
                                    <div class="col-xs-3  student-item">DNI</div>
                                    <div class="col-xs-9  student-value">'.'</div>
                                    <div class="col-xs-3  student-item">Tel&eacute;f.</div>
                                    <div class="col-xs-9  student-value">'.'</div> -->
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored btn_modal_card" onclick="goToEgresosProveedor(\''._encodeCI($row->id_proveedor).'\')">Retiro</button>
                            </div>
                            <div class="mdl-card__menu">
                                <button id="pago'.$val.'" class="mdl-button mdl-js-button mdl-button--icon" onclick="";>
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="pago'.$val.'">
                                    <li class="mdl-menu__item"><i class="mdi mdi-edit"></i>Opcion</li>
                                </ul>
                            </div>
                        </div>
                    ';
	    }
	    return $cards;
	}
	
	function goToDetalleEgresosProveedor() {
	    $url = null;
	    try{
	        $idPersona  = _decodeCI(_post('proveedor'));
	        if($idPersona == null){
	            throw new Exception(ANP);
	        }
	        $array = array(
                	          'id_persona_egreso' => $idPersona,
                	          'flg_egreso'        => 2
                	      );
	        $this->session->set_userdata($array);
	        $url = base_url().'pagos/c_egresos';
	    } catch(Exception $e){
	        $url = null;
	    }
	    echo $url;
	}
}