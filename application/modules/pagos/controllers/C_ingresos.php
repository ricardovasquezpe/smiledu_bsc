<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class C_ingresos extends CI_Controller {
    
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
        $this->load->model('m_movimientos');
        $this->load->model('m_reportes');
        $this->load->model('m_caja');
        $this->load->model('m_utils');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, null, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
    
	public function index() {
	    $tabActive           = (_getSesion('tab_active_movi') == null || _getSesion('tab_active_movi') == '' || _getSesion('tab_active_movi') == 'tab-1') ? 'tab-1' : 'tab-2';
	    $data['tabActive']   = $tabActive;
	    $data['barraSec']    = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                    <a href="#tab-1" class="mdl-layout__tab '.(($tabActive == 'tab-1') ? 'is-active' : null).'" onclick="changeDataTarget(\'#modalIngresos\')">Ingresos</a>
                                    <a href="#tab-2" class="mdl-layout__tab '.(($tabActive == 'tab-2') ? 'is-active' : null).'" onclick="changeDataTarget(\'#modalEgresos\')">Egresos</a>
                                </div>';
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
	    ////Modal Popup Iconos///
	    $data['return']           = '';
	    $data['titleHeader']      = 'Ingresos';
	    $data['ruta_logo']        = MENU_LOGO_PAGOS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
	    $data['nombre_logo']      = NAME_MODULO_PAGOS;
	    //NECESARIO//////////////////////////////////////////////////////////////////
	    $data['barraSec']         = '';
	    
	    $idPersonaMov     = _decodeCI(str_replace(" ","+", $_GET['persona']));
	    $parentesco       = $this->m_movimientos->getNombresParentescoByPersona($idPersonaMov);
	    $val = 0;
	    $data['parientes'] = $this->createButtonAlumnos($parentesco);
	    foreach($parentesco as $row){
	        $cantCompromisos = $this->m_movimientos->getCountCompromisosPorPagarVencidosByPersona($row->nid_persona);
	        ($val == 0 ) ? $data['totalComp'] = $cantCompromisos : null;
	        $idPersCrypt = _encodeCI($row->nid_persona);
	        $val++;
//          $data['barraSec'] .= '<a href="#tab-'.$val .'" onclick="changeContTableNumber(\''.$val.'\',\''.$idPersCrypt.'\','.$cantCompromisos.')" class="mdl-layout__tab '.($row->principal == 'true' ? 'is-active' : null).'">'.$row->nom_persona.'</a>';
	    }
	    //MENU
	    $rolSistemas           = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
	    $data['apps']            = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $data['menu']          = $this->load->view('v_menu', $data, true);
	    //////
	    $data['optConceptos']  = __buildComboConceptosByTipo(MOV_INGRESO);
	    $data['initVal']       = '1';
	    $data['currentPerson'] = "'".str_replace(" ","+", $_GET['persona'])."'";
	    $primeHijo             = isset($parentesco[0]) ? array($parentesco[0]) : array();
	    $data['tab']           = $this->buildContentTabByAlumno($idPersonaMov,$primeHijo);
	    $promociones           = $this->m_movimientos->getPromocionesActivas();
	    $data['promociones']   = $this->getPromociones($promociones);
	    /////////////////////////////////////////////////////////////////////////////
	    $this->session->set_userdata(array('tab_active_config' => null));
	    $this->load->view('v_ingresos', $data);
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
	
	function buildTableCompromisos($compromisos,$cont = null){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_compromisos'.$cont.'">',
	                                   'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $cabecera = (($this->_idRol == ID_ROL_SECRETARIA) ? '#' : null);
	    $head_0  = array('data' => $cabecera           ,'class' => 'text-center');
	    $head_1  = array('data' => 'Descripci&oacute;n');
	    $head_2  = array('data' => 'Monto(S/)'         , 'class' => 'text-right');
	    $head_3  = array('data' => 'Desc.(S/)    '     , 'class' => 'text-right');
	    $head_4  = array('data' => 'Mora(S/)'          , 'class' => 'text-right');
	    $head_5  = array('data' => 'Vencimiento'       , 'class' => 'text-center');
	    $head_6  = array('data' => 'Mon. a Pagar(S/)'  , 'class' => 'text-right');
	    $head_7  = array('data' => 'Fec. Pago'         , 'class' => 'text-center');
	    $head_8  = array('data' => 'Estado'            , 'class' => 'text-center');
	    $head_9  = array('data' => 'Acciones'          , 'class' => 'text-center');
	    $head_10 = array('data' => 'Fec. Desc.'        , 'class' => 'text-center');
	    $head_11 = array('data' => 'Mon. Pagado(S/)'   , 'class' => 'text-right');
	    $head_12 = array('data' => 'Fec. Regis.'       , 'class' => 'text-center');
	    $head_13 = array('data' => 'Lugar Pago'        , 'class' => 'text-center'); 
	    $this->table->set_heading($head_0,$head_1,$head_2,$head_12,$head_10,$head_3,$head_5,$head_4,$head_11,$head_6,$head_7, $head_13, $head_8, $head_9);
	    $orden = 0;
	    $val   = 0;
	    $disabled = 'false';
	    $flg_disabled = true;
	    foreach($compromisos as $row){
	        $idMovCrypt = _encodeCI($row->id_movimiento);
	        $val++;
	        $isCrono = ($row->_id_concepto == CONCEPTO_SERV_ESCOLAR) ? '1' : '0';
	        if(($row->_id_concepto == CONCEPTO_SERV_ESCOLAR || $row->_id_concepto == CUOTA_INGRESO) && $row->flg_inicio != 'begin' && $disabled == 'false' && ($row->estado == ESTADO_POR_PAGAR || $row->estado == ESTADO_VENCIDO)){
	            $disabled = 'true';
	        }
	        $icon_check = ($row->estado == ESTADO_PAGADO) ? '<a><i class="mdi mdi-lock"></i></a>' 
	                                                      : '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkComp'.$cont.'-'.$val.'">
                                                                 <input type="checkbox" '.(($row->_id_concepto == CONCEPTO_SERV_ESCOLAR && $disabled == 'true' && $row->flg_inicio != 'begin') ? 'disabled' : null).' attr-id_movi="'.$idMovCrypt.'" id="checkComp'.$cont.'-'.$val.'" 
                                                                        attr-crono="'.$isCrono.'"  attr-id_crono="'._simple_encrypt($row->_id_cronograma).'"  attr-orden="'.$orden.'" attr-tabla="tb_compromisos'.$cont.'" attr-card="card-'.$cont.'" 
                                                                        onclick="addRemoveToArray($(this)'.(($row->_id_concepto == CONCEPTO_SERV_ESCOLAR || $row->_id_concepto == CUOTA_INGRESO) ? ',true' : ',false').'); 
                                                                        assignItem(this.id, \'tb_compromisos'.$cont.'\', \'card-'.$cont.'\');" attr-monto="'.$row->monto_final.'" class="mdl-checkbox__input" >
                                                             </label>';
	        $check      = (($this->_idRol == ID_ROL_SECRETARIA) ? $icon_check : null);
	        $row_col0   = array('data' => $check );
	        $row_col1   = array('data' => $row->desc_cuota);
	        $row_col2   = array('data' => $row->monto               , 'class' => 'text-center');
	        $row_col3   = array('data' => $row->descuento_acumulado , 'class' => 'text-right');
	        $row_col4   = array('data' => $row->mora_acumulada      , 'class' => 'text-right');
	        $row_col5   = array('data' => $row->fec_vencimiento     , 'class' => 'text-center');
	        $row_col6   = array('data' => $row->monto_final         , 'class' => 'text-right');
	        $row_col7   = array('data' => $row->fecha_pago          , 'class' => 'text-center');
	        $metodo     = (($this->_idRol == ID_ROL_SECRETARIA) ? 'openModalRegistrarPago(\''.$idMovCrypt.'\',true,\''.$row->monto_final.'\')' : null);
	        $onclick1    = ($row->estado == ESTADO_PAGADO) ? null : 'onclick="'.$metodo.'"';
	        $row_col8   = array('data' => '<span  style="padding-left: 7px;cursor:pointer" class="label label-'.$row->class.'"  '.(($row->_id_concepto == CONCEPTO_SERV_ESCOLAR && $disabled == 'true') ? null : $onclick1).' style="cursor:pointer">'.$row->boleta_icon.$row->estado.'</span>','class' => 'text-center' );
	        
	        $idButton = "vistaOpciones".$cont."-".$val;
	        //BOTON DOCUMENTO
	        $disabled1      = (($this->_idRol != ID_ROL_SECRETARIA) ? "disabled" : null);
	        $documento      = (($this->_idRol == ID_ROL_SECRETARIA) ? 'openModalDocumentos(\''.$idMovCrypt.'\')' : null);
	        $botonDocumento = '<li class="mdl-menu__item" '.(($row->flg_disabled_docs == '') ? 'onclick="'.$documento.'"' : $row->flg_disabled_docs).'><i class="mdi mdi-content_copy" style="opacity:'.(($row->flg_disabled_docs == '') ? '1':'0.5').'"></i> Documentos</li>';
	        //INFO ANULAR COMPROMISO
	        $textoInfo      = ($row->_id_concepto == CONCEPTO_SERV_ESCOLAR) ? 'Recuerda que al ser un compromiso del cronograma se activar\u00e1 nuevamente.' 
	                                                                        : 'El compromiso se registrara como anulado';
	        
	        $anular         = (($this->_idRol == ID_ROL_SECRETARIA) ? 'openModalAnularPago(\''.$idMovCrypt.'\',\''.$textoInfo.'\')' : null);
	        if($row->flg_disabled_anular == 'no-pagado'){
	            $onclick = 'onclick="openModalAnularCompromisoTotal(\''.$idMovCrypt.'\')"';
	        } else if($row->flg_disabled_anular == ''){
	            $onclick = 'onclick="'.$anular.'" ';
	        } else{
	            $onclick = $row->flg_disabled_anular;
	        }
	        $botonAnular    = '<li '.$disabled1.' class="mdl-menu__item" '.$onclick.'><i class="mdi mdi-delete" '.$disabled1.' style="opacity:'.(($row->flg_disabled_anular == "" || $row->flg_disabled_anular == "no-pagado") ? '1' : '0.5').'"></i> Anular</li>';
	        //END INFO
	        //BOTON BOLETA
	        $boleta         = (($this->_idRol == ID_ROL_SECRETARIA) ? 'abrirModalGenerarBoleta(\''.$idMovCrypt.'\')' : null);
	        $botonBoleta    = '<li '.$disabled1.' class="mdl-menu__item"  '.(($row->flg_disabled_boleta == '' ) ? ((($row->estado == ESTADO_PAGADO && ($row->_id_concepto == CONCEPTO_SERV_ESCOLAR || $row->_id_concepto == CUOTA_INGRESO )) ) ? 'onclick="'.$boleta.'"' : null) : $row->flg_disabled_boleta).'><i class="mdi mdi-content_paste" style="opacity:'.(($row->flg_disabled_boleta == '' ) ? '1':'0.5').'"></i> Generar Boleta</li>';
	        //TODOS LOS BOTONES
	        $botones = '<ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" id="opcionesEncuesta'.$val.'" for="'.$idButton.'">'
        	                .$botonDocumento.$botonAnular.$botonBoleta.
        	           '</ul>';
	        $botonGeneral = '<button id="'.$idButton.'" class="mdl-button mdl-js-button mdl-button--icon">
                                 <i class="mdi mdi-more_vert"></i>
                             </button>
                             '.$botones;
	        $row_col9  = array('data' => $botonGeneral         , 'class' => 'text-center');
	        $row_col10 = array('data' => $row->fecha_descuento , 'class' => 'text-center');
	        $row_col11 = array('data' => $row->monto_adelanto  , 'class' => 'text-right');
	        $row_col12 = array('data' => $row->fecha_registro  , 'class' => 'text-center');
	        $row_col13 = array('data' => $row->lugar_pago      , 'class' => 'text-center');
	        $this->table->add_row($row_col0,$row_col1,$row_col2,$row_col12,$row_col10,$row_col3,$row_col5,$row_col4,$row_col11,$row_col6,$row_col7, $row_col13, $row_col8,$row_col9);
	        $orden++;
	    }
	    if(count($compromisos) > 0){
	        return $this->table->generate();
	    } else{
	        return '<div class="img-search">
                        <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                        <p>Ups! A&uacute;n no se han registrado datos.</p>
                    </div>';
	    }
	}
	
	function buildContentTabByAlumno($idAlumno,$parentesco){
	    $tabHTML = null;
	    $val = 0;
	    foreach($parentesco as $row){
	        $val++;
	        $cuotasDeuda    = $this->m_movimientos->verificaDeudaByAlumno($row->nid_persona);
	        $estado         = ($cuotasDeuda > 0) ? 'moroso' : 'puntual';
	        $deudas         = (($cuotasDeuda > 0) ? $cuotasDeuda.' cuota(s) vencida(s)' : 'Al d&iacute;a');
	        $compromisos    = $this->m_movimientos->getAllCompromisosByAlumno($row->nid_persona);
	        $tbCompromisos  = $this->buildTableCompromisos($compromisos,$val);
	        $disabled       = (($this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_RESP_COBRANZAS) ? 'disabled style="display:none"' : null);
            $nombreCompleto = $row->nombrecompleto;
            $foto    = (file_exists(FOTO_PROFILE_PATH . 'estudiantes/' . $row->foto_persona)) ?  RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_SMILEDU.FOTO_DEFECTO;
	        $tabHTML .= '<section class="mdl-layout__tab-panel p-0 '.(($val == 1) ? 'is-active' :  null).'" id="tab-'.$val.'">
                            <div class="mdl-content-cards">
                                <div class="mdl-card" id="card-'.$val.'">
                                    <div class="mdl-card__title" id="datos'.$val.'">
                                        <h2 class="pago '.($estado).'">'.($nombreCompleto).'</h2>
                                        <small>'.$deudas.'</small>
                                    </div>
                                    <div class="mdl-card__supporting-text p-0 br-b">
                                        <div class="col-sm-12 p-r-0 p-l-0 m-b-10" id="grafico">
                                        </div>
                                        <div class="col-sm-12 p-r-0 p-l-0" id="contTbCompromisos'.$val.'" >
                                            <div class="table-responsive">
                                                '.($tbCompromisos).'
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" disabled style="display:none" onclick="openModalRegistrarPago(null,false)">
                                            <i class="mdi mdi-money_off"  id="payMore'.$val.'"></i>
                                        </button>
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.$disabled.' data-toggle="modal" data-target="#modalAgregarCompromiso">
                                            <i class="mdi mdi-edit"></i>
                                        </button>
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" >
                                            <i class="mdi mdi-more_vert"></i>
                                        </button>
                                    </div>
                                    <div class="mdl-assign" id="cabeConfirm'.$val.'">
                                        <div class="text">
                                            0 item seleccionado 
                                        </div>
                                        <div class="option">
                                            <button class="mdl-button mdl-js-button m-0  mdl-button--raised" onclick="openModalRegistrarPago(null,false)"><i class="mdi mdi-monetization_on"></i> Pagar</button>                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>';
	    }
	    return $tabHTML;
	}
	
	function registrarIngreso(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $arrayCompromisos = _post('arrayPagar');
	        $currentTab       = _post('currentTabl');
	        $currentPers      = _decodeCI(_post('currentPers'));
	        $flg_visa         = _post('checkedVisa');
	        $flgAdelanto      = _post('checkedAdelanto');
	        $this->_idUserSess = $this->_idUserSess;
	        $monto_adelanto   = trim(_post('monto_adelanto'));
	        if($currentPers == null || $currentTab == null){
	            throw new Exception(ANP);
	        }
//	        $idSede           = $this->m_utils->getMaxSedeByAlumno($currentPers);
	        $idSedeSecre      = _getSesion('id_sede_trabajo');//$this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
	        if($idSedeSecre == null){
	            throw new Exception('Comun&iacute;quese con el administrador por favor...');
	        }
	        if(count($arrayCompromisos) == 0){
	            throw new Exception(ANP);
	        }
	        if($flg_visa != 'true' && $flg_visa != 'false'){
	            throw new Exception(ANP);
	        }
	        if($flgAdelanto != 'true' && $flgAdelanto != 'false'){
	            throw new Exception(ANP);
	        }
	        if($flgAdelanto == 'true' && $monto_adelanto <= 0){
	            throw new Exception('Ingresa un monto valido1');
	        }
	        if($flgAdelanto == 'true' && (!is_numeric($monto_adelanto) && $flgAdelanto <= 0)){
	            throw new Exception('Ingresa un monto valido2');
	        }
	        if($flgAdelanto == 'true' && $monto_adelanto >= 1000000){
	            throw new Exception('La cuota de ingeso debe ser menor que 1000000');
	        }
	        if($flgAdelanto == 'true' && filter_var($monto_adelanto, FILTER_VALIDATE_FLOAT) === false){
	            throw new Exception('Solo Numeros en Monto');
	        }
	        $caja = $this->m_caja->getCurrentCaja($idSedeSecre,$this->_idUserSess);
	        if($caja['id_caja'] == null || $caja['estado_caja'] == CERRADA || $caja['estado_caja'] == CERRADA_EMERGENCIA){
	            throw new Exception('Tu caja no esta dispobnible');
	        }
	        $id_caja = $caja['id_caja'];
	        //SELECCIONADOS
	        $arrayCompromisos = __getArrayObjectFromArray($arrayCompromisos);
	        //OBLIGATORIOS POR PAGO EN ORDEN
	        $arrayAuxObli     = array();
	        $compromisosObli  = $this->m_movimientos->getFirstCompromisosByPersona($currentPers,$arrayCompromisos);
	        foreach($compromisosObli as $row){
	            array_push($arrayAuxObli, $row->id_movimiento);
	        } 
	        if(count($arrayCompromisos) < count($arrayAuxObli)){
	            throw new Exception(ANP);
	        }
	        foreach($arrayAuxObli as $compromiso){
	            if(!in_array($compromiso, $arrayCompromisos)){
	                throw new Exception(ANP);
	            }
	        }
	        $descuento = $this->evaluatePromocionExistente($arrayCompromisos);
	        $flgUpt           = $this->m_movimientos->flgUpdateDetalleAlumno($currentPers,$arrayCompromisos);
	        $arrayEditar      = array();
	        $arrayAuditoria   = array();
	        $arrayRecibo      = array();
	        $arrayUpdateCorre = array();
	        $arrayUpdateDeta  = array('nid_persona'   => $currentPers,
	                                  'estado'        => (($flgUpt['estado'] == ALUMNO_PREREGISTRO) ? ALUMNO_REGISTRADO : ALUMNO_PROM_REGISTRO),
	                                  'flg_actualiza' => $flgUpt['count']
	                                 );
	        $nombSession      = _getSesion('nombre_completo');
	        $correDocumento   = $this->m_movimientos->getCurrentCorrelativoALumno($idSedeSecre,DOC_RECIBO,MOV_INGRESO);
	        $correlativo      = null;
	        $val = 0;
	        $flg_visa    = ($flg_visa == 'true') ? '1' : '0';
	        foreach ($arrayCompromisos as $idCompromiso){
	            $val++;
	            $montoAdelantoMov = $this->m_utils->getById('pagos.movimiento' , 'monto_adelanto' , 'id_movimiento' , $idCompromiso , 'pagos');
	            $montoFinal       = $this->m_utils->getById('pagos.movimiento' , 'monto_final'    , 'id_movimiento' , $idCompromiso , 'pagos');
	            $estadoActual     = $this->m_utils->getById('pagos.movimiento' , 'estado'         , 'id_movimiento' , $idCompromiso , 'pagos');
	            if($flgAdelanto == 'true' && $monto_adelanto > $montoFinal){
	                throw new Exception('El monto de adelanto supera al monto final');
	            }
	            $newEstado   = ($flgAdelanto == 'true' && $montoFinal > $monto_adelanto) ? (($estadoActual == ESTADO_VENCIDO) ? ESTADO_VENCIDO : ESTADO_POR_PAGAR) : ESTADO_PAGADO;
	            if($flgAdelanto == 'true'){
	                $montoAdelantoNew = $montoAdelantoMov + $monto_adelanto;
	            } else{
	                if($descuento != null && $val == count($arrayCompromisos)){
	                    $montoAdelantoNew = $montoAdelantoMov + $montoFinal - $descuento;
	                } else{
	                    $montoAdelantoNew = $montoAdelantoMov + $montoFinal;
	                }
	            }
// 	            $montoAdelantoNew = ($flgAdelanto == 'true') ? ($montoAdelantoMov + $monto_adelanto) : ($montoAdelantoMov + $montoFinal);
	            $montoFinalNew    = ($flgAdelanto == 'true') ? ($montoFinal       - $monto_adelanto) : 0;
	            $flgAdelanto = ($flgAdelanto == 'true') ? '1' : '0';
	            //ARRAY UPDATE MOVIMIENTO
	            array_push($arrayEditar, array('id_movimiento'   => $idCompromiso,
	                                           'estado'          => $newEstado,
	                                           'fecha_pago'      => date('Y-m-d H:i:s'),
	                                           'flg_adelanto'    => $flgAdelanto,
	                                           'monto_adelanto'  => $montoAdelantoNew,
	                                           'monto_final'     => $montoFinalNew,
	                                           'desc_lugar_pago' => 'Colegio',
	                                           'flg_lugar_pago'  => FLG_COLEGIO
	                                           )
	                       );
	            //ARRAY INSERT AUDITORIA
	            $correlativoByMov = $this->m_movimientos->getNextCorrelativo($idCompromiso);
	            array_push($arrayAuditoria, array('_id_movimiento' => $idCompromiso,
	                                              'correlativo'    => $correlativoByMov,
	                                              'id_pers_regi'   => $this->_idUserSess,
	                                              'audi_nomb_regi' => $nombSession,
	                                              'flg_visa'       => $flg_visa,
	                                              'audi_fec_regi'  => date('Y-m-d H:i:s'),
	                                              'accion'         => PAGAR,
	                                              'monto_pagado'   => (($flgAdelanto == '1') ? $monto_adelanto : 
	                                                                   (($descuento != null && $val == count($arrayCompromisos)) ? $montoFinal - $descuento : $montoFinal)),
	                                              '_id_caja'       =>  $id_caja,
	                                              '_id_sede'       => $idSedeSecre
	                                             )
	                      );
	            //ARRAY INSERT RECIBO
	            $correlativo = $this->getCorrelativoReciboByMovimiento($correDocumento+$val);
	            $cuenta = $this->m_movimientos->countDocumentosByMovimiento($idCompromiso,DOC_RECIBO);
	            array_push($arrayRecibo, array('_id_movimiento' => $idCompromiso,
	                                           'tipo_documento' => DOC_RECIBO,
	                                           'nro_serie'      => SERIE_DEFAULT,
	                                           'nro_documento'  => $correlativo,
	                                           '_id_sede'       => $idSedeSecre,
	                                           'flg_impreso'    => FLG_NO_IMPRESO,
	                                           'estado'         => ESTADO_CREADO,
	                                           'fecha_registro' => date('Y-m-d H:i:s'),
	                                           'accion'         => (INSERTA),
	                                           'num_corre'      => $this->getCorrelativoReciboByMovimiento(1)
	                                          )
                          );
	        }
	        //ARRAY UPDATE CORRELATIVO
	        $arrayUpdateCorre = array('_id_sede'           => $idSedeSecre,
	                                  'tipo_documento'     => DOC_RECIBO,
	                                  'numero_correlativo' => $correlativo,
	                                  'tipo_movimiento'    => MOV_INGRESO,
	                                  'nro_serie'      	   => SERIE_DEFAULT,
	                                  'accion'             => (($correDocumento == null) ? INSERTA : ACTUALIZA)
	                                 );
	        $data = $this->m_movimientos->pagarMovimientos($arrayEditar,$arrayAuditoria,$arrayRecibo,$arrayUpdateCorre,$arrayUpdateDeta);
	        if($data['error'] == EXIT_SUCCESS){
	            $data += $this->buildDataHTML($currentPers,$currentTab);
	            $datos                = $this->m_movimientos->getDataByDocumento($arrayCompromisos);
	            $data['datosRecibos'] = json_encode($datos);
// 	            $this->sendCorreos($currentPers, $arrayCompromisos, $estadoActual);
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getCorrelativoReciboByMovimiento($correlativo){
	    $lengthCorre = strlen($correlativo);
	    $correlativoNew = null;
	    for($i = $lengthCorre; $i < 8 ; $i++){
	        $correlativoNew .= '0';
	    }
	    $correlativoNew .= $correlativo;
	    return $correlativoNew;
	}
	
	function getTableByPersona(){
	    $tab         = _post('currentTabl');
	    $idPersona   = _decodeCI(_post('currentPers'));
	    $compromisos = $this->m_movimientos->getAllCompromisosByAlumno($idPersona);
	    $data        = $this->buildDataHTML($idPersona,$tab);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function anularCompromiso(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idCompromiso  = _decodeCI(_post('compromiso'));
	        $currentTab    = _post('currentTabl');
	        $currentPers   = _decodeCI(_post('currentPers'));
	        $observaciones = trim(_post('observaciones'));
	        if($idCompromiso == null){
	            throw new Exception(ANP);
	        }
	        if($currentPers == null || $currentTab == null){
	            throw new Exception(ANP);
	        }
	        $necesario = $this->m_movimientos->getEstadoConceptoByCompromiso($idCompromiso);
	        if($necesario['count'] == 0){
	            throw new Exception('Solo puedes anular los pagos de hoy');
	        }
	        if($observaciones == null){
	            throw new Exception('Ingresa una observaci&oacute;n');
	        }
	        if(strlen($observaciones) > 200) {
	            throw new Exception('La observaci&oacute;n debe ser m&aacute;ximo de 200 caracteres');
	        }
	        $montos = $this->m_movimientos->getNewMontoFinalAnular($idCompromiso);
	        //BEGIN ARRAY UPDATE COMPROMISO
	        $arrayUpdateCompro = array();
	        if($necesario['_id_concepto'] == CONCEPTO_SERV_ESCOLAR){
	            $arrayUpdateCompro = array('id_movimiento'  => $idCompromiso,
	                                       'estado'         => $necesario['new_estado'],
	                                       'fecha_pago'     => null,
	                                       'monto_adelanto' => ($montos['monto_adelanto'] - $montos['current_pago']),
	                                       'monto_final'    => ($montos['current_pago']   + $montos['monto_final'])
	                                      );
	        } else{
	            $arrayUpdateCompro = array('id_movimiento' => $idCompromiso,
                        	               'estado'        => ESTADO_ANULADO,
                        	               'fecha_pago'    => null
                        	              );
	        }
	        //END
	        //BEGIN ARRAY INSERT AUDITORIA MOVIMIENTO
	        $correlativoByMov = $this->m_movimientos->getNextCorrelativo($idCompromiso);
	        $this->_idUserSess    = $this->_idUserSess;
	        $nombSession      = _getSesion('nombre_completo');
	        $arrayInsertAudi = array('_id_movimiento' => $idCompromiso,
                                     'correlativo'    => $correlativoByMov,
                                     'id_pers_regi'   => $this->_idUserSess,
                                     'audi_nomb_regi' => $nombSession,
	                                 'audi_fec_regi'  => date('Y-m-d H:i:s'),
                                     'accion'         => ANULAR,
	                                 'observacion'    => $observaciones
                                    );
	        //END
	        //BEGIN ARRAY INSERT AUDITORIA DOCUMENTO
	        $arrayInsertAudiBoleta = array();
	        $documentos = $this->m_movimientos->getAllDocumentosByMovimiento($idCompromiso);
	        foreach($documentos as $row){
	            array_push($arrayInsertAudiBoleta, array('_id_movimiento' => $idCompromiso,
	                                                     'tipo_documento' => $row->tipo_documento,
	                                                     'id_pers_regi'   => $this->_idUserSess,
	                                                     'audi_pers_regi' => $nombSession,
	                                                     'accion'         => ANULAR,
	                                                     'nro_documento'  => $row->nro_documento
	                                                    )
	                      );
	        }
	        //END
	        $data = $this->m_movimientos->anularMovimientoByPersona($arrayUpdateCompro,$arrayInsertAudi,$arrayInsertAudiBoleta);
	        if($data['error'] == EXIT_SUCCESS){
	            $data += $this->buildDataHTML($currentPers,$currentTab);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildDataHTML($currentPers,$currentTab){
	    $compromisos    = $this->m_movimientos->getAllCompromisosByAlumno($currentPers);
	    $data['tbCompromisos'] = $this->buildTableCompromisos($compromisos,$currentTab);
	    $cuotasDeuda    = $this->m_movimientos->verificaDeudaByAlumno($currentPers);
	    $estado         = ($cuotasDeuda > 0) ? 'moroso' : 'puntual';
	    $nombreCompleto = $this->m_usuario->getDatosPersona($currentPers)['nombres'];
	    $deudas         = (($cuotasDeuda > 0) ? $cuotasDeuda.' cuota(s) vencida(s)' : 'Al dia');
	    $data['datos']         = '<h2 class="pago '.($estado).'">'.($nombreCompleto).'</h2>
                                  <small>'.$deudas.'<!--<a>Ver m&aacute;s detalle</a> --></small>';
	    return $data;
	}
	
	function generarBoleta(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idCompromiso = _decodeCI(_post('compromiso'));
	        $currentTab   = _post('currentTabl');
	        $currentPers  = _decodeCI(_post('currentPers'));
	        if($idCompromiso == null){
	            throw new Exception(ANP);
	        }
	        if($currentPers == null || $currentTab == null){
	            throw new Exception(ANP);
	        }
	        $documentosCount = $this->m_movimientos->countDocumentosByMovimiento($idCompromiso,DOC_BOLETA);
	        if($documentosCount > 1){
	             throw new Exception(ANP);
	        }
	        $necesario = $this->m_movimientos->getEstadoConceptoByCompromiso($idCompromiso);
	        if($necesario['estado'] != ESTADO_PAGADO){
	            throw new Exception(ANP);
	        }
	        $this->_idUserSess = $this->_idUserSess;
	        $idSedeSecretaria  = _getSesion('id_sede_trabajo');
// 	        $idSede            = $this->m_utils->getMaxSedeByAlumno($currentPers);
            //TRAER SEDE DEL COMPROMISO
	        $idSede            = $this->m_movimientos->getSedeByMovimiento($idCompromiso);
	        if($idSede == null){
	            throw new Exception('Comun&iacute;quese con el administrador por favor...');
	        }
	        if($idSede != $idSedeSecretaria){
	            throw new Exception('No concide la sede... porfavor comun&iacute;cate con el administrador...');
	        }
	        $nro_serie = $this->m_movimientos->getSerieActivaBySede($idSede);
	        if($nro_serie == null){
	            throw new Exception(ANP);
	        }
	        
	        $correDocumento   = $this->m_movimientos->getCurrentCorrelativoALumno($idSede,DOC_BOLETA,MOV_INGRESO,$nro_serie);
	        $correlativo = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
	        $arrayUpdateMovi      = array('id_movimiento' => $idCompromiso,
	                                      'flg_boleta'    => '1'
	        );
	        $arrayUpdateDocumento = array('_id_movimiento' => $idCompromiso,
	                                      'tipo_documento' => DOC_BOLETA,
	                                      'nro_serie'      => $nro_serie,
	                                      'nro_documento'  => $correlativo,
	                                      '_id_sede'       => $idSede,
	                                      'flg_impreso'    => FLG_NO_IMPRESO,
	                                      'estado'         => ESTADO_CREADO,
	                                      'fecha_registro' => date('Y-m-d h:i:s'),
	                                      'accion'         => INSERTA
	                                     );
	        $arrayUpdateCorrelativo = array('_id_sede'           => $idSede,
	                                        'tipo_documento'     => DOC_BOLETA,
	                                        'tipo_movimiento'    => MOV_INGRESO,
	                                        'nro_serie'          => $nro_serie,
	                                        'numero_correlativo' => $correlativo,
	                                        'accion'             => (($correDocumento == null) ? INSERTA : ACTUALIZA)
	                                       );
	        $data = $this->m_movimientos->registrarBoletaByCompromiso($arrayUpdateDocumento,$arrayUpdateCorrelativo,$arrayUpdateMovi);
	        //END
	        if($data['error'] == EXIT_SUCCESS){
	            $data += $this->buildDataHTML($currentPers,$currentTab);
	            $data['datosBoleta'] = json_encode($this->m_movimientos->getBoletasPrint(($nro_serie.'-'.$correlativo),$currentPers));
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getDocumentosByCompromiso(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idCompromiso = _decodeCI(_post('compromiso'));
	        $currentTab   = _post('currentTabl');
	        $currentPers  = _decodeCI(_post('currentPers'));
	        $data['content'] = null;
	        if($idCompromiso == null){
	            throw new Exception(ANP);
	        }
	        if($currentPers == null || $currentTab == null){
	            throw new Exception(ANP);
	        }
	        $documentos = $this->m_movimientos->getDataDocumentos($idCompromiso);
	        if(count($documentos) == 0){
	            throw new Exception(ANP);
	        }
	        $data['content'] = $this->buildContentDocumentosHTML($documentos,count($documentos));
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildContentDocumentosHTML($documentos,$cant){
	    $content = null;
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_documentos">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => 'Documento'   , 'class' => 'col-sm-3 text-left');
	    $head_1 = array('data' => 'Correlativo' , 'class' => 'col-sm-4 text-left');
	    $head_2 = array('data' => 'Acciones'    , 'class' => 'col-sm-7 text-center');
	    $this->table->set_heading($head_0,$head_1,$head_2);
	    $i = 0;
	    foreach($documentos as $row){ 
	        $i++;
	        $idComCrypt   = _encodeCI($row->_id_movimiento);
	        $tipoDoc      = strtoupper($row->tipo_documento);
	        $tipoDocCrypt = _encodeCI($tipoDoc);
	        $nroDoc       = $row->nro_documento;
	        $nroDocFlg    = ($tipoDoc == DOC_RECIBO) ? explode('-', $nroDoc)[1] : $nroDoc;
	        $nroDocCrypt  = _encodeCI($row->nro_documento);
	        $tooltip      = (($tipoDoc == DOC_BOLETA) ? '<div id="corre-'.$tipoDoc.'" data-toggle="tooltip" data-placement="top" data-original-title="'.$row->last_document.'">'.$nroDoc.' </div>'  : '<div id="corre-'.$tipoDoc.'" data-toggle="tooltip" data-placement="top" data-original-title="'.$row->last_document.'">'.$nroDocFlg.' </div>');
	        $row_col0 = array('data' => $row->tipo_documento, 'class' => 'text-left');
	        $row_col1 = array('data' => $tooltip , 'class' => 'text-left');
	        //ACCIONES
	       
	        $buttonSendMail = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" data-original-title="correo" onclick="enviarDocumento(\''.$idComCrypt.'\',\''.$tipoDocCrypt.'\')">
                                   <i class="mdi mdi-email"></i>
                               </button>';
	        $buttonPrintDoc = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" data-original-title="imprimir" onclick="getDataByDocumento(\''.$idComCrypt.'\',\''.$tipoDocCrypt.'\',\''.$nroDocCrypt.'\')">
                                   <i class="mdi mdi-print"></i>
                               </button>';
	        $textInfoDelete = '¿Est&aacute;s seguro que deseas eliminar '.(($tipoDoc == DOC_RECIBO) ? 'el recibo' : 'la boleta').'?, esto alterar&aacute; tu correlativo.';
	        $buttonDelete   = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.$row->disabled.' data-toggle="modal" data-target="#modalAnularBoleta" data-toggle="tooltip" data-placement="bottom" data-original-title="eliminar" onclick="abrirModalConfirmarDoc(\''.$idComCrypt.'\',\''.$tipoDocCrypt.'\',\''.$nroDocCrypt.'\',\''.$i.'\',\''.$textInfoDelete.'\')" data-toggle="modal" data-target="#modalAnularBoleta" >
                                   <i class="mdi mdi-delete"></i>
                               </button>';
	        $row_col2 = array('data' => $buttonSendMail.$buttonPrintDoc.$buttonDelete);
	        $this->table->add_row($row_col0,$row_col1,$row_col2);
	    }
	    return $this->table->generate();
	}
	
	function deteleDocumentoByConcepto(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idCompromiso   = _decodeCI(_post('compromiso'));
	        $tipo_documento = _decodeCI(_post('tipo_doc'));
	        $currentTab     = _post('currentTabl');
	        $currentPers    = _decodeCI(_post('currentPers'));
	        if($idCompromiso == null || $tipo_documento == null){
	            throw new Exception(ANP);
	        }
	        if($currentPers == null || $currentTab == null){
	            throw new Exception(ANP);
	        }
	        $necesario = $this->m_movimientos->getEstadoConceptoByCompromiso($idCompromiso);
	        if($necesario['estado'] != ESTADO_PAGADO){
	            throw new Exception(ANP);
	        }
	        $correlativoByMov = $this->m_movimientos->getNextCorrelativo($idCompromiso);
	        $this->_idUserSess    = $this->_idUserSess;
	        $nombSession      = _getSesion('nombre_completo');
	        $arrayInsertAudiBoleta = array('_id_movimiento' => $idCompromiso,
                        	               'tipo_documento' => $tipo_documento,
                        	               'id_pers_regi'   => $this->_idUserSess,
                        	               'audi_pers_regi' => $nombSession,
                        	               'accion'         => ANULAR
                        	              );
	    } catch(Exception $e){
	        $data ['msj'] = $e->getMessage(); 
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function registrarMovimiento(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $currentTab  = _post('currentTabl');
	        $currentPers = _decodeCI(_post('currentPers'));
	        $concepto    = _decodeCI(_post('concepto'));
	        $monto       = trim(_post('monto'));
	        if($currentPers == null || $currentTab == null){
	            throw new Exception(ANP);
	        }
	        if($concepto == null){
	            throw new Exception('Seleccione un concepto');
	        }
	        if(!is_numeric($monto)){
	            throw new Exception('Ingresa un monto valido');
	        }
	        if($monto <= 0){
	            throw new Exception('Ingresa un monto valido');
	        }
	        if($monto >= 1000000){
	            throw new Exception('La cuota de ingeso debe ser menor que 1000000');
	        }
	        if(filter_var($monto, FILTER_VALIDATE_FLOAT) === false){
	            throw new Exception('Solo Numeros en Monto');
	        }
	        $arrayInserCompromiso = array('tipo_movimiento' => MOV_INGRESO,
	                                      'monto'           => $monto,
	                                      'monto_final'     => $monto,
	                                      'estado'          => ESTADO_POR_PAGAR,
	                                      '_id_persona'     => $currentPers,
	                                      '_id_concepto'    => $concepto
	                                     );
	        $data = $this->m_movimientos->insertCompromiso($arrayInserCompromiso);
	        if($data['error'] == EXIT_SUCCESS){
	            $data += $this->buildDataHTML($currentPers,$currentTab);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function anularDocumentoByTipo(){
	    $data['error']      = EXIT_ERROR;
	    $data['msj']        = null;
	    try{
	        $compromiso    = _decodeCI(_post('compromiso'));
	        $currentPers   = _decodeCI(_post('currentPers'));
	        $currentTabl   = _post('currentTabl');
	        $tipoDocumento = _decodeCI(_post('tipoDoc'));
	        $nroDoc        = _decodeCI(_post('nro_doc'));
	        $correlativos  = explode('-', $nroDoc);
	        
 	        $nombSession   = _getSesion('nombre_usuario');
	        if($tipoDocumento != DOC_BOLETA && $tipoDocumento != DOC_RECIBO){
	            throw new Exception(ANP.'1');
	        }
	        $fechaPagoDocumento = $this->m_movimientos->getFechaRegistroPagoCompromiso($nroDoc,$tipoDocumento);
	        if($fechaPagoDocumento == null) {
	            throw new Exception(ANP.'2');
	        }
	        
	        if($fechaPagoDocumento < date('Y-m-d') && $tipoDocumento == DOC_RECIBO) {
	            throw new Exception('Solo se pueden anular los recibos del mismo d&iacute;a');
	        }
	        if($currentPers == null){
	            throw new Exception(ANP.'3');
	        }
	        if($compromiso  == null){
	            throw new Exception(ANP.'4');
	        }
	        
	        //ACTUALIZA DOCUMENTO
	        $arrayUpdateDoc  = array('compromiso'    => $compromiso,
	                                 'nroDoc'        => $correlativos[1],
	                                 'tipoDocumento' => $tipoDocumento,
	                                 'flg_anulado'   => '1',
	                                  'estado'       => ESTADO_ANULADO
	                               );
	        //INSERTA EN AUDITORIA DEL DOCUMENTO
	        $correlativoByDoc = $this->m_movimientos->getNextCorrelativoByAudiDoc($compromiso,$tipoDocumento);
	        $arrayInsertAudi  = array('_id_movimiento' => $compromiso,
	                                  'tipo_documento' => $tipoDocumento,
	                                  'correlativo'    => $correlativoByDoc,
	                                  'id_pers_regi'   => $this->_idUserSess,
                    	              'audi_pers_regi' => $nombSession,
                    	              'accion'         => ANULAR,
                    	              'nro_documento'  => $correlativos[1]
	                                 );
	        
	        //INSERTA EL DOCUMENTO DE NUEVO
	        //SECRETARIA
// 	        $idSede           = $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
            //ALUMNO
// 	        $idSede           = $this->m_utils->getMaxSedeByAlumno($currentPers);
	        //TRAER SEDE DEL COMPROMISO
	        $idSede            = ($tipoDocumento == DOC_BOLETA) ? $this->m_movimientos->getSedeByMovimiento($compromiso) : _getSesion('id_sede_trabajo');
	        if($idSede == null){
	            throw new Exception('Comun&iacute;quese con el administrador por favor...');
	        }
	        $nro_serie = ($tipoDocumento == DOC_BOLETA) ? $this->m_movimientos->getSerieActivaBySede($idSede) : SERIE_DEFAULT;
	        if($nro_serie == null){
	            throw new Exception(ANP);
	        }
	        $correDocumento = $this->m_movimientos->getCurrentCorrelativo($idSede,$tipoDocumento,MOV_INGRESO,$nro_serie);
	        $correlativo    = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
	        $arrayInsertDoc = array('_id_movimiento' => $compromiso,
                                    'tipo_documento' => $tipoDocumento,
                                    'nro_serie'      => $nro_serie,
                                    'nro_documento'  => $correlativo,
                                    '_id_sede'       => $idSede,
                                    'flg_impreso'    => FLG_NO_IMPRESO,
                                    'estado'         => ESTADO_CREADO,
                                    'fecha_registro' => date('Y-m-d h:i:s'),
                                    'accion'         => INSERTA
                                   );
	        //UPDATE CORRELATIVO
	        $arrayUpdateCorrelativo = array('_id_sede'           => $idSede,
                            	            'tipo_documento'     => $tipoDocumento,
                            	            'tipo_movimiento'    => MOV_INGRESO,
                            	            'nro_serie'          => $nro_serie,
                            	            'numero_correlativo' => $correlativo,
                            	            'accion'             => (($correDocumento == null) ? INSERTA : ACTUALIZA)
                            	        );
	        $data = $this->m_movimientos->anularDocumentByTipoByCompromiso($arrayUpdateDoc,$arrayInsertAudi,$arrayInsertDoc,$arrayUpdateCorrelativo, $fechaPagoDocumento);
	        
	        if($data['error'] == EXIT_SUCCESS){
	            $data += $this->buildDataHTML($currentPers,$currentTabl);
	            $documentos = $this->m_movimientos->getDataDocumentos($compromiso);
	            $data['content'] = $this->buildContentDocumentosHTML($documentos,count($documentos));
	            $data['parpadeo'] = ($tipoDocumento == DOC_RECIBO) ? 'corre-'.DOC_RECIBO : 'corre-'.DOC_BOLETA;
	        }
	    } catch(Exception $e){
	       $data['msj'] = $e->getMessage();   
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function getDataByDocumento(){
	    $data['error'] = null;
	    $data['msj']   = null;
	    $arrayCompromisos = array(_decodeCI(_post('movi')));
	    $data['tipo']     = _decodeCI(_post('tipo_doc'));
	    $currentPers      = _decodeCI(_post('currentPers'));
	    $correBoleta      = _decodeCI(_post('nro_doc'));
	    $datos            = null;
	    if($data['tipo'] == DOC_BOLETA){
	        $datos = $this->m_movimientos->getBoletasPrint($correBoleta,$currentPers,$arrayCompromisos);
	    } else {
	        $datos = $this->m_movimientos->getDataByDocumento($arrayCompromisos);
	    }
	    $data['datos'] = json_encode($datos); //json_encode($datos);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function createButtonAlumnos($parientes){
	    $opcion = "";
	    $i = 0;
	    foreach ($parientes as $par) {
	        $idPersCrypt = _encodeCI($par->nid_persona);
	        $foto        = (file_exists(FOTO_PROFILE_PATH . 'estudiantes/' . $par->foto_persona)) ?  RUTA_IMG_PROFILE.'estudiantes/'.$par->foto_persona : RUTA_SMILEDU.FOTO_DEFECTO;
	        $active = $i == 0 ? 'active' : '';
	        $opcion.= ' <span class="mdl-chip mdl-chip--contact mdl-chip--deletable '.$active.' chip-parientes" id="chip'.$i.'" onclick="changeContTableNumber(1,\''.$idPersCrypt.'\',1, \''.$i.'\')">
                            <img class="mdl-chip__contact" src="'.$foto.'">
                            <span class="mdl-chip__text">'.$par->nombrecompleto.'</span>
                            <div class="mdl-chip__action"><i class="mdi mdi-state"></i></div>
                        </span>';
	        $i++;
	    }
	    return $opcion;
	}
	
	function sendCorreos($currentPers, $arrayCompromisos, $estadoActual) {
	    $responsableEconomico = $this->m_reportes->getDetallePadres($currentPers);
        $datosProntoPago      = $this->m_movimientos->getDatosAlumnoCorreos($arrayCompromisos);
        $totalProntoPago      = $this->m_movimientos->getTotalPagado($arrayCompromisos);
        $fotoAux              = (($responsableEconomico[0]->foto_persona == null) ? 'nourse.svg' : $responsableEconomico[0]->foto_persona);
        $foto                 = ((file_exists(RUTA_IMG_PROFILE. 'familiares/' . $fotoAux)) ?  RUTA_IMG_PROFILE.'familiares/'.$fotoAux : RUTA_SMILEDU.FOTO_DEFECTO);
        $tr                   = null;
        $mensaje              = null;
        $cabecera             = null;
        $dia                  = date('d');
        $mes                  = strftime("%B");
        $year                 = date('Y');
        foreach ($datosProntoPago as $datosPP) {
           $tr .='<tr>
					  <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$datosPP->desc_detalle_crono.'</td>
					  <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$datosPP->monto.'</td>
					  <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$datosPP->mora_acumulada.'</td>
					  <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$datosPP->monto_adelanto.'</td>
				  </tr>';
        }
        if($estadoActual == ESTADO_POR_PAGAR) {
            foreach($datosProntoPago as $row) {
                if($row->fecha_descuento <= date('Y-m-d')) {
	                $cabecera = '¡FELICITACIONES! HA OBTENIDO DESCUENTO';
	                $mensaje = '¡Felicidades! Ha obtenido descuento por realizar sus pagos con anticipación';
                }
                $cabecera = 'Gracias por realizar tus pagos';
                $mensaje = 'Gracias por pagar las cuotas antes de la fecha de vencimiento.';
            }
        }
        if($estadoActual == ESTADO_VENCIDO) {
            $cabecera = 'Deuda de pagos';
            $mensaje = 'Nos dirigimos a usted con la finalidad de recordarle que tiene las siguientes cuotas vencidas según el cronograma de pagos '.$datosProntoPago[0]->year.'.';
        }
        $html ='<div style="border:1px solid #EEEEEE;width:800px;margin:auto;text-align:center;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);">
                <div class="parte1" style="height: 80px;padding:20px 30px; border-bottom:1px solid #EEEEEE;">
                    <div class="header" style="width:40%; float:left;">
                        <div style="width:45%;float:left;">
                            <img src="http://181.224.241.203/smiledu/public/general/img/logos_colegio/avantgard.png" style="height: 100%;width:100%;max-width:90px;">
                        </div>
                        <div style="width:55%;float:right;text-align:left;">
                            <h1 style="font-size:16px;margin:0;padding:0;">Maria Fernanda Castro</h1>
                            <p style="margin:0;padding:0;color:#666;">cobranzas@nslm.edu.pe</p>
                        </div>
                    </div>
                    <div class="titulo" style="width:50%;float:right;">
                        <div style="text-align:right;">
                            <h1 style="font-size:16px;margin:0;padding:0;">Recordatorio del mes de '.$datosProntoPago[0]->fecha_vencimiento.'</h1>
                            <p style="margin:0;padding:0;color:#666;">Villa el Salvador, '.$dia.' de '.$mes.' del '.$year.'</p>
                        </div>
                    </div>
                </div>
                <div class="parte2" style="padding:20px 30px;border-bottom:1px solid #EEEEEE;height:80px;">
                    <div style="width:40%; float:left;">
                        <div style="width:45%;float:left;">
                            <img src="'.$foto.'" style="height: 100%;width:100%;max-width:80px;">
                        </div>
                        <div style="width:55%;float:right;text-align:left;">
                            <h1 style="font-size:16px;margin:0;padding:0;">'.$responsableEconomico[0]->nombre_apoderado.'</h1>
                            <p style="margin:0;padding:0;color:#666;">'.$responsableEconomico[0]->email1.'</p>
                            <p style="margin:0; padding:0;color:#666;">'.$responsableEconomico[0]->telefono.'</p>
                        </div>
                    </div>
                    <div style="width:50%; float:right;">
                        <div style="text-align:left;">
                            <h1 style="font-size:16px;margin:0;padding:0;">'.$cabecera.'</h1>
                            <p style="margin:0;padding:0;color:#666;">'.$mensaje.'</p>
                        </div>
                    </div>
                </div>
                <div class="parte3" style="padding:30px; height:100%;">
                	<div style="text-align:center;">
                		<table style=" width:100%;border-collapse:collapse;">
							<thead>
                                <p >'.$datosProntoPago[0]->estudiante.'</p>
								<tr style="background-color: #EEEEEE;">
									<th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Concepto</th>
									<th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Monto</th>
									<th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Mora</th>
									<th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Monto Final</th>
								</tr>
							</thead>
							<tbody style="color:#666;">
								'.$tr.'
								<tr style="background-color: #EEEEEE;color:#000;">
									<td colspan="3" style="padding:5px 0 5px 15px;border:1px solid #EEE;font-weight:bold;text-align:right;">TOTAL</td>
									<td style="padding:5px 0 5px 15px;border:1px solid #EEE;font-weight:bold;" >'.$totalProntoPago.'</td></tr>
							</tbody>
						</table>
                	</div>

                	<div style="height:100px;padding:30px 0;">
                		<div style="width:68%;float:left; text-align: left;">
                			<p>Usted podr&aacute; realizar los pagos de servicio de la eduaci&oacute;n de su menor hijo(a) acerc&aacute;ndose a los siguientes bancos.</p>
                			<p>Ante cualquier duda o consulta envie un correo a cobranzas@nslm.edu.pe o ac&eacute;rquese a cualquiera de nuestras sedes.</p>
                		</div>
                		<div style="width: 30%; float:right;height:100%;max-height:200px;">
                			<div style="text-align:center;height:200px;">
                				<img src="http://181.224.241.203/smiledu/public/general/img/bancos/bbva.png" style="width:100%;max-width:140px;height:100%;max-height:25px;">
                                <img src="http://181.224.241.203/smiledu/public/general/img/bancos/bcp.png" style="width:100%;max-width:140px;height:100%;max-height:25px;">
                                <img src="http://181.224.241.203/smiledu/public/general/img/bancos/banbif.png" style="width:100%;max-width:140px;height:100%;max-height:25px;">
                                <img src="http://181.224.241.203/smiledu/public/general/img/bancos/scotiabank.png" style="width:100%;max-width:140px;height:100%;max-height:25px;">
                                <img src="http://181.224.241.203/smiledu/public/general/img/bancos/comercio.png" style="width:100%;max-width:140px;height:100%;max-height:25px;">
                			</div>
                		</div>

                	</div>
                </div>
            </div>';
        __enviarEmail('pyf136@gmail.com', null, $html);
	}
	
	function getPromociones($promociones){
	    $arrayPromociones = array();
	    foreach($promociones as $prom){
	        array_push($arrayPromociones, array(
	                                            'cuotas'    => $prom->cant_cuotas,
	                                            'descuento' => $prom->porcentaje_descuento
	                                           )
	        );
	    }
	    $this->session->set_userdata(array(
	                                       'promociones' => $arrayPromociones
	                                      )
	                                );
	    return json_encode($arrayPromociones);
	}
	
	function anularCompromisoTotal(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idMovimiento = _decodeCI(_post('idMovi'));
	        $currentTab   = _post('currentTabl');
	        $currentPers  = _decodeCI(_post('currentPers'));
	        $observacion  = trim(_post('observacion'));
	        if($idMovimiento == null){
	            throw new Exception(ANP);
	        }
	        if($observacion == null){
	            throw new Exception('Ingresa una observacion');
	        }
	        $arrayUpdate = array(
	                             'estado'      => ESTADO_ANULADO,
	                             'observacion' => $observacion
	                            );
	        $data = $this->m_movimientos->updateMovimientoById($idMovimiento,$arrayUpdate);
	        if($data['error'] == EXIT_SUCCESS){
	            $data += $this->buildDataHTML($currentPers,$currentTab);
	        }
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function evaluatePromocionExistente($arrayCompromisos){
	    $dataPromo   = $this->m_movimientos->validateCuotasCompromisos($arrayCompromisos);
	    $promociones = _getSesion('promociones');
	    $descuento   = null;
	    foreach($promociones as $prom){
	        if($prom['cuotas'] == $dataPromo['pagos']){
	            $descuento = ($dataPromo['monto']*$prom['descuento'])/100;
	        }
	    }
	    return $descuento;
	}
}