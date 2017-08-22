<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_cronograma_detalle extends CI_Controller
{
    
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
        $this->load->model('m_cronograma');
        $this->load->model('m_utils_pagos');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, null, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
    
    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
        $cronogramas = $this->m_cronograma->getSedeByCronograma($this->session->userdata("id_cronograma_sesion"));
        ////Modal Popup Iconos///
        $data['titleHeader']      = 'Cronograma '.$cronogramas['year'];
        $data['return']           = '1';
        $data['ruta_logo']        = MENU_LOGO_PAGOS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
        $data['nombre_logo']      = NAME_MODULO_PAGOS;
        //MENU
        $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
        $data['apps']             = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']             = $this->load->view('v_menu', $data, true);
        //NECESARIO
        $data['botones']          = null;
        $data['tittleTable']      = $cronogramas['desc_sede'].' ('.$cronogramas['desc_tipo_cronograma'].')';
        $data['radios']           = $this->buildRadioButton($cronogramas['_id_sede'], $cronogramas['year'], $cronogramas['id_tipo_cronograma']);
        $data['comboTipo'] = (_getSesion('id_tipo_crono_sess') == CRONO_SPORT_SUMMER || _getSesion('id_tipo_crono_sess') == CRONO_SPORT_SUMMER) ? 
                                 $this->buildComboPaquetesByTipo(_getSesion('id_tipo_crono_sess')) :
                                 null;        
        if($cronogramas['flg_cerrado'] == 0){
            $data['botones'] = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="modal" data-target="#modalCalendarioEditCronograma" onclick="mostrar_calendario_cuotas();" data-toggle="tooltip" data-placement="bottom" data-original-title="Calendario">
			                        <i class="mdi mdi-event"></i>
			                    </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="modal" data-target="#modalCrearDetalleCronograma" onclick="mostrar_radios_detalle_crono();">
                                    <i class="mdi mdi-edit"></i>
                                </button>
			                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="abrirModalPaquete(\'Opciones de tabla\')">
			                        <i class="mdi mdi-more_vert"></i>
			                    </button>
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised btn-cuotas" id="botonDefCuotas" data-toggle="modal" data-target="#modalDefinirCuotas">
								    <i class="mdi mdi-lock_open"></i> 
                                    <span id="s1">Definir Cuotas</span>
                                    <span id="s2">Cuotas</span>
								</button>';
        }else{
            $data['botones'] ='<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" disabled>
							       <i class="mdi mdi-lock"></i> Cuota Definida
							   </button>';
        }
        
        $data['lista_cronograma'] = $this->getDetalleCronograma($this->session->userdata("id_cronograma_sesion"));
        ///////////
        $this->session->set_userdata(array('tab_active_movi' => null));
        $this->load->view('v_cronograma_detalle', $data);
    }
    
    function definirCuotas() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$idCronograma  = $this->session->userdata("id_cronograma_sesion");
    	try{
    	    $cantidad = count($this->m_cronograma->getDetalleCronograma($idCronograma));
    	    $valorCerrar   = _decodeCI(_post('cerrar'));
    	    if($cantidad == 0) {
    	        throw new Exception('Primero debe generar los conceptos');
    	    }
    	    if($valorCerrar != 1 && $valorCerrar != 2 && $valorCerrar != 3) {
    	        throw new Exception('Selecciona una opci&oacute;n');
    	    }
    	    $arrayUpdate = ($valorCerrar == 1) ? array('flg_cerrado_mat' => 1)
                                    	       : array('flg_cerrado'     => 1,
                                           	           'flg_cerrado_mat' => 1
			               );
//     		$update   = array('flg_cerrado' => 1);
    		$data                     = $this->m_cronograma->cerrarCronograma($idCronograma, $arrayUpdate);
    		$data['lista_cronograma'] = $this->buildConcTablaCronogramaHTML($this->m_cronograma->getDetalleCronograma($idCronograma));
    		$cronogramas              = $this->m_cronograma->getSedeByCronograma($idCronograma);
    		$data['botones']          = null;
    		if($cronogramas['flg_cerrado'] == 0){
    			$data['botones'] = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="modal" data-target="#modalCalendarioEditCronograma" onclick="mostrar_calendario_cuotas();">
			                         	<i class="mdi mdi-event"></i>
			                        </button>
			                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="modal" data-target="#modalCrearDetalleCronograma" onclick="mostrar_radios_detalle_crono();">
			                        	<i class="mdi mdi-edit"></i>
			                        </button>
			                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="modal" data-target="#modalConfirmar">
			                        	<i class="mdi mdi-print"></i>
			                        </button>
    			                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised btn-cuotas" data-toggle="modal" data-target="#modalDefinirCuotas">
										<i class="mdi mdi-lock_open"></i> Definir Cuotas
									</button>';
    		}else{
    			$data['botones'] ='<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" disabled>
										<i class="mdi mdi-lock"></i> Cuota Definida
									</button>';
    		}
    		$data['radios']           = $this->buildRadioButton($cronogramas['_id_sede'], $cronogramas['year'], $cronogramas['id_tipo_cronograma']);
    	}catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function deleteConceptoToCronograma() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $id_cronograma = empty($this->input->post('idConcepto')) ? null : _decodeCI(str_replace(" ", "+", $this->input->post('idConcepto')));
            
            if ($id_cronograma == null) {
                throw new Exception('Seleccione una sede');
            }
            
            $data                     = $this->m_cronograma->eliminarConceptosCronograma($id_cronograma);
            $data['lista_cronograma'] = $this->getDetalleCronograma($this->session->userdata("id_cronograma_sesion"));
            $this->session->set_userdata("lista_cronograma_sesion", $data['lista_cronograma']);
            $data['error'] = EXIT_SUCCESS;
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildRadiosByCrono($idCronograma,$accion,$checked = null){
        $result = $this->m_cronograma->getTipoCuotasByCronograma($idCronograma);
        $radios = null;
        $cont   = 1;
        foreach($result as $row){
            $radios .= '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-r-10" for="'.$accion.$cont.'">
                            <input type="radio" id="'.$accion.$cont.'" class="mdl-radio__button" name="'.(($accion == 'registrar') ? 'condicion_op2' : 'condicion_op1').'" value="'._encodeCI($row->id_tipo_cuota).'" '.(($cont == count($result) && $checked == null) ? 'checked' : (($checked == $row->id_tipo_cuota) ? 'checked' : null)).'>
                            <span class="mdl-radio__label">'.$row->desc_tipo_cuota.'</span>
                        </label>';
            $cont++;
        }
        return $radios;
    }
    
    function mostrar_CrearConcepto() {
        $id_cronograma  = $this->session->userdata('id_cronograma_sesion');
        $data['radios'] = $this->buildRadiosByCrono($id_cronograma,'registrar');
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function mostrar_editConcepto() {   
    	$sede           = $this->session->userdata("id_sede_cronograma_sesion");
    	$id_cronograma  = $this->session->userdata('id_cronograma_sesion');
        $idConcepto     = _decodeCI(str_replace(" ", "+", $this->input->post('idConcepto'))); 
        $data           = $this->m_cronograma->getItemConceptoCronograma2($idConcepto);
        $data['radios'] = $this->buildRadiosByCrono($id_cronograma,'editar',$data['condicional']);
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function editConceptoToCronograma() {
        $sede            = $this->session->userdata("id_sede_cronograma_sesion");
        $id_cronograma   = $this->session->userdata('id_cronograma_sesion');
        $idConcepto      = _decodeCI(str_replace(" ", "+", $this->input->post('concepto2')));
        $data['error']   = EXIT_ERROR;
        $data['msj']     = null;
        $desc_detalle    = trim(utf8_decode($this->input->post('desc_detalle')));
        $mora            = trim($this->input->post('mora'));
        $fec_vencimiento = trim($this->input->post('fvencimiento'));
        $fec_descuento   = trim($this->input->post('fdescuento'));
        $flg_tipo        = _decodeCI(trim($this->input->post('condicion')));
        $id_persona      = $this->_idUserSess;
        $name_persona    = _getSesion('nombre_completo');
        $descOriginal    = $this->m_utils->getById('pagos.detalle_cronograma', 'desc_detalle_crono', 'id_detalle_cronograma', $idConcepto, 'pagos');
        
        try {
            if ($desc_detalle == null) {
                throw new Exception('Ingresa una Descripci&oacute;n,');
            }
            if($flg_tipo == 3){
	            if ($mora == null) {
	            	throw new Exception('Ingresa la mora');
	            }
	            if (!is_numeric($mora)) {
	            	throw new Exception('La mora debe ser num&eacute;rico');
	            }
	            if (1 <= $mora) {
	            	throw new Exception('La mora debe ser menor que 1');
	            }
	            if($mora < 0){
	            	throw new Exception('La mora no puede ser negativo');
	            }
            }else {
            	if ($mora == null) {
            		$mora = 0;
            	}
            }
            
            if ($fec_vencimiento == null) {
            	throw new Exception('Ingresa la fecha de vencimiento');
            }
            if ($flg_tipo == null) {
                throw new Exception('La condici&oacuten no es v&aacute;lida');
            }
            
            if (50 <= strlen($desc_detalle)) {
                throw new Exception('La descripci&oacuten debe ser menor de 50 caracteres');
            }
			if($flg_tipo == 3){
	            if ($fec_descuento == null /*&& $fecha_valida_fdescuento != 1*/) {
	                throw new Exception('La fecha de descuento no es v&aacute;lida');
	            }
			}
            if ($fec_vencimiento <= $fec_descuento) {
                throw new Exception('La fecha de descuento debe ser menor que la fecha de vencimiento');
            }
            if(strtolower($desc_detalle) != strtolower($descOriginal)){
                $data['validar_descConceptoCrono'] = $this->m_cronograma->validarDescConceptoCronograma($desc_detalle, $id_cronograma);
                if ($data['validar_descConceptoCrono'] != null) {
                    throw new Exception('La descripci&oacute;n ya existe, por favor ingresa otra');
                }      
            }
            $data['year_cronograma'] = $this->m_cronograma->year_cronograma($id_cronograma);
            $dateFecV                = explode('/' ,  $fec_vencimiento);
            $dateFecD                = explode('/' , $fec_descuento);
            if ($data['year_cronograma'] != $dateFecV[2]) {
                throw new Exception('El a&ntilde;o de vencimiento debe ser igual al del cronograma');
            }
            if ($fec_descuento != null && $data['year_cronograma'] != $dateFecD[2]) {
                throw new Exception('El a&ntilde;o de descuento debe ser igual al del cronograma');
            }
            if ($fec_descuento != null && $dateFecV[1] != $dateFecD[1]) {
                throw new Exception('El mes de la fecha de descuento y vencimiento deben ser iguales');
            }
            $valMesCrono = $this->m_cronograma->validar_ConceptoMesCrono((int)$dateFecV[1],$id_cronograma,$sede,$dateFecV[2]);
            
            if ($valMesCrono[0]['cant_cuotas'] < $valMesCrono[0]['n_cuotas']) {
                throw new Exception('No puedes poner m&aacute;s conceptos en el mes');
            }
            if ($fec_descuento != null) {
                $datos = array("desc_detalle_crono"  => $desc_detalle,
			                    "cantidad_mora"       => $mora,
			                    "fecha_vencimiento"   => $fec_vencimiento,
			                    "fecha_descuento"     => $fec_descuento,
			                    "flg_tipo"            => $flg_tipo,
                		        "id_pers_registro"    => $id_persona,
                		        "nombre_pers_registro" => $name_persona);
            } else {
                
                $datos = array("desc_detalle_crono"  => $desc_detalle,
			                    "cantidad_mora"       => $mora,
			                    "fecha_vencimiento"   => $fec_vencimiento,
			                    "fecha_descuento"     => NULL,
			                    "flg_tipo"            => $flg_tipo,
                		        "id_pers_registro"    => $id_persona,
                		        "nombre_pers_registro" => $name_persona);
            }
            $data                     = $this->m_cronograma->editarConceptosCronograma($idConcepto, $datos);
            $data['lista_cronograma'] = $this->getDetalleCronograma($id_cronograma);
            $data['error']            = EXIT_SUCCESS;
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function addConceptoToCronograma() {
        $data['error']   = EXIT_ERROR;
        $sede            = $this->session->userdata("id_sede_cronograma_sesion");
        $id_cronograma   = trim($this->session->userdata("id_cronograma_sesion"));
        $idTipoCrono     = _getSesion('id_tipo_crono_sess');
        $desc_detalle    = _ucfirst(trim(utf8_decode($this->input->post('desc_detalle'))));
        $mora            = empty(trim($this->input->post('mora'))) ? 0    : trim($this->input->post('mora'));
        $fec_vencimiento = trim($this->input->post('fvencimiento'));
        $fec_descuento   = trim($this->input->post('fdescuento'));
        $flg_tipo        = _decodeCI(trim($this->input->post('condicion')));
        $id_persona      = $this->_idUserSess;
        $name_persona    = _getSesion('nombre_completo');
        $idPaquete       = (_decodeCI(_post('paquete')) == null) ? 0 : _decodeCI(_post('paquete'));
        try {
            if ($idTipoCrono != CRONO_CREATIVE_SUMMER && $idTipoCrono != CRONO_SPORT_SUMMER && $desc_detalle == null) {
                throw new Exception('Ingresa una descrici&oacute;n');
            }
            if($idTipoCrono == CRONO_SPORT_SUMMER && $idPaquete == null){
                throw new Exception('Selecciona un paquete');
            }
            if($fec_vencimiento == null){
                throw new Exception('Ingresa una fecha de vencimiento');
            }
            if (!is_numeric($mora)) {
                throw new Exception('La mora debe ser num&eacute;rico');
            }
            if (strlen($mora) > 6) {
                throw new Exception('La mora debe ser m&aacute;ximo de 5 d&iacute;gitos');
            }
            if ($flg_tipo == null) {
                throw new Exception('La condici&oacute;n no es v&aacute;lida');
            }
            if (1 <= $mora) {
                throw new Exception('La mora debe ser menor que 1');
            }
            if($mora < 0){
                throw new Exception('La mora no puede ser negativo');
            }
            if($flg_tipo == 3){
	            if ($fec_descuento == null) {
	                throw new Exception('La fecha de descuento no es v&aacute;lida');
	            }
            }
            if ($fec_vencimiento <= $fec_descuento) {
                throw new Exception('La fecha de descuento debe ser menor que la fecha de vencimiento');
            }
            $fec_vencimiento_aux = explode('/', $fec_vencimiento);
            if(!(checkdate($fec_vencimiento_aux[1],$fec_vencimiento_aux[0],$fec_vencimiento_aux[2]))){
                throw new Exception('Ingresa una fecha v&aacute;lida');
            }
            $data['year_cronograma'] = $this->m_cronograma->year_cronograma($id_cronograma);
            $dateFecV                = explode('/',$fec_vencimiento);
            $dateFecD                = explode('/',$fec_descuento);
            if ($data['year_cronograma'] != $dateFecV[2]) {
                throw new Exception('El a&ntilde;o de vencimiento debe ser igual al del cronograma');
            }
            if ($fec_descuento != null && $data['year_cronograma'] != $dateFecD[2]) {
                throw new Exception('El a&ntilde;o de descuento debe ser igual al del cronograma');
            }
            if ($idTipoCrono == 2 && ($fec_descuento != null && $dateFecV[1] != $dateFecD[1])) {
                throw new Exception('El mes de descuento debe ser igual a la fecha de vencimiento');
            }
            $valMesCrono = $this->m_cronograma->validar_ConceptoMesCrono((int)$dateFecV[1],$id_cronograma,$sede,$dateFecV[2]);
            if($valMesCrono == null){
                throw new Exception('Hubo un problema en el servidor, intentalo m&aacute;s tarde');
            }
            if ($valMesCrono[0]['cant_cuotas'] < $valMesCrono[0]['n_cuotas']+1) {
                throw new Exception('No puedes poner m&aacute;s conceptos en el mes de '.__mesesTexto($dateFecV[1]));
            }
            if($idTipoCrono != CRONO_CREATIVE_SUMMER && $idTipoCrono != CRONO_SPORT_SUMMER){
                if (50 < strlen($desc_detalle)) {
                    throw new Exception('La descripci&oacute;n debe ser menor de 50 caracteres');
                }
                $data['validar_descConceptoCrono'] = $this->m_cronograma->validarDescConceptoCronograma($desc_detalle, $id_cronograma);
                if ($data['validar_descConceptoCrono'] != null) {
                    throw new Exception('La descripci&oacute;n ya existe, por favor ingresa otra');
                }
            } else{
                $desc_detalle = $this->m_utils->getById('pagos.paquete', 'desc_paquete','id_paquete', $idPaquete).' '.$data['year_cronograma'];
            }
            $idPaquete = ($idTipoCrono == CRONO_SPORT_SUMMER) ? $idPaquete : 0;
            date_default_timezone_set('America/Lima');
            $flg_beca = '0';
            if ($fec_descuento != null) {
                $crear_cronograma = array("desc_detalle_crono"    => $desc_detalle,
                                           "cantidad_mora"        => $mora,
                                           "fecha_vencimiento"    => $fec_vencimiento,
                                           "fecha_descuento"      => $fec_descuento,
                                           "_id_cronograma"       => $id_cronograma,
                                           "flg_tipo"             => $flg_tipo,
                                           "flg_beca"             => $flg_beca,
                		                   "id_pers_registro"     => $id_persona,
                		                   "nombre_pers_registro" => $name_persona,
                                           "_id_paquete"          => $idPaquete
                                          );
            } else {
                $crear_cronograma = array("desc_detalle_crono"  => $desc_detalle,
                                           "cantidad_mora"       => $mora,
                                           "fecha_vencimiento"   => $fec_vencimiento,
                                           "_id_cronograma"      => $id_cronograma,
                                           "fecha_descuento"     => NULL,
                                           "flg_tipo"            => $flg_tipo,
                                           "flg_beca"            => $flg_beca);
            }
            $data = $this->m_cronograma->crearConceptosCronograma($crear_cronograma);
            if ($data['error'] == EXIT_SUCCESS) {
                if($idTipoCrono != null){
                    $data['optPaquete'] = $this->buildComboPaquetesByTipo($idTipoCrono);
                }
                $data['lista_cronograma'] = $this->buildConcTablaCronogramaHTML($this->m_cronograma->getDetalleCronograma($data['insert_id']));
                $this->session->set_userdata("lista_cronograma_sesion", $data['lista_cronograma']);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        $data['lista_cronograma'] = $this->getDetalleCronograma($id_cronograma);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getDetalleCronograma($id_cronograma){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if ($id_cronograma == null) {
                $data['error']    = EXIT_ERROR;
                $data['optNivel'] = null;
            }
            $data['lista_cronograma'] = $this->buildConcTablaCronogramaHTML($this->m_cronograma->getDetalleCronograma($id_cronograma));
            $this->session->set_userdata("lista_cronograma_sesion", $data['lista_cronograma']);
            $data['error']            = EXIT_SUCCESS;
            return $data['lista_cronograma'];
            
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    }
    
    function saveBecaCronograma() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $id_concepto_cronograma = _decodeCI(trim($this->input->post('concepto')));
            $beca                   = (empty($this->input->post('beca'))) ? null : trim($this->input->post('beca'));
	        $id_persona             = $this->_idUserSess;
	        $name_persona           = _getSesion('nombre_completo');
            if ($id_concepto_cronograma == null) {
                throw new Exception('No existe el concepto del cronograma');
            }
            if($beca == "checked"){
                $beca = '1';
            } else{
                $beca = '0';
            }
            $data = $this->m_cronograma->saveBecaCronograma($id_concepto_cronograma,$beca, $id_persona, $name_persona);
            $data['error']            = EXIT_SUCCESS;
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildConcTablaCronogramaHTML($listaCronograma) {
        $tmpl = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-show-columns="false" data-search="false" id="tb_cronograma">',
           			  'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Descripci&oacute;n'    , 'class' => 'text-left');
        $head_2 = array('data' => 'Fecha Desc.'           , 'class' => 'text-center');
        $head_3 = array('data' => 'Fecha Venc.'           , 'class' => 'text-center');
        $head_4 = array('data' => '% Mora'                , 'class' => 'text-center');
        $head_5 = array('data' => '&#191;Aplica beca&#63;', 'class' => 'text-center');
        $head_6 = array('data' => 'Acciones'              , 'class' => 'text-center');
        $val    = 0; 
        
        $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5,$head_6);
        $count = 0;
        $idCronograma = $this->session->userdata("id_cronograma_sesion");
        $cronogramas = $this->m_cronograma->getSedeByCronograma($idCronograma);
        foreach($listaCronograma as $row) {
        	$fecha_vencimiento = null;
        	if($row->count != 0 ){
        		$fecha_vencimiento = $row->fecha_vencimiento;
        	}
            $count++;
            $idCryptCronograma    = _encodeCI($row->id_detalle_cronograma);
            $row_cell_1           = array('data' => ucwords(strtolower($row->desc_detalle_crono)), 'class' => 'text-left');
            $row_cell_2 = null;
            if(trim($row->fecha_descuento) != null){
                $date_descuento   = new DateTime($row->fecha_descuento);
                $row_cell_2       = array('data' => ucwords(strtolower($date_descuento->format('d/m/Y'))), 'class' => 'text-center');
            }
            else{
                $row_cell_2       = array('data' => '');
            }
            $date_vencimiento     = new DateTime($row->fecha_vencimiento);
            $row_cell_3           = array('data' => ucwords(strtolower($date_vencimiento->format('d/m/Y'))), 'class' => 'text-center');
            $row_cell_4           = array('data' => ucwords(strtolower($row->cantidad_mora)), 'class' => 'text-center');
            $checked = ($row->flg_beca == 1) ? 'checked' : '';
            $onclickSwitch        = 'onclick="cambiar_estado_beca_cronograma(\'' . _encodeCI($row->id_detalle_cronograma) . '\',\'switch-' .$count . '\')"';
            $row_cell_5           = array( 'data' =>'<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch-'.$count.'">
                                                     	<input type="checkbox" id="switch-'.$count.'" class="mdl-switch__input" '.$checked.' '.(($cronogramas['flg_cerrado'] == FLG_CERRADO) ? 'disabled' : $onclickSwitch).'>
                                                     	<span class="mdl-switch__label"></span>
                                                     </label>', 'class' => 'text-center');
            
            $eliminar = null;
            if($fecha_vencimiento != null){
                if($row->fecha_vencimiento <= $fecha_vencimiento){
                    $eliminar = '<button disabled class="mdl-button mdl-js-button mdl-button--icon" >
                    				<i class="mdi mdi-delete"></i>
                    			 </button>';
                }
            }
            else{
                $eliminar = '<button data-target="#modalEliminarConcCrono" onclick="idConceptoCronograma(\'' . $idCryptCronograma . '\')" data-toggle="modal" class="mdl-button mdl-js-button mdl-button--icon">
                				<i class="mdi mdi-delete"></i>
                			 </button>';
            }
            $boton = null;
            if($cronogramas['flg_cerrado'] == 0){
            	$boton = $eliminar.'<button class="mdl-button mdl-js-button mdl-button--icon" data-toggle="modal" data-target="#modalConceptoEditCronograma" onclick="editidConceptoCronograma(\'' . $idCryptCronograma. '\')"" >
            						    <i class="mdi mdi-edit"></i>
            						</button>';
            }else{
            	$boton ='<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
						     <i class="mdi mdi-lock"></i>
						 </button>';
            }
            if($row->flg_tipo == FLG_MATRICULA) {
                if($cronogramas['flg_cerrado_mat'] == 1){
                    $boton ='<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
    						     <i class="mdi mdi-lock"></i>
    						 </button>';
                }
            }
            $row_cell_6 = array('data' => $boton);
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5,$row_cell_6);
        }
        $tabla = null;
        if(count($listaCronograma) == 0){
            $tabla = '  <div class="img-search">
                            <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                            <p>Ups! A&uacute;n no se han registrado datos.</p>
                        </div>';
        } else{
            $tabla = $this->table->generate();
        }        
        return $tabla;
    }
    
    function saveCuotaSede() {
        $data['error'] = EXIT_ERROR;
        $id_cronograma = trim($this->session->userdata("id_cronograma_sesion"));
        $sede          = _decodeCI($this->input->post('sede'));
        $year          = trim($this->input->post('year'));
        $meses_val     = $this->input->post('mes'); // obtiene un array de todos los meses que han cambiado de valor
        
        try {
           	$cuota_x_mes = array(array());
            foreach($meses_val as $key => $item){
                if($item != ''){
                    if(is_numeric($item) == false) {
                      throw new Exception('El n&uacute;mero de cuotas debe ser un d&iacute;gito');
                    }
                    if($item<0) {
                        throw new Exception('El n&uacute;mero de cuotas debe ser positivo');
                    }
                    $push = array('mes'          =>  __mesesTexto($key),
                                   'year'        =>  $year,
                                   '_id_sede'    =>  $sede,
                                   'cant_cuotas' =>  $item);
                    array_push($cuota_x_mes, $push);
                }
            }    
            foreach($cuota_x_mes as $key => $item){
                if(count($item) == 0){
                	unset($cuota_x_mes[$key]);
                }
            }
			if(count($cuota_x_mes) == 0) {
               throw new Exception('Debes modificar al menos una cantidad de cuotas');
            }
            $n_cuotas_cronograma = $this->m_cronograma->validarCuotasCronograma($id_cronograma); 
            if($n_cuotas_cronograma != null){
                foreach($n_cuotas_cronograma as $item) {
                      foreach($cuota_x_mes as $item_cuota){
                          if($item->mes == __mesesTextoNumber($item_cuota['mes'])){ 
                              if($item_cuota['cant_cuotas'] < $item->n_cuota){
                                  throw new Exception('La cantidad de cuota no puede ser menor en el mes '.$item_cuota['mes'].' (Porque existe m&aacute;s conceptos en dicho mes)');
                              }
                          }
                      }  
                }
            }
            $data = $this->m_cronograma->update_cuota_x_mes($cuota_x_mes);
          	if ($data['error'] == EXIT_SUCCESS) {
                $data['error'] = EXIT_SUCCESS;
            }
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildCalendarEditTablaCronogramaHTML() {   
    	$id_sede   = $this->session->userdata("id_sede_cronograma_sesion");
        $year_crono = $this->session->userdata("year_cronograma_sesion");
        $listaCalendar = $this->m_cronograma->getItemCronoCalendarEdit($id_sede,$year_crono);
        $tmpl2 = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                               data-pagination="true" data-page-size="6" data-page-list="[5, 10, 15]"
                                               data-show-columns="false" data-search="false" id="tb_cronograma_calendario">',
                        'table_close' => '</table>');
        $this->table->set_template($tmpl2);
        $head_1 = array('data' => 'Mes', 'class' => 'text-left', 'data-field' => 'lista', 'class' => 'text-left');
        $head_2 = array('data' => 'Cuotas', 'data-field' => 'input', 'class' => 'text-center');
        
        $this->table->set_heading($head_1, $head_2);
        $filas_mes    = array();
        $filas_cuotas = array();
        $i=0;
    
        foreach($listaCalendar as $item){      
               $row_0 = array('data' => ucwords(strtolower($item['mes'])), 'class' => 'text-left');
               $row_1 = array('data' => '<div class="mdl-textfield mdl-js-textfield mdl-textfield__edit">
               							     <input type="text" class="mdl-textfield__input" name="'.strtolower($item['mes']).'" id="'.strtolower($item['mes']).'" value="'.$item['cant_cuotas'].'">
               							 </div>', 'class' => 'text-right');
               $row_2 = array('data' => $item['cant_cuotas'], 'class' => "ocultar");
               $this->table->add_row($row_0,$row_1);
        }        
        $input = '<input type="hidden" name="sede_calendar" id="sede_calendar" value="'._encodeCI($id_sede).'"><input type="hidden" name="year_calendar" id="year_calendar" value="'.$year_crono.'">';
        $tabla = $this->table->generate();
        echo json_encode(array_map('utf8_encode',array("input"=>$input,"tabla" =>$tabla)));
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
            $return .= '<li class='. $class .'>
            			    <a href="javascript:void(0)" onclick="cambioRol(\'' . $idRol . '\');">
            				    <span class="title">' . $var->desc_rol . $check . '</span>
            				</a>
            			</li>';
        }
        $dataUser = array(
            "roles_menu" => $return
        );
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
        $result .= '</ul>';
        $data['roles'] = $result;
        
        echo json_encode(array_map('utf8_encode', $data));
    }
            
    function buildRadioButton($sede,$year,$tipo) {
        $radios = null;
	    $cont   = 1;
	    $flags  = $this->m_utils_pagos->getFlgCerrados($sede,$year,$tipo, 'cronograma');
	    $disableMat    = ($flags['flg_cerrado_mat'] == FLG_CERRADO) ? 'disabled' : null;
	    $disableCuotas = ($flags['flg_cerrado_mat'] == FLG_CERRADO) ? null : 'disabled';
	    $encMat        = _encodeCI(FLG_CERRADO_MATRICULA);
	    $encCyR        = _encodeCI(FLG_CERRADO_CUOTA);
	    $encTodos      = _encodeCI(FLG_CERRADO_TODO);
	    $radios = '<div class="col-sm-12">
    				    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-1">
                            <input type="radio" id="option-1" class="mdl-radio__button" name="cerrarCrono" value="'.$encMat.'" '.$disableMat.'>
                            <span class="mdl-radio__label">Matr&iacute;cula</span>
                        </label>
    				</div>
                    <div class="col-sm-12">
    				    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-2">
                            <input type="radio" id="option-2" class="mdl-radio__button" name="cerrarCrono" value="'.$encCyR.'" '.$disableCuotas.'>
                            <span class="mdl-radio__label">Ratificaci&oacute;n y Cuotas</span>
                        </label>
    				</div>
    				<div class="col-sm-12">
    				    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-3">
                            <input type="radio" id="option-3" class="mdl-radio__button" name="cerrarCrono" value="'.$encTodos.'">
                            <span class="mdl-radio__label">Todos</span>
                        </label>
    				</div>';
	    return $radios;
    }
    
    function buildComboPaquetesByTipo($tipoCrono){
        $paquetes = $this->m_utils_pagos->getPaquetesByTipo($tipoCrono,_getSesion('id_cronograma_sesion'));
        $opt = null;
        foreach($paquetes as $row){
            $idPaqueteCrypt = _encodeCI($row->id_paquete);
            $opt .= '<option value="'.$idPaqueteCrypt.'">'.$row->desc_paquete.'</option>';
        }
        return $opt;
    }
}