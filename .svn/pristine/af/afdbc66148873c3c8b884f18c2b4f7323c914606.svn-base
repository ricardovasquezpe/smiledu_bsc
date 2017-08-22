<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_pagos extends CI_Controller {

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
        $this->load->model('m_movimientos');
        $this->load->model('m_pagos');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_MIS_PAGOS, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
    
	public function index() {
	    $parentesco          = $this->m_pagos->getNombresParentescoByPersona(_getSesion('cod_familiar'),$this->_idUserSess);
	    $val = 0;
	    $flg_active = null;
	    $data['parientes']  = $this->createButtonAlumnos($parentesco);
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
	    ////Modal Popup Iconos///
	    $data['titleHeader']      = 'Mis Pagos';
	    $data['ruta_logo']        = MENU_LOGO_PAGOS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
	    $data['nombre_logo']      = NAME_MODULO_PAGOS;
	    $data['barraSec']         = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                    	               <a href="#tab-0" class="mdl-layout__tab is-active">HIJOS</a>
                    	             </div>';
	    //MENU
	    $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
	    $data['apps']             = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $data['menu']             = $this->load->view('v_menu', $data, true);
	    //NECESARIO
	    $data['initVal']          = '0';
	    $data['currentPerson']    = _encodeCI($this->_idUserSess);
	    $data['tab']              = $this->buildContentTabByAlumno(array($parentesco[0]));
	    $this->session->set_userdata(array('entraFirstPadre' => 'true'));
	    ///////////
	    $this->session->set_userdata(array('tab_active_config' => null));
        $this->session->set_userdata(array('year_table' => _getYear()));
	    $this->load->view('v_pagosPrueba', $data);
	}
	
	function buildContentTabByAlumno($parentesco) {
	    $tabHTML = null;
	    $val = 0;
	    foreach($parentesco as $row){
	        $cuotasDeuda    = $this->m_movimientos->verificaDeudaByAlumno($row->nid_persona);
	        $estado         = ($cuotasDeuda > 0) ? 'moroso' : 'puntual';
	        $deudas         = (($cuotasDeuda > 0) ? $cuotasDeuda.' cuota(s) vencida(s)' : 'Al d&iacute;a');
	        $compromisos    = $this->m_pagos->getAllCompromisosByAlumno($row->nid_persona,_getYear());
	        $tbCompromisos  = $this->buildTableCompromisos($compromisos,$val);
	        $tbCalendario   = $this->buildCalendarioCompromisos($val);
	        $idPersCrypt    = _encodeCI($row->nid_persona);
	        $onclickPrev    = 'onclick="changeYear(\''._encodeCI('preview').'\',\''.$val.'\',\''.$idPersCrypt.'\')"';
	        $onclickNext    = 'onclick="changeYear(\''._encodeCI('next').'\'   ,\''.$val.'\',\''.$idPersCrypt.'\')"';
	        $nombreCompleto = $row->nombrecompleto;
	        $fotoAux        = (($row->foto_persona == null) ? 'nourse.svg' : $row->foto_persona);
	        $foto           = ((file_exists(FOTO_PROFILE_PATH . 'estudiantes/' . $fotoAux)) ?  RUTA_IMG_PROFILE.'estudiantes/'.$fotoAux : RUTA_SMILEDU.FOTO_DEFECTO);
	        $tabHTML .= '<section class="mdl-layout__tab-panel p-0 '.(($val == 0) ? 'is-active' :  null).'" id="tab-'.$val.'">
                            <div class="mdl-content-cards">
                                <div class="mdl-card">
                                    <div class="mdl-card__title" id="datos'.$val.'">
                                        <h2 class="pago '.($estado).'">Compromisos de pago</h2>
                                        <small id="deudas'.$val.'" style="display:block">'.$deudas.' </small>
                                        <small id="calendarText'.$val.'" style="display:none"></small>
                                    </div>
                                    <div class="mdl-card__supporting-text p-0 br-b">
                                        <div class="col-sm-12 p-r-0 p-l-0" id="contTbCompromisos'.$val.'" >
                                            '.($tbCompromisos).'
                                            '.($tbCalendario).' 
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <div id="fecha_options'.$val.'" class="pull-right form-inline">
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.$onclickPrev.'>
                                                <i class="mdi mdi-keyboard_arrow_left"></i>
                                            </button>
                                            <button id="year'.$val.'" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect m-0" disabled style="cursor: default !important">'._getYear().'</button>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect m-l-0" '.$onclickNext.'>
                                                <i class="mdi mdi-keyboard_arrow_right"></i>
                                            </button>
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" data-original-title="Ver calendario" id="btn-calendario'.$val.'" onclick="cambioCalendario(\''.$val.'\',\''.$idPersCrypt.'\')">
                                                <i class="mdi mdi-autorenew"></i>
                                            </button>
                                        </div>
                                        <div class=" form-inline" id="btn-group-dates'.$val.'" style="display: none">
											<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-calendar-nav="prev"><i class="mdi mdi-keyboard_arrow_left"></i></button>
                                           	<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-calendar-nav="today" data-toggle="tooltip" data-placement="bottom" data-original-title="Hoy"><i class="mdi mdi-today"></i></button>
                                           	<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-calendar-nav="next"><i class="mdi mdi-keyboard_arrow_right"></i></button>
                                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Ver listado" id="btn-list'.$val.'" onclick="changeContTableNumber(\''.$val.'\',\''.$idPersCrypt.'\',1)">
                                                <i class="mdi mdi-autorenew"></i>
                                            </button>
									    </div>
                                         
                                    </div>
                                </div>
                            </div>
                        </section>';
	        $val++;
	    }
	    return $tabHTML;
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
	        $idRol    = _simple_encrypt($var->nid_rol);
	        $return  .= "<li class='".$class."'>";
	        $return  .= '<a href="javascript:void(0)" onclick="cambioRol(\''.$idRol.'\')"><span class="title">'.$var->desc_rol.$check.'</span></a>';
	        $return  .= "</li>";
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
	    $result       .= '</ul>';
	    $data['roles'] = $result;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getCalendarioByPersona() {
		$tab                   = _post('currentTabl');
		$idPersona             = _decodeCI(_post('currentPers'));
		$fechasArray           = array();
		$data['clCompromisos'] = $this->buildCalendarioCompromisos($tab);
		$fechasArray           = $this->m_pagos->getAllFechasVencimiento($idPersona);
		$fechasArray1          = $this->m_pagos->getAllFechasDescuento($idPersona);
		$fechasArray2          = $this->m_pagos->getAllFechasPagos($idPersona);
		$arrayGeneral          = array();
		foreach ($fechasArray as $row){
			$arraySubUpdate = array('title' => "Vence la \n".utf8_encode($row->desc_detalle_crono)."",
									'start' => strtotime($row->fecha_vencimiento).'000',
					                'end'   => strtotime($row->fecha_vencimiento).'000',
			                        'class' => 'event-important');
			array_push($arrayGeneral, $arraySubUpdate);
		}
		foreach ($fechasArray1 as $row){
			$arraySubUpdate = array('title' => "Descuento para la \n".utf8_encode($row->desc_detalle_crono)."",
									'start' => strtotime($row->fechainicio).'000',
								    'end'   => strtotime($row->fecha_descuento).'000',
					                'class' => 'event-success');
			array_push($arrayGeneral, $arraySubUpdate);
		}
		foreach ($fechasArray2 as $row){
			$arraySubUpdate = array('title' => "Pago la \n".utf8_encode($row->desc_detalle_crono)."",
									'start' => strtotime($row->fecha_pago).'000',
					                 'end'  => strtotime($row->fecha_pago).'000',
									'class' => 'event-info');
			array_push($arrayGeneral, $arraySubUpdate);
		}
		$data['fecVencimiento'] = json_encode($arrayGeneral);
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildCalendarioCompromisos($tab) {
	    return '<div id="calendar'.$tab.'"></div>';
	}
	
   function getTableByPersona() {
	    $tab                   = _post('currentTabl');
	    $idPersona             = _decodeCI(_post('currentPers'));
// 	    $compromisos           = $this->m_pagos->getAllCompromisosByAlumno($idPersona,_getSesion('year_table'));
        $data                  = $this->buildDataHTML($idPersona,$tab);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTableCompromisos($compromisos,$cont = null) {
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_compromisos'.$cont.'">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0  = array('data' => '#'                  , 'class' => 'text-left');
	    $head_1  = array('data' => 'Descripci&oacute;n' , 'class' => 'text-left');
	    $head_2  = array('data' => 'Pensi&oacute;n(S/)' , 'class' => 'text-right');
	    $head_3  = array('data' => 'Mora(S/)'           , 'class' => 'text-right');
	    $head_4  = array('data' => 'Monto a Pagar(S/)'  , 'class' => 'text-right');
	    $head_5  = array('data' => 'Monto Pagado(S/)'   , 'class' => 'text-right');
	    $head_6  = array('data' => 'Pendiente(S/)'      , 'class' => 'text-right');
	    $head_7  = array('data' => 'Vencimiento'        , 'class' => 'text-center');
	    $head_8  = array('data' => 'Fecha de Pago'      , 'class' => 'text-center');
	    $head_9  = array('data' => 'Estado'             , 'class' => 'text-center' );
	    $head_10 = array('data' => 'Acci&oacute;n'      , 'class' => 'text-center' );
	    $this->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4,$head_5,$head_6,$head_7,$head_8, $head_9/*,$head_10*/);
	    $val   = 0;
	    $flg_disabled = true;
	    $onclick = null;
	    if(count($compromisos) == 0){
	        $table = '<div class="img-search">
                          <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                          <p>Ups! A&uacute;n no se han registrado datos.</p>
                      </div>';
	        return $table;
	    }
	    foreach($compromisos as $row){
	        $idButton       = 'idButton'.$val;
	        $botonDocumento = '<li class="mdl-menu__item" disabled>
	                               <i class="mdi mdi-content_copy">
	                               </i> Recibo
	                           </li>';
	        $val++;
	        $row_col0  = array('data' => $val                 , 'class' => 'text-left');
	        $row_col1  = array('data' => $row->desc_cuota     , 'class' => 'text-left');
	        $row_col2  = array('data' => $row->monto          , 'class' => 'text-right');
	        $row_col3  = array('data' => $row->mora_acumulada , 'class' => 'text-right');
	        $row_col4  = array('data' => $row->monto_final    , 'class' => 'text-right');
	        $row_col5  = array('data' => $row->monto_adelanto , 'class' => 'text-right');
	        $row_col6  = array('data' => $row->monto_pendiente, 'class' => 'text-right');
	        $row_col7  = array('data' => $row->fec_vencimiento, 'class' => 'text-center');
	        $row_col8  = array('data' => $row->fecha_pago     , 'class' => 'text-center');
	        
	        if ( $row->class == 'success'){
	            $onclick = 'style="cursor:pointer" onclick="abrirModalPasarela(\'Pasarela de Pagos: Por pagar\')"';
	        } else if ( $row->class == 'danger') {
	            $onclick = 'style="cursor:pointer" onclick="abrirModalPasarela(\'Pasarela de Pagos: Vencido\')"';
	        }
	        
	        $row_col9  = array('data' => '<span class="label label-'.$row->class.'" '.$onclick.'>'.$row->estado.'</span>','class' => 'text-center' );
	        $botones   = '<ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" id="opcionesEncuesta'.$val.'" for="'.$idButton.'">'
        	                 .$botonDocumento.
        	             '</ul>';
	        $botonGeneral = '<button id="'.$idButton.'" class="mdl-button mdl-js-button mdl-button--icon" >
                                 <i class="mdi mdi-more_vert"></i>
                             </button>
                             '.$botones;
	        $row_col10 = array('data' => $botonGeneral     , 'class' => 'text-center');
	        $this->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col4,$row_col5,$row_col6,$row_col7,$row_col8, $row_col9/*,$row_col10*/);
	    }
	    return $this->table->generate();
	}
	
	function changeYear(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $type = _decodeCI(_post('type'));
	        $tab  = _post('tab');
	        $pers = _decodeCI(_post('pers'));
	        if($type == null || $tab == null || $pers == null){
	            throw new Exception(ANP);
	        }
	        $type = ($type == 'preview') ? -1 : 1;
	        $year = _getSesion('year_table');
	        $year = intval($year) + intval($type);
	        $compromisos           = $this->m_pagos->getAllCompromisosByAlumno($pers,$year);
	        if(count($compromisos) == 0) {
	            throw new Exception('No presenta compromisos ese año');
	        }
            $data['tbCompromisos'] = $this->buildTableCompromisos($compromisos,$tab);
	        $this->session->set_userdata(array('year_table' => $year));
	        $data['year'] = $year;
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data)); 
	}
	
	function createButtonAlumnos($parientes){
	    $opcion = "";
	    $i = 0;
	    foreach ($parientes as $par) {
	        $active = $i == 0 ? 'active' : '';
	        $fotoAux     = (($par->foto_persona == null) ? 'nourse.svg' : $par->foto_persona);
	        $foto        = ((file_exists(FOTO_PROFILE_PATH . 'estudiantes/' . $fotoAux)) ?  RUTA_IMG_PROFILE.'estudiantes/'.$fotoAux : RUTA_SMILEDU.FOTO_DEFECTO);
	        $idPersCrypt = _encodeCI($par->nid_persona);
	        $opcion.= ' <span class="mdl-chip mdl-chip--contact mdl-chip--deletable '.$active.' chip-parientes" id="chip'.$i.'" onclick="changeContTableNumber(0,\''.$idPersCrypt.'\',1,\''.$i.'\')">
                            <img class="mdl-chip__contact" src="'.$foto.'">
                            <span class="mdl-chip__text">'.$par->nombrecompleto.'</span>
                            <div class="mdl-chip__action"><i class="mdi mdi-state"></i></div>
                        </span>';
	        $i++;
	    }
	    return $opcion;
	}
	
	function verDatosAlumno(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $data['noMsj'] = 2;
	    try {
	        $parentesco  = $this->m_pagos->getNombresParentescoByPersona(_getSesion('cod_familiar'),$this->_idUserSess);
	        $tipo        = _post('tipo');
	        $idPersona   = _simpleDecryptInt(_post('idPersona'));
	        $id_familiar = null;
	        if($tipo == 1){
	            $id_familiar = _post('idfamiliar') != null ? _simpleDecryptInt(_post('idfamiliar')) : null;
	        } else if ($tipo == 2){
	            $id_familiar = _post('idfamiliar') != null ? (_post('idfamiliar')) : null;
	        }
            $data['tabla'] = $this->buildContentTabByAlumno($idPersona); 
            $data['error'] = EXIT_SUCCESS;
	    } catch(Exception $e) {
	        $data['noMsj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildDataHTML($currentPers,$currentTab){
	    $compromisos           = $this->m_pagos->getAllCompromisosByAlumno($currentPers,_getSesion('year_table'));
	    $data['tbCompromisos'] = $this->buildTableCompromisos($compromisos,$currentTab);
	    $cuotasDeuda           = $this->m_movimientos->verificaDeudaByAlumno($currentPers);
	    $estado                = ($cuotasDeuda > 0) ? 'moroso' : 'puntual';
	    $nombreCompleto        = $this->m_usuario->getDatosPersona($currentPers)['nombres'];
	    $deudas                = (($cuotasDeuda > 0) ? $cuotasDeuda.' cuota(s) vencida(s)' : 'Al dia');
	    $val                   = 0;
	    $data['datos']         = '<h2 class="pago '.($estado).'">Compromisos de pago</h2>
                                  <small id="deudas'.$val.'" style="display:block">'.$deudas.' </small>';
	    return $data;
	}
}