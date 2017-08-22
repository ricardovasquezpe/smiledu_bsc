<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_cronograma extends CI_Controller
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
        $this->load->model('m_utils');
        $this->load->model('m_cronograma');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, null, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
    
    function CrearCronograma() {
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        $sede           = empty(_post('sedeCrono')) ? null : _decodeCI(_post('sedeCrono'));
        $yearCrono      = _post('yearCrono');
        $tipoCronograma = _decodeCI(_post('tipoCrono'));
        $valorTipoCrono = $this->m_utils->getById('pagos.tipo_cronograma' , 'desc_tipo_cronograma' , 'id_tipo_cronograma' , $tipoCronograma);
        try {
            if ($sede == null) {
                throw new Exception('Selecciona una sede');
            }
            if ($yearCrono == null) {
                throw new Exception('Ingrese el A&ntilde;o');
            }
            if (is_numeric($yearCrono) == FALSE) {
                throw new Exception('El a&ntilde;o debe ser num&eacute;rico');
            }
            if($tipoCronograma == null){
                throw new Exception('Selecciona un tipo de cronograma');
            }
            $year_count  = $this->m_cronograma->validar_year_cronograma($sede, $yearCrono,$tipoCronograma);
            $year_actual = date('Y');
            
            if (2 <= $year_count || $year_count == 1) {
                throw new Exception('No puedes crear mas de 2 cronogramas del mismo tipo por año');
            }
            $lastCronograma = null;
            if($yearCrono == _getYear()){
            	$lastCronograma = 1;
            }else{
            	$existe = $this->m_cronograma->getExisteCrono($sede);
            	if ($existe['existe'] == 't'){
            		$lastCronograma = $this->m_cronograma->getLastCrono($sede, ($yearCrono-1));
            	}else{
            		if($yearCrono == _getYear()){
            			$lastCronograma = 1;
            		}else{
            		    $lastCronograma = 1;
//             			throw new Exception('Debe crear el cronograma para el '._getYear().' y debe definirlo');
            		}
            	}
            }
            if($lastCronograma == 1){      
	            date_default_timezone_set('America/Lima');
	            $datos_cuotas = array(); 
	            $existeCuotaXMes = $this->m_cronograma->checkIfExistsCuotaXMesBySedeYear($sede,$yearCrono);
	            if($existeCuotaXMes == 0){
	                for($i=1; $i<=12; $i++){
	                    $datos_cuotas[$i]['mes']         = __mesesTexto($i);
	                    $datos_cuotas[$i]['year']        = $yearCrono;
	                    $datos_cuotas[$i]['_id_sede']    = $sede;
	                    $datos_cuotas[$i]['cant_cuotas'] = 1;
	                    $datos_cuotas[$i]['numero_mes']  = $i;
	                }   
	            } else{
	                $datos_cuotas = null;
	            }
	            $desc_crono = $this->m_utils->getById('sede', 'desc_sede', 'nid_sede', $sede, 'smiledu');
	            
	            $crear_cronograma = array("desc_cronograma"     => 'Cronograma ' . $yearCrono . ' (' . $desc_crono . ')' .' ('.  $valorTipoCrono .') ',
	                                      "fecha_regi"          => date('Y-m-d'),
	                                      "year"                => $yearCrono,
	                                      "estado"              => FLG_ESTADO_ACTIVO,
	                                      "_id_sede"            => $sede,
	                                      "id_audi_pers"        => $this->_idUserSess,
	                                      "audi_nomb_pers"      => _getSesion('nombre_abvr'),
	                                      "_id_tipo_cronograma" => $tipoCronograma
	                                    );
	            $data = $this->m_cronograma->crearCronograma($crear_cronograma, $datos_cuotas);
	            if ($data['error'] == EXIT_SUCCESS) {
	                $data['enlace_cronograma'] = base_url() . 'pagos/c_cronograma_detalle';
	                $data['lista_cronograma']  = $this->buildConcTablaCronogramaHTML($this->m_cronograma->getItemCronograma($data['insert_id']));
	                $this->session->set_userdata("lista_cronograma_sesion"   , $data['lista_cronograma']);
	                $this->session->set_userdata("id_cronograma_sesion"      , $data['insert_id']);
	                $this->session->set_userdata("id_tipo_crono_sess"        , $tipoCronograma);
	                $this->session->set_userdata("id_sede_cronograma_sesion" , $sede);
	                $this->session->set_userdata("year_cronograma_sesion"    , $yearCrono);
	                $data['error'] = EXIT_SUCCESS;
	            }
	        }else{
	        	throw new Exception('El cronograma del a&ntilde;o '.($yearCrono-1).' no ha sido cerrado');
            }
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getCronogramaDetalleUrl() {
        $cronograma                 = empty(_post('idCrono'))         ? null : _decodeCI(_post('idCrono'));
        $titulo                     = empty(_post('desc_cronograma')) ? null : _decodeCI(_post('desc_cronograma'));
        $nombre_crono               = $this->m_cronograma->getYearCronograma($cronograma);
        $yearCrono                  = $nombre_crono[0]->year;
//         $data['title_cronograma']   = $this->m_utils->getById('pagos.cronograma','desc_cronograma','id_cronograma',$cronograma);
        $dataCrono                  = $this->m_utils->getCamposById('pagos.cronograma', array('desc_cronograma','_id_tipo_cronograma'),'id_cronograma',$cronograma);
        $data['enlace_cronograma']  = base_url() . 'pagos/c_cronograma_detalle';
        $data['lista_cronograma']   = $this->buildConcTablaCronogramaHTML($this->m_cronograma->getItemCronograma($cronograma));
        $this->session->set_userdata("lista_cronograma_sesion"   , $data['lista_cronograma']);
        $this->session->set_userdata("id_cronograma_sesion", $cronograma);
        $this->session->set_userdata("id_tipo_crono_sess"        , $dataCrono['_id_tipo_cronograma']);
        $this->session->set_userdata("title_cronograma_sesion"   , $dataCrono['desc_cronograma']);
        $this->session->set_userdata("id_sede_cronograma_sesion" , $nombre_crono[0]->_id_sede);
        $this->session->set_userdata("year_cronograma_sesion"    , $yearCrono);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildConcTablaCronogramaHTML($listaCronograma) {
        $tmpl = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                              data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                              data-show-columns="false" data-search="false" id="tb_cronograma">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Descripci&oacute;n','class' => 'text-left');
        $head_2 = array('data' => 'Fecha Desc.');
        $head_3 = array('data' => 'Fecha Venc.');
        $head_4 = array('data' => 'Monto Mora');
        $head_5 = array('data' => 'Monto Desc.');
        $head_6 = array('data' => 'Acciones','class' => 'text-center');
        $val    = 0;
        $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function registrarPlantillaCronograma() {
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        $idCrono        = empty(_post('idCrono')) ? null : _simple_decrypt(_post('idCrono')); 
        $array          = $this->m_cronograma->getSedeByCronograma($idCrono);
        $sede           = $array['_id_sede'];
        $year           = $array['year'];
        $sedeCrono      = empty(_post('sedeCrono')) ? null : _decodeCI(_post('sedeCrono'));
        $yearCrono      = _post('yearCrono');
        $tipoCronograma = $this->m_utils->getById('pagos.cronograma', '_id_tipo_cronograma', 'id_cronograma', $idCrono);
        $valorTipoCrono = $this->m_utils->getById('pagos.tipo_cronograma' , 'desc_tipo_cronograma' , 'id_tipo_cronograma' , $tipoCronograma);
        try {
            if ($idCrono == null) {
                throw new Exception('Selecciona un cronograma');
            }
            if ($sedeCrono == null) {
                throw new Exception('Selecciona una sede');
            }
            if (is_numeric($yearCrono) == FALSE) {
                throw new Exception('El año debe ser numérico');
            }
            $year_count  = $this->m_cronograma->validar_year_cronograma($sedeCrono, $yearCrono, $tipoCronograma);
            $year_actual = date('Y');
            
            if ($yearCrono < $year_actual || $year_actual + (NUMERO_CRONOGRAMA_X_SEDE - 0) < $yearCrono) {
//                 throw new Exception('Debes crear un cronograma con el año actual o un año posterior');
            }
            if (1 == $year_count) {
                throw new Exception('No puedes crear mas de 1 tipo de cronograma cronogramas por año');
            }
            date_default_timezone_set('America/Lima');
            $datos_cuotas = array();
            $datos_cuotas = array(array());
            $existeCuotaXMes = $this->m_cronograma->checkIfExistsCuotaXMesBySedeYear($sedeCrono,$yearCrono);
            if($existeCuotaXMes == 0){
                $lista_calendar = $this->m_cronograma->getListaCalendarCronograma($sede,$year);
                if(0 < count($lista_calendar)){
                    foreach ($lista_calendar as $item){
                        $push = array(
                            'mes'          => $item->mes,
                            'year'         => $yearCrono,
                            '_id_sede'     => $sedeCrono,
                            'cant_cuotas'  => $item->cant_cuotas,
                            'numero_mes'   => $item->numero_mes
                        );
                        array_push($datos_cuotas, $push);
                    }
                    unset($datos_cuotas[0]);
                } else{
                    for($i=1; $i<=12; $i++){
                        $datos_cuotas[$i]['mes']         = __mesesTexto($i);
                        $datos_cuotas[$i]['year']        = $yearCrono;
                        $datos_cuotas[$i]['_id_sede']    = $sedeCrono;
                        $datos_cuotas[$i]['cant_cuotas'] = 1;
                        $datos_cuotas[$i]['numero_mes']  = $i;
                    }
                }   
            } else{
                $datos_cuotas = null;
            }
            $desc_crono = $this->m_utils->getById('sede', 'desc_sede', 'nid_sede', $sedeCrono, 'smiledu');
            $crear_cronograma = array("desc_cronograma"     => 'Cronograma ' . $yearCrono . ' (' . $desc_crono . ')' .' ('. $valorTipoCrono.') ',
                                      "fecha_regi"          => date('Y-m-d'),
                                      "year"                => $yearCrono,
                                      "estado"              => "ACTIVO",
                                      "_id_sede"            => $sedeCrono,
                                      "id_audi_pers"        => $this->_idUserSess,
                                      "audi_nomb_pers"      => _getSesion('nombre_abvr'),
                                      "_id_tipo_cronograma" => $tipoCronograma);
            
            $data = $this->m_cronograma->crearPlantillaCronograma($idCrono, $yearCrono, $crear_cronograma,$datos_cuotas);
            if ($data['error'] == EXIT_SUCCESS) {
                $lista_new_conceptos = array();
                $i                   = 0;
                if($data['lista_conceptos'] != null){
                    foreach($data['lista_conceptos'] as $item) {
                    	$lista_new_conceptos[$i]['desc_detalle_crono']  = $item['desc_detalle_crono'];
                        $lista_new_conceptos[$i]['cantidad_mora']       = $item['cantidad_mora'];
                    	$mes_cro_v                                      = substr($item['fecha_vencimiento'], 5, 2);
                        $dia_cro_v                                      = substr($item['fecha_vencimiento'], 8, 2);
                        $lista_new_conceptos[$i]['fecha_vencimiento']   = $yearCrono . '-' . $mes_cro_v . '-' . $dia_cro_v;
                        $lista_new_conceptos[$i]['_id_paquete']         = $item['_id_paquete'];
                    	if (trim($item['fecha_descuento']) != '') {
                            $date_fecha_desc = new DateTime($item['fecha_descuento']);
                           	$mes_cro_d                                  = $date_fecha_desc->format('m');
                            $dia_cro_d                                  = $date_fecha_desc->format('d');
                            $lista_new_conceptos[$i]['fecha_descuento'] = $yearCrono . '-' . $mes_cro_d . '-' . $dia_cro_d;
                        }
                        else{
                            $lista_new_conceptos[$i]['fecha_descuento'] = null;
                        }
                        $lista_new_conceptos[$i]['_id_cronograma']      = $data['id_cronograma'];
                        $lista_new_conceptos[$i]['flg_tipo']            = $item['flg_tipo'];
                        $lista_new_conceptos[$i]['flg_beca']            = $item['flg_beca'];
                        $i++;
                    } 
                }
                
                $id_cronograma = $data['id_cronograma'];
                $data          = $this->m_cronograma->crearPlantillaConceptosCronograma($lista_new_conceptos);
            }
            if ($data['error'] == EXIT_SUCCESS) {
                $this->session->set_userdata("id_cronograma_sesion", $id_cronograma);
                $this->session->set_userdata('id_tipo_crono_sess',$tipoCronograma);
                $this->session->set_userdata("title_cronograma_sesion", "Cronograma " . $yearCrono . ' (' . $desc_crono . ')');
                $this->session->set_userdata("id_sede_cronograma_sesion", $sedeCrono);
                $this->session->set_userdata("year_cronograma_sesion",$yearCrono);
                $data['enlace_cronograma'] = base_url() . 'pagos/c_cronograma_detalle';
                $data['error']             = EXIT_SUCCESS;
            }
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getCronogramaSede() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede = empty(_post('idSedeC')) ? null : _decodeCI(_post('idSedeC'));
            if ($idSede == null) {
                throw new Exception('Seleccione una sede');
            }
            $data['lista_cronograma'] = $this->buildTablaCronogramaHTML($this->m_cronograma->getCronogramaByFiltro($idSede));
            $data['title_cronograma'] = $this->m_cronograma->getNombreSedes($idSede);
            $data['error']            = EXIT_SUCCESS;
        }
        catch (Exception $e) {
            $data['msj']   = $e->getMessage();
            $data['error'] = EXIT_ERROR;
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function editItemCronograma() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idItemCronograma = empty(_post('idItemCronograma')) ? null : _decodeCI(_post('idItemCronograma'));
            if ($idItemCronograma == null) {
                throw new Exception('Seleccione un concepto para editar');
            }
            $data['lista_item_cronograma'] = __buildEditItemCronograma($this->m_cronograma->getItemCronograma($idItemCronograma));
            $data['error']                 = EXIT_SUCCESS;
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getCronograma($id_cronograma) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if ($id_cronograma == null) {
                $data['error']    = EXIT_ERROR;
                $data['optNivel'] = null;
            }
            $data['lista_cronograma'] = $this->buildEditTablaCronogramaHTML($this->m_cronograma->getCronograma($id_cronograma));
            $data['error']            = EXIT_SUCCESS;
            return $data['lista_cronograma'];
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    }
    
    function buildCalendarCuotasTablaCronogramaHTML() {   
    	$id_sede       = $this->session->userdata("id_sede_cronograma_sesion");
        $year_crono    = $this->session->userdata("year_cronograma_sesion");
        $id_cronograma = empty(_post('cronograma')) ? null : _decodeCI(_post('cronograma'));
        $listaConceptosCronograma=$this->m_cronograma->getItemCronoCalendarCuotas($id_cronograma);
        $tmpl2 = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-show-columns="false" data-search="false" id="tb_cronograma_calendario">',
            		   'table_close' => '</table>');
        $this->table->set_template($tmpl2);
        $head_1 = array('data' => 'Mes'    , 'class' => 'text-left');
        $head_2 = array('data' => 'Cuotas' , 'class' => 'text-left');
        $head_3 = array('data' => 'Fec. Vencimiento' , 'class' => 'text-left');
        $this->table->set_heading($head_1, $head_2,$head_3);
        $filas_mes      = array();
        $filas_cuotas   = array();
        for($i=1; $i<=12; $i++){
            $row_0   = array('data' => ucwords(strtolower(__mesesTexto($i))));
            $lista     = '<ul>';
            $listaVenc = '<ul>';
            foreach($listaConceptosCronograma as $row){
                if($row->n_mes == $i){
                    $lista .= '<li>'.$row->desc_detalle_crono.'</li>';
                    $listaVenc .= '<li>'.$row->fec_vencimiento.'</li>';
                }
            }
            $listaVenc .= '<ul>';
            $lista     .= '</ul>';
            $row_1      = array('data' => $lista     , 'class' => 'text-left');
            $row_2      = array('data' => $listaVenc , 'class' => 'text-left');
            $this->table->add_row($row_0,$row_1,$row_2);
        }
        $tabla = $this->table->generate();
        echo json_encode(array_map('utf8_encode',array("tabla" =>$tabla)));
    }
    
    function buildTablaCronogramaHTML($listaCronograma) {
        $tmpl = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-show-columns="false" data-search="false" id="tb_cronograma">',
            		  'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Descripci&oacute;n', 'class' => 'text-left');
        $head_2 = array('data' => 'A&ntilde;o', 'class' => 'text-center');
        $head_3 = array('data' => 'Acciones', 'class' => 'text-center');
        $val    = 0;
        $this->table->set_heading($head_1, $head_2, $head_3);
        foreach ($listaCronograma as $row) {
            $idCryptCronograma = _encodeCI($row-> id_cronograma);
            $tituloCronograma  = _encodeCI($row-> desc_cronograma);
            $val++;
            $row_cell_1 = array('data' => 'Cronograma ' . ucwords(strtolower($row->year)), 'class' => 'text-left');
            $row_cell_2 = array('data' => ucwords(strtolower($row->year)), 'class' => 'text-center');
            $row_cell_3 = array('data' => '<a href="#modalPlantillaCronograma" onclick="getidCronograma(\'' . $idCryptCronograma . '\')" data-toggle="modal" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                <i class="mdi mdi-receipt"></i>
            							   </a>
                                           <a href="#modalVistaPreviaCronograma" onclick="vista_previa_cronograma(\'' . $idCryptCronograma . '\' ,\'' . $tituloCronograma . '\')" data-toggle="modal" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                <i class="mdi mdi-visibility"></i>
            							   </a>
                                           <a href="#"  data-toggle="modal" onclick="getCronogramaDetalle(\'' . $idCryptCronograma . '\')" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                <i class="mdi mdi-edit"></i>
            							   </a>', 'class' => 'text-center');
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function eliminarCronograma() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $cronograma    = _decodeCI(_post('idCronoEliminar'));
        try {
        	if($cronograma == null){
        	    throw new Exception('No seleccion&oacute; un cronograma.');
        	}
        	$flg_cerrado = $this->m_utils->getById('pagos.cronograma', 'flg_cerrado', 'id_cronograma', $cronograma);
        	if($flg_cerrado == FLG_CERRADO){
        	    throw new Exception(ANP);
        	}
        	$count = $this->m_cronograma->countCompromisoSinMov($cronograma);
        	if($count > 0){
        	    throw new Exception('El cronograma presenta movimiento.');
        	}
        	$data = $this->m_cronograma->eliminaCronograma($cronograma);
        	$idSede = $this->m_cronograma->getSedeByCronograma($cronograma);
        	if($data['error'] == EXIT_SUCCESS){
        	    $arraySedes               = $this->m_utils->getSedes();
        	    $data['tableCronograma']  = __buildTablaCronogramaHTML($arraySedes);
        	}
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}