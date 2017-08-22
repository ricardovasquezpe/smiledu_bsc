<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_boleta extends CI_Controller{

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
        $this->load->model('m_boleta');
        $this->load->model('m_movimientos');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_BOLETAS, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }

    public function index() {
        $data['barraSec']  = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                  <a href="#tab-detalle" class="mdl-layout__tab is-active">Historico</a>
                                  <a href="#tab-compromiso" class="mdl-layout__tab">Generar</a>
       							  <a href="#tab-boleta" class="mdl-layout__tab">Imprimir</a>
                                  <a href="#tab-correlativos" class="mdl-layout__tab">Correlativos</a>
       						  </div>';
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader']      = 'Boletas';
        $data['ruta_logo']        = MENU_LOGO_PAGOS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
        $data['nombre_logo']      = NAME_MODULO_PAGOS;
        //NECESARIO/////////////////////////////////////////////////
        $sede                     = _getSesion('id_sede_trabajo');
        $nro_serie                = $this->m_movimientos->getSerieActivaBySede(_getSesion('id_sede_trabajo'));
        $this->session->set_userdata(array('nro_serie_boleta' => $nro_serie));
        $correDocumento           = $this->m_movimientos->getCurrentCorrelativo(_getSesion('id_sede_trabajo'),DOC_BOLETA,MOV_INGRESO,$nro_serie);
        $correlativo              = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
        //Estilos para quitar funcionalidad boton
        $data['generar'] = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" disabled style="opacity:0.5">
                                <i class="mdi mdi-create"></i>
                            </button>';
        //MENU
        $rolSistemas             = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
        $data['apps']            = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $fecInicio               = date('Y-m').'-01';
        $fecFin                  = date('Y-m').'-31';
        //TABLA HISTORIAL CORRELATIVOS
        $boletas                 = $this->m_boleta->getHistorialDocumentos($sede,$fecInicio,$fecFin);
        $data['tb_correlativos'] = $this->buildTableHistorialDocs($boletas);
        //----------------------------
        //TABLA ULTIMOS CORRELATIVOS
        $data['correlativos'] = $this->buildHistoricoCorrelativos();
        //--------------------------
        //TABLA BOLETAS A IMPRIMIR------
        $arrayBoletas            = $this->m_boleta->getBoletas($nro_serie, $sede);
        $data['tb_bol_imprimir'] = $this->buildTablaBoletasHTML($arrayBoletas);
        $data['btnImprimir']     = '<button class="mdl-button mdl-js-button m-0 mdl-button--raised" '.((count($arrayBoletas) > 0) ? 'onclick="blockPrint();"' : null ).'>
                                 	<i class="mdi mdi-print"></i> Imprimir
                                 </button>';
        //------------------------------
        $data['menu']            = $this->load->view('v_menu', $data, true);
        $data['optCronograma']   = __buildComboCronograma(_getSesion('id_sede_trabajo'));
        ////////////////////////////////////////////////////////////
        $this->session->set_userdata(array('tab_active_movi' => null));
        $this->session->set_userdata(array('tab_active_config' => null));
        $this->load->view('v_boleta', $data);
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
        $idPersona = _getSesion('id_persona');
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
        $idSistema  = _decodeCI(_post('id_sis'));
        $idRol      = _decodeCI(_post('rol'));
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
        $roles     = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'), $idSistema);
        $result    = '<ul>';
        foreach ($roles as $rol) {
            $idRol   = _encodeCI($rol->nid_rol);
            $result .= '<li style="cursor:pointer" onclick="goToSistema(\'' . _post('sistema') . '\', \'' . $idRol . '\')">' . $rol->desc_rol . '</li>';
        }
        $result       .= '</ul>';
        $data['roles'] = $result;
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
	function listarCompromisos() {
		$sede                     = $this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
// 		$arrayCompromisos         = $this->m_boleta->getCompromisos($sede);
		$arrayCuotasIngreso       = $this->m_boleta->getCuotaIngreso($sede);
		$resultado                = array_merge($arrayCuotasIngreso);
		$data['tablaCompromisos'] = $this->buildTablaCompromisosHTML($resultado);
		$nro_serie                = _getSesion('nro_serie_boleta');//$this->m_movimientos->getSerieActivaBySede(_getSesion('id_sede_trabajo'));
		$correDocumento           = $this->m_movimientos->getCurrentCorrelativo(_getSesion('id_sede_trabajo'),DOC_BOLETA,MOV_INGRESO,$nro_serie);
		$correlativo              = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
		$arrayGlobal              = _getSesion('arrayMovimiento');
		$botton                   = null;
		if($arrayGlobal == null) {
		    $botton = '';
		} else if(count($arrayGlobal) == NUM_ROWS_UNO){
		    $botton = 'onclick="openModalGenerar(\''.$correlativo.'\', \''.$correlativo.'\');"';
		} else if(count($arrayGlobal) > NUM_ROWS_UNO){
		    $ultimaBoleta = count($arrayGlobal) + $correDocumento;
		    $ultimaBoleta = $this->getCorrelativoReciboByMovimiento($ultimaBoleta);
		    $botton       = 'onclick="openModalGenerar(\''.$correlativo.'\', \''.$ultimaBoleta.'\');"';
		}
		$data['generar'] = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.$botton.'>
                                <i class="mdi mdi-create"></i>
                            </button>
		                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                <i class="mdi mdi-more_vert"></i>
                            </button>';
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTablaCompromisosHTML($arrayCompromisos,$correlativo) {
		$tmpl = array('table_open' => '<table data-toggle="table" class="table borderless"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="false" id="tb_compromisos">',
				      'table_close' => '</table>');
		$this->table->set_template($tmpl);
		$head_1 = array('data' => 'N&#176;'     , 'class' => 'text-left');
		$head_2 = array('data' => 'Estudiante'  , 'class' => 'text-left');
		$head_3 = array('data' => 'Fecha Pago'  , 'class' => 'text-left');
		$head_4 = array('data' => 'Concepto'    , 'class' => 'text-left');
		$head_5 = array('data' => 'Monto'       , 'class' => 'text-right');
		$head_6 = array('data' => 'Correlativo' , 'class' => 'text-left');
		$this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5,$head_6);
		$nombrePersona=null;
		$val=0;
		$arrayGlobal = array();
		foreach ($arrayCompromisos as $row) {
		    $foto  = '<img alt="Student" class="img-circle m-r-5" WIDTH=25 HIEGHT=25 src="'.RUTA_IMG.'/profile/'.$row->foto_persona.'">'; 
			$val++;
			array_push($arrayGlobal, $row->id_movimiento);
			$row_cell_1 = array('data' => $val                           , 'class' => 'text-left');
			$row_cell_2 = array('data' => $foto.' '.$row->nombrecompleto , 'class' => 'text-left');
			$row_cell_3 = array('data' => $row->fecha_pago               , 'class' => 'text-left');
			$row_cell_4 = array('data' => $row->desc_detalle_crono       , 'class' => 'text-left');
			$row_cell_5 = array('data' => $row->monto                    , 'class' => 'text-right');
			$row_cell_6 = array('data' => $correlativo                   , 'class' => 'text-left');
			$correlativo = $correlativo+1;
			$correlativo = __generateFormatString($correlativo, 8);
			$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5,$row_cell_6);
		}
		$this->session->set_userdata(array('arrayMovimiento' => $arrayGlobal));
		if(count($arrayCompromisos) == 0){
	        return '<div class="img-search m-b-50">
	                    <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                        <p>Ups! No se encontrar&oacute;n boletas por mostrar</p>
	                </div>';   
		} else{
		    return $this->table->generate();
		}
	}
	
	function getCorrelativoReciboByMovimiento($correlativo) {
		$lengthCorre    = strlen($correlativo);
		$correlativoNew = null;
		for($i = $lengthCorre; $i < 8 ; $i++){
			$correlativoNew .= '0';
		}
		$correlativoNew .= $correlativo;
		return $correlativoNew;
	}
	
	function generarBoletas() {
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		$arrayUpdateDocumento  = array();
		$arrayUpdateMovimiento = array();
		try{
		    $sede           = _getSesion('id_sede_trabajo');//$this->m_utils->getSedeTrabajoByColaborador($this->_idUserSess);
		    $arrayGlobal    = _getSesion('arrayMovimiento');
		    $flg_tipo       = _post('flg_tipo');
		    $fecha_emision  = _post('fecha_emision');
		    $idCuota        = _decodeCI(_post('idCuota'));
		    $fechaInicio    = _post('fecInicio');
		    $fechaFin       = _post('fecFin');
			$nro_serie      = _getSesion('nro_serie_boleta');//$this->m_movimientos->getSerieActivaBySede($sede);
			$correDocumento = $this->m_movimientos->getCurrentCorrelativo($sede,DOC_BOLETA,MOV_INGRESO,$nro_serie);
			$correlativo    = $this->getCorrelativoReciboByMovimiento($correDocumento);
			if($sede == null){
			    throw new Exception('Comun&iacute;quese con el administrador por favor....');
			}
			if($idCuota == null && $flg_tipo == '1'){
			    throw new Exception(ANP);
			}
			if($flg_tipo != '0' && $flg_tipo != '1'){
			    throw new Exception('Selecciona un tipo');
			}
			foreach ($arrayGlobal as $row){
			    $correlativo++;
			    $correlativo    = $this->getCorrelativoReciboByMovimiento($correlativo);
			    $correlativoByMov = $this->m_movimientos->getNextCorrelativo($row);
				$subArray = array('_id_movimiento' => $row,
								  'tipo_documento' => DOC_BOLETA,
								  'nro_serie'      => $nro_serie,
								  'nro_documento'  => $correlativo,
								  '_id_sede'       => $sede,
								  'flg_impreso'    => FLG_NO_IMPRESO,
								  'estado'         => ESTADO_CREADO,
								  'fecha_registro' => $fecha_emision,
						          'num_corre'      => $correlativoByMov);
				$subArrMovi = array('id_movimiento' => $row, 
				                    'flg_boleta'    => '1'
				                   );
				array_push($arrayUpdateDocumento, $subArray);
				array_push($arrayUpdateMovimiento, $subArrMovi);
			} 
			$arrayUpdateCorrelativo = array('_id_sede'           => $sede,
											'tipo_documento'     => DOC_BOLETA,
											'tipo_movimiento'    => MOV_INGRESO,
											'nro_serie'          => $nro_serie,
											'numero_correlativo' => $correlativo,
											'accion'             => (($correDocumento == null) ? INSERTA : ACTUALIZA));
			$data = $this->m_boleta->registrarBoletasByCompromisos($arrayUpdateDocumento,$arrayUpdateCorrelativo,$arrayUpdateMovimiento);
			if($data['error'] == EXIT_SUCCESS){
				$arrayCompromisos         = $this->m_boleta->getCompromisos($sede,$idCuota,$flg_tipo,$fechaInicio,$fechaFin);
				$data['tablaCompromisos'] = $this->buildTablaCompromisosHTML($arrayCompromisos,$correlativo);
				//INFO CORRELATIVOS
				$botton                   = null;
				if($arrayGlobal == null) {
				    $botton = '';
				} else if(count($arrayGlobal) == NUM_ROWS_UNO){
				    $botton = 'onclick="openModalGenerar(\''.$correlativo.'\', \''.$correlativo.'\');"';
				} else if(count($arrayGlobal) > NUM_ROWS_UNO){
				    $ultimaBoleta = count($arrayGlobal) + $correDocumento;
				    $ultimaBoleta = $this->getCorrelativoReciboByMovimiento($ultimaBoleta);
				    $botton       = 'onclick="openModalGenerar(\''.$correlativo.'\', \''.$ultimaBoleta.'\');"';
				}
				$data['generar'] = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.$botton.'>
                                        <i class="mdi mdi-create"></i>
                                    </button>
        		                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>';
			}
		} catch(Exception $e){
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
    function listarBoletas() {
    	$sede                 = _getSesion('id_sede_trabajo');
    	$serie                = _getSesion('nro_serie_boleta');//$this->m_boleta->serieSede($sede);
    	$arrayBoletas         = $this->m_boleta->getBoletas($serie, $sede);
    	$data['tableBoletas'] = $this->buildTablaBoletasHTML($arrayBoletas);
    	$botton               = null;
    	if(count($arrayBoletas) == 0){$botton = ''; }else{$botton = 'onclick="blockPrint();"';}
    	$data['imprimir']     = '<button class="mdl-button mdl-js-button m-0 mdl-button--raised" '.$botton.'>
                                 	<i class="mdi mdi-print"></i> Imprimir
                                 </button>';
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function imprimirBoleta() {
    	$boletas        = $this->input->post('arrayPagar');
    	$boletasAux     = array();
    	foreach($boletas as $corre){
    	    if($corre != '00000000'){
    	        array_push($boletasAux, $corre);
    	    }
    	}
    	$result         = array();
    	$data           = array();
    	$sede           = _getSesion('id_sede_trabajo');
    	$serie          = _getSesion('nro_serie_boleta');//$this->m_boleta->serieSede($sede);
    	$arrayBoletas   = $this->m_boleta->getBoletasPrint($boletasAux, $serie);
        //$arrayBoletasCuotas   = $this->m_boleta->getBoletasCuotasIngresoPrint($serie, $boletas);
// 		$resultado = array_merge($arrayBoletas, $arrayBoletasCuotas);
		
 		$updateBoleta   = array();
 		$insertAudiDoc  = array();
		$nombrecompleto = _getSesion('nombre_completo');
		foreach ($arrayBoletas as $row) {
		    //$row->ubicacion = $this->m_boleta->getPersonaUbicacion($row->_id_persona, $row->year);
		    unset($row->_id_persona);
		    if($row->estado == ESTADO_ANULADO){
		    	$subArray = array('nombrecompleto' => utf8_encode($row->nombrecompleto),
		    					  'ubicacion'      => utf8_encode($row->ubicacion),
		    					  'nro_documento'  => $row->nro_documento,
		    					  'fecha'          => $row->fecha_emision,
		    					  'cuota'          => utf8_encode($row->desc_detalle_crono),
		    					  'descuento'      => 0,
		    					  'monto'          => 0,
		    					  'total'          => 0,
		    					  'mora'           => 0);
		    }else if($row->estado == ESTADO_CREADO){
		    	if($row->mora_acumulada == 0 ){
		    		$total = (float)$row->monto - (float)$row->descuento_acumulado;
		    	}else if($row->mora_acumulada > 0 ){
		    		$total = (float)$row->monto + (float)$row->mora_acumulada;
		    	}
		    	$subArray = array('nombrecompleto' => utf8_encode($row->nombrecompleto),
		    					  'ubicacion'      => utf8_encode($row->ubicacion),
		    					  'nro_documento'  => $row->nro_documento,
		    					  'fecha'          => $row->fecha_emision,
		    					  'cuota'          => utf8_encode($row->desc_detalle_crono),
		    					  'descuento'      => $row->descuento_acumulado,
		    					  'monto'          => $row->monto,
		    					  'total'          => $total,
		    					  'mora'           => $row->mora_acumulada);
		    }
			$subArrayUpdate = array('nro_documento'  => $row->documento,
							    	'estado'         => 'IMPRESO',
									'_id_sede'       => $row->_id_sede);
			$subArrayInsert = array('_id_movimiento' => $row->_id_movimiento,
							    	'tipo_documento' => $row->tipo_documento,
									'correlativo'    => $row->num_corre,
							    	'id_pers_regi'   => $this->_idUserSess,
									'audi_pers_regi' => $nombrecompleto,
									'accion'         => 'IMPRIMIR',
							    	'nro_documento'  => $row->documento);
		    $subArray = json_encode($subArray);
		    array_push($data, $subArray);
		    array_push($updateBoleta, $subArrayUpdate);
		    array_push($insertAudiDoc, $subArrayInsert);
	    }
	    $result['arrays']       = json_encode($data);
		$data = $this->m_boleta->actualizarBoletas($updateBoleta, $serie, $insertAudiDoc);
		if($data['error'] == EXIT_SUCCESS){
		    $arrayBoletas         = $this->m_boleta->getBoletas($serie, $sede);
// 		    $arrayBoletasCuotas   = $this->m_boleta->getBoletasCuotasIngreso($serie, $sede);
// 		    $resultado = array_merge($arrayBoletas, $arrayBoletasCuotas);
		    $result['tableBoletas'] = $this->buildTablaBoletasHTML($arrayBoletas);
		}
    	echo json_encode(array_map('utf8_encode', $result));
    }
    
    function buildTablaBoletasHTML($arrayBoletas) {
    	$tmpl = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" 
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="false" id="tb_boleta">',
    			      'table_close' => '</table>');
    	$this->table->set_template($tmpl);
    	//BLOQUEAR CHECK SELECCIONAR TODO
    	$head_1 = array('data' => '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-allx">
									  <input type="checkbox" id="checkbox-allx" class="mdl-checkbox__input" onclick="checkAllBoletas($(this));assignItemAUX(this.id, \'tb_boleta\', \'cabeConfirmar\');">
    									<span class="mdl-checkbox__label"></span>
									</label>', 'class' => 'text-center');
//     	$head_1 = array('data' => '#');
    	$head_2 = array('data' => 'Estudiante'           , 'class' => 'text-left');
    	$head_3 = array('data' => 'Fecha Emisi&oacute;n' , 'class' => 'text-center');
    	$head_4 = array('data' => 'Fecha Pago'           , 'class' => 'text-center');
    	$head_5 = array('data' => 'Concepto'             , 'class' => 'text-left');
    	$head_6 = array('data' => 'Nro. Boleta'          , 'class' => 'text-right');
    	$head_7 = array('data' => 'Estados'              , 'class' => 'text-center');
    	$this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
    	$val   = 0;
    	$orden = 0;
    	foreach ($arrayBoletas as $row) {
    		$val++;
			$icon = ($row->estado == DOC_IMPRESO) ? '<a style="cursor:pointer"><i class="mdi mdi-lock"></i></a>' : '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkComp'.$val.'">
																													    <input type="checkbox" attr-id_movi="'.$row->nro_documento.'" id="checkComp'.$val.'" 
																														attr-orden="'.$orden.'" onclick="addRemoveToArray($(this),null);assignItemAUX(this.id, \'tb_boleta\', \'cabeConfirmar\');"class="mdl-checkbox__input" >
																													</label>';
    		$row_cell_1 = array('data' => $icon, 'class' => 'text-center');
    		$row_cell_2 = array('data' => $row->nombrecompleto     , 'class' => 'text-left');
    		$row_cell_3 = array('data' => $row->fecha_emision      , 'class' => 'text-center');
    		$row_cell_4 = array('data' => $row->fecha_pago         , 'class' => 'text-center');
    		$row_cell_5 = array('data' => $row->desc_detalle_crono , 'class' => 'text-left');
    		$row_cell_6 = array('data' => $row->nro_documento      , 'class' => 'text-right');
    		$row_cell_7 = array('data' => $row->estado             , 'class' => 'text-center');
    		$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6, $row_cell_7);
    		$orden++;
    	}
    	$imgEmpty = '<div class="img-search">
                        <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                        <p>Ups! A&uacute;n no se han registrado datos.</p>
                    </div>';
    	return (count($arrayBoletas) > 0) ? $this->table->generate() : $imgEmpty;
    }
    
    function reordenarArray($resultado) {
    	$subArray = array();
    	$count = 0;
    	$array = array();
    	foreach ($resultado as $row){
    		if ($row->estado == 'CREADO'){
    			array_push($subArray, $row);
    		unset($resultado[$count]);
    		}else{
            	array_push($array, $row);
    		}
    		$count++;
    	}
    	foreach ($subArray as $row){
    		array_unshift($array, $row);
    	}
    	return $array;
    }
    
    function buildTableHistorialDocs($boletas){
        $tmpl = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_historial_boleta">',
                                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#');
    	$head_2 = array('data' => 'Estudiante'           , 'class' => 'text-left');
    	$head_3 = array('data' => 'Cuota'                , 'class' => 'text-center');
    	$head_4 = array('data' => 'Correlativo'          , 'class' => 'text-center');
    	$head_5 = array('data' => 'Fecha Emisi&oacute;n' , 'class' => 'text-center');
    	$head_6 = array('data' => 'Fecha Pago'           , 'class' => 'text-left');
    	$head_7 = array('data' => 'Estado'               , 'class' => 'text-right');
    	$this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
    	$val = 1;
    	foreach($boletas as $row) {
    	    $fotoAux = (($row->foto_persona == null) ? 'nourse.svg' : $row->foto_persona);
    	    $foto    = (file_exists(FOTO_PROFILE_PATH . 'estudiantes/' . $fotoAux)) ?  RUTA_IMG_PROFILE.'estudiantes/'.$fotoAux : RUTA_SMILEDU.FOTO_DEFECTO;
    	    $img  = '<img width=25 height=25 class="img-circle m-r-10" src="'.$foto.'">';
    	    $colorCelda = (($row->flg_anulado == '1') ? 'colorBoleta' : null);
    	    $row_cell_1 = array('data' => $val                  , 'class' => 'text-left '. $colorCelda);
    	    $row_cell_2 = array('data' => $img .' '. $row->nombre_completo , 'class' => 'text-left '. $colorCelda);
    		$row_cell_3 = array('data' => $row->detalle         , 'class' => 'text-center '. $colorCelda);
    		$row_cell_4 = array('data' => $row->correlativo     , 'class' => 'text-center '. $colorCelda);
    		$row_cell_5 = array('data' => $row->fecha_registro  , 'class' => 'text-center '. $colorCelda);
    		$row_cell_6 = array('data' => $row->fecha_pago      , 'class' => 'text-left '. $colorCelda);
    		$row_cell_7 = array('data' => $row->estado          , 'class' => 'text-right '. $colorCelda);
    	   	$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6, $row_cell_7);
    	   	$val++;
    	}
    	return (count($boletas) > 0) ? $this->table->generate() :
                                    	'<div class="img-search m-b-50">
                    	                     <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                                             <p>Ups! No se encontrar&oacute;n correlativos</p>
                    	                 </div>';
    }
    
    function comboCronogramaCuota() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idCronograma    = empty($this->input->post('idCronograma')) ? null : _decodeCI($this->input->post('idCronograma'));
            $data['optCuotas'] = null;
            if($idCronograma == null) {
                $data['error']    = EXIT_ERROR;
                $data['optCuotas'] = null;
                throw new Exception(ANP);
            }
            $data['optCuotas'] = __buildComboCuotasByCronograma($idCronograma);
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getCompromisosByCuota(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idCuota     = _decodeCI(_post('idCuota'));
            $sede        = _getSesion('id_sede_trabajo');
            $flg_tipo    = _post('flg_tipo');
            $fechaInicio = _post('fecInicio');
            $fechaFin    = _post('fecFin');
            if($idCuota == null && $flg_tipo = '0'){
                throw new Exception(ANP);
            }
            if($sede == null){
                throw new Exception(ANP);
            }
            if($flg_tipo != '1' && $flg_tipo != '0'){
                throw new Exception(ANP);
            }
            $idCuota     = ($flg_tipo == '1') ? $idCuota : 0;
            //CORRELATIVO
            $nro_serie                = _getSesion('nro_serie_boleta');//$this->m_movimientos->getSerieActivaBySede(_getSesion('id_sede_trabajo'));
            $correDocumento           = $this->m_movimientos->getCurrentCorrelativo(_getSesion('id_sede_trabajo'),DOC_BOLETA,MOV_INGRESO,$nro_serie)+1;
            $correlativo              = __generateFormatString($correDocumento, 8);
            $arrayCompromisos         = $this->m_boleta->getCompromisos($sede,$idCuota,$flg_tipo,$fechaInicio,$fechaFin);
            $data['tablaCompromisos'] = $this->buildTablaCompromisosHTML($arrayCompromisos,$correlativo);
            $arrayGlobal              = _getSesion('arrayMovimiento');
            $botton                   = null;
            if($arrayGlobal == null) {
                $botton = '';
            } else if(count($arrayGlobal) == NUM_ROWS_UNO){
                $botton = 'onclick="openModalGenerar(\''.$correlativo.'\', \''.$correlativo.'\');"';
            } else if(count($arrayGlobal) > NUM_ROWS_UNO){
                $ultimaBoleta = (count($arrayGlobal)-1) + $correDocumento;
                $ultimaBoleta = __generateFormatString($ultimaBoleta,8);
                $botton       = 'onclick="openModalGenerar(\''.$correlativo.'\', \''.$ultimaBoleta.'\');"';
            }
            $data['generar'] = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.$botton.'>
                                <i class="mdi mdi-create"></i>
                            </button>
		                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                <i class="mdi mdi-more_vert"></i>
                            </button>';
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['error'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function buildHistoricoCorrelativos(){
        $correlativos = $this->m_boleta->getHistoricoCorrelativos(_getSesion('id_sede_trabajo'));
        $tmpl = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_historial_boleta">',
                                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#');
    	$head_2 = array('data' => 'Tipo'           , 'class' => 'text-left');
    	$head_3 = array('data' => 'Correlativo'    , 'class' => 'text-center');
    	$this->table->set_heading($head_1, $head_2, $head_3);
    	$val = 1;
    	foreach($correlativos as $row) {
    	    $row_cell_1 = array('data' => $val                     , 'class' => 'text-left');
    	    $row_cell_2 = array('data' => $row->tipo_documento     , 'class' => 'text-left');
    	    $row_cell_3 = array('data' => $row->numero_correlativo , 'class' => 'text-center');
    	    $val++;
    	    $this->table->add_row($row_cell_1,$row_cell_2,$row_cell_3);
    	}
    	return $this->table->generate();
    }
}