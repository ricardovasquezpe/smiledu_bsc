<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_incidencia extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->model('m_caja');
        $this->load->library('table');
//         _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_CAJA, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }

    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
        ////Modal Popup Iconos///
        $flag=_getSesion('flg_devolucion');
        ($this->_idRol == ID_ROL_SECRETARIA) ? $data['titleHeader'] = 'Mis Incidencia' : $data['titleHeader'] = 'Incidencia';
        $data['return']           = '';
        $data['ruta_logo']        = MENU_LOGO_PAGOS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
        $data['nombre_logo']      = NAME_MODULO_PAGOS;
        
        //MENU
//         $idSecretaria             = _getSesion('id_secretaria');
        $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
        $data['apps']             = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']             = $this->load->view('v_menu', $data, true);
        $idSecretaria             = _decodeCI(str_replace(" ","+",$_GET['persona']));
        //-------------------------------------
        if($flag == null){
            $idPersona             = $this->_idUserSess;
            $persona               = $this->m_caja->getPersona($idSecretaria);
            $data['tbIncidencias'] = $this->buildContentIncidencia($persona);
            $this->load->view('v_incidencia', $data);
        }
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
        $dataUser  = array("id_rol" => $idRol,
            "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $result['url'] = base_url() . "c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }
    
    function getRolesByUsuario() {
        $idPersona = $this->_idUserSess;
        $idRol     = _getSesion('id_rol');
        $roles     = $this->m_usuario->getRolesByUsuario($idPersona, $idRol);
        $return    = null;
        foreach ($roles as $var) {
            $check = null;
            $class = null;
            if ($var->check == 1) {
                $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
                $class = 'active';
            }
            $idRol   = _simple_encrypt($var->nid_rol);
            $return .= "<li class='" . $class . "'>";
            $return .= '<a href="javascript:void(0)" onclick="cambioRol(\'' . $idRol . '\')"><span class="title">' . $var->desc_rol . $check . '</span></a>';
            $return .= "</li>";
        }
        $dataUser = array("roles_menu" => $return);
        $this->session->set_userdata($dataUser);
    }
    
    function setIdSistemaInSession() {
        $idSistema = _decodeCI(_post('id_sis'));
        $idRol     = _decodeCI(_post('rol'));
        if ($idSistema == null || $idRol == null) {
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema, $idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function enviarFeedBack() {
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        __enviarFeedBack($mensaje, $url, $nombre);
    }
    
    function mostrarRolesSistema() {
        $idSistema = _decodeCI(_post('sistema'));
        $roles     = $this->m_usuario->getRolesOnlySistem($this->_idUserSess, $idSistema);
        $result    = '<ul>';
        foreach ($roles as $rol) {
            $idRol   = _encodeCI($rol->nid_rol);
            $result .= '<li style="cursor:pointer" onclick="goToSistema(\'' . _post('sistema') . '\', \'' . $idRol . '\')">' . $rol->desc_rol . '</li>';
        }
        $result        .= '</ul>';
        $data['roles']  = $result;
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function chageTableIncidencia($idPersona){
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-show-columns="false" data-search="false" id="tb_incidencia">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#'                  , 'class' => 'text-left');
        $head_2 = array('data' => 'Fecha'              , 'class' => 'text-center');
        $head_3 = array('data' => 'Monto'              , 'class' => 'text-center');
        $head_4 = array('data' => 'Observaci&oacute;n' , 'class' => 'text-center');
        $this->table->set_heading($head_1, $head_2, $head_3, $head_4);
        $idSede = $this->m_caja->getSedeBySecretaria($idPersona);
        $datos  = $this->m_caja->getDetalleIncidencia($idPersona,$idSede);
        $val = 1;
        if(count($datos) < 1) {
            $tabla = '<div class="img-search">
                          <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                          <p>Ups! A&uacute;n no se han registrado datos.</p>
                      </div>';
	        return $tabla;
        }
        foreach ($datos as $row) {
            $row_cell_1 = array('data' => $val              , 'class' => 'text-left');
            $row_cell_2 = array('data' => $row->fecha       , 'class' => 'text-center');
            $row_cell_3 = array('data' => $row->monto_pagado, 'class' => 'text-center');
            $row_cell_4 = array('data' => $row->observacion , 'class' => 'text-center');
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4);
            $val++;
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function buildContentIncidencia($secretaria) {
        $tabHTML = null;
        $val = 0;
        $cabecera       = (($this->_idRol == ID_ROL_SECRETARIA) ? 'Mis Incidencias' : 'Incidencias');
        $tbincidencia   = $this->chageTableIncidencia($secretaria['nid_persona']);
        $tbCalendario   = $this->buildCalendarioIncidencia($val);
        $idPersCrypt    = _encodeCI($secretaria['nid_persona']);
        $nombreCompleto = $secretaria['nombre_completo'];
        $fotoAux        = (($secretaria['foto_persona'] == null) ? 'nourse.svg' : $secretaria['foto_persona']);
        $foto           = ((file_exists(FOTO_PROFILE_PATH . 'colaboradores/' . $fotoAux)) ?  RUTA_IMG_PROFILE.'colaboradores/'.$fotoAux : RUTA_SMILEDU.FOTO_DEFECTO);
        $tabHTML .= '<section class="mdl-layout__tab-panel p-0 '.(($val == 0) ? 'is-active' :  null).'" id="tab-'.$val.'">
                        <div class="mdl-content-cards">
                            <div class="mdl-card">
                                <div class="mdl-card__title" id="datos'.$val.'">
                                    <h2 class="pago '.$val.'" style="color:#009688;">'.$cabecera.' </h2>
                                    <small id="deudas'.$val.'" style="display:block"> </small>
                                    <small id="calendarText'.$val.'" style="display:none"></small>
                                </div>
                                <div class="mdl-card__supporting-text p-0 br-b">
                                    <div class="col-sm-12 p-r-0 p-l-0" id="contTbIncidencia'.$val.'" >
                                        '.($tbincidencia).'
                                        '.($tbCalendario).'
                                    </div>
                                </div>
                                <div class="mdl-card__menu">
                                    <div id="fecha_options'.$val.'" class="pull-right form-inline">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" data-original-title="Ver calendario" id="btn-calendario'.$val.'" onclick="cambiarCalendario(\''.$val.'\',\''.$idPersCrypt.'\')">
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
        return $tabHTML;
    }
    
    function buildCalendarioIncidencia($tab) {
        return '<div id="calendar'.$tab.'"></div>';
    }
    
    function getCalendario(){
        $tab                   = _post('currentTabl');
        $idPersona             = _decodeCI(_post('currentPers'));
        $fechasArray           = array();
        $sede                  = $this->m_utils->getSedeTrabajoByColaborador($idPersona);
        $data['clIncidencia']  = $this->buildCalendarioIncidencia($tab);
        $fechasArray           = $this->m_caja->getDetalleIncidencia($idPersona, $sede);
        $arrayGeneral          = array();
        foreach ($fechasArray as $row){
            $arraySubUpdate = array('title' => "Observacion: \n".utf8_encode($row->observacion)."",
                                    'start' => strtotime($row->fecha).'000',
                                    'end'   => strtotime($row->fecha).'000',
                                    'class' => 'event-important');
            array_push($arrayGeneral, $arraySubUpdate);
        }
        $data['fecIncidencia'] = json_encode($arrayGeneral);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTableByPersona() {
        $tab                   = _post('currentTabl');
	    $idPersona             = _decodeCI(_post('currentPers'));
        $data                  = $this->buildDataHTML($idPersona,$tab);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildDataHTML($currentPers,$currentTab){
	    $data['tablaIncidencia'] = $this->chageTableIncidencia($currentPers);
	    $nombreCompleto          = $this->m_usuario->getDatosPersona($currentPers)['nombres'];
        $cabecera                = (($this->_idRol == ID_ROL_SECRETARIA) ? 'Mis Incidencias' : 'Incidencias');
	    $val                     = 0;
	    $data['datos']           = '<h2 class="pago '.$val.'">'.$cabecera.'</h2>
	                                <small id="calendarText'.$val.'" style="display:none"></small>';
	    return $data;
	}
}