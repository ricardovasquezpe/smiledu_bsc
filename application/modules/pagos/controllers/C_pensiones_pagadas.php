<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_pensiones_pagadas extends CI_Controller{
	
    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct(){
		parent::__construct();
		$this->output->set_header(CHARSET_ISO_8859_1);
		$this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->load->model('../mf_usuario/m_usuario');
		$this->load->model('m_utils');
		$this->load->model('m_reportes');
		$this->load->library('table');
		_validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_REPORTES, PAGOS_ROL_SESS);
		$this->_idUserSess = _getSesion('nid_persona');
		$this->_idRol      = _getSesion(PAGOS_ROL_SESS);
	}
	
	function comboSedesNivel() {
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
			$idSede    = empty($this->input->post('idSede')) ? null : _decodeCI($this->input->post('idSede'));
			$fecInicio = $this->input->post('fecInicio');
			$fecFin    = $this->input->post('fecFin');
			$data['optNivel'] = null;
			if($idSede == null) {
				$data['error']    = EXIT_ERROR;
				$data['optNivel'] = null;
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
			$data += $this->chageTablePagados($fecInicio, $fecFin, $idSede, null, null, null);
			$data['optNivel'] = __buildComboNivelesBySede($idSede, null);
			$data['error']    = EXIT_SUCCESS;
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getComboGradoByNivel() {
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
			$idSede           = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
			$idNivel          = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
			$tab              = $this->input->post('tabGlobal');
			$fecInicio        = $this->input->post('fecInicio');
			$fecFin           = $this->input->post('fecFin');
			$idCronograma     = empty($this->input->post('idCronograma')) ? null : _decodeCI($this->input->post('idCronograma'));
			$idCuota          = empty($this->input->post('idCuota'))      ? null : _decodeCI($this->input->post('idCuota'));
			$data['optGrado'] = null;
			if(empty($fecInicio)){
				throw new Exception('Ingrese Fecha Inicio');
			}
			if(empty($fecFin)){
				throw new Exception('Ingrese Fecha Fin');
			}
			if($fecInicio > $fecFin){
				throw new Exception('Fecha Inicio no debe ser mayor que Fecha Fin');
			}
			if($idNivel == null || $idSede == null) {
				$data['error']    = EXIT_ERROR;
				$data['optGrado'] = null;
			}
			$data            += $this->chageTablePagados($fecInicio, $fecFin, $idSede, $idNivel, null, null);
			$data['optGrado'] = __buildComboGradosByNivel($idNivel, $idSede);
			$data['error']    = EXIT_SUCCESS;
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboAulasByGrado() {
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		$idSede        = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
		$idNivel       = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
		$idGrado       = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
		$tab           = $this->input->post('tabGlobal');
		$fecInicio     = $this->input->post('fecInicio');
		$fecFin        = $this->input->post('fecFin');
		try {
			$data['optAula']  = null;
			if(empty($fecInicio)){
				throw new Exception('Ingrese Fecha Inicio');
			}
			if(empty($fecFin)){
				throw new Exception('Ingrese Fecha Fin');
			}
			if($fecInicio > $fecFin){
// 				throw new Exception('Fecha Inicio no debe ser mayor que Fecha Fin');
			}
			if($idGrado == null || $idSede == null) {
				$data['error']    = EXIT_ERROR;
				$data['optAula']  = null;
			}
			$data += $this->chageTablePagados($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, null);
			$data['optAula'] = __buildComboAulas($idGrado,$idSede);
			$data['error'] = EXIT_SUCCESS;
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAlumnosFromAula() {
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		$idSede        = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
		$idNivel       = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
		$idGrado       = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
		$idAula        = empty($this->input->post('idAula'))  ? null : _decodeCI($this->input->post('idAula'));
		$tab           = $this->input->post('tabGlobal');
		$fecInicio     = $this->input->post('fecInicio');
		$fecFin        = $this->input->post('fecFin');
		try {
			if($idAula == null) {
				$data['error']    = EXIT_ERROR;
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
			$data += $this->chageTablePagados($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, $idAula);
			$data['error'] = EXIT_SUCCESS;
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function chageTablePagados($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, $idAula){
		$personas       = array();
		$arrayPersonas  = array();
		$cantGeneral    = 0;
		$cantDescuento  = 0;
		$cantNormal     = 0;
		$cantMoroso     = 0;
		$montoGeneral   = 0;
		$montoDescuento = 0;
		$montoNormal    = 0;
		$montoMoroso    = 0;
		$arrayGlobal    = array();
		$datos = $this->m_reportes->getPencionesPagadas($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, $idAula);
		foreach ($datos as $row){
			array_push($arrayGlobal, $row->_id_persona);
	
			if($row->estado == 'PRONTO PAGO'){
				$cantDescuento++;
				$montoDescuento = $montoDescuento + ((float)$row->monto - (float)$row->descuento_acumulado);
			}else if($row->estado == 'PAGO NORMAL') {
				$cantNormal++;
				$montoNormal = $montoNormal + ((float)$row->monto);
			}else if($row->estado == 'PAGO MOROSO') {
				$cantMoroso++;
				$montoMoroso = $montoMoroso + ((float)$row->monto + (float)$row->mora_acumulada);
			}
		}
		$this->session->set_userdata(array('arrayAlumnos' => $arrayGlobal));
		$cantGeneral          = $cantDescuento + $cantNormal + $cantMoroso;
		$cantidades           = array('cantDescuento' => $cantDescuento,
				'cantNormal'  => $cantNormal,
				'cantMoroso'  => $cantMoroso,
				'cantGeneral' => $cantGeneral);
		$montoGeneral         = $montoDescuento + $montoNormal + $montoMoroso;
		$montos               = array('montoDescuento' => $montoDescuento,
				'montoNormal' => $montoNormal,
				'montoMoroso' => $montoMoroso,
				'montoGeneral'=> $montoGeneral);
		$data['tableGeneral'] = $this->buildTablaPensionesPagadasHTML($cantidades, $montos);
		return  $data;
	}
	
	function buildTablaPensionesPagadasHTML($cantidades, $montos) {
		$porcentaje = null;
		$tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_general">',
				'table_close' => '</table>');
		$this->table->set_template($tmpl);
		$head_1 = array('data' => 'Descripci&oacute;n' , 'class' => 'text-left');
		$head_2 = array('data' => 'Porcentaje'         , 'class' => 'text-right');
		$head_3 = array('data' => 'Cant. Estudiantes'  , 'class' => 'text-right');
		$head_4 = array('data' => 'Monto'              , 'class' => 'text-right');
		$head_5 = array('data' => 'Estudiante'            , 'class' => 'text-center');
		$this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5);
		$porcentaje = ($cantidades['cantGeneral'] == 0) ? 0 : round(((float)$cantidades['cantDescuento']*100/(float)$cantidades['cantGeneral']), 3);
		$row_cell_1 = array('data' => 'Pronto Pago'                , 'class' => 'text-left');
		$row_cell_2 = array('data' => $porcentaje.'%'              , 'class' => 'text-right');
		$row_cell_3 = array('data' => $cantidades['cantDescuento'] , 'class' => 'text-right');
		$row_cell_4 = array('data' => $montos['montoDescuento']    , 'class' => 'text-right');
		$row_cell_5 = array('data' => '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalAlumnos(\'descuento\');">
	                                            <i class="mdi mdi-students"></i>
	                                        </a>');
		$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5);
		$porcentaje = ($cantidades['cantGeneral'] == 0) ? 0 : round(((float)$cantidades['cantNormal']*100/(float)$cantidades['cantGeneral']), 3);
		$row_cell_1 = array('data' => 'Pago Normal'             , 'class' => 'text-left');
		$row_cell_2 = array('data' => $porcentaje.'%'           , 'class' => 'text-right');
		$row_cell_3 = array('data' => $cantidades['cantNormal'] , 'class' => 'text-right');
		$row_cell_4 = array('data' => $montos['montoNormal']    , 'class' => 'text-right');
		$row_cell_5 = array('data' => '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalAlumnos(\'normal\');">
	                                            <i class="mdi mdi-students"></i>
	                                        </a>');
		$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5);
		$porcentaje = ($cantidades['cantGeneral'] == 0) ? 0 : round(((float)$cantidades['cantMoroso']*100/(float)$cantidades['cantGeneral']), 3);
		$row_cell_1 = array('data' => 'Pago Moroso'             , 'class' => 'text-left');
		$row_cell_2 = array('data' => $porcentaje.'%'           , 'class' => 'text-right');
		$row_cell_3 = array('data' => $cantidades['cantMoroso'] , 'class' => 'text-right');
		$row_cell_4 = array('data' => $montos['montoMoroso']    , 'class' => 'text-right');
		$row_cell_5 = array('data' => '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalAlumnos(\'mora\');">
	                                            <i class="mdi mdi-students"></i>
	                                        </a>');
		$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5);
		$tabla = $this->table->generate();
		return $tabla;
	}
	
	function createTableModal(){
		$tipo                 = $this->input->post('tipo');
		$fecInicio            = $this->input->post('fecInicio');
		$fecFin               = $this->input->post('fecFin');
		$personaArray         = array();
		$arrayGlobal          = _getSesion('arrayAlumnos');
		$personaArray         = $this->m_reportes->filtrarAlumnos($arrayGlobal, $fecInicio, $fecFin, $tipo);
		$data['tablaAlumnos'] = $this->buildTablaAlumnos($personaArray);
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTablaAlumnos($idAlumno){
		$tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_alumnos">',
				'table_close' => '</table>');
		$this->table->set_template($tmpl);
		$head_1 = array('data' => 'Cod.Estudiante',  'class' => 'text-right');
		$head_2 = array('data' => 'Estudiante', '     class' => 'text-left');
		$head_3 = array('data' => 'Apoderado',   'class' => 'text-left');
		$head_5 = array('data' => 'Descripci&oacute;n',    'class' => 'text-left');
        $head_6 = array('data' => 'Fecha Pago',    'class' => 'text-left');
		$this->table->set_heading($head_1, $head_2, $head_3, $head_5, $head_6);
		foreach ($idAlumno as $row) {
			$alumnos = $this->m_reportes->getDatosAlumnos($row->_id_persona);
			$row_cell_1 = array('data' => $alumnos['cod_alumno'],  'class' => 'text-right');
			$row_cell_2 = array('data' => $alumnos['nombre_alumno'],   'class' => 'text-left');
			$row_cell_3 = array('data' => $alumnos['nombre_apoderado'],   'class' => 'text-left');
            $row_cell_5 = array('data' => $row->desc_detalle_crono,  'class' => 'text-left');
            $row_cell_6 = array('data' => $row->fecha_pago,  'class' => 'text-left');
			$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_5, $row_cell_6);
		}
		$tabla = $this->table->generate();
		return $tabla;
	}
	
	function buildGraficoByTab(){
		try{
			$tab       = _post('tab');
			$fecInicio = _post('fecInicio');
			$fecFin    = _post('fecFin');
			$idSede    = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
			$idNivel   = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
			$idGrado   = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
			$idAula    = empty($this->input->post('idAula'))  ? null : _decodeCI($this->input->post('idAula'));
			 
			$arrayPersonas         = array();
			$personas              = $this->m_reportes->getAlumnosByFiltro($idSede, $idNivel, $idGrado, $idAula);
			foreach ($personas as $row){
				array_push($arrayPersonas, $row->nid_persona);
			}
			$datos = $this->m_reportes->getDatosGrafico($arrayPersonas, $fecInicio, $fecFin);
			$arraySeries = array();
			$arrayCate   = array();
			array_push($arraySeries , array('y'     => intval($datos['count_pronto_pago']),
                                		    'name'  => 'Pronto Pago S/.'.round($datos['pronto_pago'],2),
                                		   	'color' => ''
					                       )
			          );
		    array_push($arraySeries , array('y'    => intval($datos['count_moroso']),
                        			        'name' => 'Moroso S/.'.round($datos['moroso'],2),
                        				    'color' => ''
							               )
		              );
			array_push($arraySeries , array('y'    => intval($datos['count_normal']),
                                			'name' => 'Normal S/.'.round($datos['normal'],2),
                                			'color' => ''
                                           )
			          );
			$data['series'] = json_encode(array($arraySeries));
			$data['cate']   = json_encode($arrayCate);
		} catch(Exception $e){
			 $data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
}