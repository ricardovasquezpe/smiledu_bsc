<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_auditoria extends CI_Controller{
    
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
        $this->load->model('m_reportes');
        $this->load->model('m_migracion');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_REPORTES, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }

    public function index() {
        $data['titleHeader']      = 'Reportes';
        $data['ruta_logo']        = MENU_LOGO_PAGOS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
        $data['nombre_logo']      = NAME_MODULO_PAGOS;
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
        $data['barraSec'] = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                			     <a href="#tab-pagados" onclick="createFabByTab(\'tab1\')" class="mdl-layout__tab is-active">Pensiones Pagadas</a>
				                 <a href="#tab-vencimiento" onclick="createFabByTab(\'tab2\')" class="mdl-layout__tab">Pensiones Vencidas</a>
				                 <a href="#tab-puntual" onclick="createFabByTab(\'tab3\')" class="mdl-layout__tab">Pagos Puntuales</a>
                				 <a href="#tab-puntual" onclick="createFabByTab(\'tab4\')" class="mdl-layout__tab">Auditoria Del Sistema</a>'
                		   .'</div>';
        $rolSistemas           = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
        $data['apps']            = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $menu                  = $this->load->view('v_menu', $data, true);
        $data['menu']          = $menu;
        $data['optSede']       = $this->lib_utils->buildComboSedes();
        $data['optCronograma'] = $this->lib_utils->buildComboCronograma();
        $this->load->view('v_reportes', $data);
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
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
	    $dataUser  = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = $this->_idUserSess;
	    $idRol     = _getSesion('id_rol');
	    $roles     = $this->m_usuario->getRolesByUsuario($idPersona,$idRol);
	    $return    = null;
	    foreach ($roles as $var) {
	        $check = null;
	        $class = null;
	        if($var->check == 1) {
	            $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
	            $class = 'active';
	        }
	        $idRol  = _simple_encrypt($var->nid_rol);
	        $return .= "<li class='".$class."'>";
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
    
	function enviarFeedBack() {
	    $nombre = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    __enviarFeedBack($mensaje,$url,$nombre);
	}
	
	function mostrarRolesSistema() {
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
	
	function getTablaAuditoria() {
	    throw new Exception('Esta opci&oacute;n est&aacute; habilitada en el siguiente paquete');
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
	    	$datos                  = $this->m_reportes->getAudiConta();
			$data['tableConta']     = $this->buildTablaAudiContabilidadHTML($datos);
			$data['tableSedeBanco'] = $this->buildTablaAudiBancoHTML();
	    	$datos1                 = $this->m_reportes->getAudiMov(null, null);
			$data['tableMov']       = $this->buildTablaAudiMovimientoHTML($datos1, null, null);
		} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTablaAudiContabilidadHTML($datos, $fecInicio, $fecFin) {
		$tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_conta">',
				      'table_close' => '</table>');
		$this->table->set_template($tmpl);
		$head_1 = array('data' => 'Empresa'                   , 'class' => 'text-left');
		$head_2 = array('data' => 'Ultima Exportaci&oacute;n' , 'class' => 'text-right');
		$head_3 = array('data' => 'Persona'                   , 'class' => 'text-left');
		$head_4 = array('data' => 'Tel&eacute;fono'           , 'class' => 'text-right');
		$head_5 = array('data' => 'Correo'                    , 'class' => 'text-left');
		$head_6 = array('data' => 'Historial'                 , 'class' => 'text-left');
		$this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
		$val = 1;
		foreach ($datos as $row) {
			$idEmpresaEncripty = _encodeCI($row->id_empresa);
			$row_cell_1 = array('data' => $row->desc_empresa , 'class' => 'text-left');
			$row_cell_2 = array('data' => $row->last_fecha   , 'class' => 'text-right');
			$row_cell_3 = array('data' => $row->persona      , 'class' => 'text-left');
			$row_cell_4 = array('data' => $row->telf_pers    , 'class' => 'text-right');
			$row_cell_5 = array('data' => $row->correo       , 'class' => 'text-left');
			$boton = '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalContaHistorial(\''.$idEmpresaEncripty.'\',\''.$fecInicio.'\',\''.$fecFin.'\');">
	                      <i class="mdi mdi-list"></i>
                      </a>';
			$row_cell_6 = array('data' => $boton             , 'class' => 'text-left');
			$val++;
			$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5);
		}
		$tabla = $this->table->generate();
		return $tabla;
	}
	
	function buildTablaAudiBancoHTML($fechaIni, $fechaFin) {
		$tabla     = null;
	    $tabla    .=  '<table id="" class="tree table">
	                       <tr>
                               <td class="col-xs-5 text left p-l-20" style="border-top: none;">Descripci&oacute;n</td>
	                           <td class="col-sm-2 text-left" style="border-top: none;">&Uacute;ltima importaci&oacute;n</td>
	                           <td class="col-sm-2 text-left" style="border-top: none;">&Uacute;ltima exportaci&oacute;n</td>
                               <td class="col-sm-2 text-center" style="border-top: none;">Historial</td>
                           </tr>';
    	$empresas = $this->m_migracion->getAllEmpresas();
	    $valNodo = 0;
	    $valAux  = null;
	    $exportar = 'exportar';
	    $importar = 'importar';
	    foreach($empresas as $emp){
	        $lastBancoImportar = explode('|', $emp->last_import);
	        $lastBancoExportar = explode('|', $emp->last_export);
	        $valNodo++;
	        $valAux = $valNodo;
	        $imgBancoImport = null;
	        $imgBancoExport = null;
	        if(count($lastBancoImportar) > 1){
	            $imgBancoImport = '<img style="cursor:pointer" class="img-banco" src="'.RUTA_IMG.'bancos/'.(json_decode(IMAGENES_BANCO_ID)->$lastBancoImportar[1]).'" data-toggle="tooltip" data-placement="bottom">
	                               </img>';
	        }
	        if(count($lastBancoExportar) > 1){
	            $imgBancoExport = '<img style="cursor:pointer" class="img-banco" src="'.RUTA_IMG.'bancos/'.(json_decode(IMAGENES_BANCO_ID)->$lastBancoExportar[1]).'" data-toggle="tooltip" data-placement="bottom">
	                               </img>';
	        }
	        $tabla .='<tr class="treegrid-'.$valNodo.'">
	                      <td class="text-left p-l-10 col-sm-3">'.$emp->desc_empresa.' ('.$emp->sedes.')</td>
	                      <td class="text-left col-sm-3 img-table">'.$imgBancoImport./*$lastBancoImportar[0].*/'</td>
	                      <td class="text-left col-sm-3 img-table">'.$imgBancoExport./*$lastBancoExportar[0].*/'</td>
	                      <td class="text-center col-sm-3"></td>
            	      </tr>';
            $arraySedes = array();
	        foreach(explode(',', $emp->ids) as $idSede){
	            array_push($arraySedes, _encodeCI($idSede));
	        }
	        $bancos   = $this->m_migracion->getAllBancosActivosBySede(explode(',', $emp->ids));
	        foreach($bancos as $banco) {
	            $idEmpresaCrypt      = _encodeCI($emp->id_empresa);
	            $audiMigracionImport = $this->m_migracion->getLastMigracionByBancoSede($banco->_id_banco,$emp->id_empresa,IMPORTAR, $fechaIni, $fechaFin);
	            $audiMigracionExport = $this->m_migracion->getLastMigracionByBancoSede($banco->_id_banco,$emp->id_empresa,EXPORTAR, $fechaIni, $fechaFin);
	            $valNodo++;
	            $idBancoCrypt        = _encodeCI($banco->_id_banco);
	            $imgPersonaImport    = null;
	            $imgPersonaExport    = null;
	            if($audiMigracionImport['fecha'] != null) {
	                $imgPersonaImport = '<img style="cursor:pointer" src="'.RUTA_IMG.'profile/nouser.svg" data-toggle="tooltip" data-placement="bottom" title="'.$audiMigracionImport['persona'].'"></img>';
	            }
	            if($audiMigracionExport['fecha'] != null) {
	                $imgPersonaExport = '<img style="cursor:pointer" src="'.RUTA_IMG.'profile/nouser.svg" data-toggle="tooltip" data-placement="bottom" title="'.$audiMigracionExport['persona'].'"></img>';
	            }
	            $boton = '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModaBancosHistorial(\''.$idBancoCrypt.'\', \''.$idEmpresaCrypt.'\',\''.$fechaIni.'\',\''.$fechaFin.'\');">
	                          <i class="mdi mdi-list"></i>
	                      </a>';
	            $idBanco = $banco->_id_banco;
	            $imgBanco ='<img style="cursor:pointer" class="img-banco" src="'.RUTA_IMG.'bancos/'.(json_decode(IMAGENES_BANCO_ID)->$idBanco).'" data-toggle="tooltip" data-placement="bottom">
	                         </img>';
	            $tabla .='<tr class="treegrid-'.$valNodo.' treegrid-parent-'.$valAux.'">
	                          <td class="text-left p-l-5">'.$imgBanco.'</td>
	                          <td class="text-left img-table">'.$imgPersonaImport.$audiMigracionImport['fecha'].'</label></td>
	                          <td class="text-left img-table">'.$imgPersonaExport.$audiMigracionExport['fecha'].'</td>
	                          <td class="text-center" style="display: flex">'.$boton.'</td>
            	          </tr>';
	        }
	    }
	    $tabla .= '</table>';
	    return $tabla;
	}
	
	function buildTablaAudiMovimientoHTML($datos, $fecInicio, $fecFin) {
		$tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_mov">',
				      'table_close' => '</table>');
		$this->table->set_template($tmpl);
		$head_1 = array('data' => 'Colaborador'       , 'class' => 'text-left');
		$head_2 = array('data' => 'Ultimo Movimiento' , 'class' => 'text-right');
		$head_4 = array('data' => 'Monto'             , 'class' => 'text-right');
		$head_5 = array('data' => 'Sede'              , 'class' => 'text-left');
		$head_3 = array('data' => 'Acci&oacute;n'     , 'class' => 'text-left');
		$head_7 = array('data' => 'Historial'         , 'class' => 'text-left');
		$this->table->set_heading($head_1, $head_2, $head_4, $head_5, $head_3, $head_7);
		$val = 1;
		foreach ($datos as $row) {
			$idPersonaEncripty = _encodeCI($row->id_pers_regi);
			$row_cell_1 = array('data' => $row->audi_nomb_regi , 'class' => 'text-left');
			$row_cell_2 = array('data' => $row->last_fecha     , 'class' => 'text-right');
			$row_cell_4 = array('data' => $row->monto_pagado   , 'class' => 'text-right');
			$row_cell_5 = array('data' => $row->desc_sede      , 'class' => 'text-left');
			$row_cell_3 = array('data' => $row->accion         , 'class' => 'text-left');
			$boton = '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalMovHistorial(\''.$idPersonaEncripty.'\',\''.$fecInicio.'\',\''.$fecFin.'\');">
	                      <i class="mdi mdi-list"></i>
	                  </a>';
			$row_cell_7 = array('data' => $boton               , 'class' => 'text-left');
			$val++;
			$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_4, $row_cell_5, $row_cell_3, $row_cell_7);
		}
		$tabla = $this->table->generate();
		return $tabla;
	}
	
	function changeContaHistorial() {
		$idEmpresa      = _decodeCI($this->input->post('idEmpresa'));
		$fechaInicio    = _post('fechaIni');
		$fechaFin       = _post('fechaFin');
		$data['error']  = EXIT_ERROR;
		$data['msj']    = null;
		try{
		$arrayHistorial = $this->m_reportes->getHistorialByEmpresa($idEmpresa, $fechaInicio, $fechaFin);
		if($arrayHistorial == array()) {
		    throw new Exception('No Hay Registros');
		}
	    $data['nameEmpresa']          = $arrayHistorial['0']->desc_empresa;
		$data['tableContaHistorial']  = $this->buildTablaHistorialByEmpresa($arrayHistorial);
		$data['error'] = EXIT_SUCCESS;
		} catch(Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTablaHistorialByEmpresa($arrayHistorial) {
		$tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_conta_historial">',
				      'table_close' => '</table>');
		$this->table->set_template($tmpl);
		$head_1 = array('data' => 'Colaborador'       , 'class' => 'text-left');
		$head_2 = array('data' => 'Ultimo Movimiento' , 'class' => 'text-right');
		$head_3 = array('data' => 'Tel&eacute;fono'   , 'class' => 'text-left');
		$head_4 = array('data' => 'Correo'            , 'class' => 'text-right');
		$this->table->set_heading($head_1, $head_2, $head_3, $head_4);
		$val = 1;
		foreach ($arrayHistorial as $row) {
			$row_cell_1 = array('data' => $row->audi_pers_regi , 'class' => 'text-left');
			$row_cell_2 = array('data' => $row->audi_fec_regi  , 'class' => 'text-right');
			$row_cell_3 = array('data' => $row->telf_pers      , 'class' => 'text-left');
			$row_cell_4 = array('data' => $row->correo         , 'class' => 'text-right');
			$val++;
			$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4);
		}
		$tabla = $this->table->generate();
		return $tabla;
	}
	
	function changeMovHistorial() {
		$idPersona     = _decodeCI($this->input->post('idPersona'));
		$fechaInicio   = _post('fechaIni');
		$fechaFin      = _post('fechaFin');
	    $data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
			$arrayHistorial                 = $this->m_reportes->getHistorialByPersona($idPersona, $fechaInicio, $fechaFin);
			if($arrayHistorial != array()) {
			    $data['namePersona']        = $arrayHistorial['0']->audi_nomb_regi;
				$data['tableMovHistorial']  = $this->buildTablaHistorialByPersona($arrayHistorial);
				$data['error'] = EXIT_SUCCESS;
			}else {
				throw new Exception('No Hay Registros');
			}
		} catch(Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTablaHistorialByPersona($arrayHistorial) {
		$tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_mov_historial">',
				      'table_close' => '</table>');
		$this->table->set_template($tmpl);
		$head_1 = array('data' => 'Persona'             , 'class' => 'text-left');
		$head_2 = array('data' => 'Descripci&oacute;n'  , 'class' => 'text-left');
		$head_3 = array('data' => 'Fecha'               , 'class' => 'text-right');
		$head_4 = array('data' => 'Monto'               , 'class' => 'text-left');
		$head_5 = array('data' => 'Tipo de Pago'        , 'class' => 'text-left');
		$head_6 = array('data' => 'Acci&oacute;n'       , 'class' => 'text-left');
		$this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
		$val = 1;
		foreach ($arrayHistorial as $row) {
			$descripcion = '';
			if($row->id_concepto == 1) {
				$descripcion = $row->desc_detalle_crono;
			}else {
				$descripcion = $row->desc_concepto;
			}
			$tipo = '';
			if($row->flg_visa == 0) {
				$tipo = 'efectivo';
			}else {
				$tipo = 'visa';
			}
			$row_cell_1 = array('data' => $row->nombre_completo                             , 'class' => 'text-left');
			$row_cell_2 = array('data' => $descripcion                                      , 'class' => 'text-left');
			$row_cell_3 = array('data' => $row->audi_fec_regi                               , 'class' => 'text-right');
			$row_cell_4 = array('data' => $row->monto_pagado                                , 'class' => 'text-left');
			$row_cell_5 = array('data' => '<i class="mdi mdi-'.$row->icon_mod_pago.'"></i>' , 'class' => 'text-center');
			$row_cell_6 = array('data' => $row->accion                                      , 'class' => 'text-left');
			$val++;
			$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6);
		}
		$tabla = $this->table->generate();
		return $tabla;
	}
	
	function changeBancoHistorial() {
		$idBanco       = _decodeCI($this->input->post('idBanco'));
		$idEmpresa     = _decodeCI($this->input->post('idEmpresa'));
		$fecInicio     = _post('fechaIni');
		$fecFin        = _post('fechaFin');
	    $data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
			$arrayHistorial                  = $this->m_reportes->getHistorialByBanco($idBanco, $idEmpresa, $fecInicio, $fecFin);
			if($arrayHistorial != array()){
				$data['nameBanco']           = /*$arrayHistorial['0']->desc_sede.*/' '.$arrayHistorial['0']->desc_banco;
				$data['tableBancoHistorial'] = $this->buildTablaHistorialByBanco($arrayHistorial);
				$data['error'] = EXIT_SUCCESS;
			}else {
				throw new Exception('No Hay Registros');
			}
		} catch(Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTablaHistorialByBanco($arrayHistorial) {
		$tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_banco_historial">',
				      'table_close' => '</table>');
		$this->table->set_template($tmpl);
		$head_1 = array('data' => 'Colaborador'     , 'class' => 'text-left');
		$head_2 = array('data' => 'Tel&eacute;fono' , 'class' => 'text-right');
		$head_3 = array('data' => 'Correo'          , 'class' => 'text-right');
		$head_4 = array('data' => 'Fecha'           , 'class' => 'text-right');
		$head_5 = array('data' => 'Acci&oacute;n'   , 'class' => 'text-left');
		$this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5);
		$val = 1;
		foreach ($arrayHistorial as $row) {
			$descripcion = '';
			$row_cell_1 = array('data' => $row->audi_pers_regi , 'class' => 'text-left');
			$row_cell_2 = array('data' => $row->telf_pers      , 'class' => 'text-right');
			$row_cell_3 = array('data' => $row->correo         , 'class' => 'text-right');
			$row_cell_4 = array('data' => $row->fecha_migracion, 'class' => 'text-right');
			$row_cell_5 = array('data' => $row->accion         , 'class' => 'text-left');
			$val++;
			$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5);
		}
		$tabla = $this->table->generate();
		return $tabla;
	}
	
	function getTableByFiltro(){
	    try{
	        $tableSel  = _simpleDecryptInt(_post('tableSelected'));
	        $bancoSel  = (((_simpleDecryptInt(_post('bancoSelected')) != null) ? _simpleDecryptInt(_post('bancoSelected')) : _decodeCI(_post('bancoSelected'))) != FALSE) ?  ((_simpleDecryptInt(_post('bancoSelected')) != null) ? _simpleDecryptInt(_post('bancoSelected')) : _decodeCI(_post('bancoSelected'))) : NULL ;
    		$fecInicio = $this->input->post('fechaInicio');
    		$fecFin    = $this->input->post('fechaFin');
	        if($tableSel == null){
	            throw new Exception(ANP);
	        }
			if(empty($fecInicio)){
				throw new Exception('Ingrese Fecha Inicio');
			}
			if(empty($fecFin)){
				throw new Exception('Ingrese Fecha Fin');
			}
			if($fecInicio > $fecFin){
				throw new Exception('Fecha Inicio no debe ser mayor que Fecha Fin');
			}
	        if($tableSel == 1){
	            $datos           = $this->m_reportes->getAudiConta($fecInicio, $fecFin);
	            $data['table']   = $this->buildTablaAudiContabilidadHTML($datos, $fecInicio, $fecFin);
	            $data['content'] = 'tableContabilidad';
	        } else if($tableSel == 2){
	            $data['table']   = $this->buildTablaAudiBancoHTML($fecInicio, $fecFin);
	            $data['content'] = 'tableSedesBanco';
	        } else if($tableSel == 3){
	            $datos1          = $this->m_reportes->getAudiMov($fecInicio, $fecFin);
			    $data['table']   = $this->buildTablaAudiMovimientoHTML($datos1, $fecInicio, $fecFin);
			    $data['content'] = 'tableMovimiento';
	        } else if($tableSel == 4){
	            $datos2          = $this->m_reportes->getAudiMovBancos($bancoSel, $fecInicio, $fecFin);
			    $data['table']   = $this->buildTablaAudiPagosBancoHTML($datos2, $fecInicio, $fecFin);
			    $data['content'] = 'tablePagosBanco';
	        }
	    } catch (Exception $e){
	        
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}

	function buildGraficoByTab(){
	    $data['error'] = EXIT_ERROR;
	    try{
	        $tab       = _post('tab');
	        $fecInicio = _post('fecInicio');
	        $fecFin    = _post('fecFin');
	        $datos = $this->m_migracion->getBancosMasUsados($fecInicio, $fecFin);
	        $arraySeries = array();
	        $arrayCate   = array();
	        if(count($datos) > 0) {
	            foreach($datos as $row) {
	                array_push($arraySeries , array('y' => intval($row->count_bancos),
                                	                'name'  => $row->abvr.': '. $row->count_bancos.' pagos',
                                	                'color' => ''
	                                               )
	                           );
	            }
	        } else{
	            throw new Exception('No se puede generar gr&aacute;fico sin datos');
	        }
	        $data['error']  = EXIT_SUCCESS;
	        $data['series'] = json_encode(array($arraySeries));
	        $data['cate']   = json_encode($arrayCate);
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTablaAudiPagosBancoHTML($datos2, $fecInicio, $fecFin) {
		$tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_mov">',
				      'table_close' => '</table>');
		$this->table->set_template($tmpl);
		$head_1 = array('data' => 'Estudiante'        , 'class' => 'text-left');
		$head_2 = array('data' => '&Uacute;ltimo Movimiento' , 'class' => 'text-right');
		$head_4 = array('data' => 'Monto'             , 'class' => 'text-right');
		$head_5 = array('data' => 'Sede'              , 'class' => 'text-left');
		$head_3 = array('data' => 'Banco'             , 'class' => 'text-center');
		$head_7 = array('data' => 'Historial'         , 'class' => 'text-left');
		$head_8 = array('data' => 'Desc. Pago'        , 'class' => 'text-center');
		$this->table->set_heading($head_1, $head_3, $head_2, $head_8, $head_4, $head_5/*, $head_7*/);
		$val = 1;
		foreach ($datos2 as $row) {
		    $idBanco           = $row->id_banco;
			$idPersonaEncripty = _encodeCI($row->nid_persona);
            $imgBanco   = '<img style="cursor:pointer" class="img-banco" src="'.RUTA_IMG.'bancos/'.(json_decode(IMAGENES_BANCO_ID)->$idBanco).'" data-toggle="tooltip" data-placement="bottom">
                           </img>';
			$row_cell_1 = array('data' => $row->nombre_completo    , 'class' => 'text-left');
			$row_cell_2 = array('data' => $row->fecha_pago         , 'class' => 'text-right');
			$row_cell_4 = array('data' => $row->monto              , 'class' => 'text-right');
			$row_cell_5 = array('data' => $row->desc_sede          , 'class' => 'text-left');
			$row_cell_3 = array('data' => $imgBanco               , 'class' => 'text-center');
			$boton = '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalMovHistorial(\''.$idPersonaEncripty.'\',\''.$fecInicio.'\',\''.$fecFin.'\');">
	                      <i class="mdi mdi-list"></i>
	                  </a>';
			$row_cell_7 = array('data' => $boton                   , 'class' => 'text-left');
			$row_cell_8 = array('data' => $row->desc_detalle_crono , 'class' => 'text-center');
			$val++;
			$this->table->add_row($row_cell_1, $row_cell_3, $row_cell_2, $row_cell_8, $row_cell_4, $row_cell_5/*, $row_cell_7*/);
		}
		$tabla = $this->table->generate();
		return $tabla;
	}
}