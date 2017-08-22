<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_pagos_puntuales extends CI_Controller{
    
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
    
    function comboCronogramaCuota() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	try {
    		$idCronograma    = empty($this->input->post('idCronograma')) ? null : _decodeCI($this->input->post('idCronograma'));
//    		$fecInicio     = $this->input->post('fecInicioGlobal');
//    		$fecFin        = $this->input->post('fecFinGlobal');
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
    
    function comboCuotasNivel() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	try {
    		$idCronograma     = empty($this->input->post('idCronograma')) ? null : _decodeCI($this->input->post('idCronograma'));
    		$idCuota          = empty($this->input->post('idCuota')) ? null : _decodeCI($this->input->post('idCuota'));
    		$idSede           = $this->m_reportes->getSedeByCronograma($idCronograma); // 1
    		$data['optNivel'] = null;
    		if($idCuota == null) {
    			$data['error']    = EXIT_ERROR;
    			$data['optNivel'] = null;
    			throw new Exception(ANP);
    		}
    		$data += $this->chageTablePuntuales($idCuota, $idSede, null, null, null);
    		$data['optNivel'] = __buildComboNivelesBySede($idSede);
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
    		$idNivel          = empty($this->input->post('idNivel'))      ? null : _decodeCI($this->input->post('idNivel'));
    		$idCronograma     = empty($this->input->post('idCronograma')) ? null : _decodeCI($this->input->post('idCronograma'));
    		$idCuota          = empty($this->input->post('idCuota'))      ? null : _decodeCI($this->input->post('idCuota'));
    		$data['optGrado'] = null;
    		if($idNivel == null || $idCuota == null) {
    				$data['error']    = EXIT_ERROR;
    				$data['optGrado'] = null;
    		}
    		$Sede             = $this->m_reportes->getSedeByCronograma($idCronograma);
    		$data            += $this->chageTablePuntuales($idCuota, $Sede, $idNivel, null, null);
    		$data['optGrado'] = __buildComboGradosByNivel($idNivel, $Sede);
    		$data['error']    = EXIT_SUCCESS;
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function comboAulasByGrado() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$idNivel       = empty($this->input->post('idNivel'))      ? null : _decodeCI($this->input->post('idNivel'));
    	$idGrado       = empty($this->input->post('idGrado'))      ? null : _decodeCI($this->input->post('idGrado'));
    	$idCronograma  = empty($this->input->post('idCronograma')) ? null : _decodeCI($this->input->post('idCronograma'));
    	$idCuota       = empty($this->input->post('idCuota'))      ? null : _decodeCI($this->input->post('idCuota'));
    	try {
    		$data['optAula']  = null;
    		if($idGrado == null || $idCuota == null) {
    			$data['error']    = EXIT_ERROR;
    			$data['optAula']  = null;
    		}
    		$Sede            = $this->m_reportes->getSedeByCronograma($idCronograma);
    		$data           += $this->chageTablePuntuales($idCuota, $Sede, $idNivel, $idGrado, null);
    		$data['optAula'] = __buildComboAulas($idGrado, $Sede);
    		$data['error'] = EXIT_SUCCESS;
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getAlumnosFromAula() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$idNivel       = empty($this->input->post('idNivel'))      ? null : _decodeCI($this->input->post('idNivel'));
    	$idGrado       = empty($this->input->post('idGrado'))      ? null : _decodeCI($this->input->post('idGrado'));
    	$idAula        = empty($this->input->post('idAula'))       ? null : _decodeCI($this->input->post('idAula'));
    	$idCronograma  = empty($this->input->post('idCronograma')) ? null : _decodeCI($this->input->post('idCronograma'));
    	$idCuota       = empty($this->input->post('idCuota'))      ? null : _decodeCI($this->input->post('idCuota'));
// 		$fecInicio     = $this->input->post('fecInicioGlobal');
// 		$fecFin        = $this->input->post('fecFinGlobal');
    	try {
    		if($idAula == null) {
    			$data['error']    = EXIT_ERROR;
    		}
    		if($idCronograma == null){
    		    throw new Exception('Selecciona un cronograma');
    		}
    		$Sede  = $this->m_reportes->getSedeByCronograma($idCronograma);
    		$data += $this->chageTablePuntuales($idCuota, $Sede, $idNivel, $idGrado, $idAula);
    		$data['error'] = EXIT_SUCCESS;
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function chageTablePuntuales($idCuota, $Sede, $idNivel, $idGrado, $idAula){
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	try {
    		if(empty($idCuota)){
    			throw new Exception('Seleccione una Cuota');
    		}
    		if($idCuota == null) {
    			$data['error']    = EXIT_ERROR;
    		}
	    	$datos = $this->m_reportes->getPagosPuntuales($idCuota, $Sede, $idNivel, $idGrado, $idAula);
	    	$data['totalPunt'] = count($datos);
	    	if(count($datos) > 0){
	    	    $data['tablePunt'] = $this->buildTablaPagosPuntualesHTML($datos);
	    	} else {
	    	    $data['tablePunt'] = '<div class="img-search">
	                    	              <img src="'.RUTA_IMG.'/smiledu_faces/not_filter_fab.png">
	                    	              <p>¡Ups!</p>
                                          <p>No hay pagos por mostrar</p>
                    	                  <p>Por favor vuelve a filtrar.</p>
	        	                      </div>';
	    	}
	    	$data['error'] = EXIT_SUCCESS;
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	return $data;
    }
    
    function buildTablaPagosPuntualesHTML($datos) {
    	$tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-show-columns="false" data-search="false" id="tb_puntual">',
    			'table_close' => '</table>');
    	$this->table->set_template($tmpl);
    	$head_1 = array('data' => 'Nº'                    , 'class' => 'text-right');
    	$head_2 = array('data' => 'C&oacute;digo'         , 'class' => 'text-right');
    	$head_3 = array('data' => 'Estudiante'            , 'class' => 'text-left');
    	$head_4 = array('data' => 'Sede/Nivel/Grado/Aula' , 'class' => 'text-left');
    	$head_5 = array('data' => 'Apoderado'             , 'class' => 'text-left');
    	$head_6 = array('data' => 'Correo'                , 'class' => 'text-left');
    	$head_7 = array('data' => 'Descripci&oacute;n'    , 'class' => 'text-left');
    	$head_8 = array('data' => 'Fecha Pago'            , 'class' => 'text-right');
    	$head_9 = array('data' => 'Total'                 , 'class' => 'text-right');
    	$this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7, $head_8, $head_9);
    	$val = 1;
    	foreach ($datos as $row) {
    	    $monto_total   = null;
    		if($row->estado == 'PRONTO PAGO'){
    			$monto_total = $row->monto- $row->descuento_acumulado;
    			$monto_total = number_format((float)$monto_total, 2, '.', '');
			}elseif ($row->estado == 'PAGO NORMAL') {
				$monto_total = $row->monto;
				$monto_total = number_format((float)$monto_total, 2, '.', '');
			}
			$row_cell_1 = array('data' => $val                     , 'class' => 'text-right');
			$row_cell_2 = array('data' => $row->cod_alumno         , 'class' => 'text-right');
			$row_cell_3 = array('data' => $row->nombre_alumno      , 'class' => 'text-left');
			$row_cell_4 = array('data' => $row->ubicacion          , 'class' => 'text-left');
			$row_cell_5 = array('data' => $row->nombre_apoderado   , 'class' => 'text-left');
			$row_cell_6 = array('data' => $row->email1             , 'class' => 'text-left');
            $row_cell_7 = array('data' => $row->desc_detalle_crono , 'class' => 'text-left');
			$row_cell_8 = array('data' => $row->fecha_pago         , 'class' => 'text-right');
			$row_cell_9 = array('data' => $monto_total             , 'class' => 'text-right');
			$val++;
			$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6, $row_cell_7, $row_cell_8, $row_cell_9);
    	}
    	$smile = '<div class="img-search">
    	              <img src="'.RUTA_IMG.'/smiledu_faces/not_filter_fab.png">
    	              <p>¡Ups!</p>
                      <p>Tu filtro no ha sido</p>
                      <p>encontrado.</p>
                  </div>';
    	return ($val == 1) ? $smile : $tabla = $this->table->generate();
    }
    
    function buildGraficoByTab() {
        try{
            $tab       = _post('tab');
            $Sede      = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
            $idNivel   = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
            $idGrado   = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
            $idAula    = empty($this->input->post('idAula'))  ? null : _decodeCI($this->input->post('idAula'));
            $idSede    = $this->m_reportes->getSedeByCronograma($Sede);
            $datos     = $this->m_reportes->getPensionesPuntualesGrafico($idSede, $idNivel, $idGrado, $idAula);
            $arraySeries1 = array();
            $arraySeries2 = array();
            $arrayCate    = array();
            foreach ($datos as $row){
            	array_push($arrayCate , utf8_encode($row['desc_sede']));
	            array_push($arraySeries1 , array('y'    => round($row['pronto_pago_monto'],2),
								                'name'  => 'Pronto Pago',
								                'color' => ''));
	            array_push($arraySeries2 , array('y'    => round($row['pago_normal_monto'],2),
								                 'name' => 'Normal',
								                 'color'=> ''));
            }
            $data['series1'] = json_encode(array($arraySeries1));
            $data['series2'] = json_encode(array($arraySeries2));
            $data['cate']    = json_encode($arrayCate);
        } catch(Exception $e){
    
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}