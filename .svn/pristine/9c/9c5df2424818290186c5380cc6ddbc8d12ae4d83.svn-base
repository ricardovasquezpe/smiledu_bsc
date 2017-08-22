<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_caja extends CI_Controller {

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
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_CAJA, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }

    public function index() {
//         $this->getDataByRol();
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
        ////Modal Popup Iconos///
        $flag=_getSesion('flg_devolucion');
        if ( $flag == null ){
            $data['titleHeader']      = 'Caja';
        } else if ( $flag == 1 ) {
            $data['titleHeader']      = 'Devoluciones';
        }
        $data['return']           = '';
        $data['ruta_logo']        = MENU_LOGO_PAGOS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
        $data['nombre_logo']      = NAME_MODULO_PAGOS;
        
        //MENU
        $data['rolSession']       = (($this->_idRol == ID_ROL_SECRETARIA) || (isset($_GET['persona']))) ? 'true' : 'false';
        $secretaria               = _getSesion('id_secretaria');
        $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
        $data['apps']             = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        if($this->_idRol == ID_ROL_SECRETARIA){
            $data['barraSec']     = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                         <a href="#miCaja" class="mdl-layout__tab is-active">Mi caja</a>
                                         <a href="#otraCaja" class="mdl-layout__tab">Apoyo</a>
                                     </div>';
        }
        $data['menu']             = $this->load->view('v_menu', $data, true);
        //NECESARIO///////////////////////////////////////////////////////////////////////
        if($this->_idRol == ID_ROL_SECRETARIA ) {
            $secretarias = $this->m_caja->getSecretariasReemplazo($this->_idUserSess);
            $data['optSecretarias']   = $this->buildComboSecretarias($secretarias);
            $fechaActual              = date('Y-m-d');
            $data['secretaria']       = _simple_encrypt($secretaria);
            $data['tbCaja']           = $this->buildTableHTMLIngresosEgresos2($this->_idUserSess,$fechaActual,$fechaActual);
            $ingresos                 = $this->m_caja->getIngresosEgresosByCaja(array(PAGAR,DEVOLVER), $fechaActual , $fechaActual, $this->_idUserSess);
            $egresos                  = $this->m_caja->getIngresosEgresosByCaja(array(RETIRAR)       , $fechaActual , $fechaActual, $this->_idUserSess);
            $data['tableEgresos']     = $this->buildTableHTMLIngresosEgresos($egresos   , 'egresos');
            $data['tableIngresos']    = $this->buildTableHTMLIngresosEgresos($ingresos  , 'ingresos');
            $data['optTipo']          = __buildComboByGrupo(COMBO_TIPO_MOVIMIENTO);
            $idSede                   = $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
            $cajaAperturada           = $this->m_caja->getCurrentCaja($idSede,$this->_idUserSess);
            $cajaCerrada              = $this->m_caja->getCajaCerrada($idSede,$this->_idUserSess);
            $data['completeApert']    = null;
            $data['completeCerrada']  = null;
            $data['fechaFiltro']      = date("d/m/Y");
            /////////////////////////////////WIZARD
            if(($cajaAperturada['estado_caja'] == APERTURADA && $cajaAperturada['id_caja'] != null && $cajaCerrada == 0) || ($cajaCerrada == 1)) {
                $data['completeApert'] = 'complete';
            }
            if(($cajaAperturada['estado_caja'] == CERRADA || $cajaAperturada['estado_caja'] == CERRADA_EMERGENCIA) && $cajaCerrada == 1){
                $data['completeCerrada'] = 'complete';
            }
            $data['width']      = ($cajaAperturada['estado_caja'] == CERRADA_EMERGENCIA) ? '50%' : (($cajaAperturada['estado_caja'] == CERRADA) ? '100%' : '0%');
            $result             = $this->m_caja->getLastCajaBySede($idSede,$this->_idUserSess);
            $data['flg_cerrar'] = ($result['estado_caja'] != CERRADA) ? APERTURADA : null;
            //CAJAS ASIGNADAS
            $cajasAsignadas         = $this->m_caja->getCajasAsignadas($secretaria);
            $data['cajasAsignadas'] = $this->buildCardsCajasAsignadas($cajasAsignadas);
            //-------------------------------------
        }
        else if (isset($_GET['persona'])) {
            $this->session->set_userdata(array('id_secre_promo' => $_GET['persona']));
            $idSecretaria             = _simple_decrypt(str_replace(" ","+",$_GET['persona']));
            $data['secretaria']       = _simple_encrypt($idSecretaria);
            $fechaActual              = date('Y-m-d');
            $data['tbCaja']           = $this->buildTableHTMLIngresosEgresos2($idSecretaria,$fechaActual,$fechaActual);
            $ingresos                 = $this->m_caja->getIngresosEgresosByCaja(array(PAGAR,DEVOLVER), $fechaActual , $fechaActual, $idSecretaria);
            $egresos                  = $this->m_caja->getIngresosEgresosByCaja(array(RETIRAR)       , $fechaActual , $fechaActual, $idSecretaria);
            $data['tableEgresos']     = $this->buildTableHTMLIngresosEgresos($egresos   , 'egresos');
            $data['tableIngresos']    = $this->buildTableHTMLIngresosEgresos($ingresos  , 'ingresos');
            $data['optTipo']          = __buildComboByGrupo(COMBO_TIPO_MOVIMIENTO);
            $idSede                   = $this->m_utils->getSedeTrabajoByColaborador($idSecretaria);
            $cajaAperturada           = $this->m_caja->getCurrentCaja($idSede,$idSecretaria);
            $cajaCerrada              = $this->m_caja->getCajaCerrada($idSede,$idSecretaria);
            $data['completeApert']    = null;
            $data['completeCerrada']  = null;
            $data['fechaFiltro']      = null;
            /////////////////////////////////WIZARD
            if(($cajaAperturada['estado_caja'] == APERTURADA && $cajaAperturada['id_caja'] != null && $cajaCerrada == 0) || ($cajaCerrada == 1)) {
                $data['completeApert'] = 'complete';
            }
            if($cajaAperturada['estado_caja'] == CERRADA && $cajaCerrada == 1){
                $data['completeCerrada'] = 'complete';
            }
            $data['width']      = ($cajaAperturada['estado_caja'] == CERRADA_EMERGENCIA) ? '50%' : (($cajaAperturada['estado_caja'] == CERRADA) ? '100%' : '0%');
            $result             = $this->m_caja->getLastCajaBySede($idSede,$idSecretaria);
            $data['flg_cerrar'] = ($result['estado_caja'] != CERRADA) ? APERTURADA : null;
            //-------------------------------------
        }
        else if($this->_idRol == ID_ROL_PROMOTOR) {
            $data['cardSecretaria']   = $this->buildCardBySecretaria();
        }
        
        //COMBOS
        $data['optSede']   = __buildComboSedes();
        $data['optCerrar'] = $this->buildComboTipoCerrado();
        $data['optInci']   = __buildComboByGrupo(COMBO_INCIDENCIA);
        //-------------------------------------
        if($flag == null){
            $this->load->view('v_caja', $data);
        } else if($flag == 1){
            $idPersona = $this->_idUserSess;
            $persona = $this->m_caja->getPersona($this->_idUserSess);
            $data['tableColaborador'] = $this->chageTableColaboradores($this->_idUserSess);
            $this->load->view('v_devolucion', $data);
            $this->session->set_userdata(array('flg_devolucion' => null));
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
    
    function buildTableHTMLIngresosEgresos2($idUsuario,$fechaInicio,$fechaFin){
        $idSede           = $this->m_utils->getSedeTrabajoByColaborador($idUsuario);
        $caja = $this->m_caja->getCurrentCaja($idSede,$idUsuario,$fechaInicio);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_caja">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1  = array('data' => 'Movimientos'       , 'class' => 'text-left');
        $head_2  = array('data' => 'Cant. Movimientos' , 'class' => 'text-right');
        $head_3  = array('data' => 'Monto'             , 'class' => 'text-right');
        $head_4  = array('data' => 'Acci&oacute;n'     , 'class' => 'text-center');
        $this->table->set_heading( $head_1, $head_2, $head_3, $head_4 );
        //INICIO
        $row_col1 = array('data' => 'Inicio'             , 'class' => 'text-left');
        $row_col2 = array('data' => '-'                  , 'class' => 'text-right');
        $row_col3 = array('data' => $caja['monto_inicio'], 'class' => 'text-right');
        $row_col4 = array('data' => '-'                  , 'class' => 'text-center');
        $this->table->add_row( $row_col1, $row_col2, $row_col3, $row_col4 );
        //INGRESOS
        $datosIng = $this->m_caja->getIngresosEgresosByCaja2($fechaInicio , $fechaFin,array(PAGAR,DEVOLVER),$idUsuario);
        $btnIng   = '<button class="mdl-button mdl-js-button mdl-button--icon" data-toggle="modal" data-target="#modalIngresos">
                        <i class="mdi mdi-remove_red_eye">
                     </button>';
        $row_col1 = array('data' => 'Ingreso'                , 'class' => 'text-left');
        $row_col2 = array('data' => $datosIng['count']       , 'class' => 'text-right');
        $row_col3 = array('data' => $datosIng['monto_pagado'], 'class' => 'text-right');
        $row_col4 = array('data' => $btnIng                  , 'class' => 'text-center');
        $this->table->add_row( $row_col1, $row_col2, $row_col3, $row_col4 );
        
        //EGRESOS
        $datosEgr = $this->m_caja->getIngresosEgresosByCaja2($fechaInicio , $fechaFin,array(RETIRAR),$idUsuario);
        $btnEgr   = '<button class="mdl-button mdl-js-button mdl-button--icon" data-toggle="modal" data-target="#modalEgresos">
                        <i class="mdi mdi-remove_red_eye">
                     </button>';
        $row_col1 = array('data' => 'Egreso'                 , 'class' => 'text-left');
        $row_col2 = array('data' => $datosEgr['count']       , 'class' => 'text-right');
        $row_col3 = array('data' => $datosEgr['monto_pagado'], 'class' => 'text-right');
        $row_col4 = array('data' => $btnEgr                  , 'class' => 'text-center');
        $this->table->add_row( $row_col1, $row_col2, $row_col3, $row_col4 );
        
        $actual   = $caja['monto_inicio'] + $datosIng['monto_pagado'] - $datosEgr['monto_pagado'];
        //FIN
        $row_col1 = array('data' => 'Fin'           , 'class' => 'text-left');
        $row_col2 = array('data' => ''              , 'class' => 'text-right');
        $row_col3 = array('data' => round($actual,2), 'class' => 'text-right');
        $row_col4 = array('data' => '-'             , 'class' => 'text-center');
        $this->table->add_row( $row_col1, $row_col2, $row_col3, $row_col4 );
        return $this->table->generate();
    }
    
    function buildTableHTMLIngresosEgresos($data,$movi){
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_'.$movi.'">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1  = array('data' => 'Descripci&oacute;n', 'class' => 'text-left');
        $head_2  = array('data' => 'Monto'             , 'class' => 'text-right');
        $head_3  = array('data' => 'Fecha'             , 'class' => 'text-center');
        $head_4  = array('data' => 'Persona'           , 'class' => 'text-left');
        $head_5  = array('data' => 'Observaci&oacute;n', 'class' => 'text-left');
        $head_6  = array('data' => 'Mod. Pago'         , 'class' => 'text-center');
        $head_7  = array('data' => 'Nro doc'         , 'class' => 'text-center');
        if($movi == 'ingresos'){
            $this->table->set_heading($head_1,$head_2,$head_3,$head_4,$head_6,$head_7);
        } else{
            $this->table->set_heading($head_1,$head_2,$head_3,$head_4,$head_5);
        }
        $val = 0;
        foreach($data as $row){
            $val++;
            $row_col1 = array('data' => $row->desc_cuota    , 'class' => 'text-left');
            $row_col2 = array('data' => $row->monto_pagado  , 'class' => 'text-right');
            $row_col3 = array('data' => $row->audi_fec_regi , 'class' => 'text-center');
            $row->nombre_persona = _ucwords(_getSesion('nombre_abvr'));
            $row_col4 = array('data' => _ucwords($row->nom_persona), 'class' => 'text-left');
            $row_col5 = array('data' => $row->observacion, 'class' => 'text-left');
            $row_col6 = array('data' => '<i class="mdi mdi-'.$row->icon_mod_pago.'"></i>', 'class' => 'text-center');
            $row_col7 = array('data' => $row->nro_doc, 'class' => 'text-center');
            if($movi == 'ingresos'){
                $this->table->add_row($row_col1,$row_col2,$row_col3,$row_col4,$row_col6,$row_col7);
            } else{
                $this->table->add_row($row_col1,$row_col2,$row_col3,$row_col4,$row_col5);
            }
        }
        if(count($data) > 0){
            $table = $this->table->generate();
            return $table;
        } else{
            $empty = '  <div class="img-search m-b-30">
                            <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                            <p>Ups! No se encontraron registros.</p>
                        </div>'; 
            return $empty;
        }
    }
    
    function buildTableHTMLCaja($arrays,$dataCaja){
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
                          'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1  = array('data' => 'Descripci&oacute;n');
        $head_2  = array('data' => 'Observacion');
        $head_3  = array('data' => 'Fecha');
        $head_4  = array('data' => 'Persona');
        $head_5  = array('data' => 'Monto');
        $head_6  = array('data' => 'Dif.');
        $head_7  = array('data' => '¿Tarjeta?');
        $head_8  = array('data' => '¿Banco?');
        $this->table->set_heading($head_1,$head_2,$head_3,$head_4,$head_7,$head_8,$head_5,$head_6);
        $val = 0;
        //FIRST ROW
        $row_col1 = array('data' => '<FONT FACE="Arial" SIZE=3>Inicio</FONT>');
        $row_col2 = array('data' => null);
        $row_col3 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$dataCaja['fecha_inicio'].'</FONT>');
        $row_col4 = array('data' => '<FONT FACE="Arial" SIZE=3>'._getSesion('nombre_usuario').'</FONT>');
        $row_col5 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$dataCaja['monto_inicio'].'</FONT>');
        $row_col6 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$dataCaja['monto_inicio'].'</FONT>');
        $row_col7 = array('data' => null);
        $row_col8 = array('data' => null);
        $this->table->add_row($row_col1,$row_col2,$row_col3,$row_col4,$row_col7,$row_col8,$row_col5,$row_col6);
        //SECOND ROW
        $row_col1 = array('data' => 'Ingresos');
        $row_col2 = array('data' => null);
        $row_col3 = array('data' => null);
        $row_col4 = array('data' => null);
        $row_col5 = array('data' => null);
        $row_col6 = array('data' => null);
        $row_col7 = array('data' => null);
        $row_col8 = array('data' => null);
        $this->table->add_row($row_col1,$row_col2,$row_col3,$row_col4,$row_col7,$row_col8,$row_col5,$row_col6);
        $monto = $dataCaja['monto_inicio'];
        //ROWS
        foreach($arrays as $data){
            foreach($data as $row){
                $row_col1 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$row->desc_cuota.'</FONT>');
                $row_col2 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$row->observacion.'</FONT>');
                $row_col3 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$row->audi_fec_regi.'</FONT>');
//                 $row_col4 = array('data' => '<FONT FACE="Arial" SIZE=3>'.(_ucwords($this->m_utils->getNombrePersona($row->_id_persona))).'</FONT>');
                $row_col4 = array('data' => '<FONT FACE="Arial" SIZE=3>'.(_ucwords($row->nom_persona)).'</FONT>');
                $row_col5 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$row->monto_pagado.'</FONT>');
                ($val == 0 && $row->flg_visa == 0 && $row->flg_lugar_pago == FLG_COLEGIO) ? $monto = $monto+$row->monto_pagado : (($val == 1 && $row->flg_visa == 0) ? $monto = $monto-$row->monto_pagado : $monto = $monto + 0);
                $row_col6 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$monto.'</FONT>', 'class' => 'text-right');
                $row_col7 = array('data' => ($row->flg_visa  == 0) ? 'No' : 'Sí');
                $row_col8 = array('data' => ($row->flg_lugar_pago == FLG_COLEGIO) ? 'No' : 'Sí');
                $this->table->add_row($row_col1,$row_col2,$row_col3,$row_col4,$row_col7,$row_col8,$row_col5,$row_col6);
            }
            $row_col1 = array('data' => ($val == 0) ? '<FONT FACE="Arial" SIZE=3>Egresos</FONT>' : '<FONT FACE="Arial" SIZE=3>Cierre</FONT>');
            $row_col2 = array('data' => ($val == 0) ? null : null);
            $row_col3 = array('data' => ($val == 0) ? null : '<FONT FACE="Arial" SIZE=3>'.$dataCaja['fecha_cierre'].'</FONT>');
            $row_col4 = array('data' => ($val == 0) ? null : null);
            $row_col5 = array('data' => ($val == 0) ? null : null);
            $row_col6 = array('data' => ($val == 0) ? null : '<FONT FACE="Arial" SIZE=3>'.$monto.'</FONT>');
            $row_col7 = array('data' => null);
            $row_col8 = array('data' => null);
            $this->table->add_row($row_col1,$row_col2,$row_col3,$row_col4,$row_col7,$row_col8,$row_col5,$row_col6);
            $val++;
        }
        return $this->table->generate();
    }
    
    function createPdfDocument(){
        $this->load->library('m_pdf');
        $idUsuario   = $this->_idUserSess;
        $fechaInicio = $_POST['fechaInicioForm'];
        $fechaFin    = $_POST['fechaFinForm'];
        $idSede      = $this->m_utils->getSedeTrabajoByColaborador($idUsuario);
        $ingresos    = $this->m_caja->getIngresosEgresosByCaja(array(PAGAR,DEVOLVER),$fechaInicio,$fechaFin,$idUsuario);
        $egresos     = $this->m_caja->getIngresosEgresosByCaja(array(RETIRAR),$fechaInicio,$fechaFin,$idUsuario);
        $dataCaja    = $this->m_caja->getCurrentCaja($idSede,$idUsuario,$fechaInicio);
        $html        = $this->buildTableHTMLCaja(array($ingresos,$egresos),$dataCaja);
        $pdf              = $this->m_pdf->load('','', 0, '', 15, 15, 16, 16, 9, 9, 'L');
        $pdf->SetFooter('|{PAGENO}|'.date('d/m/Y h:i:s a'));
        $data['cabecera'] = 'Fecha: '.date('d/m/Y').'<p style="margin-left:400px;margin-top:-50px;text-decoration: underline;font-size:15px"></p>
	                         <img src="../smiledu/public/general/img/logos_colegio/avantgardLogo.png" width="80" height="80" style="margin-bottom:20px;margin-left:600px;margin-top:-30px" /><br/><br/>';
        $data['html']     = $html;
        $data['pdfObj']   = $pdf;
        $data['name']     = 'caja.pdf';
        $this->load->view('v_pdf_download',$data);
    }
    
    function borrarPDF(){
        $imagen = $this->input->post('ruta');
        if(file_exists($imagen)) {
            $imagen = './'.$this->input->post('ruta');
            if (!unlink($imagen)){
                echo ("No se borró el archivo $imagen");
            }else{
                echo ("Se borró $imagen");
            }
        }
        echo null;
    }
    
    function refreshCajaByDates(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $fechaInicio = _post('fechaInicio');
            $fechaFin    = _post('fechaFin');
            $idUsuario   = (($this->_idRol == ID_ROL_PROMOTOR)) ? _simple_decrypt(_getSesion('id_secre_promo')) : $this->_idUserSess;
            $idSede      = $this->m_utils->getSedeTrabajoByColaborador($idUsuario);
            $idSedeAux   = (($idSede != null) ? $idSede : $this->m_utils->getSedeTrabajoByColaborador(_simple_decrypt(_getSesion('id_secre_promo'))));
            if($fechaInicio == null && $fechaFin == null){
                throw new Exception('Seleccione las fechas');
            }
            if($idSedeAux == null){
                throw new Exception(ANP);
            }
            $data['tbCaja'] = (($this->_idRol == ID_ROL_PROMOTOR) ? $this->buildTableHTMLIngresosEgresos2(_simple_decrypt(_getSesion('id_secre_promo')),$fechaInicio,$fechaFin) : $this->buildTableHTMLIngresosEgresos2($idUsuario,$fechaInicio,$fechaFin));
            $datosIng       = $this->m_caja->getIngresosEgresosByCaja(array(PAGAR,DEVOLVER), $fechaInicio , $fechaFin, $idUsuario);
            $datosEgr       = $this->m_caja->getIngresosEgresosByCaja(array(RETIRAR)       , $fechaInicio , $fechaFin, $idUsuario);
            $data['tbIngresos']  = $this->buildTableHTMLIngresosEgresos($datosIng,'ingresos');
            $data['tbEgresos']   = $this->buildTableHTMLIngresosEgresos($datosEgr,'egresos');
            $data['fechaFiltro'] = $fechaInicio.' - '.$fechaFin;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function cerrarCaja(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idUsuario       = $this->_idUserSess;
            $idSede          = $this->m_utils->getSedeTrabajoByColaborador($idUsuario);
            $tipoCerrado     = _simple_decrypt(_post('tipo'));
            $persSolicitar   = _decodeCI(_post('persona'));
            $observacion     = utf8_decode(trim(_post('observacion')));
            $tipoIncidencia  = _simpleDecryptInt(_post('tipo_incidencia'));
            $secretarias     = _post('secretarias');
            //Movimiento
            $montoIncidencia = _post('monto');
            if($this->_idRol != ID_ROL_SECRETARIA){
                throw new Exception(ANP);
            }
            if($idSede == null){
                throw new Exception(ANP);
            }
            $caja = $this->m_caja->getCurrentCaja($idSede,$this->_idUserSess);
            if(!isset($caja['id_caja']) || $caja['id_caja'] == CERRADA){
                throw new Exception('Esta caja ya fue cerrada');
            }
            if($tipoCerrado == null){
                throw new Exception('Seleccione un tipo');
            }
            if($secretarias > 0 && $tipoCerrado == 1 && $persSolicitar == null){
                throw new Exception('Selecciona un apoyo');
            }
            if($tipoIncidencia == null){
                throw new Exception('Selecciona un tipo de incidencia');
            }
            //MONTO
            if($tipoIncidencia != SIN_INCIDENCIA && $montoIncidencia <= 0){
                throw new Exception('Ingresa un monto valido1');
            }
            if($tipoIncidencia != SIN_INCIDENCIA && !is_numeric($montoIncidencia)){
                throw new Exception('Ingresa un monto valido2');
            }
            if($tipoIncidencia != SIN_INCIDENCIA && $montoIncidencia >= 1000000){
                throw new Exception('La cuota de ingeso debe ser menor que 1000000');
            }
            if($tipoIncidencia != SIN_INCIDENCIA && filter_var($montoIncidencia, FILTER_VALIDATE_FLOAT) === false){
                throw new Exception('Solo Numeros en Monto');
            }
            //OBSERVACION
            if($tipoIncidencia != SIN_INCIDENCIA && $observacion == null){
                throw new Exception('Ingresa una observaci&oacute;n');   
            }
            //INSERT AUDITORIA CAJA
            $idPersSession = $this->_idUserSess;
            $nombPersSess  = _getSesion('nombre');
            $correlativo   = $this->m_caja->getNextCorrelativoByAudiDoc($caja['id_caja']);
            $arrayInsert   = array('_id_caja'       => $caja['id_caja'],
                                   'correlativo'    => $correlativo,
                                   'accion'         => CERRAR,
                                   'id_pers_regi'   => $idPersSession,
                                   'audi_nomb_regi' => $nombPersSess
                                  );
            $arrayInsertMovi = array();
            $arrayInserAudiMovi = array();
            if($tipoIncidencia != SIN_INCIDENCIA){
                //ARRAY INSERTAR EN MOVIMIENTO
                $arrayInsertMovi = array('tipo_movimiento' => (($tipoIncidencia == PERDIDA) ? MOV_EGRESO : MOV_INGRESO),
                                         'monto'           => $montoIncidencia,
                                         'monto_final'     => $montoIncidencia,
                                         'estado'          => ESTADO_RETIRADO,
                                         'fecha_registro'  => date('Y-m-d H:i:s'),
                                         '_id_persona'     => $this->_idUserSess,
                                         '_id_concepto'    => (($tipoIncidencia == PERDIDA) ? PERDIDA : REPOSICION),
                                         'flg_incidencia'  => 1
                );
                //ARRAY INSERTAR EN AUDI MOVIMIENTO
                $arrayInserAudiMovi = array('correlativo'    => 1,
                                            'id_pers_regi'   => $this->_idUserSess,
                                            'audi_nomb_regi' => _getSesion('nombre_completo'),
                                            'accion'         => (($tipoIncidencia == PERDIDA) ? RETIRAR : DEVOLVER),
                                            'observacion'    => $observacion,
                                            '_id_sede'       => $idSede,
                                            'monto_pagado'   => $montoIncidencia,
                                            '_id_caja'       => $caja['id_caja']
                                           );
            }
            if($tipoIncidencia != SIN_INCIDENCIA){
                $data = $this->m_caja->saveIncidencia($arrayInsertMovi,$arrayInserAudiMovi);
            } else{
                $data['error'] = EXIT_SUCCESS;
            }
            $flujo = $this->m_caja->getDataCierreCaja($caja['id_caja']);
            $monto = (isset($flujo[0])) ? $flujo[0]->monto_inicio : 0;
            $estado_caja = ($tipoCerrado == 1) ? CERRADA_EMERGENCIA : CERRADA;
            foreach($flujo as $row){
                $monto = ($row->accion == PAGAR && $row->flg_visa == '0') ? ($monto + $row->monto_pagado) : (($row->accion == RETIRAR) ? ($monto - $row->monto_pagado) : $monto);
            }
            //UPDATE CAJA
            $arrayUpdate  = array('id_caja'           => $caja['id_caja'],
                                  'estado_caja'       => $estado_caja,
                                  'monto_fin'         => $monto,
                                  'fecha_cierre'      => date('Y-m-d H:i:s')
            );
            if($tipoCerrado == 1) {
                $arrayUpdate['id_pers_reemplazo'] = $persSolicitar;
            }
            if($data['error'] == EXIT_SUCCESS){
                $data = $this->m_caja->cerrarCaja($arrayUpdate,$arrayInsert);
            }
            if($data['error'] == EXIT_SUCCESS){
                $data['clase'] = ($estado_caja == CERRADA) ? 'complete' : null;
                $data['width'] = ($estado_caja == CERRADA) ? '100%'     : '50%';
                //REFRESCAR TABLAS INGRESO Y EGRESO
                $fechaActual   = date('Y-m-d');
                $data['tbCaja'] = $this->buildTableHTMLIngresosEgresos2($idUsuario,$fechaActual,$fechaActual);
                $datosIng       = $this->m_caja->getIngresosEgresosByCaja(array(PAGAR,DEVOLVER), $fechaActual , $fechaActual,$idUsuario);
                $datosEgr       = $this->m_caja->getIngresosEgresosByCaja(array(RETIRAR)       , $fechaActual , $fechaActual,$idUsuario);
                $data['tbIngresos'] = $this->buildTableHTMLIngresosEgresos($datosIng,'ingresos');
                $data['tbEgresos'] = $this->buildTableHTMLIngresosEgresos($datosEgr,'egresos');
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function redirectDevoluciones(){
    	$this->session->set_userdata(array('flg_devolucion' => 1));
    	echo null;
    }
    
    function getTableColaboradores(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede    = empty($this->input->post('idSede')) ? null : _decodeCI($this->input->post('idSede'));
            if($idSede == null) {
                $data['error']    = EXIT_ERROR;
                throw new Exception(ANP);
            }
            $data ['tableColaborador']= $this->chageTableColaboradores($idSede);
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function chageTableColaboradores($idPersona){
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-show-columns="false" data-search="true" id="tb_egresos">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Registrado por'            , 'class' => 'text-center');
        $head_2 = array('data' => 'Asignado a'                , 'class' => 'text-center');
        $head_3 = array('data' => 'Descripci&oacute;n'        , 'class' => 'text-left');
        $head_4 = array('data' => 'Fecha Egreso'              , 'class' => 'text-center');
        $head_5 = array('data' => 'Fecha de Devoluci&oacute;n', 'class' => 'text-center');
        $head_6 = array('data' => 'Monto Prestado'            , 'class' => 'text-right');
        $head_7 = array('data' => 'Monto Devuelto'            , 'class' => 'text-right');
        $head_8 = array('data' => 'Acci&oacute;n'             , 'class' => 'text-center');
        $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7,$head_8);
        $idSede = _getSesion('id_sede_trabajo');
        $datos = $this->m_caja->getDetalleColaborador($idPersona,$idSede);
        foreach ($datos as $row) {
            $colaborador = $this->m_caja->getColaborador($row->_id_persona);
            $encrypt = _encodeCI($row->id_movimiento);
            $fotoRegistro = (file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_pers_regi))   ?  RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_pers_regi : RUTA_SMILEDU.FOTO_DEFECTO;
            $imgRetiro    = '<img class="img-circle" width="25" height="25" src="'.$fotoRegistro.'" data-toggle="tooltip" data-placement="bottom" title="'.$row->nombre_completo_retiro.'">';
            $fotoRetiro   = (file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_pers_retiro)) ?  RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_pers_retiro : RUTA_SMILEDU.FOTO_DEFECTO;
            $imgRegi      = '<img class="img-circle" width="25" height="25" src="'.$fotoRetiro.'" data-toggle="tooltip" data-placement="bottom" title="'.$row->nombre_completo_regi.'">';
            $row_cell_1 = array('data' => $imgRegi, 'class' => 'text-center');
            $row_cell_2 = array('data' => $imgRetiro, 'class' => 'text-center');
            $row_cell_3 = array('data' => $row->desc_concepto, 'class' => 'text-left');
            $row_cell_4 = array('data' => $row->fecha_registro, 'class' => 'text-center');
            $row_cell_5 = array('data' => $row->fecha_registro, 'class' => 'text-center');
            $row_cell_6 = array('data' => $row->monto_final, 'class' => 'text-right');
            $row_cell_7 = array('data' => $row->devuelto, 'class' => 'text-right');
            $row_cell_8 = array('data' => ($row->devolucion == 1) ? '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                                                                             <input type="checkbox" class="mdl-switch__input" checked disabled>
                                                                             <span class="mdl-switch__label"></span>
                                                                    </label>' : '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                                                                                    <input type="checkbox" class="mdl-switch__input" onclick="openModalDevolver(\''.$encrypt.'\', \''.$colaborador.'\', $(this), \''.$row->monto_final.'\');">
                                                                                    <span class="mdl-switch__label"></span>
                                                                                </label>', 'class' => 'text-center');
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5,  $row_cell_6, $row_cell_7,$row_cell_8);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function cambiarEstado(){
    	$data['error']  = EXIT_ERROR;
    	$data['msj']    = null;
    	$idSede         = _getSesion('id_sede_trabajo');
    	$id_movimiento  = empty($this->input->post('id_movimiento')) ? null : _decodeCI($this->input->post('id_movimiento'));
    	$monto          = $this->input->post('monto');
        $montoGlobal          = $this->input->post('montoGlobal');
    	$observacion    = $this->input->post('observacion');
    	$idPersonaR     = $this->_idUserSess;
    	
        
    	try{
    		if(empty($observacion)){
    			throw new Exception('Ingrese una Observacion');
    		}
    		if(empty($monto)){
    			throw new Exception('Ingrese una monto');
    		}
    		if($monto <= 0){
    			throw new Exception('Debe ser un numero positivo');
    		}
    		if(filter_var($monto, FILTER_VALIDATE_FLOAT) === false){
    			throw new Exception('Solo Numeros en Monto');
    		}
    		if($monto > $montoGlobal){
    			throw new Exception('El monto debe ser menor que '.$montoGlobal);
    		}
            $personaR  = $this->m_caja->getPersona($idPersonaR);
       		$idPerC    = $this->m_caja->getIdPersona($id_movimiento);
        	$caja      = $this->m_caja->getCurrentCaja($idSede,$this->_idUserSess);
            if($caja['id_caja'] == null || $caja['estado_caja'] == CERRADA){
                throw new Exception('Tu caja no esta disponible');
            }
        	$arrayInsertMov = array('tipo_movimiento' => 'INGRESO',
                                	'monto'           => $monto,
                               		 'estado'          => 'DEVUELTO',
                                	'fecha_pago'      => date('Y-m-d h:m:s'),
                               		 '_id_concepto'    => 2,
                                	'observacion'     => $observacion,
                                	'_id_persona'     => $idPerC);
        
        	$arrayInsertAud = array('correlativo'     => 1,
                                	'id_pers_regi'    => $idPersonaR,
                                	'audi_nomb_regi'  => $personaR['nombre_completo'],
                                	'audi_fec_regi'   => date('Y-m-d h:m:s'),
                                	'accion'          => 'DEVOLVER',
                                	'observacion'     => $observacion,
                                	'monto_pagado'    => $monto,
                                	'_id_caja'        => $caja['id_caja'],
                                	'_id_sede'        => $personaR['id_sede_control'],
                                	'id_devolucion'   => $id_movimiento);
    	$data                      = $this->m_caja->insertDevolver($arrayInsertMov, $arrayInsertAud);
    	$data ['tableColaborador'] = $this->chageTableColaboradores($idPersonaR);
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function aperturarCaja(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idSede    = $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
            $result    = $this->m_caja->getLastCajaBySede($idSede,$this->_idUserSess);
            if($result['fecha_inicio'] != null && $result['fecha_inicio'] == $result['actual']){
                throw new Exception('Ya has aperturado tu caja');
            }
            if($idSede == null){
                throw new Exception(ANP);
            }
            $lastMonto   = $result['monto_fin'];
            $arrayInsert = array('desc_caja'    => 'Caja '.date('d/m/Y'),
                                 'estado_caja'  => APERTURADA,
                                 '_id_sede'     => $idSede,
                                 'monto_inicio' => $lastMonto,
                                 'id_pers_caja' => $this->_idUserSess
                                );
            $data = $this->m_caja->accionCaja($arrayInsert,INSERTA);
            if($data['error'] == EXIT_SUCCESS){
                $fechaActual    = date('Y-m-d');
                $data['tbCaja'] = $this->buildTableHTMLIngresosEgresos2($this->_idUserSess,$fechaActual,$fechaActual);
                $data['clase']  = 'complete';
            }
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildComboTipoCerrado(){
        $combo = $this->m_utils->getComboTipoByGrupo(COMBO_TIPO_CERRADO, 'orden');
        $opcion = null;
        foreach ($combo as $row){
            $opcion .= '<option value="'._simple_encrypt($row->valor).'" data-flg="'.(($row->valor == 1) ? '1' : '0').'" >'.$row->desc_combo.'</option>';
        }
        return $opcion; 
    }
    
    function buildComboSecretarias($secretarias){
        $option = null;
        foreach($secretarias as $secre){
            $idEncry = _encodeCI($secre->nid_persona);
            $option .= '<option value="'.$idEncry.'">'.$secre->nombre_completo.'</option>';
        }
        return $option;
    }
    
    function buildCardBySecretaria(){
        $tabHTML            = null;
        $val                = 0;
        $secretariasConCaja = $this->m_caja->getSecretariasConCaja();
        foreach($secretariasConCaja as $row){
            $val++;             
            $nombreCompleto = $row->nombre.', '.$row->apellidos;
            $foto    = (file_exists(FOTO_PROFILE_PATH . 'colaboradores/' . $row->foto_persona)) ?  RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_persona : RUTA_SMILEDU.FOTO_DEFECTO;
            $tabHTML .= '<div class="mdl-card mdl-collaborator">            
                             <div class="mdl-card__title">
                                 <img alt="collaborator" src="'.RUTA_IMG.'profile/nouser.svg" id="fotoFamiliar1" class="mdl-img" style="margin: auto;">
                             </div>
                             <div class="mdl-card__supporting-text">
                                 <div class="row p-0 m-0">
                                 <div class="col-xs-12 collaborator-lastname">'.$row->apellidos.'</div>
                                 <div class="col-xs-12 collaborator-name">'.$row->nombre.'</div>
                                 <div class="col-xs-12 collaborator-rol">'.$row->desc_rol.'</div>
                                 <div class="col-xs-12 collaborator-head"><strong>Detalles del colaborador</strong></div>
                                 <div class="col-xs-3  collaborator-item">Sede</div>
                                 <div class="col-xs-9  collaborator-value">'.$row->desc_sede.'</div>
                                 <div class="col-xs-3  collaborator-item">Tel&eacute;fono</div>
                                 <div class="col-xs-9  collaborator-value"> '.$row->telf_pers.' </div>
                                 <div class="col-xs-3  collaborator-item">Correo</div>
                                 <div class="col-xs-9  collaborator-value"> '.$row->correo_pers.' </div>
                             </div>
                         </div>
                         <div class="mdl-card__actions">
                             <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="detalleCaja(\''._simple_encrypt($row->nid_persona).'\')">IR A CAJA</button>
                         </div>
                         <div class="mdl-card__menu">
                             <button id="collaborator-'.$row->nid_persona.'" class="mdl-button mdl-js-button mdl-button--icon">
                                 <i class="mdi mdi-more_vert"></i>
                            </button>
                         </div>
                     </div>';
        }
        return $tabHTML;
    }
    
    function redirectIncidencias () {
	    $data = null;
	    $idPersona  = _simple_decrypt(_post('persona'));
	    $data['url'] = ($idPersona != null) ? RUTA_SMILEDU.'pagos/c_incidencia?persona='._encodeCI($idPersona) : null;
	    echo json_encode(array_map('utf8_encode', $data));
    }
    
    function redirectMisIncidencias () {
        $data = null;
        $idPersona  = _simple_decrypt(_post('persona'));
        $data['url'] = ($idPersona != null) ? RUTA_SMILEDU.'pagos/c_incidencia?persona='._encodeCI($idPersona) : null;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildWizarByTipo(){
        $wizardHTML = '<div class="form-wizard form-wizard-horizontal" id="rootwizard1">
                            <div class="form-wizard-nav">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-primary" Id ="progressBar"></div>
                                </div>
                                <ul class="nav nav-justified nav-pills">
                                    <li class="active m-b-20" id="li1">
                                        <a data-toggle="tab" aria-expanded="true" href="#tab1" id="step1" onclick="progressBarByStep(1)">
                                            <span class="step"></span>
                                            <span class="title">Familiares</span>
                                        </a>
                                    </li>
                                    <li class="" id="li2" class ="my-link">
                                        <a data-toggle="tab" aria-expanded="false" href="#tab2" class ="my-link" id="step2" onclick="progressBarByStep(2)">
                                            <span class="step"></span>
                                            <span class="title" >Postulantes</span>
                                        </a>
                                    </li>
                                    <li class="" id="li3" class ="my-link">
                                        <a data-toggle="tab" aria-expanded="false" href="#tab3" class ="my-link" id="step3" onclick="progressBarByStep(3)">
                                            <span class="step"></span>
                                            <span class="title">T&eacute;rminos y condiciones</span>
                                        </a>
                                    </li>
                                    <li class="" id="li4" class ="my-link">
                                        <a data-toggle="tab" aria-expanded="false" href="#tab4"  class ="my-link" id="step4" onclick="progressBarByStep(4)">
                                            <span class="step"></span>
                                            <span class="title">Evento</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
						</div>';
        return $wizardHTML;
    }
    
    function buildCardsCajasAsignadas($result){
        $cards = null;
        $val   = 0;
        foreach($result as $row){
            $idCajaCrypt = _encodeCI($row->id_caja);
            $foto = ($row->flg_foto == 'persona') ? RUTA_SMILEDU.'public/general/img/profile/'.$row->foto_persona.'' : $row->foto_persona; 
            $cards .= ' <div class="mdl-card mdl-student '.(($row->flg_acepta == '0') ? 'part-disabled' : null).'">
                            <div class="mdl-card__title">
                                <img alt="Student" class="mdl-img" src="'.$foto.'">
                            </div>
                            <div class="mdl-card__supporting-text pago puntual">
                                <div class="row p-0 m-0">
                                    <div class="col-xs-12 student-name">'.$row->apellidos.'</div>
                                    <div class="col-xs-12 student-name">'.$row->nombres.'</div>
                                    <div class="col-xs-12 student-head"><strong>Detalles del Caja:</strong></div>
                                    <div class="col-xs-6  student-item">Sede</div>
                                    <div class="col-xs-6  student-value">'.$row->desc_sede.'</div>
                                    <div class="col-xs-6  student-item">Monto Inicio</div>
                                    <div class="col-xs-6  student-value">'.$row->monto_inicio.'</div>
                                    <div class="col-xs-6  student-item" style="overflow:inherit">Monto Actual</div>
                                    <div class="col-xs-6  student-value">'.$row->monto_fin.'</div>
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" style="background-color: red; margin-left:0px;"onclick="acptarRechazarCaja(0,\''.$idCajaCrypt.'\')">RECHAZAR</button>
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised " style="margin-left:0px; padding-right:19px; padding-left:19px;"  onclick="acptarRechazarCaja(1,\''.$idCajaCrypt.'\')">ACEPTAR</button>
                            </div>
                            <div class="mdl-card__menu">
                                <button id="pago'.$val.'" class="mdl-button mdl-js-button mdl-button--icon" onclick="";>
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="pago'.$val.'">
                                    <li class="mdl-menu__item"><i class="mdi mdi-edit"></i>Opcion</li>
                                </ul>
                            </div>
                        </div>';
            $val++;
        }
        return $cards;
    }
    
    function aceptaRechazaCaja(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idCaja   = _decodeCI(_post('caja'));
            $flg_caja = _post('flg');
            if($idCaja == null){
                throw new Exception(ANP);
            }
            if($flg_caja != 0 && $flg_caja != 1){
                throw new Exception(ANP);
            }
            $arrayUpdate = array('estado_caja' => ($flg_caja == 1) ? REAPERTURADA : CERRADA_EMERGENCIA,
                                 'flg_acepta'  => $flg_caja
            );
            $data = $this->m_caja->updateCaja($idCaja,$arrayUpdate);
            if($data['error'] == EXIT_SUCCESS){
                $fechaActual    = date('Y-m-d');
            }
        } catch (Exception $e){
            
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
}
