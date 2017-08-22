<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_egresos extends CI_Controller {

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
        $this->load->model('m_mantenimiento');
        $this->load->model('m_caja');
        $this->load->model('m_utils');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_MOVIMIENTOS, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
    
	public function index() {
	    $idPersonaMov      = _getSesion('id_persona_egreso');
	    $fotoAux           = $this->m_utils->getById('pagos.proveedor', 'foto_proveedor', 'id_proveedor', $idPersonaMov);
	    $flg_egreso        = _getSesion('flg_egreso');
	    $foto              = ($fotoAux != null) ? $fotoAux : 'nouser.svg';
	    $data['return']    = '';
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
	    ////Modal Popup Iconos///
	    $data['titleHeader']      = 'Egresos';
	    $data['ruta_logo']        = MENU_LOGO_PAGOS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
	    $data['nombre_logo']      = NAME_MODULO_PAGOS;
	    //MENU
	    $rolSistemas       = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
	    $data['apps']      = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $data['menu']      = $this->load->view('v_menu', $data, true);
	    //NECESARIO
	    $egresos           = $this->m_movimientos->getAllEgresosByPersona($idPersonaMov,$flg_egreso);
	    $data['tbEgresos'] = $this->buildTableEgresosHTML($egresos);
	    $data['fotoPers']  = (file_exists(FOTO_PROFILE_PATH . 'colaboradores/' . $foto)) ?  RUTA_IMG_PROFILE.'colaboradores/'.$foto : RUTA_SMILEDU.FOTO_DEFECTO;
	    $data['nombres']   = ($flg_egreso == 1) ? $this->m_utils->getNombrePersona($idPersonaMov) : $this->m_utils->getById('pagos.proveedor', 'nombre_proveedor', 'id_proveedor', $idPersonaMov);
	    $data['optConceptos'] = __buildComboConceptosByTipo(MOV_EGRESO);
	   
	    $data['rol']          = ($flg_egreso == 1) ? $this->m_utils->getRolByPersona($idPersonaMov) : null;
	    ///////////
	    $this->session->set_userdata(array('tab_active_config' => null));
	    $this->session->set_userdata(array('entraFirstDocente' => 'true'));
	    $this->load->view('v_egresos', $data);
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
	    $idPersona = _getSesion('nid_persona');
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
	    $roles = $this->m_usuario->getRolesOnlySistem(_getSesion('nid_persona'),$idSistema);
	    $result = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;   
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	//DNI 432
	function buildTableEgresosHTML($egresos){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-search="false" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_egresos">',
	                                   'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => 'Descripci&oacute;n');
	    $head_1 = array('data' => 'Fecha Registro' , 'class' => 'text-center');
	    $head_2 = array('data' => 'Monto(S/)'      , 'class' => 'text-right');
	    $head_3 = array('data' => 'Documentos'     , 'class' => 'text-center');
	    $head_4 = array('data' => 'Acciones'       , 'class' => 'text-center');
	    ($this->_idRol == ID_ROL_DOCENTE) ? $this->table->set_heading($head_0,$head_1,$head_2,$head_3) : 
	                                        $this->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4);
	    $val = 0;
	    foreach($egresos as $row){
	        $idMovCrypt = _encodeCI($row->id_movimiento);
	        $row_0      = array('data' => $row->desc_concepto, 'class' => 'text-left');
	        $row_1      = array('data' => $row->fecha_registro, 'class' => 'text-center');
	        $row_2      = array('data' => $row->monto, 'class' => 'text-right');
	        $onclick1   = (($this->_idRol == ID_ROL_SECRETARIA) ? "getReciboByEgreso(\''.$idMovCrypt.'\')" : null);
	        $disabled   = (($this->_idRol != ID_ROL_SECRETARIA) ? 'disabled' : null);
	        $row_3      = array('data' => '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick=>'.$onclick1.'
                                               <i class="mdi mdi-content_copy"></i>
                                           </button>', 'class' => 'text-center');
	        $idButton = "vistaOpciones".$val;
	        $anular   = (($this->_idRol == ID_ROL_SECRETARIA) ? "openModalAnularEgreso(\''.$idMovCrypt.'\')" : null);
	        $botonAnular    = '<li class="mdl-menu__item" data-toggle="modal" '.$disabled.' onclick='.$anular.'><i class="mdi mdi-block"></i> Anular</li>';
	        $botones = '<ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" id="opcionesEncuesta'.$val.'" for="'.$idButton.'">
        	               '.($botonAnular).'
        	           </ul>';
	        $botonGeneral = '<button id="'.$idButton.'" class="mdl-button mdl-js-button mdl-button--icon" >
                                 <i class="mdi mdi-more_vert"></i>
                             </button>
                             '.$botones;
	        $row_4 = array('data' => $botonGeneral,'class' => 'text-center');
	        ($this->_idRol == ID_ROL_DOCENTE) ? $this->table->add_row($row_0,$row_1,$row_2,$row_3) :
	                                            $this->table->add_row($row_0,$row_1,$row_2,$row_3,$row_4);
	        $val++;
	    }
	    if(count($egresos) == 0){
	        $empty =  '<div class="img-search"> 
                           <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                           <p>A&uacute;n no ha solicitado ning&uacute;n egreso</p>
                       </div>';
	        return $empty;
	    } else{
	        return $this->table->generate();
	    }
	}
	
	function registrarEgresoPersona(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $flg_egreso   = _getSesion('flg_egreso');
	        $idPersonaMov = _getSesion('id_persona_egreso');
	        $concepto     = _decodeCI(_post('concepto'));
	        $monto        = trim(round(_post('monto'),2));
	        $idUsuario    = _getSesion('nid_persona');
	        $nombres      = _getSesion('nombre');
	        $observacion  = utf8_decode(trim(_post('observacion')));
	        $idSede       = $this->m_utils->getSedeTrabajoByColaborador($idUsuario);
	        if($idSede == null){
	            throw new Exception('Comun�quese con el administrador por favor...');
	        }
	        if($concepto == null){
	            throw new Exception('Seleccione un concepto');
	        }
	        if(!is_numeric($monto) ){
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
	        if($observacion == null){
	            throw new Exception('Ingresa una observaci&oacute;n');
	        }
	        $caja = $this->m_caja->getCurrentCaja($idSede,$this->_idUserSess);
	        if($caja['id_caja'] == null || $caja['estado_caja'] == CERRADA || $caja['estado_caja'] == CERRADA_EMERGENCIA){
	            throw new Exception('Tu caja no esta disponible');
	        }
	        $id_caja = $caja['id_caja'];
	        //ARRAY INSERT MOVIMIENTO
	        $arrayInsertMovi = array('tipo_movimiento' => MOV_EGRESO,
	                                 'monto_final'     => $monto,
	                                 'monto'           => $monto,
	                                 'fecha_registro'  => date('Y-m-d'),
	                                 '_id_concepto'    => $concepto,
	                                 '_id_persona'     => $idPersonaMov,
	                                 'estado'          => ESTADO_RETIRADO,
	                                 'observacion'     => $observacion,
	                                 'flg_regi_movi'   => $flg_egreso
	                                );
	        //ARRAY INSERT AUDITORIA
	        $arrayInsertAudi = array('id_pers_regi'   => $idUsuario,
	                                 'audi_nomb_regi' => $nombres,
	                                 'accion'         => RETIRAR,
	                                 'monto_pagado'   => $monto,
	                                 'observacion'    => $observacion,
	                                 '_id_caja'       => $id_caja,
	                                 '_id_sede'       => $idSede
	                                );
	        //ARRAY INSERT DOCUMENTO
	        $correDocumento   = $this->m_movimientos->getCurrentCorrelativoALumno($idSede,DOC_RECIBO,MOV_EGRESO);
	        $newCorrelativo   = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
	        $arrayInsertDocumento = array('tipo_documento'  => DOC_RECIBO,
	                                      'nro_documento'   => $newCorrelativo,
	                                      '_id_sede'        => $idSede,
	                                      'flg_impreso'     => FLG_NO_IMPRESO,
	                                      'estado'          => ESTADO_CREADO
	                                     );
	        //ARRAY UPDATE CORRELATIVO
	        $arrayUpdateCorre = array('_id_sede'           => $idSede,
                        	          'tipo_documento'     => DOC_RECIBO,
	                                  'tipo_movimiento'    => MOV_EGRESO,
                        	          'numero_correlativo' => $newCorrelativo,
	                                  'nro_serie'          => SERIE_DEFAULT,
	                                  'accion'             => (($correDocumento == null) ? INSERTA : ACTUALIZA)
                        	        );
	        $data = $this->m_movimientos->registrarEgresoByPersona($arrayInsertMovi,$arrayInsertAudi,$arrayInsertDocumento,$arrayUpdateCorre);
	        if($data['error'] == EXIT_SUCCESS){
	            $egresos = $this->m_movimientos->getAllEgresosByPersona($idPersonaMov,$flg_egreso);
	            $data['tbEgresos']  = $this->buildTableEgresosHTML($egresos);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function anularEgreso(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idEgreso     = _decodeCI(_post('egreso'));
	        $idPersonaMov = _getSesion('id_persona_egreso');
	        $idUsuario    = _getSesion('nid_persona');
	        $nombSession  = _getSesion('nombre');
	        $observacion  = utf8_decode(trim(_post('observacion')));
	        $flg_egreso   = _getSesion('flg_egreso');
	        if($idEgreso == null){
	            throw new Exception(ANP);
	        }
	        if($observacion == null){
	            throw new Exception('Ingresa una observaci&oacutes;n');
	        }
	        //ARRAY UPDATE MOVIMIENTO
	        $arrayUpdateCompro     = array('id_movimiento' => $idEgreso,
	                                       'estado'        => ESTADO_ANULADO
	                                      );
	        $nroDoc = $this->m_movimientos->getNroDocumentoByEgreso($idEgreso);
	        //ARRAY INSERT AUDITORIA MOVIMIENTO
	        $correlativoByMov = $this->m_movimientos->getNextCorrelativo($idEgreso);
	        $arrayInsertAudi = array('_id_movimiento' => $idEgreso,
                    	             'correlativo'    => $correlativoByMov,
                    	             'id_pers_regi'   => $idUsuario,
                    	             'audi_nomb_regi' => $nombSession,
                    	             'accion'         => ANULAR,
	                                 'observacion'    => $observacion
                    	        );
	        //ARRAY INSERT AUDI DOCUMENTO
	        $arrayInsertAudiBoletaGeneral    = array();
	        array_push($arrayInsertAudiBoletaGeneral, array('_id_movimiento' => $idEgreso,
                                    	                    'tipo_documento' => DOC_RECIBO,
                                    	                    'id_pers_regi'   => $idUsuario,
                                    	                    'audi_pers_regi' => $nombSession,
                                    	                    'accion'         => ANULAR,
	                                                        'nro_documento'  => $nroDoc
                            	                           )
                      );
	        $data = $this->m_movimientos->anularMovimientoByPersona($arrayUpdateCompro,$arrayInsertAudi,$arrayInsertAudiBoletaGeneral);
	        if($data['error'] == EXIT_SUCCESS){
	            $egresos = $this->m_movimientos->getAllEgresosByPersona($idPersonaMov,$flg_egreso);
	            $data['tbEgresos'] = $this->buildTableEgresosHTML($egresos);
	        }
	    } catch(Exception $e){
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
	
	function getReciboByEgreso(){
	    $data = null;
	    try{
	        $idEgreso = _decodeCI(_post('egreso'));
	        if($idEgreso == null){
	            throw new Exception(ANP);
	        }
	        $documentos = $this->m_movimientos->getDataDocumentos($idEgreso);
	        $data['boleta'] = $this->buildContentDocumentosHTML($documentos,count($documentos));
	    }catch (Exception $e){
	        $data['boleta'] = null;
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildContentDocumentosHTML($documentos,$cant){
	    $content = null;
	    $class   = ($cant == 2) ? 'col-sm-6' : 'col-sm-12';
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_documentos">',
                      'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => 'Documento'   , 'class' => 'text-left');
	    $head_1 = array('data' => 'Correlativo' , 'class' => 'text-left');
	    $head_2 = array('data' => 'Acciones'    , 'class' => 'text-center');
	    $this->table->set_heading($head_0,$head_1,$head_2);
	    foreach($documentos as $row){
	        $idComCrypt   = _encodeCI($row->_id_movimiento);
	        $tipoDocCrypt = _encodeCI(strtoupper($row->tipo_documento));
	        $nroDoc       = _encodeCI($row->nro_documento);
	        $row_col0 = array('data' => $row->tipo_documento , 'class' => 'text-left');
	        $row_col1 = array('data' => explode('-', $row->nro_documento)[1] , 'class' => 'text-left');
	        //ACCIONES
	        $onclickImprimir = 'onclick="imprimirDocumento(\''.$idComCrypt.'\',\''.$tipoDocCrypt.'\')"';
	        $buttonSendMail = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" data-original-title="correo" onclick="enviarDocumento(\''.$idComCrypt.'\',\''.$tipoDocCrypt.'\')">
                                   <i class="mdi mdi-email"></i>
                               </button>';
	        $buttonPrintDoc = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" data-original-title="imprimir" '.$onclickImprimir.'>
                                   <i class="mdi mdi-print"></i>
                               </button>';
	        $row_col2 = array('data' => $buttonSendMail.$buttonPrintDoc);
	        $this->table->add_row($row_col0,$row_col1,$row_col2);
	    }
	    $content = $this->table->generate();
	    return $content;
	}
	//GUARDAR DOCUMENTOS
	function guardarConceptoEgreso(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $concepto     = trim(_post('concepto'));
	        $monto        = trim(_post('monto'));
	        $idPersonaMov = _getSesion('id_persona_egreso');
	        $observacion  = utf8_decode(trim(_post('observacion')));
	        $idUsuario    = _getSesion('nid_persona');
	        $nombres      = _getSesion('nombre');
	        $idSede       = $this->m_utils->getSedeTrabajoByColaborador($idUsuario);
	        $flg_egreso   = _getSesion('flg_egreso');
	        if($idSede == null){
	            throw new Exception(ANP);
	        }
	        if($concepto == null){
	            throw new Exception('Ingresa un concepto');
	        }
	        if(strlen($concepto) > 100){
	            throw new Exception('Solo se aceptan 100 letras');
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
	        if($observacion == null){
	            throw new Exception('Ingresa una observaci\u00f3n');
	        }
	        $descripccion=$this->m_mantenimiento->allConcepto($concepto);
	        if($descripccion == 1){
	            throw new Exception('Concepto ya registrado');
	        }
	        //ARRAY INSERTA CONCEPTO
	        $arrayInsertConcepto = array('desc_concepto'    => _ucwords($concepto), 
	                                     'monto_referencia' => $monto,
	                                     'tipo_movimiento'  => MOV_EGRESO,
	                                     'estado'           => FLG_ESTADO_ACTIVO
	        );
	        //ARRAY INSERTA EGRESO
	        $arrayInsertEgreso  = array('tipo_movimiento' => MOV_EGRESO,
                        	            'monto'           => $monto,
                        	            'monto_final'     => $monto,
                        	            'estado'          => ESTADO_RETIRADO,
                        	            '_id_persona'     => $idPersonaMov,
                        	            'fecha_registro'  => date('Y-m-d'),
	                                    'flg_regi_movi'   => $flg_egreso
                        	            );
	        //ARRAY INSERT AUDITORIA
	        $id_caja = $this->m_caja->getCurrentCaja($idSede,$this->_idUserSess)['id_caja'];
	        $arrayInsertAudi = array('id_pers_regi'   => $idUsuario,
                                     'audi_nomb_regi' => $nombres,
                                     'accion'         => RETIRAR,
	                                 'monto_pagado'   => $monto,
	                                 'observacion'     => $observacion,
	                                 '_id_caja'       => $id_caja
                                    );
	        //ARRAY INSERT DOCUMENTO
	        $correDocumento   = $this->m_movimientos->getCurrentCorrelativo($idSede,DOC_RECIBO,MOV_EGRESO);
	        $newCorrelativo   = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
	        $arrayInsertDocumento = array('tipo_documento'  => DOC_RECIBO,
                        	              'nro_documento'   => $newCorrelativo,
                        	              '_id_sede'        => $idSede,
                        	              'flg_impreso'     => FLG_NO_IMPRESO,
                        	              'estado'          => ESTADO_CREADO
	                                     );
	        //ARRAY UPDATE CORRELATIVO
	        $arrayUpdateCorre = array('_id_sede'           => $idSede,
                    	              'tipo_documento'     => DOC_RECIBO,
                        	          'tipo_movimiento'    => MOV_EGRESO,
                        	          'numero_correlativo' => $newCorrelativo,
	                                  'nro_serie'          => '000',
                        	          'accion'             => (($correDocumento == null) ? INSERTA : ACTUALIZA)
                        	        );
	        $data = $this->m_movimientos->registraConceptoAndEgreso($arrayInsertConcepto,$arrayInsertEgreso,$arrayInsertDocumento,$arrayUpdateCorre,$arrayInsertAudi);
	        if($data['error'] == EXIT_SUCCESS){
	            $data['optConceptos'] = __buildComboConceptosByTipo(MOV_EGRESO);
	            $egresos = $this->m_movimientos->getAllEgresosByPersona($idPersonaMov,$flg_egreso);
	            $data['tbEgresos'] = $this->buildTableEgresosHTML($egresos);
	        }
	    } catch(Exception $e){
	       $data['msj'] = $e->getMessage();   
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getDatosByRecibo(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $compromiso = _decodeCI(_post('compromiso'));
	        $tipo_doc   = _decodeCI(_post('tipo_doc'));
	        $flg_egreso = _getSesion('flg_egreso');
	        if($compromiso == null){
	            throw new Exception(ANP);
	        }
	        if($tipo_doc  == null){
	            throw new Exception(ANP);
	        }
	        $result            = $this->m_movimientos->getDataCreateRecibo($compromiso);
	        $result['sede']    = strtoupper($this->m_utils->getById('sede'    , 'desc_sede' , 'nid_sede'    , $result['_id_sede'], 'smiledu'));
	        $result['usuario'] = strtoupper($this->m_utils->getById('persona' , 'usuario'   , 'nid_persona' , $result['id_pers_regi'], 'smiledu'));
	        $result['persona'] = ($flg_egreso == 1) ? $this->m_utils->getNombrePersona($result['_id_persona']) : $this->m_utils->getById('pagos.proveedor', 'nombre_proveedor', 'id_proveedor', $result['_id_persona']);
	        unset($result['_id_sede']);
	        unset($result['_id_persona']);
	        unset($result['id_pers_regi']);
	        $data    += $result;
	        $data['error'] = EXIT_SUCCESS;
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
}