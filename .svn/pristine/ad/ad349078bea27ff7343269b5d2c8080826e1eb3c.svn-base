<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_main extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('mf_alumno/m_alumno');
        $this->load->model('mf_matricula/m_matricula');
        $this->load->model('mf_aula/m_aula');
        $this->load->library('table');
        $this->_idUserSess = _getSesion('nid_persona');
		$this->_idRol      = _getSesion(MATRICULA_ROL_SESS);
		if($this->_idRol == ID_ROL_FAMILIA){
		    redirect('matricula/c_matricula', true);
		}
        if(!isset($_COOKIE[__getCookieName()])) {
            $this->session->sess_destroy();
            Redirect(RUTA_SMILEDU, 'refresh');
        }
        if($this->_idUserSess == null || $this->_idRol == null) {
            $this->session->sess_destroy();
            Redirect(RUTA_SMILEDU, 'refresh');
        }
    }
    
	public function index() {
	    $dataUser = array("previousPage" => 'c_main');
	    $this->session->set_userdata($dataUser);
	    if($this->_idRol != ID_ROL_FAMILIA){
	        $data = _searchInputHTML('Busca aulas o estudiantes');
	    }
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_MATRICULA, MATRICULA_FOLDER);

	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_MATRICULA, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    // MENÔøΩ
		$data['comboTipoTraslado']       = __buildComboByGrupo(COMBO_TIPO_TRASLADO);
	    $data['main'] = true;
	    $data['ruta_logo'] = MENU_LOGO_MATRICULA;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_MATRICULA;
	    $data['nombre_logo'] = NAME_MODULO_MATRICULA;
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    $this->load->view('v_main', $data);
    }
    
	function busquedaGeneral(){
	    $nombre = utf8_decode(_post("valorGeneral"));
        $idRol = $this->_idRol;
        $idSede = null;
        $fechasAll = $this->m_matricula->getFechasReferencia(array('T','R'));
        $fechas    = null;
        $fechasRat = null;
        if($fechasAll != null){
            foreach ($fechasAll as $fec){
                switch ($fec->tipo) {
                    case CONFIG_RATIFICACION:        $fechasRat = (array)$fec;        break;
                    case CONFIG_TRASLADOS:           $fechas    = (array)$fec;        break;
                }
            }
        }
	    if($idRol != ID_ROL_ADMINISTRADOR){
            $idSede = _getSesion("id_sede_trabajo");
	    }
	    
        $alumnos = $this->m_alumno->getAlumnosByNombre($nombre, null, $idSede, 0, 5, _getYear(), null, null, null);
        $data['tablaAlumnos'] = _createCardAlumnos($alumnos, null, null, $fechas, $fechasRat);
        $aulas = $this->m_aula->getAulasByBusqueda($nombre, $idSede, 10);
        $data['tablaAulas'] = _createTableAulas($aulas);
 
        $data['countAlumnos'] = count($alumnos);
        $data['countAulas']   = count($aulas);
        
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	function abrirModalAlumnos(){
	    $idAula   = _simpleDecryptInt(_post('idaula'));
	    $alumnos = $this->m_matricula->getAlumnosByAulaLista($idAula);
	    $data['tablaAlumnos'] = _createTableAlumnos($alumnos);
	
	    echo json_encode(array_map('utf8_encode', $data));
	}

	function busquedaGeneralVerMas(){
	    $nombre = utf8_decode(_post("valorGeneral"));
	    $tipo   = _post("tipo");
	    $cant   = _post("c");
	     
	    $count = null;
	    if($cant == 1){
	        $count = 10;
	    }
	    
	    $idRol = $this->_idRol;
	    $idSede = null;
	    if($idRol != ID_ROL_ADMINISTRADOR){
            $idSede = $this->m_utils->getById("rrhh.personal_detalle", "id_sede_control", "id_persona", $this->_idUserSess);
	    }
        $fechasAll = $this->m_matricula->getFechasReferencia(array('T','R'));
        $fechas    = null;
        $fechasRat = null;
        if($fechasAll != null){
            foreach ($fechasAll as $fec){
                switch ($fec->tipo) {
                    case CONFIG_RATIFICACION:        $fechasRat = (array)$fec;        break;
                    case CONFIG_TRASLADOS:           $fechas    = (array)$fec;        break;
                }
            }
        }
	    if($tipo == 1){
	        $aulas = $this->m_aula->getAulasByBusqueda($nombre, $idSede, $count);
            $data['tablaAulas'] = _createTableAulas($aulas);
	    }else{
	        $alumnos = $this->m_alumno->getAlumnosByNombre($nombre, null, $idSede, $count);

            $data['tablaAlumnos'] = _createCardAlumnos($alumnos, null, null, $fechas, $fechasRat);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE[__getCookieName()]);
        $cookie_name2 = __getCookieName();
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
	
	function goToViewAlumno(){
	    $idAlumno = _simpleDecryptInt(_post("idalumno"));
	    $dataUser = array("idAlumnoEdit"        => $idAlumno,
	        "accionDetalleAlumno" => 0
	    );
	    $this->session->set_userdata($dataUser);
	}
	
	function goToEditAlumno(){
	    $idAlumno = _simpleDecryptInt(_post("idalumno"));
	    $dataUser = array("idAlumnoEdit"        => $idAlumno,
	        "accionDetalleAlumno" => 1,
	        "estadoCambio"        => 1
	    );
	    $this->session->set_userdata($dataUser);
	}
	
	function goToViewAula(){
	    $idAula = _simpleDecryptInt(_post("idaula"));
	    $dataUser = array("idAulaEdit"        => $idAula,
	        "accionDetalleAula" => 0
	    );
	    $this->session->set_userdata($dataUser);
	}
	
	function goToEditAula(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try {
	        $idAula      = _simpleDecryptInt(_post("idaula"));
	        $detalleAula = $this->m_aula->getDetalleAulas($idAula);
	        if( $detalleAula['year'] < date("Y") ){
	            throw new Exception('No puede editar aulas de a&ntilde;os anteriores');
	        }
	        $dataUser = array("idAulaEdit"        => $idAula,
	            "accionDetalleAula" => 1
	        );
	        $this->session->set_userdata($dataUser);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	/**
	 * Retorna las deudas pendientes de cronograma de un estudiante, usado en el icono de moneda
	 * en los cards de estudiantes
	 * @author dfloresgonz 02.12.2016
	 * @since  02.12.2016
	 */
	function getDeudasByEstudiante() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idEstudiante = _simpleDecryptInt(_post('idpostulante'));
	        if($idEstudiante == null) {
	            throw new Exception(ANP);
	        }
	        $data['table'] = __getDeudasByEstu($idEstudiante);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	/**
	 * @deprecated reemplazado por getDeudasByEstudiante
	 * @throws Exception
	 */
	function mostrarCompromisosYearAlumno(){
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
    	    $tipo = _post('tipo') != null ? (_post('tipo')) : null;
    	    if($tipo == 1){
    	    	$id_postulante = _post('idpostulante') != null ? _simpleDecryptInt(_post('idpostulante')) : null;
    	    } else {
    	    	$id_postulante = _post('idpostulante') != null ? (_post('idpostulante')) : null;
    	    }
    	    
    	    if($id_postulante == null){
    	        throw new Exception(ANP);
    	    }
    	    $datosIngreso = $this->m_matricula->datosIngresoPostulante($id_postulante);
    	    if($tipo == 1){
    	    	$countDeudas = $this->m_matricula->getDeudasByEstudiantes($datosIngreso['cod_alumno_temp']);
    	    }
    	    $fechas = $this->m_matricula->getFechasReferenciaByTipo('R');
    	    if(count($fechas) == 0){
    	        throw new Exception('No se ha configurado la fecha de ratificaci&oacute;n');
    	    }
    	    
    	    if(count($countDeudas) == 0){
		        $sede         = $datosIngreso['id_sede_ingreso'];
		        $nivel        = $datosIngreso['id_nivel_ingreso'];
		        $grado        = $datosIngreso['id_grado_ingreso'];
		        $year         = $datosIngreso['year_ingreso'];
		        $fechaIniRat = explode('-', $fechas['fec_inicio']);
		        $fechaAct = explode('-', date("Y-m-d"));
		        $okRat = 0;
		        if($fechaAct[1] == $fechaIniRat[1]){
		            if($fechaAct[2] < $fechaIniRat[2]){
		                $okRat = 1;
		            }
		        } else if ($fechaAct[1] < $fechaIniRat[1]) {
		            $okRat = 1;
		        }
		        if($okRat == 0){//para el proximo aÒo
		            $gradonivel = $this->m_matricula->getGradoNivelRatificacion($grado+1);
		            $year  = _getYear() + 1;
		            $nivel = $gradonivel['nid_nivel'];
		            $grado = $gradonivel['nid_grado'];
		        }
		        $calendar     = $this->m_matricula->getCuotasGeneradas($sede,$nivel,$grado,$year,$id_postulante,$tipo);
		    	$config       = $this->m_alumno->getConfig($year, $sede);
	        	if(count($config) != 0 && $config['estado'] == ESTADO_ACTIVO){
	        		$flg_cuota_ingreso = $this->m_matricula->evaluateCuotaIngresoByPersona($id_postulante);
	        	} else {
	        		$flg_cuota_ingreso = null;
	        	}
		        $tab = $this->getTableEstudiantesCronograma($calendar['result'],$calendar['descuento'],$calendar['codigo'],$year, $flg_cuota_ingreso,$id_postulante);
		        $data['table']       = $tab['table'];
		        $data['codigo']      = $tab['codigo'];
		        
		        $data['error']       = EXIT_SUCCESS;
    	    } else {
    	    	$data['table']    = _createTableDeudas(1, $countDeudas);
	        	$data['codigo']   = 1;
    	    	$data['error']    = EXIT_SUCCESS;
    	    }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getTableEstudiantesCronograma($calendar,$descuento,$codigo,$year, $flg_cuota_ingreso,$id_postulante) {
	    /* CREAR LISTA DE ESTUDIANTES PARA CADA AULA*/
	    $tmpl  = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                                              data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                                              data-search="false" id="tb_compromisoCalendarAlu-'.$codigo.'">',
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_2      = array('data' => 'Descripci&oacute;n', 'class' => 'text-center');
	    $head_3      = array('data' => 'F. de vencimiento' , 'class' => 'text-center');
	    $head_4      = array('data' => 'F. de descuento'   , 'class' => 'text-center');
	    $head_5      = array('data' => 'Monto S/.'       , 'class' => 'text-center');
	    $head_6      = array('data' => 'Beca'			   , 'class' => 'text-center');
	    $head_6_7    = array('data' => 'F. de Pago'	   , 'class' => 'text-center');
	    $head_7      = array('data' => 'Estado'			   , 'class' => 'text-center');
	     
	    $this->table->set_heading($head_2, $head_3,$head_4,$head_5,$head_6, $head_6_7, $head_7);
	    $val2=0;
	    foreach ($calendar as $row2){
            $val2++;
	    	if($flg_cuota_ingreso != null && $flg_cuota_ingreso != 0){
	            if($row2->flg_tipo == 1){
	                if($row2->_id_tipo_cronograma == 2){
	                	$detalleCuota = $this->m_matricula->getDetalleCuotaIngreso($id_postulante);
	                    $detalle = _encodeCI(null);
	                    $row_cell_2           = array('data'   =>  'Cuota Ingreso', 'class' => 'text-center');
	                    $row_cell_3           = array('data'   => '-', 'class' => 'text-center');
	                    $row_cell_4           = array('data'   => '-', 'class' => 'text-center');
	                    $row_cell_5           = array('data'   => $detalleCuota['monto'], 'class' => 'text-center');
	                    $row_cell_6           = array('data'   => '-', 'class' => 'text-center');
	                    $row_cell_6_7         = array('data'   => _fecha_tabla($detalleCuota['fecha_pago'], "d/m/Y"), 'class' => 'text-center');
	                    $row_cell_7           = array('data'   => $detalleCuota['estado'], 'class' => 'text-center');
	                    $this->table->add_row($row_cell_2, $row_cell_3,$row_cell_4,$row_cell_5,$row_cell_6, $row_cell_6_7, $row_cell_7);
	                    $val2++;
	                }
	            }
            }
            $detalle = _encodeCI($row2->id_detalle_cronograma);
            $row_cell_2           = array('data'   => (($row2->detalle)), 'class' => 'text-center');
            $row_cell_3           = array('data'   => _fecha_tabla(strtolower($row2->fecha_v), "d/m/Y"), 'class' => 'text-center');
            $row_cell_4           = array('data'   => ($row2->fecha_d != NULL) ? (_fecha_tabla(strtolower($row2->fecha_d), "d/m/Y")) : '-', 'class' => 'text-center');
             
            $row_cell_5           = array('data'   => (strtolower($row2->monto)), 'class' => 'text-center');
            $row_cell_6           = array('data'   => ($row2->descuento == 'BECA') ? (strtolower(round($descuento).' %')) : '-','class' => 'text-center');
            $row_cell_6_7         = array('data'   => _fecha_tabla($row2->fecha_pago, "d/m/Y"), 'class' => 'text-center');
            $row_cell_7           = array('data'   => ($row2->estado),'class' => 'text-center');
            $this->table->add_row($row_cell_2, $row_cell_3,$row_cell_4,$row_cell_5,$row_cell_6, $row_cell_6_7,$row_cell_7);
	    }
	    return array("table" => $this->table->generate(),'codigo' =>$codigo);
	}	
	
	function enviarFeedBack(){
	    $nombre  = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url     = _post('url');
	    $html = '<p>'.$url.'</p>';
	    $html .= '<p>'.$mensaje.'</p>';
	    $html .= '<p>'.$nombre.'</p>';
	    $arrayInsertCorreo = array('correos_destino'         => CORREO_BASE,
	        'asunto'                  => utf8_encode("°Sugerencias a Smiledu!"),
	        'body'                    => $html,
	        'estado_correo'           => CORREO_PENDIENTE,
	        'sistema'                 => 'SMILEDU');
	    $dataCorreo = $this->m_utils->insertarEnviarCorreo($arrayInsertCorreo);
	}
	
	function cambiarEstadoEstudiante(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try {
	        $idAlumno = _simpleDecryptInt(_post('idalumno'));
	        if($idAlumno == null){
	            throw new Exception("Seleccione un estudiante");
	        }
	        $estado = ($this->m_utils->getById("persona", "flg_acti", "nid_persona", $idAlumno) == FLG_ACTIVO) ? 0 : 1; 
	        $arrayUpdate = array("flg_acti" => $estado);
	        $data     = $this->m_alumno->updateCampoDetalleAlumno($arrayUpdate, $idAlumno, 2);
	        if(_post('retiro') == 1){
	            $arrayUpdate = array("estado" => ESTADO_RETIRADO);
	            $data     = $this->m_alumno->updateCampoDetalleAlumno($arrayUpdate, $idAlumno, 1);
	            
	            $arrayUpdate = array("flg_acti" => ESTADO_RETIRADO_PERSONA_AULA);
	            $data     = $this->m_alumno->updatePersona_x_aul($arrayUpdate, $idAlumno, date('Y'));
	        }
	        if($data['error'] == EXIT_SUCCESS){
                $fechasAll = $this->m_matricula->getFechasReferencia(array('T','R'));
                $fechas    = null;
                $fechasRat = null;
                if($fechasAll != null){
                    foreach ($fechasAll as $fec){
                        switch ($fec->tipo) {
                            case CONFIG_RATIFICACION:        $fechasRat = (array)$fec;        break;
                            case CONFIG_TRASLADOS:           $fechas    = (array)$fec;        break;
                        }
                    }
                }
                $data['alumno'] = _createCardAlumnos($this->m_alumno->getArrayAlumnoById($idAlumno), null, null, $fechas, $fechasRat);
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function evaluarTipoTraslado(){
	    $tTraslado = _simpleDecryptInt(_post("traslado"));
	    $idPersona = _simpleDecryptInt(_post("idalumno"));
	    $tipo = null;
	    if($tTraslado == TIPO_TRASLADO_INTRASEDE){
	        $tipo = 0;
	        //$origenAlumno = $this->m_alumno->getUbicacionAlumno($idPersona);
	        //$data['aulas'] = $this->createComboAulasTrasladoIntrasede($origenAlumno['nid_sede'], $origenAlumno['nid_nivel'], $origenAlumno['nid_grado'], $origenAlumno['nid_aula']);
	    }else if($tTraslado == TIPO_TRASLADO_INTERSEDES){
	        $tipo = 1;
	        $data['sedes'] = $this->createComboSedesNoSedePersona($idPersona);
	    }
	     
	    $data['tipo'] = $tipo;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function enviarSolicitud(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idSedeDestino     = _simpleDecryptInt(_post('sedeDestino'));
	        $tipoTraslado      = _simpleDecryptInt(_post('tipoTraslado'));
	        $idAlumno          = _simpleDecryptInt(_post('idAlumno'));
	        $motivoTraslado    = _post('motivoTraslado');
	        $nombreLogeoUsario = $this->session->userdata('nombre_completo');
	
	        if($idSedeDestino == null && $tipoTraslado == TIPO_TRASLADO_INTERSEDES){
	            throw new Exception('Seleccione la sede');
	        }
	        if($motivoTraslado == null){
	            throw new Exception('Ingresar el Motivo de Traslado');
	        }
	        
	        if(strlen($motivoTraslado) > 200){
	            throw new Exception('El motivo del traslado debe tener como m·ximo 200 caracteres');
	        }
	
	        if($tipoTraslado == null ||$idAlumno == null || $tipoTraslado == null  || $nombreLogeoUsario == null){
	            throw new Exception(ANP);
	        }
	        $countSolicitudesAlumno = $this->m_traslado->getCountSolicitudesAlumno($idAlumno);
	        if($countSolicitudesAlumno > 0){
	            throw new Exception('El alumno ya tiene una solicitud pendiente');
	        }
	         
	        $origenAlumno = $this->m_alumno->getUbicacionAlumno($idAlumno);
	        if($origenAlumno == null){
	            throw new Exception('No se puede trasladar al alumno');
	        }
	
	        if($tipoTraslado == TIPO_TRASLADO_INTRASEDE){
	            $idSedeDestino = $origenAlumno['nid_sede'];
	        }
	         
	        $dataInsert = array(
	            'id_alumno'                => $idAlumno,
	            'id_aula_origen'           => $origenAlumno['nid_aula'],
	            'tipo_traslado'            => strtoupper($this->m_utils->getDescComboTipoByGrupoValor(COMBO_TIPO_TRASLADO, $tipoTraslado)),
	            'id_usuario_traslado'      => $this->_idUserSess,
	            'nombres_usuario_traslado' => $nombreLogeoUsario,
	            'motivo_traslado'          => $motivoTraslado,
	            'estado'                   => SOLICITUD_SOLICITADA,
	            'id_sede_origen'           => $origenAlumno['nid_sede'],
	            'id_sede_destino'          => $idSedeDestino
	        );
	
	        $data = $this->m_traslado->insertSolicitudTraslado($dataInsert);
	
	        if($data['error'] == EXIT_SUCCESS){
	            //$nombreAlumno = $this->m_utils->getNombreCompletoPersona($idAlumno);
	            //$body ='<p>El alumno: '.$nombreAlumno.' solicit√≥ un traslado de tipo '.$descTipoTraslado.'</p>
	            //<p>Origen: '.$descOrigen.' - Destino: '.$descDestino.'</p>
	            // <p>Persona Solicit√≥: '.$nombreLogeoUsario.'<p/>
	            // <p>Fecha: '.date('Y-m-d H:i:s').'</p>';
	            //$this->lib_utils->enviarEmail('dfloresgonz@gmail.com', 'PRUEBA', $body);
	            
	            /*$arrayInsertCorreo = array('correos_destino'         => $correoInst,
                        	               'asunto'                  => utf8_encode("Se ha modificado tu usuario en Smiledu"),
                        	               'body'                    => $html,
                        	               'estado_correo'           => CORREO_PENDIENTE,
                        	               'sistema'                 => 'SMILEDU');
	            $dataCorreo = $this->m_utils->insertarEnviarCorreo($arrayInsertCorreo);*/
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	
	    echo json_encode(array_map('utf8_encode', $data));
	}
		
	function abrirModalConfirmarEliminarAula(){
	    $data['error']    = EXIT_ERROR;
	    try{
	        $idAula = _simpleDecryptInt(_post('idaula'));
	        $data['desc_aula']  = $this->m_utils->getById("aula", "desc_aula", "nid_aula", $idAula);
	        $year               = $this->m_utils->getById("aula", "year", "nid_aula", $idAula);
	        
	        if($year <  date("Y") ){
	            $data['eliminarPermiso'] = 0;
	            throw new Exception('No puede eliminar aulas de a&ntilde;os anteriores');
	        }
	        $cantAlumn          = $this->m_aula->getCapaActualAula($idAula);
	        if($cantAlumn != 0){
	            $data['eliminarPermiso'] = 0;
	            throw new Exception('No puede eliminar un aula con estudiantes matriculados');
	        }
	        $data['eliminarPermiso'] = 1;
	        $data['error']  = EXIT_SUCCESS;
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function eliminarAula(){
	    $data['error']    = EXIT_ERROR;
		$data['msj']      = MSJ_ERROR;
	    try {
	        $idAula      = _simpleDecryptInt(_post('idaula'));
	        if($idAula == null){
	            throw new Exception();
	        }
	        $data        = $this->m_aula->deleteAula($idAula);
	        if($data['error'] == EXIT_SUCCESS){
	            $txtBusqueda   = (strlen(_post('textobusqueda')) == 0) ? NULL : _post('textobusqueda');
	            $tipoCiclo     = (strlen(_post('ciclo')) == 0) ? NULL : _post('ciclo');
	            $idSedeRol     = _getSesion('id_sede_trabajo');
	            $data['error'] = NULL;
	            
	            if($this->_idRol != ID_ROL_ADMINISTRADOR){
	                if (strlen($txtBusqueda) > 0){//Se escribio en la busqueda
	                    $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede($txtBusqueda,$tipoCiclo,$idSedeRol);
	                    $data['tablaAulas'] = _createTableAulas($aulas);
	                } else if (strlen(_post('year')) > 0){//Se eligio filtro
	                    $year         = _post('year');
	                    $idSede       = _simpleDecryptInt(_post('idsede'));
	                    
                        if($idSede != _getSesion('id_sede_trabajo')){
                            throw new Exception(ANP);
                        }
	                    
	                    $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	                    $gradoNivel   = explode('_', $idGradoNivel);
	                     
	                    $aulas        = $this->m_aula->getAllAulasByGradoYear($year, $idSede, $gradoNivel[1], $gradoNivel[0], $tipoCiclo);
	                    $data['tablaAulas'] = _createTableAulas($aulas);
	                } else {
	                    $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede(null, null, $idSedeRol,5);
	                    $data['tablaAulas'] = _createTableAulas($aulas);
	                 }
	            } else {
	                if (strlen($txtBusqueda) > 0){
	                    $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede($txtBusqueda,$tipoCiclo,NULL);
	                    $data['tablaAulas'] = _createTableAulas($aulas);
	                } else if (strlen(_post('year')) > 0){
	                    $year         = _post('year');
	                    $idSede       = _simpleDecryptInt(_post('idsede'));
	                    $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	                    $gradoNivel   = explode('_', $idGradoNivel);
	                     
	                    $aulas        = $this->m_aula->getAllAulasByGradoYear($year, $idSede, $gradoNivel[1], $gradoNivel[0], $tipoCiclo);
	                    $data['tablaAulas'] = _createTableAulas($aulas);
	                } else {
        	            $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede(null, null, null, 5);
        	            $data['tablaAulas'] = _createTableAulas($aulas);
        	        }
	            }
	        }
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function confirmarDeclaracion(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try {
	        $idAlumno = _post('idalumno') != null ? _simpleDecryptInt(_post('idalumno')) : null;
	        if($idAlumno == null){
	            throw new Exception("Seleccione un estudiante");
	        }
	        $idSede = _post('idsede') != null ? _simpleDecryptInt(_post('idsede')) : null;
	        if($idSede == null){
	            throw new Exception("Seleccione una sede");
	        }
	        $datosIngreso = $this->m_matricula->datosIngresoPostulante($idAlumno);
            $sede         = $datosIngreso['id_sede_ingreso'];
            $nivel        = $datosIngreso['id_nivel_ingreso'];
            $grado        = $datosIngreso['id_grado_ingreso'];
            $year         = _getYear();
	        $fechas = $this->m_matricula->getFechasReferenciaByTipo('R');
	        if(count($fechas) == 0){
	            throw new Exception('A&uacute;n no se ha configurado la fecha de ratificaci&oacute;n');
	        }
	        $fechaIniRat = explode('-', $fechas['fec_inicio']);
	        $fechaAct = explode('-', date("Y-m-d"));
	        $okRat = 0;
            $gradonivel = $this->m_matricula->getGradoNivelRatificacion($idAlumno);
	        if($fechaAct[1] == $fechaIniRat[1]){
	            if($fechaAct[2] < $fechaIniRat[2]){
	                $okRat = 1;
	            }
	        } else if ($fechaAct[1] < $fechaIniRat[1]) {
	            $okRat = 1;
	        }
	        
	        if($okRat == 0){//para el proximo aÒo
	            $year  = _getYear() + 1;
	        }
            $confirmoDatos = $this->m_matricula->countConfirmacionDatos($year,$idAlumno, 'R',1);
	        if($confirmoDatos['existe'] == 0){
	            $arrayInsert = array("year_confirmacion"  => $year,
                	                 "id_estudiante"      => $idAlumno,
                	                 "tipo"               => 'R',
	                                 "flg_recibido"       => '1'  );
	            
	            $data    = $this->m_matricula->insertConfirmacion($arrayInsert);
	        } else {
                $arrayUpdate = array("flg_recibido" => '1',
                                     "fecha_registro" => date('Y-m-d H:i:s'));
                $data    = $this->m_alumno->updateConfirmaDeclaracion($arrayUpdate, $idAlumno, $year);
	        }

	        if($data['error'] == EXIT_SUCCESS){
	            $arrayUpdateAlumno = array(
	                "id_grado_ratificacion" => $gradonivel['nid_grado'],
	                "id_nivel_ratificacion" => $gradonivel['nid_nivel'],
	                "id_sede_ratificacion"  => $idSede,
	                "year_ratificacion"     => $year);
	            $data     = $this->m_alumno->updateDatosRatificacionAlumno($arrayUpdateAlumno, $idAlumno);
	            if($data['error'] == EXIT_SUCCESS){
	                $fechasAll = $this->m_matricula->getFechasReferencia(array('T','R'));
	                $fechas    = null;
	                $fechasRat = null;
	                if($fechasAll != null){
	                    foreach ($fechasAll as $fec){
	                        switch ($fec->tipo) {
	                            case CONFIG_RATIFICACION:        $fechasRat = (array)$fec;        break;
	                            case CONFIG_TRASLADOS:           $fechas    = (array)$fec;        break;
	                        }
	                    }
	                }
	                $data['alumno'] = _createCardAlumnos($this->m_alumno->getArrayAlumnoById($idAlumno), null, null, $fechas, $fechasRat);
	            }
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function abrirModalConfirmDeclaracionJurada(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try {
            $idalumno = _post('idalumno') != null ? _simpleDecryptInt(_post('idalumno')) : null;
            if($idalumno == null){
                throw new Exception(ANP);
            }
            $idSedeActual = $this->m_utils->getById("sima.detalle_alumno", "id_sede_ingreso", "nid_persona", $idalumno);
            $gradonivel = $this->m_matricula->getGradoNivelRatificacion($idalumno);
            $data['gradoNivel'] = $gradonivel['desc_grado'].' - '.$gradonivel['desc_nivel'];
            $data['comboSedes'] = _buildComboSedesRatificacion($gradonivel['nid_nivel'], $gradonivel['nid_grado']);
            $data['sedeActual'] = $idSedeActual != null ? _simple_encrypt($idSedeActual) : null;
            $data['error']      = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
	}
}