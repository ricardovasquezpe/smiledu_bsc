<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_compromisos extends CI_Controller {

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
        $this->load->model('m_utils');
        $this->load->model('m_compromisos');
        $this->load->model('m_pensiones');
        $this->load->model('m_movimientos');
        $this->load->model('m_caja');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, null, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
    
	function loadConceptos(){
	    //$data['error']     = EXIT_ERROR;
	    $data['error']    = EXIT_SUCCESS;
	    $data['opConceptos'] = __buildComboConceptosByTipo('INGRESO'); 
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function comboSedesNivel() {
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
	        $idSede        = empty(_post('idSede')) ? null : _decodeCI(_post('idSede'));
	        if($idSede == null) {
	            throw new Exception('Selecciona una sede');
	        }
	        $aulas = $this->m_compromisos->getCardCompromisoAll($idSede); 
	        $data['cards'] = $this->buildCardsAulasHTML($aulas);
	        $data['optNivel'] = __buildComboNivelesBySede($idSede);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboNivelGrado() {
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
	        $idSede           = empty(_post('idSede')) ? null : _decodeCI(_post('idSede'));
	        $idNivel          = empty(_post('idNivel')) ? null : _decodeCI(_post('idNivel'));
	        $aulas            = $this->m_compromisos->getCardCompromisoAll($idSede,$idNivel);
	        $data['cards']    = $this->buildCardsAulasHTML($aulas);
	        $data['optGrado'] = __buildComboGradosByNivel($idNivel,$idSede);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboGrado() {
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
	        $idSede        = empty(_post('idSede')) ? null : _decodeCI(_post('idSede'));
	        $idNivel       = empty(_post('idNivel')) ? null : _decodeCI(_post('idNivel'));
	        $idGrado       = empty(_post('idGrado')) ? null : _decodeCI(_post('idGrado'));
	        $aulas = $this->m_compromisos->getCardCompromisoAll($idSede,$idNivel,$idGrado); 
	        $data['cards'] = $this->buildCardsAulasHTML($aulas);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function inputNombreAlumnos() {
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
	        $offset        = empty(trim(_post('count'))) ? null      : _post('count');
	        $alu = $this->m_compromisos->validarAlumnosCompromisos(null,null,null,_getSesion('id_sede_trabajo'));
	        $data['tablaAlumnos'] = $this->buildTablaAlumnosHTML($alu);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function inputApellidosAlumnos() {
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
	        $apellidos     = empty(trim(_post('apellidos'))) ? null  : utf8_decode(trim(_post('apellidos')));
	        $offset        = empty(_post('count')) ? null            : _post('count');
	        $alu = $this->m_compromisos->validarAlumnosCompromisos($apellidos,null,null,_getSesion('id_sede_trabajo'));
	        $data['tablaAlumnos'] = $this->buildTablaAlumnosHTML($alu);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function inputCodigoAlumno() {
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
	        $apellidos     = empty(trim(_post('apellidos'))) ? null  : trim(_post('apellidos'));
	        $codAlu        = empty(trim(_post('codAlu'))) ? null     : trim(_post('codAlu'));
	        $offset        = empty(_post('count')) ? null : trim(_post('count'));
	        $alu = $this->m_compromisos->validarAlumnosCompromisos($apellidos,$codAlu,null,_getSesion('id_sede_trabajo'));
	        $data['tablaAlumnos'] = $this->buildTablaAlumnosHTML($alu);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function inputCodigoFamiliaAlumno() {
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
	        $apellidos     = empty(trim(_post('apellidos'))) ? null  : trim(_post('apellidos'));
	        $codAlu        = empty(trim(_post('codAlu'))) ? null     : trim(_post('codAlu'));
	        $codFamilia    = empty(trim(_post('codFamilia'))) ? null : trim(_post('codFamilia'));
	        $offset        = empty(_post('count')) ? null : _post('count');
	        $alu = $this->m_compromisos->validarAlumnosCompromisos($apellidos,$codAlu,$codFamilia,_getSesion('id_sede_trabajo'));
	        $data['tablaAlumnos'] = $this->buildTablaAlumnosHTML($alu);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function inputSearchAluCompromisos() {
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
	        $nombre        = empty(trim(_post('nombre'))) ? null     : trim(_post('nombre'));
	        $apellidos     = empty(trim(_post('apellidos'))) ? null  : trim(_post('apellidos'));
	        $codAlu        = empty(trim(_post('codAlu'))) ? null     : trim(_post('codAlu'));
	        $codFamilia    = empty(trim(_post('codFamilia'))) ? null : trim(_post('codFamilia'));
	        $offset        = empty(_post('count')) ? null : 12*_post('count');
	        $alu = $this->m_compromisos->validarAlumnosCompromisos($nombre,$apellidos,$codAlu,$codFamilia,$offset,_getSesion('id_sede_trabajo'));
	        $data['tablaAlumnos'] = $this->buildTablaAlumnosHTML($alu);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function mostrarYearCronoAlumno(){
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
    	    $sede = _decodeCI(_post('sede'));
    	    $data['opYear']        = '<option value="2016">2016</option><option value="2017">2017</option>';//__buildComboYearCronogramaBySede($sede);
    	    $data['optTiposCrono'] = __buildComboTiposCronograma();
    	    $data['comboNiveles']  = $this->buildCombosEstudiante(null,null,null);
    	    $data['table']         = $this->getTableEstudiantesCronograma(array(),null,null,null,null,null,null)['table'];
    	    $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function mostrarCompromisosYearAlumno(){
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
        	 $id_persona   = _decodeCI(trim(_post("id_persona")));
        	 $year         = trim(_post("year"));
        	 $sede         = _decodeCI(trim(_post("sede")));
        	 $nivel        = _decodeCI(trim(_post("nivel")));
        	 $grado        = _decodeCI(trim(_post("grado")));
        	 $flg_combo    = _post('flg_combo');
        	 $tipoCrono    = _decodeCI(trim(_post("tipoCrono")));
        	 if($tipoCrono == null){
        	     throw new Exception('Selecciona un tipo de cronograma');
        	 }
        	 if($year == null){
        	     throw new Exception('Selecciona un año');
        	 }
        	 if($flg_combo != '1'){
        	     $dataSNG = $this->m_compromisos->getNivelGradoSiguiente($id_persona,$year);
        	     $sede    = $dataSNG['id_sede_ingreso'];
        	     $nivel   = $dataSNG['id_nivel'];
        	     $grado   = $dataSNG['nid_grado'];
        	 }
        	 $data['combos'] = null;
        	 if($flg_combo != '1'){
        	     $data['combos'] = $this->buildCombosEstudiante($sede,$nivel,$grado);
        	 }
        	 $calendar     = $this->m_compromisos->ValidarCronoAluCompromisos($sede,$nivel,$grado,$year,$id_persona,$tipoCrono);
        	 $cuotIngreso  = $this->m_pensiones->getCuotaIngresoBySedeNivelGrado($sede,$nivel,$grado,$year);  
        	 $tab = $this->getTableEstudiantesCronograma($calendar['result'],$calendar['descuento'],$calendar['codigo'],$year,$cuotIngreso,$id_persona,$sede);
        	 $data['table']  = $tab['table'];
        	 $data['codigo'] = $tab['codigo'];
        	 $data['error']  = EXIT_SUCCESS;
	    } catch (Exception $e) {
	         $data['msj'] = $e->getMessage();
    	}
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function saveCompromisosAlu(){
	    $data['error']      = EXIT_ERROR;
	    $data['msj']        = null;
	    try {
	        $total_becas = null;
	        $persSess       = $this->_idUserSess;
	        $getnombre      = _getSesion('nombre_abvr');
	        $compromisos    = array(array());
	        $datos_audi_mov = array(array());
	        $year           = empty(trim(_post('year')))      ? null : trim(_post('year'));
	        $tipoCrono      = empty(trim(_post('tipoCrono'))) ? null : _decodeCI(trim(_post('tipoCrono')));
	        $detalles       = empty(_post('detalles'))        ? null : _post('detalles');
	        $id_persona     = empty(_post('idpersona'))       ? null : _decodeCI(_post('idpersona'));
	        $monto_final    = empty(_post('montofinal'))      ? null : _post('montofinal');
	        $sede           = empty(_post('sede'))            ? null : _decodeCI(_post('sede'));
	        $nivel          = empty(_post('nivel'))           ? null :_decodeCI( _post('nivel'));
	        $grado          = empty(_post('grado'))           ? null : _decodeCI(_post('grado'));
	        $beca           = empty(_post('beca'))            ? null : _post('beca');
	        if($id_persona == null){
	            throw new Exception(ANP);
	        }
	        $countDeuda = $this->m_movimientos->verificaDeudaByAlumno($id_persona);
	        if($countDeuda > 0){
	            throw new Exception('Este estudiante tiene deudas');
	        }
	        if($sede == null){
	            throw new Exception('Selecciona una sede');
	        }
	        if($nivel == null){
	            throw new Exception('Selecciona un nivel');
	        }
	        if($grado == null){
	            throw new Exception('Selecciona un grado');
	        }
	        if($year == null) {
	            throw new Exception('Selecciona a&ntilde;o');
	        }
	        if(count($detalles) == 0) {
	            throw new Exception('Selecciona al menos un concepto del cronograma de pago');
	        }
	        if(0 < count($beca)){
	            foreach ($beca as $item){
	                if(is_numeric($item)){
	                    $total_becas += $item;
	                }
	            }
	        } else{
	            $total_becas = 0;
	        }
	        $i=0;
	        $porcentaje  = (100-$this->m_pensiones->getBecaByPersona($id_persona))/100;
	        $idCondicion = null;
	        foreach ($detalles as $item){
	            $itemDecry      = _decodeCI($item);
	            $detalleCond    = $this->m_compromisos->getId_condicionAlumno($sede,$nivel,$grado,$year,$tipoCrono,$itemDecry);
	            $idCondicion    = $detalleCond['id_condicion'];
	            $fecha_vencimiento = null;
	            $flg_cuota_ingreso = false;
	            if($itemDecry != null && $item != ""){
	                $necesario  = $this->m_compromisos->getMoraByDetalle($itemDecry,$porcentaje,$sede,$nivel,$grado,$year,$tipoCrono);
	            } else{
	                $necesario['estado']     = ESTADO_POR_PAGAR;
	                $necesario['monto_base'] = $monto_final[$i];
	            }
	            $fec_vencimiento = $this->m_utils->getById('pagos.detalle_cronograma', 'fecha_vencimiento', 'id_detalle_cronograma', $itemDecry);
	            $idDetCronoDecry = _decodeCI($item);
	            if($idDetCronoDecry != null){
	                $fecha_vencimiento = $this->m_utils->getById('pagos.detalle_cronograma', 'fecha_vencimiento', 'id_detalle_cronograma', $idDetCronoDecry);
	            }
	            $push = array("tipo_movimiento"         => MOV_INGRESO,
	                          "estado"                  => $necesario['estado'],
	                          "monto"                   => $necesario['monto_base'],
            	              "monto_final"             => $monto_final[$i],
            	              "_id_persona"             => $id_persona,
            	              "_id_detalle_cronograma"  => (_decodeCI($item) == "") ? null : $itemDecry,
            	              "_id_concepto"            => (($itemDecry == null) ? CUOTA_INGRESO : CONCEPTO_SERV_ESCOLAR),
	                          "descuento_acumulado"     => $detalleCond['descuento_nivel'],
                              'fecha_vencimiento_aux'       => $fec_vencimiento
	                         );
	            array_push($compromisos, $push); $i++;
	        }
	        unset($compromisos[0]);
	        $data 				= $this->m_compromisos->SaveCompromisosMovimientos($compromisos);
            $id_sede_trabajo    = $this->m_utils->getSedeTrabajoByColaborador($persSess);
	        $id_caja            = $this->m_caja->getCurrentCaja($id_sede_trabajo,$persSess);
	        $fisrt_id_mov 		= ($data['id_movimiento'] - $data['n_total_mov']);
	        $last_id_mov  		= $data['id_movimiento'];
			for($i = $fisrt_id_mov ; $i< $last_id_mov; $i++){
    	        $push = array('_id_movimiento' => $i,
                	          'correlativo'    => $this->m_movimientos->getNextCorrelativo($i),
                	          'id_pers_regi'   => $persSess,
                	          'audi_nomb_regi' => $getnombre,
                	          'accion'         => REGISTRAR,
                	          'observacion'    => '',
                	          '_id_caja'       => $id_caja['id_caja'],
                	          '_id_sede'       => $id_sede_trabajo,
    	                      'flg_audi_regi'  => 1
    	                     );
    	        array_push($datos_audi_mov, $push);
	        }
	        unset($datos_audi_mov[0]);
	        $condicion_x_persona = array(
	            '_id_condicion'  => $idCondicion,
	            '_id_persona'    => $id_persona,
	            'estado'         => FLG_ESTADO_ACTIVO,
	            'flg_beca'       => (0<$total_becas) ? 1 : 0
	        );
			$data = $this->m_compromisos->SaveCompromisosAudiMovimientos(array_values($datos_audi_mov),$condicion_x_persona);
			$this->db->trans_rollback();
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTablaAlumnosHTML($alumnos){
	    $idAlu         = array();
	    $idSede        = array();
	    $idAluNew      = array();
	    $detalleCro    = array();
	    $lista_alumnos = array(array());
	    foreach($alumnos as $item){
	        $idAlu[] = $item->nid_persona;
	        $idSede[] = $item->nid_sede;
	    }
	    if(0<count($idSede)){
	        $detalleCro = $this->m_compromisos->total_cronoBySedeArray($idSede);
	    }
	    if(0<count($detalleCro)){
    	    foreach ($detalleCro as $item){
    	        $detTotalCronogramaSede[$item->_id_sede] = $item->total;
    	    }
	    }
	    if(0<count($idAlu)){
	       $comp = $this->m_compromisos->ValidarAluCompromisos($idAlu);
	       foreach ($comp as $item){
	           $idAluNew [$item->_id_persona] = $item->total;
	       }
	    }
        if(0<count($idAluNew)){
	        foreach($alumnos as $item){
// 	            foreach($idAluNew as $k => $item2){
// 	                if($k == $item->nid_persona && isset($detTotalCronogramaSede[$item->nid_sede])){
// 	                    if($item2 != $detTotalCronogramaSede[$item->nid_sede]){
// 	                       $continuar = 1;
// 	                   } else{
// 	                       $continuar = 0;
// 	                   }
// 	                } else{
// 	                   $continuar = 1; 
// 	                }   
// 	                if($continuar == 1){
    	                $push = array('nid_persona'        =>  $item->nid_persona,
    	                        	  'nombres'            =>  $item->nombres,
    	                        	  'apellidos'          =>  $item->apellidos,
    	                        	  'nid_sede'           =>  $item->nid_sede,
    	                        	  'nid_nivel'          =>  $item->nid_nivel,
    	                        	  'nid_grado'          =>  $item->nid_grado,
    	                        	  'desc_sede'          =>  $item->desc_sede,
    	                        	  'desc_nivel'         =>  $item->desc_nivel,
    	                        	  'desc_grado'         =>  $item->desc_grado,
    	                        	  'desc_aula'          =>  $item->desc_aula,
    	                        	  'cod_familia'        =>  $item->cod_familia,
    	                        	  'cod_alumno'         =>  $item->cod_alumno,
    	                              'moroso'             =>  $item->moroso
    	                );
	                    array_push($lista_alumnos, $push);
// 	                }
// 	            }
	        }
	        unset($lista_alumnos[0]);
	    }
	    else{
    	    foreach($alumnos as $item){
    	        $push = array('nid_persona'        =>  $item->nid_persona,
    	                      'nombres'            =>  $item->nombres,
    	                      'apellidos'          =>  $item->apellidos,
    	                      'nid_sede'           =>  $item->nid_sede,
    	                      'nid_nivel'          =>  $item->nid_nivel,
    	                      'nid_grado'          =>  $item->nid_grado,
    	                      'desc_sede'          =>  $item->desc_sede,
    	                      'desc_nivel'         =>  $item->desc_nivel,
    	                      'desc_grado'         =>  $item->desc_grado,
    	                      'desc_aula'          =>  $item->desc_aula,
    	                      'cod_familia'        =>  $item->cod_familia,
    	                      'cod_alumno'         =>  $item->cod_alumno,
    	                      'moroso'             =>  $item->moroso);
    	                    array_push($lista_alumnos, $push);
    	        }
	        unset($lista_alumnos[0]);
	    }
		$card=''; $val = 0;
        foreach ($lista_alumnos as $row){
               $val++;
               $class = ($row['moroso'] > 0) ? 'moroso' : 'puntual'; 
               $card .= '<div class="mdl-card mdl-student" id="alu_'.$row['cod_alumno'].'">
                            <div class="mdl-card__title">
                                <img alt="Student" src='.RUTA_IMG.'profile/nouser.svg>
                            </div>
                            <div class="mdl-card__supporting-text pago '.$class.'">
                                <div class="row p-0 m-0">
                                    <div class="col-xs-12 student-name">'.$row['apellidos'].'</div>
                                    <div class="col-xs-12 student-name">'.$row['nombres'].'</div>
                                    <div class="col-xs-12 student-head"><strong>Detalles del Estudiante</strong></div>
                                    <div class="col-xs-7  student-item">Cod. de Alumno</div>
                                    <div class="col-xs-5  student-value">'.$row['cod_alumno'].'</div>
                                    <div class="col-xs-7  student-item">Cod. de Familia</div>
                                    <div class="col-xs-5  student-value">'.$row['cod_familia'].'</div>
                                    <div class="col-xs-3  student-item">Sede</div>
                                    <div class="col-xs-9  student-value">'.$row['desc_sede'].'</div> 
                                    <div class="col-xs-3  student-item">Nivel</div>
                                    <div class="col-xs-9  student-value">'.$row['desc_nivel'].'</div>
                                    <div class="col-xs-3  student-item">Grado</div>
                                    <div class="col-xs-9  student-value">'.$row['desc_grado'].'</div>
                                    <div class="col-xs-3  student-item">Aula</div>
                                    <div class="col-xs-9  student-value">'.$row['desc_aula'].'</div>
                                </div>  
                            </div>
                            <div class="mdl-card__actions">
                                <button id="btn_ver_compromisos" class="mdl-button mdl-js-button mdl-button--raised btn_modal_card" onclick="getYearCronoAlu(\''._encodeCI($row['nid_persona']) .'\',\''._encodeCI($row['nid_sede']).'\',\''._encodeCI($row['nid_nivel']).'\',\''._encodeCI($row['nid_grado']).'\','.$row['moroso'].')">compromisos</button>
                            </div>
                            <div class="mdl-card__menu">                     
                                <input type="hidden" name="student_year_compromisos[]" id="student_year">    
                                <input type="hidden" name="student_detalles_cronogramas[][]" id="student_detalles_cronogramas">    
                                <input type="hidden" name="student_compromisos[]" id="student" value="'._encodeCI($row['nid_persona']).'">           
                                <input type="hidden" name="student_sede_compromiso[]" id="student_sede" value="'._encodeCI($row['nid_sede']).'">
                                <input type="hidden" name="student_nivel_compromiso[]" id="student_nivel" value="'._encodeCI($row['nid_nivel']).'">
                                <input type="hidden" name="student_grado_compromiso[]" id="student_grado" value="'._encodeCI($row['nid_grado']).'">   
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="abrirModalPaquete(\'Compromisos extras\')">
                                <i class="mdi mdi-more_vert"></i>
                             </button>                            
                            </div>
                        </div>';
	    }
	    return $card;
	}
	
	function getTableEstudiantesAula($val,$nom_aula,$aula) {
	    /* CREAR LISTA DE ESTUDIANTES PARA CADA AULA*/
	    $tmpl[$val]  = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                                  data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                                  data-show-columns="false" data-search="false" id="tb_compromiso'.$val.'">',
                    	      'table_close' => '</table>');
	    $this->table->set_template($tmpl[$val]);
	    $head_1      = array('data' => '<label class="mdl-checkbox mdl-js-checkbox" for="checkbox-student">
                                          <input type="checkbox" id="checkbox-student" class="mdl-checkbox__input"  value="all" onclick="click_total_compromisosAula()">
                                          <span class="mdl-checkbox__label"></span>
    	                                </label>', 'data-checkbox' => '', 'class' => 'text-left');
	    $head_2      = array('data' => 'Estudiantes', 'class' => 'text-left');
	     
	    $this->table->set_heading($head_1, $head_2);
	    $val2=0;
		foreach ($aula as $row2){ 
    	    $val2++;
    	    $nom_aula = ($nom_aula == '') ? 'aula-':$nom_aula.'-';
    	    $id_aula = 'checkbox-student-'.__urls_amigables($nom_aula).__urls_amigables($row2->nombre_estudiante);
    	     
    	    $row_cell_1           = array('data' => '<input type="checkbox" name="student_aulas['.$val.']['.$val2.']" id="'.$id_aula.'" value="'._encodeCI($row2->id_estudiantes).'">',
                                	      'class' => 'text-center id', 'data-field' => 'checkbox');
    	    $row_cell_2           = array('data'   => '<div style="cursor:pointer" class="img-table"><img class="img-circle m-r-5" WIDTH=25 HIEGHT=25 src="'.RUTA_IMG.'/profile/'.$row2->foto_persona.'">'.(($row2->nombre_estudiante)).'</div>', 'class' => 'text-left img-table');
    	     
    	    $this->table->add_row($row_cell_1, $row_cell_2);
	    }
	    return array("table" => $this->table->generate(),"val" => $val2);
	}
	
	function getTableEstudiantesCronograma($calendar,$descuento,$codigo,$year,$cuotIngreso,$id_persona,$sede) {
	    /* CREAR LISTA DE ESTUDIANTES PARA CADA AULA*/
	    $tmpl  = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                                              data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                                              data-search="false" id="tb_compromisoCalendarAlu-'.$year.'-'.$codigo.'">',
            	       'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_1      = array('data' => '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-compromiso-global-'.$codigo.'">
                                                          <input type="checkbox" id="checkbox-compromiso-global-'.$codigo.'" class="mdl-checkbox__input" onclick="check_cronogramas_compromisos_global(\''.$codigo.'\',\''.$year.'\')">
                                                          <span class="mdl-checkbox__label"></span>
                                        </label>', 'data-field' => 'checkbox');
	    $head_2      = array('data' => 'Descripci&oacute;n');
	    $head_3      = array('data' => 'F. de vencimiento','style' => 'text-align:left', 'data-sortable' => 'true');
	    $head_4      = array('data' => 'F. de descuento');
	    $head_5      = array('data' => 'Monto Final');
	    $head_6      = array('data' => 'Beca');
	    $head_7      = array('data' => 'Concepto');
	    
	    $this->table->set_heading($head_1, $head_2, $head_3,$head_4,$head_5/*,$head_6*/,$head_7);
	    $configCI = $this->m_compromisos->verifyConfigCI($sede,$year);
	    $val2=0;
	    foreach ($calendar as $row2){
            if($row2->flg_registrado == '0'){
	           $val2++;
// 	           if($row2->flg_tipo == FLG_MATRICULA && ($configCI != null && $configCI['estado'] != 'INACTIVO')){
// 	               $flg_cuota_ingreso = $this->m_compromisos->evaluateCuotaIngresoByPersonaFamilia($id_persona,$configCI['flg_afecta']);
// 	               if($flg_cuota_ingreso == 0 && $row2->_id_tipo_cronograma == 2){
// 	                   $detalle = _encodeCI(null);
// 	                   $row_cell_1           = array('data'   => '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-compromiso-'.$codigo.'-'.$val2.'">
//                                                                     <input type="checkbox" id="checkbox-compromiso-'.$codigo.'-'.$val2.'" class="mdl-checkbox__input" data-detalle="'.$detalle.'" onclick="check_cronogramas_compromisos(\''.$codigo.'\',\''.$year.'\',\''.$val2.'\')">
//                                                                     <span class="mdl-checkbox__label"></span>
//                                                                   </label>', 'data-detalle' => 'Cuota Ingreso');
// 	                   $row_cell_2           = array('data'   =>  'Cuota Ingreso', 'class'  => 'id');
// 	                   $row_cell_3           = array('data'   => null);
// 	                   $row_cell_4           = array('data'   => null);
// 	                   $row_cell_5           = array('data'   => $cuotIngreso, 'class'  => 'monto_final');
// 	                   $row_cell_6           = array('data'   => '-', 'class'  => 'beca' );
// 	                   $row_cell_7           = array('data'   => 'Cuota Ingreso');
// 	                   $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3,$row_cell_4,$row_cell_5,$row_cell_6,$row_cell_7);
// 	                   $val2++;
// 	               }
	               
	               
// 	           }
	           $detalle = _encodeCI($row2->id_cuota);
    	        $row_cell_1           = array('data'   => '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-compromiso-'.$codigo.'-'.$val2.'">
                                                                    <input type="checkbox" id="checkbox-compromiso-'.$codigo.'-'.$val2.'" class="mdl-checkbox__input" data-detalle="'.$detalle.'" onclick="check_cronogramas_compromisos(\''.$codigo.'\',\''.$year.'\',\''.$val2.'\')">
                                                                    <span class="mdl-checkbox__label"></span>
                                                                  </label>', 'data-detalle' => $detalle);
    	        $row_cell_2           = array('data'   => (($row2->desc_deta)), 'class'  => 'id');
    	        $row_cell_3           = array('data'   => (strtolower($row2->fec_venc)));
    	        $row_cell_4           = array('data'   => ($row2->fec_desc != NULL) ? (strtolower($row2->fec_desc)) : '-');
    	        
    	        $row_cell_5           = array('data'   => (strtolower($row2->monto_final)), 'class'  => 'monto_final');
    	        //$row_cell_6           = array('data'   => ($row2->descuento == 'BECA') ? (strtolower(round($descuento).' %')) : '-', 'class'  => 'beca' );
    	        //$row_cell_7           = array('data'   => (($row2->concepto)));
    	        $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3,$row_cell_4,$row_cell_5/*,$row_cell_6,$row_cell_7*/);
            }
	    }
	    $table = $this->table->generate();
		return array("table" => $table,'codigo' =>$codigo);
	}
	
	function anularCompromisosExtras() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $id_mov             = array();
	        $datos_insert       = array();
	        $idpersona          = $this->_idUserSess;
	        $getnombre          = _getSesion('nombre');
	        $id_movimientos     = _post("movimientos");
	        $observacion        = _post("observacion");
	        $idGlobal           = _decodeCI(_post('idGlobal'));
	        $n_correlativo = null;
	        foreach ($id_movimientos as $item){
	            $id_mov[] =(int) _decodeCI($item);
	        }
	        
	        rsort($id_mov); 
	        $id_sede_trabajo    = $this->m_utils->getSedeTrabajoByColaborador($idpersona);
	        $correlativo        = $this->m_movimientos->getNextCorrelativoArray($id_mov);
	        $caja               = $this->m_caja->getCurrentCaja($id_sede_trabajo,$idpersona);
	        
	        if(count($id_sede_trabajo) == 0){
	            throw new Exception('Comun&iacute;quese con el administrador porfavor');
	        }
	        if($caja['id_caja'] == null){
                throw new Exception('No has aperturado tu caja');
            }
            if($caja['estado_caja'] == CERRADA || $caja['estado_caja'] == CERRADA_EMERGENCIA){
                throw new Exception('Tu caja ya ha sido cerrada');
            }
	        
	        foreach($correlativo as $item){
	            $n_correlativo [$item->_id_movimiento] = $item->cuenta;
	        }
            $i = 1;
	        foreach ($id_mov as $id_m){
	            if(isset($n_correlativo[$id_m])){
	                $correlat = $n_correlativo[$id_m];
	            } else{
	                $correlat = $i; 
	                $i++;
	            }
	            $correlat = $this->getCorrelativoReciboByMovimiento($correlat);
	            $push = array('_id_movimiento' => $id_m,
    	                      'correlativo'    => $correlat,
    	                      'id_pers_regi'   => $idpersona,
	                          'audi_nomb_regi' => $getnombre, 
	                          'accion'         => ANULAR,
	                          'observacion'    => $observacion,
	                          '_id_caja'       => $caja['id_caja'],
                	          '_id_sede'       => $id_sede_trabajo);
	               array_push($datos_insert, $push);
	        }
	        $data = $this->m_compromisos->anularCompromisosExtras($id_mov,$observacion,$datos_insert, $idGlobal);
	        $data['error'] = EXIT_SUCCESS;
	    }
	    catch (Exception $e) {
	        $data['error']    = EXIT_ERROR;
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getCorrelativoReciboByMovimiento($correlativo) {
		$lengthCorre = strlen($correlativo);
		$correlativoNew = null;
		for($i = $lengthCorre; $i < 8 ; $i++){
			$correlativoNew .= '0';
		}
		$correlativoNew .= $correlativo;
		return $correlativoNew;
	}
	
	function saveCompromisosMulti() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $alu          = _post("alumnos");
	        $idconcepto   = trim(_decodeCI(_post("concepto")));
	        $descripcion  = trim(_post("descripcion"));
	        $monto        = trim(_post("monto"));
			if (count($alu) == 0) {
	            throw new Exception('Debes seleccionar al menos un estudiante');
	        }
	        if ($monto == null) {
	            throw new Exception('Debes ingresar un monto');
	        }
	        if (is_numeric($monto) == null) {
	            throw new Exception('El monto debe ser numérico');
	        }
	        if ($monto < 0) {
	            throw new Exception('El monto no debe ser negativo');
	        }
            if(($idconcepto == null && $descripcion == null) || ($idconcepto != null && $descripcion != null)){
                throw new Exception('Selecciona un concepto o escribe una descripción');
            }
	       	if ($idconcepto == null) {
                
    	        if($this->m_compromisos->ValidarConceptoCompromisos($descripcion) == null)
    	        {
    	            throw new Exception('El concepto ya existe!!!');
    	        }
    	        $datos = array("desc_concepto"    => $descripcion,
            	               "monto_referencia" => $monto,
            	               "tipo_movimiento"  => "INGRESO",
            	               "estado"           => FLG_ESTADO_ACTIVO);
    	        $data = $this->m_compromisos->crearConceptoCompromisos($datos);
    	        $id_concept = $data['insert_id_concepto'];
    	        $descrip    = $descripcion;
	        }
	        else{
	            $id_concept = $idconcepto;
	            $descrip    = $this->m_compromisos->buscarConcepto($id_concept);
	        }
	        $datos2 = array("id_audi_persona" => $this->_idUserSess,
	                        "audi_pers_regi"  => _getSesion('nombre_abvr'),
	                        "desc_concepto"   => $descrip,
	        				"estado"          => ESTADO_ACTIVO
	        );
	        $data = $this->m_compromisos->crearCompromisosGlobales($datos2);
			$i = 0;
	        foreach ($alu as $item){
	            $compromisos[$i]["tipo_movimiento"]        = "INGRESO";
	            $compromisos[$i]["monto"]                  = $monto;
	            $compromisos[$i]["monto_final"]            = $monto;
	            $compromisos[$i]["estado"]                 = "POR PAGAR";
	            $compromisos[$i]["_id_persona"]            = _decodeCI($item);
	            $compromisos[$i]["_id_concepto"]           = $id_concept;
	            $compromisos[$i]["_id_compromiso_global"]  = $data['insert_id'];
	            $i++;
	        }
	        $this->m_compromisos->crearCompromisosMasivos($compromisos);
	        $data['error'] = EXIT_SUCCESS;
	        $data['imagen'] = '<img src="'.base_url().'public/img/smiledu_faces/not_data_found.png">
                                          <p>Ups! A&uacute;n no se han registrado datos.</p>';
	    }
	    catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getTableCompromisosGlobales($lista_compromisos,$nombre_personas,$id_mov) {
	    /* CREAR LISTA DE ESTUDIANTES PARA CADA AULA*/
	    $tmpl  = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                            data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                            data-show-columns="false" data-search="false" id="tb_lista_compromisoAlu">',
	        'table_close' => '</table>'
	    );
	    $this->table->set_template($tmpl);
	    $head_1      = array('data'          => '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-compromiso_global">
                                                    <input type="checkbox" id="checkbox-compromiso_global" class="mdl-checkbox__input" onclick="AllcheckUpdateCompromisoDelete();">
                                                        <span class="mdl-checkbox__label"></span>
                                                </label>'     , 'class'         => 'text-center');
	    $head_2      = array('data'          => 'Estudiante'  , 'data-sortable' => 'true', 'data-field'    =>'is_check', 'class' => 'text-left');
	    $head_3      = array('data'          => 'Monto'       , 'class' => 'text-right');
	    $head_4      = array('data'          => 'Monto final' , 'class' => 'text-right');
	    $head_5      = array('data'          => 'F. registro' , 'class' => 'text-center');
	    $head_6      = array('data'          => 'Concepto'    , 'class' => 'text-left');
	
	    $this->table->set_heading($head_1, $head_2, $head_3,$head_4,$head_5,$head_6);
	    $val2=0;
	
	    if(0<count($lista_compromisos)){
    	    foreach ($lista_compromisos as $row2){
    	        $val2++;
    	        $row_cell_1           = array('data'      => '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-compromiso-'.$val2.'">
                                                              	<input type="checkbox" data-mov = "'._encodeCI($id_mov[$val2-1]).'" id="checkbox-compromiso-'.$val2.'" class="mdl-checkbox__input" onclick="checkUpdateCompromisoDelete(\''.$val2 .'\');assignItemAUX(this.id, \'tb_lista_compromisoAlu\', \'cabeConfirm\');">
                                                                <span class="mdl-checkbox__label"></span>
                                                              </label>' , 'class' => 'text-center');
    	        $row_cell_2           = array('data'      => (($nombre_personas[$row2->_id_persona]))               , 'class' => 'text-left');
    	        $row_cell_3           = array('data'      => (strtolower($row2->monto))                             , 'class' => 'text-right');
    	        $row_cell_4           = array('data'      => (strtolower($row2->monto_final))                       , 'class' => 'text-right');
    	        $row_cell_5           = array('data'      => (($row2->fecha_registro))                              , 'class' => 'text-center');
    	        $row_cell_6           = array('data'      => (($row2->concepto))                                    , 'class' => 'text-left');
    			$this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3,$row_cell_4,$row_cell_5,$row_cell_6);
    	    }
	   	}
	   	else{$this->table->add_row('', '', '','','','');}
	    return $this->table->generate();
	}
	
	function listaCompromisosGlobales() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $data['lista'] = '';
	        $nombre_personas = array();
	        $id_mov = array();
	        $idcompromiso      = _decodeCI(_post("compromisoglobal"));  
	        $lista_compromisos = $this->m_compromisos->listaGlobalCompromisos($idcompromiso);
	        if(count($lista_compromisos)){
	            $id = array(); 
	            foreach ($lista_compromisos as $item){
	                $id[] = (int)$item->_id_persona;
	                $id_mov[] = (int)$item->id_movimiento;
	            }
	            $lista_personas = $this->m_compromisos->GetListaPersonas($id);
	            foreach ($lista_personas as $item){
	                $nombre_personas[$item->nid_persona] = $item->persona;
	            } 
	            $data['lista'] = $this->getTableCompromisosGlobales($lista_compromisos,$nombre_personas,$id_mov);
	        }
	        else{
	            $data['lista'] = $this->getTableCompromisosGlobales(array(),$nombre_personas,$id_mov);
	            $data['error'] = EXIT_ERROR;
	        }
	        $data['error'] = EXIT_SUCCESS;
	    }
	    catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getCompromisosGlobales() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $data['combo'] = __buildComboCompromisosGlobales();
	        $data['error'] = EXIT_SUCCESS;
	    }
	    catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function saveCompromisosMultiAlu() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $year      = array();
	        $detCrono  = array();
	        $student   = array();
	        $sede      = array();
	        $nivel     = array();
	        $grado     = array();
	        $yearr     = _post("year");
	        $Crono     = _post("detCrono");
	        $stud      = _post("student");
	        $sed       = _post("sede");
	        $niv       = _post("nivel");
	        $grad      = _post("grado");
	        $student   = array();
	      	foreach ($yearr as $item){
	            $year[] = (int)_decodeCI($item);
	        }
	        foreach ($Crono as $item){
	            $detCrono[] = (int)_decodeCI($item);
	        }
	        foreach ($stud as $item){
	            $student[] = (int)_decodeCI($item);
	        }
	        foreach ($sed as $item){
	            $sede[]    = (int)_decodeCI($item);
	        }
	        foreach ($niv as $item){
	            $nivel[]   = (int)_decodeCI($item);
	        }
	        foreach ($grad as $item){
	            $grado[]   = (int)_decodeCI($item);
	        }
	        $condiciones_pensiones = array(array());
	        $condiciones_becas= array(array());
	        $compromisos_alu= array(array());
			$r_condiciones    = $this->m_compromisos->condicionesPagoBySedeNivelGrado();
	        foreach ($r_condiciones as $item){
	            if($item->condicion == 'pension'){
    	            $push = array(
                	               'id_sede'                  =>  $item->_id_sede,
                	               'id_nivel'                 =>  $item->_id_nivel,
                	               'id_grado'                 =>  $item->_id_grado,
                	               'id_condicion'             =>  $item->id_condicion,
                	               'monto_cuota_ingreso'      =>  $item->monto_cuota_ingreso,
                	               'monto_matricula'          =>  $item->monto_matricula,
                	               'monto_pension'            =>  $item->monto_pension,
                	               'descuento_nivel'          =>  $item->descuento_nivel
    	                          );
    	            array_push($condiciones_pensiones, $push);
	            }
	            else{ 
	                $push2 = array(
            	                    'id_condicion'             =>  $item->id_condicion,
            	                    'porcentaje_beca'          =>  $item->porcentaje_beca
            	                  );
	                array_push($condiciones_becas, $push2);
	            }
	        }
	        unset($condiciones_becas[0]);   
	        unset($condiciones_pensiones[0]); 
	        $id_becas = array();
	        foreach ($condiciones_becas as $item){
	            $id_becas[] = (int)$item['id_condicion'];
	        }
	        $becas_alumnos = array();
	        if(count($id_becas) > 0){
	            $becas_alumnos = $this->m_compromisos->becasByidAlumnosIdcondicion($id_becas,$student);
	        }
	        $CronogramasDet = $this->m_compromisos->getDetallesAllCronogramas($year,$detCrono);
	        $i = 0; $n_personas = 0; $temp_id = null;
	        foreach ($student as $id_alumno){
 	            $porcentaje_beca = null;
 	            foreach($becas_alumnos as $becas){
 	                foreach($condiciones_becas as $cbecas){  
     	                if($becas->_id_persona == $id_alumno && $becas->_id_condicion == $cbecas['id_condicion']){
     	                    $porcentaje_beca = $cbecas['porcentaje_beca'];
     	                }
 	                }
 	            }
 	            foreach ($CronogramasDet as $crono){
	                if($sede[$i] == $crono->_id_sede){ 
	                    foreach($condiciones_pensiones as $pensiones){
	                        if($pensiones['id_sede'] == $sede[$i] && $pensiones['id_nivel'] == $nivel[$i] && $pensiones['id_grado'] == $grado[$i]){
	                            $descuento_nivel = $pensiones['descuento_nivel'];
	                            if($crono->flg_tipo == 1){
	                                $monto = $pensiones['monto_matricula'];
	                            }
	                            else{
	                                if($crono->flg_tipo == 2){
	                                    $monto = $pensiones['monto_cuota_ingreso'];
	                                }
	                                else{
	                                    $monto = $pensiones['monto_pension'];
	                                }
	                            }
	                            if($porcentaje_beca != null && $crono->flg_beca == '1'){
	                                $monto_final = round((($monto * $porcentaje_beca)/100) - (($descuento_nivel * $porcentaje_beca)/100),2);
	                            }
	                            else{
	                                $monto_final = $monto - $descuento_nivel;
	                            }
	                            
	                            if($temp_id != $id_alumno){
	                               $n_personas++;  $temp_id = $id_alumno;
	                            }
	                        }
	                    }
	                    $push = array('tipo_movimiento'             =>  MOV_INGRESO,
            	                       'monto'                       =>  $monto,
            	                       'monto_final'                 =>  $monto_final,
            	                       'estado'                      =>  ESTADO_POR_PAGAR,
            	                       '_id_persona'                 =>  $id_alumno,
            	                       '_id_detalle_cronograma'      =>  $crono->id_detalle_cronograma,
            	                       'descuento_acumulado'         =>  $descuento_nivel,
	                                   '_id_concepto'                => CONCEPTO_SERV_ESCOLAR,
	                                   'fecha_vencimiento_aux'       => $crono->fecha_vencimiento 
	                    );
	                    array_push($compromisos_alu, $push);
	                }
	            }
	           	$i++;
 	        }
 	        unset($compromisos_alu[0]);
 	        if ($n_personas < count($student)) {
 	            if($n_personas == 0){
 	                if(count($student) == 1){
 	                    throw new Exception("El estudiante seleccionado no cumple con los requisitos");
 	                }
 	                else{
 	                    throw new Exception("Ningï¿½no de los ".count($student)." estudiantes comple con los requisitos");
 	                }
 	            }
 	            else{
 	                throw new Exception("Error al registrar compromisos porque solo ".$n_personas.' de '.count($student).' estudiantes cumplian los requisitos');
 	            }
 	            $data['error'] = EXIT_ERROR;
 	        }
            else{
	           $message = "Los compromisos se generaron con exito ".$n_personas.' de '.count($student);
	           $this->m_compromisos->crearCompromisosMasivos($compromisos_alu,$message);
            }
	        
            
	        $data['error'] = EXIT_SUCCESS;
	    }
	    catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildCardsAulasHTML($aulas) {
	    $card = null;
	    $val = 0; $result_graficos = array(array());
	    foreach($aulas as $row){
	        $val++;
	        $aula      = $this->m_compromisos->getEstudiantesByAula($row->n_aula);
	        $id_estudents = array();
	         
	        foreach ($aula as $row2){
	            $id_estudents[]=$row2->id_estudiantes;
	        }
	        $tabla = $this->getTableEstudiantesAula($val,$row->nombre_aula,$aula);
	        $card .= 	'<div class="mdl-card mdl-classroom puntual" id="aula_'.$val.'">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">'.(($row->nombre_aula == null) ? '-' : $row->nombre_aula).'</h2>
                            </div>
                            <div class="mdl-card__menu">
                                <div class="checkbox-item">
                                   <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-all-aula_'.$val.'">
                                      <input type="checkbox" id="checkbox-all-aula_'.$val.'" class="mdl-checkbox__input" onclick="clickCheckAula(\''.$val .'\')">
                                      <span class="mdl-checkbox__label"></span>
                                    </label> 
                                </div>  
                            </div>
                            <div class="mdl-card__supporting-text">
                                <div class="row p-0 m-0">
                                    <div class="col-xs-12 classroom-head">Detalles del Aula</div>
                                    <div class="col-xs-7  classroom-item">Sede</div>
                                    <div class="col-xs-5  classroom-value">'.$row->desc_sede.'</div>
                                    <div class="col-xs-7  classroom-item">Nivel</div>
                                    <div class="col-xs-5  classroom-value">'.$row->desc_nivel.'</div>
                                    <div class="col-xs-7  classroom-item">Grado</div>
                                    <div class="col-xs-5  classroom-value">'.$row->desc_grado.'</div>
                                    <div class="col-xs-7  classroom-item">Secci&oacute;n</div>
                                    <div class="col-xs-5  classroom-value">'.(($row->seccion == '') ? '-' : $row->seccion).'</div>
                                    <div class="col-xs-7  classroom-item">Capacidad</div>
                                    <div class="col-xs-5  classroom-value">'.$tabla['val'].'</div>
                                    
                                    <div class="col-xs-9 classroom-photo-desc">
                                        <div class="row-fluid m-0 p-0">
                                            <div class="col-xs-12 m-0 p-0 text-left"><strong>Tutor</strong></div>
                                            <div class="col-xs-12 m-0 p-0 classroom-item">'.(($row->tutor == null) ? '-' : $row->tutor).'</div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 classroom-photo">';
                                        if(trim($row->tutor) != null){
                                         $card .= '<img alt="" src="'.RUTA_SMILEDU.'uploads/images/foto_perfil/'.(($row->foto_persona == null) ? 'nouser.svg' : $row->foto_persona).'">';
                                        }
                                        else{
                                            $card .= '-';
                                        }
                          $card .= '</div> 
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                <div style="display:none" id="lista_estudiantes">'. $tabla['table'].'</div>
                                <button href="#modalVerEstudiantes" data-toggle="modal" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised btn_modal_card " onclick="modalDetalleAulaCompromiso(\''.$val .'\')">
                                  <span>Ver </span><span id="n_checked">0</span><span> / </span> <span id="total_estudiantes">'.$tabla['val'].'</button>
                            </div>
                        </div>';	                                  
                   
	    }
	    $total_aulas   = '<input type="hidden" id="total_cards" value="'.$val.'">';
	    return $total_aulas.$card;
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
	
	function setIdSistemaInSession() {
	    $idSistema = _decodeCI(_post('id_sis'));
	    $idRol     = _decodeCI(_post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }	    
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
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
	
	function buildCombosEstudiante($sede,$nivel,$grado){
	    $cmbSede  = '<div><select class="pickerButn" id="selectSede" onchange="getNivelesBySede()">
	                     <option>Selecciona una Sede</option>'.
	                     __buildComboSedes(null,$sede).'
	                 </select></div>';
	    $cmbNivel = '<select class="pickerButn" id="selectNivel" onchange="getGradosByNivelSede()">
	                     <option>Selecciona un Nivel</option>'.
	                     __buildComboNiveles(null,$nivel).'
	                 </select>';
	    $cmbGrado = '<select class="pickerButn" id="selectGrado" onchange="getCompromisosByGrado()">
	                     <option>Selecciona un Grado</option>'.
	                     __buildComboGradosByNivel($nivel, $sede,null,$grado).'
	                 </select>';
	    $combos = $cmbSede.$cmbNivel.$cmbGrado; 
	    return $combos;
	}
	
	function getComboNivelBySede(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $sede = _decodeCI(_post('idSede'));
	        if($sede == null){
	            throw new Exception('Selecciona una sede');
	        }
	        $data['optNivel'] = __buildComboNivelesBySede($sede);
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGradoBySedeCompromisos(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $sede  = _decodeCI(_post('idSede'));
	        $nivel = _decodeCI(_post('idNivel'));
	        if($sede == null){
	            throw new Exception('Selecciona una sede');
	        }
	        if($nivel == null){
	            throw new Exception('Selecciona un nivel');
	        }
	        $data['optGrado'] = __buildComboGradosByNivel($nivel, $sede);
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
}


