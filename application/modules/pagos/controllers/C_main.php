<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
        $this->load->library('table');
        $this->load->model('m_movimientos');
        $this->load->model('m_caja');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
        if($this->_idRol == ID_ROL_FAMILIA && _getSesion('entraFirstPadre') == null){
            redirect('pagos/c_pagos');
        }
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
	   $secretaria = ($this->_idRol == ID_ROL_SECRETARIA) ? $this->_idUserSess : null;
	   $this->session->set_userdata(array ('id_secretaria' => $secretaria));
	   if($this->_idRol == ID_ROL_SECRETARIA || $this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_RESP_COBRANZAS){
           $data = _searchInputHTML('Busca tus estudiantes o aulas');
	   }
       $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
       ////Modal Popup Iconos///
       $rolSistemas   = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
       $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
       //MENU
       $data['main'] = true;
       $data['ruta_logo']        = MENU_LOGO_PAGOS;
       $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
       $data['nombre_logo']      = NAME_MODULO_PAGOS;
       $menu                     = $this->load->view('v_menu', $data, true);
       $data['menu']             = $menu;
       $this->session->set_userdata(array('tab_active_movi' => null));
       $this->session->set_userdata(array('tab_active_config' => null));
       $cajaAperturada           = $this->m_caja->getCurrentCaja(_getSesion('id_sede_trabajo'),$this->_idUserSess);
       $idSede                   = ($this->_idRol != ID_ROL_PROMOTOR) ? _getSesion('id_sede_trabajo') : null;
       $data['optYear']          = __buildComboYearsAcademicos();
       $data['optSede']          = ($this->_idRol != ID_ROL_PROMOTOR) ? __buildComboSedes(null, $idSede) : null;
       $data['notificacionCaja'] = '""';
       $data['notificacionCaja'] = ($cajaAperturada['id_caja'] == null && $this->_idRol == ID_ROL_SECRETARIA) ? 'true' : '""';
       $this->session->set_userdata(array('id_persona_egreso' => $this->_idUserSess));
       $this->load->view('v_main', $data);
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
	    $nombreRol = $this->m_utils->getById("public.rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
	    $dataUser = array(PAGOS_ROL_SESS => $idRol,
	                      "nombre_rol"   => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = _getSesion('id_persona');
	    $idRol     = _getSesion(PAGOS_ROL_SESS);
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
	    __enviarFeedBack($mensaje,$url,$nombre);
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
	
	function busquedaGeneral(){
	    $nombre               = utf8_decode(_post("valorGeneral"));
	    $year                 = (_decodeCI(_post('year')) == null) ? null : _decodeCI(_post('year'));
	    $idSede               = (($this->_idRol != ID_ROL_PROMOTOR) ? $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess) : (empty($this->input->post('idSede')) ? null : _simple_decrypt($this->input->post('idSede'))));
	    $idNivel              = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
	    $idGrado              = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
	    $idAula               = empty($this->input->post('idAula'))  ? null : _decodeCI($this->input->post('idAula'));
	    $scroll               = _post("count");
	    $idRol                = $this->_idRol;
// 	    $sede                 = _getSesion('id_sede_trabajo');
        $estudiantes          = $this->m_movimientos->getAlumnosByFiltro($idSede,$idNivel,$idGrado,$idAula,$nombre);
        $data['cardsAlumnos'] = __buildCardsAlumnosPagosHTML($estudiantes,'main');
        $aulas                = $this->m_movimientos->getDataAulas($nombre,$year, $idSede, $idNivel, $idGrado, $idAula);
        $data['cardsAulas']   = __buildCardsAulasPagosHTML($aulas);
        if($data['cardsAlumnos'] == null || $data['cardsAlumnos'] == ""){
            $data['cardsAlumnos'] = null;
        }
        if($data['cardsAulas'] == null || $data['cardsAulas'] == ""){
            $data['cardsAulas'] = null;
        }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getDetaAula(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
        try{
            $idAula = _decodeCI(_post('aula'));
            $estudiantes       = $this->m_movimientos->getAlumnosByAula($idAula);
            $data['aula']      = $this->m_utils->getById('aula', 'desc_aula', 'nid_aula', $idAula);
            if(count($estudiantes) > 0){
                $data['table'] = $this->buildTableEstudiantes($estudiantes);
            } else{
                $data['table'] = '<div class="img-search">
                                      <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                                      <p>Ups! A&uacute;n no se han registrado datos.</p>
                                  </div>';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTableEstudiantes($estudiantes){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_compromisos">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0  = array('data' => '#','class' => 'text-center p-l-10');
	    $head_1  = array('data' => 'Estudiante', 'class' => 'text-left p-r-120 p-l-120 ');
	    $head_2  = array('data' => 'Cod. Estudiante'    , 'class' => 'text-right' );
	    $head_3  = array('data' => '&Uacute;ltimo pago' , 'class' => 'text-right' );
	    $head_4  = array('data' => 'Monto'              , 'class' => 'text-right' );
	    $head_5  = array('data' => 'Cuotas Vencidas'    , 'class' => 'text-center');
	    $head_6  = array('data' => 'Deuda'              , 'class' => 'text-right p-r-10' );
	    $head_7  = array('data' => 'Estado'      , 'class' => 'text-right' );
	    $this->table->set_heading(/*$head_0,*/$head_1,$head_2,$head_3,$head_4,$head_5,$head_6, $head_7);
	    $disabled = 'false';
	    foreach ($estudiantes as $row){
	        $idEstudianteCrypt = _encodeCI($row->nid_persona);
	        $img        = '<img alt="Student" class="img-circle m-r-5" WIDTH=25 HIEGHT=25 src="'.RUTA_IMG.'/profile/'.$row->foto_persona.'" data-toggle="tooltip" data-original-title=" '.$row->estudiante.'" data-placement="bottom">';
	        $row_col0   = array('data' => $row->row_num);
	        $row_col1   = array('data' => $img.' '.$row->nombreabreviado, 'class' =>'p-r-0');
	        $row_col2   = array('data' => $row->cod_alumno          , 'class' => 'text-center');
	        //ARRAY ULTIMO PAGO
	        $last_pago  = ($row->ultimo_pago != null) ? explode('|', $row->ultimo_pago) : 
	                                                    array(null,null);
	        $row_col3   = array('data' => $last_pago[0]             , 'class' => 'text-right');
	        $row_col4   = array('data' => $last_pago[1]             , 'class' => 'text-right');
	        //ARRAY VENCIDOS
	        $vencido    = explode('|', $row->vencido);
	        $class      = (($vencido[0] > 0) ? 'moroso' : 'pagado');
	        $classLabel = ($class == 'moroso') ? 'danger' : 'success';
	        $button     = '<button id="pago'.$row->cod_alumno.'" '.$class.' class="mdl-button mdl-js-button mdl-button--icon";>
                                <i class="mdi mdi-more_vert"></i>
                            </button>';
	        $row_col5   = array('data' => $vencido[0]       , 'class' => 'text-center');
	        $row_col6   = array('data' => $vencido[1]       , 'class' => 'text-right');
	        $estado     = (($vencido[0] == 0) ? 'AL DÍA' : 'VENCIDO');
	        $row_col7   = array('data' => '<span  style="padding-left: 7px;cursor:pointer" class="label label-'.$classLabel.'" style="cursor:pointer">'.$estado.'</span>','class' => 'text-right' );
	        $this->table->add_row(/*$row_col0,*/$row_col1,$row_col2,$row_col3,$row_col4,$row_col5,$row_col6 ,$row_col7);
	    }
	    return $table = $this->table->generate();
	}
	
	function getGraficoByAlumno(){
	    try{
	        $idPersona      = _decodeCI(_post('persona'));
	        $currentTab     = _post('current_tab');
	        $result         = $this->m_movimientos->getDataHistoricoByAlumno($idPersona);
	        $data['arr']    = $this->buildSeriesHistoricoAlumno($result);
	        $result         = $this->m_utils->getCamposById('persona', array('nom_persona','ape_pate_pers','ape_mate_pers'), 'nid_persona', $idPersona);
	        $data['nombre'] = strtoupper($result['ape_pate_pers']).' '.strtoupper($result['ape_mate_pers']).', '.strtoupper($result['nom_persona']);
	    } catch(Exception $e){
	         
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildSeriesHistoricoAlumno($result){
	    $arrayJson = array();
	    $arrayCate = array();
	    foreach ($result as $row){
	        array_push($arrayJson, floatval($row->monto_adelanto));
	        array_push($arrayCate, $row->detalle);
	    }
	    $data['arrayJson']  = json_encode($arrayJson);
	    $data['arrayCate']  = json_encode($arrayCate);
	    return json_encode($data);
	}
	
	function onScrollGetCards() {
	    $nombre               = _post("valorGeneral");
	    $idSede               = (($this->_idRol != ID_ROL_PROMOTOR) ? $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess) : (empty($this->input->post('idSede')) ? null : _simple_decrypt($this->input->post('idSede'))));
	    $idNivel              = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
	    $idGrado              = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
	    $idAula               = empty($this->input->post('idAula'))  ? null : _decodeCI($this->input->post('idAula'));
	    $scroll               = _post("countScroll");
	    $idRol                = $this->_idRol;
	    $estudiantes          = $this->m_movimientos->getAlumnosByFiltro($idSede,$idNivel,$idGrado,$idAula,$nombre,(NUMERO_CARDS_CARGA * $scroll) + 1);
	    $data['cardsAlumnos'] = __buildCardsAlumnosPagosHTML($estudiantes,'main');
	    $aulas                = $this->m_movimientos->getDataAulas($nombre,$idSede,$idNivel,$idGrado,$idAula,((NUMERO_CARDS_CARGA * $scroll) + 1));
	    $data['cardsAulas']   = __buildCardsAulasPagosHTML($aulas);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboSedesByYear() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $nombre = _post("valorGeneral");
	        $year   = _decodeCI(_post('year'));
	        $idSede = (($this->_idRol != ID_ROL_PROMOTOR) ? _getSesion('id_sede_trabajo') : (empty($this->input->post('idSede')) ? null : _simple_decrypt($this->input->post('idSede'))));
	        $offset = (NUMERO_CARGA*_post('count'));
	        $data['optSede']  = null;
	        $data['optNivel'] = null;
	        $data['cards']    = null;
	        if($year == null) {
	            $data['optSede'] = null;
	        }
	        if($this->_idRol != ID_ROL_PROMOTOR) {
	        	$estudiantes          = $this->m_movimientos->getAlumnosByFiltro($idSede,null,null,null,$nombre);
	        	$data['cardsAlumnos'] = __buildCardsAlumnosPagosHTML($estudiantes,'main');
	        	$aulas                = $this->m_movimientos->getDataAulas($nombre,$year, $idSede, null, null, null);
	        	$data['cardsAulas']   = __buildCardsAulasPagosHTML($aulas);
	        	if($data['cardsAlumnos'] == null || $data['cardsAlumnos'] == ""){
	        		$data['cardsAlumnos'] = null;
	        		$data['error'] = EXIT_ERROR;
	        	}
	        	if($data['cardsAulas'] == null || $data['cardsAulas'] == ""){
	        		$data['cardsAulas'] = null;
	        		$data['error'] = EXIT_ERROR;
	        	}
	        	$data['optNivel'] = __buildComboNivelesBySede($idSede);
	        }
	        $data['optSede'] = ($this->_idRol != ID_ROL_PROMOTOR) ? __buildComboSedes(null, $idSede) :__buildComboSedesByYear($year);
	        $data['error']   = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboSedesNivel() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $nombre = _post("valorGeneral");
	        $year   = _decodeCI(_post('year'));
	        $idSede = (($this->_idRol != ID_ROL_PROMOTOR) ? $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess) : (empty($this->input->post('idSede')) ? null : _simple_decrypt($this->input->post('idSede')))); 
	        $offset = (NUMERO_CARGA*_post('count'));
	        $data['optNivel'] = null;
	        $data['cards']    = null;
	        if($idSede == null) {
	            $data['optNivel'] = null;
	        }
	        $estudiantes          = $this->m_movimientos->getAlumnosByFiltro($idSede,null,null,null,$nombre);
	        $data['cardsAlumnos'] = __buildCardsAlumnosPagosHTML($estudiantes,'main');
	        $aulas                = $this->m_movimientos->getDataAulas($nombre,$year, $idSede, null, null, null);
	        $data['cardsAulas']   = __buildCardsAulasPagosHTML($aulas);
	        if($data['cardsAlumnos'] == null || $data['cardsAlumnos'] == ""){
	            $data['cardsAlumnos'] = null;
	            $data['error'] = EXIT_ERROR;
	        }
	        if($data['cardsAulas'] == null || $data['cardsAulas'] == ""){
	            $data['cardsAulas'] = null;
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
	        $nombre  = _post("valorGeneral");
	        $year    = _decodeCI(_post('year'));
	        $idSede  = (($this->_idRol != ID_ROL_PROMOTOR) ? $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess) : (empty($this->input->post('idSede')) ? null : _simple_decrypt($this->input->post('idSede'))));
	        $idNivel = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
	        $offset  = (NUMERO_CARGA*_post('count'));
	        if($idNivel == null || $idSede == null) {
	            $data['optGrado'] = null;
	        }
	        $estudiantes          = $this->m_movimientos->getAlumnosByFiltro($idSede,$idNivel,null,null,$nombre);
	        $data['cardsAlumnos'] = __buildCardsAlumnosPagosHTML($estudiantes,'main');
	        $aulas                = $this->m_movimientos->getDataAulas($nombre,$year, $idSede, $idNivel, null, null);
	        $data['cardsAulas']   = __buildCardsAulasPagosHTML($aulas);
	        if($data['cardsAlumnos'] == null || $data['cardsAlumnos'] == ""){
	            $data['cardsAlumnos'] = null;
	            $data['error'] = EXIT_ERROR;
	        }
	        if($data['cardsAulas'] == null || $data['cardsAulas'] == ""){
	            $data['cardsAulas'] = null;
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
	        $nombre  = _post("valorGeneral");
	        $year    = _decodeCI(_post('year'));
	        $idSede  = (($this->_idRol != ID_ROL_PROMOTOR) ? $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess) : (empty($this->input->post('idSede')) ? null : _simple_decrypt($this->input->post('idSede'))));
	        $idNivel = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
	        $idGrado = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
	        $offset  = (NUMERO_CARGA*_post('count'));
	        if($idGrado == null || $idSede == null) {
	            $data['optAula']  = null;
	        }
	        $estudiantes          = $this->m_movimientos->getAlumnosByFiltro($idSede,$idNivel,$idGrado,null,$nombre);
	        $data['cardsAlumnos'] = __buildCardsAlumnosPagosHTML($estudiantes,'main');
	        $aulas                = $this->m_movimientos->getDataAulas($nombre,$year, $idSede, $idNivel, $idGrado, null);
	        $data['cardsAulas']   = __buildCardsAulasPagosHTML($aulas);
	        if($data['cardsAlumnos'] == null || $data['cardsAlumnos'] == ""){
	            $data['cardsAlumnos'] = null;
	            $data['error'] = EXIT_ERROR;
	        }
	        if($data['cardsAulas'] == null || $data['cardsAulas'] == ""){
	            $data['cardsAulas'] = null;
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
	        $nombre  = _post("valorGeneral");
	        $year    = _decodeCI(_post('year'));
	        $idSede  = (($this->_idRol != ID_ROL_PROMOTOR) ? $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess) : (empty($this->input->post('idSede')) ? null : _simple_decrypt($this->input->post('idSede'))));
	        $idNivel = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
	        $idGrado = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
	        $idAula  = empty($this->input->post('idAula'))  ? null : _decodeCI($this->input->post('idAula'));
	        $offset  = (NUMERO_CARGA*_post('count'));
	        if($idAula == null) {
	            $data['optAula']  = null;
	        }
	        $estudiantes          = $this->m_movimientos->getAlumnosByFiltro($idSede,$idNivel,$idGrado,$idAula,$nombre);
	        $data['cardsAlumnos'] = __buildCardsAlumnosPagosHTML($estudiantes,'main');
	        $aulas                = $this->m_movimientos->getDataAulas($nombre,$year, $idSede, $idNivel, $idGrado, $idAula);
	        $data['cardsAulas']   = __buildCardsAulasPagosHTML($aulas);
	        if($data['cardsAlumnos'] == null || $data['cardsAlumnos'] == ""){
	            $data['cardsAlumnos'] = null;
	            $data['error'] = EXIT_ERROR;
	        }
	        if($data['cardsAulas'] == null || $data['cardsAulas'] == ""){
	            $data['cardsAulas'] = null;
	            $data['error'] = EXIT_ERROR;
	        }
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
}