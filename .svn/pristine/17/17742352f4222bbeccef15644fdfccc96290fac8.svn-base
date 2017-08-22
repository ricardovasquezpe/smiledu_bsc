<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_detalle_aula extends CI_Controller{

    private $_idUserSess = null;
    private $_idRol      = null;
    
	function __construct(){
		parent::__construct();
		$this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
		$this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
		$this->load->library('table');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
		$this->load->model('mf_alumno/m_alumno');
		$this->load->model('mf_aula/m_aula');
		$this->load->model('mf_matricula/m_matricula');
		_validate_uso_controladorModulos(ID_SISTEMA_MATRICULA, ID_PERMISO_AULA, MATRICULA_ROL_SESS);
		$this->_idUserSess = _getSesion('nid_persona');
		$this->_idRol      = _getSesion(MATRICULA_ROL_SESS);
	}
	
	public function index(){
	    $data['return']            = _getSesion('previousPage');
	    $data['barraSec']          = '  <div class="mdl-layout__tab-bar mdl-js-ripple-effect" >
                                           <a href="#tab-1" class="mdl-layout__tab is-active" >Informaci&oacute;n</a>
                                           <a href="#tab-2" class="mdl-layout__tab" onclick="getEstudiantesAula()">Estudiantes</a>
                                           <a href="#tab-3" class="mdl-layout__tab" >Docentes</a>
                                        </div>';
	    $data['visibleSede']   = 0;
	    if( $this->_idRol != ID_ROL_ADMINISTRADOR ){
	        $data['visibleSede']   = 1;
		    $idSedeRol             = _getSesion('id_sede_trabajo');
	        $desc_sede             = $this->m_utils->getById('sede', 'desc_sede', 'nid_sede', $idSedeRol);
	        $data['comboSedes']    = '<option value="'._simple_encrypt(_getSesion('id_sede_trabajo')).'">'.strtoupper($desc_sede).'</option>';
	        $data['sedeActual']    = _simple_encrypt($idSedeRol);
	        $data['comboAulas']    = __buildComboGetAulasNoActiBysede($idSedeRol);
	    }else{
		    $data['comboSedes']    = __buildComboSedes(1);
	        $data['comboAulas']    = __buildComboGetAulasNoActiBysede(NULL);
	    }
	    $data['comboYearCronograma'] = __buildComboYearByCompromisos();
		
		$data['docentes']            = __buildComboByRol(ID_ROL_DOCENTE,1);
		
		$data['comboTipoCiclo']      = __buildComboByGrupo(COMBO_TIPO_CICLO);
		
		$data['tablaAlumnos']      = null;
		$data['tablaDocentes']     = null;
		$data['countAlum'] = 0;
		$data['countDoc'] = 0;
		$dataUser = array("visita" => 1);
		$this->session->set_userdata($dataUser);
	    $disabled = null;
	    if(_getSesion('accionDetalleAula') == 0){
	        $data['titleHeader'] = "Ver: ".($this->m_utils->getById("aula", "desc_aula", "nid_aula", _getSesion('idAulaEdit')));
	    } else if(_getSesion('accionDetalleAula') == 1){
	        $data['titleHeader'] = "Editar: ".($this->m_utils->getById("aula", "desc_aula", "nid_aula", _getSesion('idAulaEdit')));
	    } else if(_getSesion('accionDetalleAula') == 2){
	        $data['titleHeader']       = 'Nueva Aula';
	    }
	    $idtutor = null;
	    if(_getSesion('idAulaEdit') != null){
	        $idtutor   = $this->m_utils->getById("aula", "id_tutor", "nid_aula", _getSesion('idAulaEdit'));
	    }
	    $data['comboTutores'] = __buildComboTutores($idtutor);

	    if(_getSesion('accionDetalleAula') == 0 || _getSesion('accionDetalleAula') == 1){
	        $idAula = _getSesion('idAulaEdit');
    	    $detalleAula = $this->m_aula->getDetalleAulas($idAula);
    	    $data['comboAulas']   = __buildComboGetAulasNoActiBysede($detalleAula['nid_sede']);
    	    $data['descAula']     = (strlen($detalleAula['desc_aula']) != 0) ? ($detalleAula['desc_aula']) : null;
    	    $data['nidSede']      = (strlen($detalleAula['nid_sede']) != 0) ?_simple_encrypt($detalleAula['nid_sede']) : null;
    	    $data['nidGrado']     = (strlen($detalleAula['nid_grado']) != 0) ?_simple_encrypt($detalleAula['nid_grado']) : null;
    	    $data['nidNivel']     = (strlen($detalleAula['nid_nivel']) != 0) ?_simple_encrypt($detalleAula['nid_nivel']) : null;
    	    $data['capaMax']      = $detalleAula['capa_max'];
    	    $data['idTutor']      = (strlen($detalleAula['id_tutor']) != 0) ?_simple_encrypt($detalleAula['id_tutor']) : null;
    	    $data['idTipoNota']   = (strlen($detalleAula['id_tipo_nota']) != 0) ?_simple_encrypt($detalleAula['id_tipo_nota']) : null;
    	    $data['nombreLetra']  = $detalleAula['nombre_letra'];
    	    $data['year']         = $detalleAula['year'];
    	    $data['observacion']  = ($detalleAula['observacion']);
    	    $data['tipoCiclo']    = (strlen($detalleAula['tipo_ciclo']) != 0) ?_simple_encrypt($detalleAula['tipo_ciclo']) : null;

    	    //$alumnos    = $this->m_matricula->getAlumnosByAula($idAula);
    	    $fechas = $this->m_matricula->getFechasReferenciaByTipo('T');
    	    //$data['tablaAlumnos']  = _createCardDetalleAlumnos($alumnos,$fechas);
    	    $data['tablaAlumnos'] = null;
    	    $docentes   = $this->m_aula->getProfesoresCursosByAula($idAula);
    	    $data['tablaDocentes'] = _createTablaProfesoresAulaCurso($docentes);
    	    //$data['countAlum'] = count($alumnos);
    	    $data['countAlum'] = null;
    	    $data['countDoc']  = count($docentes);

    	    if(_getSesion('accionDetalleAula') == 0){
    	        $disabled = 'disabled';
    	    }

    	    if($detalleAula['nid_grado'] != null && $detalleAula['year'] != null){
    	        $cursos = $this->m_aula->getCursos($detalleAula['year'], $detalleAula['nid_grado']);
    	        $data['comboCursos']       = $this->buildComboCursos($cursos);
    	    }
	    }

	    //ENVIAMOS LA DATA A LA VISTA
	    $data['ruta_logo']        = MENU_LOGO_MATRICULA;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_MATRICULA;
	    $data['nombre_logo']      = NAME_MODULO_MATRICULA;
	     

		$rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_MATRICULA, $this->_idUserSess);
		$data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $data['disabled'] = $disabled;
	    
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_MATRICULA, MATRICULA_FOLDER);
	    $menu = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    $this->load->view('v_detalle_aula',$data);
	}
	
	function getCapaActual(){
	    //$data['error']    = EXIT_ERROR;
	    //if( $this->_idRol != ID_ROL_ADMINISTRADOR ){
	    //    $data['sedeRol']   = $this->m_utils->getById("aula", "tipo_ciclo", "nid_aula", $idAu);
	    //    $data['rol']       = 1;
	    //} else{
		//    $data['comboSedes']    = __buildComboSedes(1);
	    //}
	    
	    
	    if(_getSesion('accionDetalleAula') == 2){
	        $data['error'] = EXIT_ERROR;
	    } else {
	        $idAula = _getSesion('idAulaEdit');
	        $capaActual = $this->m_aula->getCapaActualAula($idAula);
	        $data['error']    = EXIT_SUCCESS;
	        $data['capa_actual'] = $capaActual;
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getNivelesBySede(){
	    $idSede = _simpleDecryptInt(_post('idsede'));
	    $niveles = __buildComboNivelesBySede($idSede,1);
	    $data['comboNiveles'] = $niveles;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function updateCampoCambio(){
	    $data['error']    = 2;
	    $data['msj']      = MSJ_ERROR;
	    $campo  = _post("abc");
	    $accion = _getSesion('accionDetalleAula');
        try{
            $data['error']    = EXIT_ERROR;
            $data['msj']      = MSJ_ERROR;
            $valor      = _post("valor");
            $idAula     = _getSesion('idAulaEdit');
            $descaula1  = _post('descaula1');
            $descaula2  = _post('descaula2');
            $ugel       = _post('ugel');
            
            if ($campo == 'nid_sede' && $valor == null){
                $data['error'] = 4;
                throw new Exception('Es un campo obligatorio');
            } else if ($campo == 'nid_nivel' && $valor == null){
                $data['error'] = 4;
                throw new Exception('Es un campo obligatorio');
            } else {
                if(in_array($campo, json_decode(CAMPOS_OBLIGATORIOS_AULA)) && $valor == null){
                    if($campo == 'nid_grado' || $campo == 'tipo_ciclo'){
                        $data['error'] = 4;
                    } else {
                        $data['error'] = 3;
                    }
                    throw new Exception('Es un campo obligatorio');
                }
            }
            
            if($campo == 'desc_aula' || $campo == 'desc_aula2'){
                if($valor == null){
                    if($ugel == null){
                        throw new Exception('El nombre del aula no puede estar vacío si no tiene secciónn UGEL');
                    }
                }
            }
            
            if($campo == 'observacion' && strlen($valor) > 100){
                throw new Exception('Ingrese una observación de menos de 100 caracteres');
            }
            
            if($campo == 'nombre_letra'){
                $detalleAula = $this->m_aula->getDetalleAulas($idAula);
                $sede  = $detalleAula['nid_sede'];
                $nivel = $detalleAula['nid_nivel'];
                $grado = $detalleAula['nid_grado'];
                if($valor == null){
                    if($descaula1 == null && $descaula2 == null){
                        throw new Exception('El nombre de sección UGEL no puede estar vacío');
                    }
                }
                $countUgelGrado = $this->m_aula->countUgelByGrado($sede, $nivel, $grado, $valor);
                if($countUgelGrado != 0){
                    throw new Exception('No puede asignar la misma secci&oacute;n UGEL en el mismo grado');
                }
            }
            if($campo == 'desc_aula' && strlen($valor) > 150){
                throw new Exception('Solo puede contener máximo 150 caracteres');
            }else if($campo == 'nombre_letra' && strlen($valor) > 1){
                throw new Exception('Solo puede contener máximo 1 caracteres');
            }
            
            if( $campo == 'nid_sede' || $campo == 'nid_nivel' || $campo == 'nid_grado' || $campo == 'id_tutor' || $campo == 'tipo_ciclo'){
                $valor = _simpleDecryptInt($valor);
            }
            $valor = ($campo == 'capa_max' && $valor == '' ) ? NULL : $valor;
            $valor = ($campo == 'year' && $valor == '' ) ? NULL : $valor;
            $valor = ($campo == 'desc_aula' && $valor == '' ) ? NULL : $valor;
            $valor = ($campo == 'desc_aula2' && $valor == '' ) ? NULL : $valor;
            
            if( $campo == 'capa_max' && is_numeric($valor) == false){
                throw new Exception('Solo ingresar numeros en la Capacidad Maxima');
            }
            if( $campo == 'year' && is_numeric($valor) == false ){
                throw new Exception('Solo ingresar numeros en el a&ntilde;o');
            }
            if( $campo == 'year'){
                if($valor< _getYear() || $valor > _getYear() + 5){
                    throw new Exception('No puede ingresar un a&ntilde;o menor al actual o mayor a 5 a&ntilde;os');
                }
            }
            if( $campo == 'nombre_letra' && $this->soloLetras($valor) != true){
                throw new Exception('Solo debe ingresar letras en UGEL');
            }

            if( $campo == 'desc_aula' && $valor != NULL){
                $detalleAula = $this->m_aula->getDetalleAulas($idAula);
                $sede  = $detalleAula['nid_sede'];
                $nivel = $detalleAula['nid_nivel'];
                $grado = $detalleAula['nid_grado'];
                $countAulas = $this->m_aula->getCountAulasByDescripcion($sede, $nivel, $grado, $valor);
                if($countAulas > 0){
                    throw new Exception('El aula que desea ingresar ya existe');
                }
            }
            
            if( $campo == 'nid_grado' && $valor != NULL){
                $detalleAula = $this->m_aula->getDetalleAulas($idAula);
                $sede      = $detalleAula['nid_sede'];
                $nivel     = $detalleAula['nid_nivel'];
                $desc_aula = $detalleAula['desc_aula'];
                if($desc_aula != null){
	                $countAulas = $this->m_aula->getCountAulasByDescripcion($sede, $nivel, $valor, $desc_aula);
	                if($countAulas > 0){
	                    throw new Exception('El aula que desea ingresar ya existe');
	                }
                }
            }
            
            if( $campo == 'year' && $valor != NULL){
                $fechas = $this->m_matricula->getFechasReferenciaByTipo('M');
                if($fechas == null){
                    throw new Exception('A&uacute;n no se ha configurado la fecha l&iacute;mite de matr&iacute;cula');
                }
                if($valor == date('Y')){
                    if($fechas != null){
                        $fechaIni = explode('-', $fechas['fec_inicio']);
                        $fechaAct = explode('-', date("Y-m-d"));
                        $ok1 = 0;
                        if($fechaIni[1] == $fechaAct[1]){
                            if($fechaIni[2] <= $fechaAct[2]){
                                $ok1 = 1;
                            }
                        } else if ($fechaIni[1] < $fechaAct[1]) {
                            $ok1 = 1;
                        }
                         
                        if($ok1 == 1){
                            throw new Exception('La fecha configurada ya pas&oacute;');
                        }
                    }
                }
            }
            if($accion == 1){ // EDITAR AULA
                $capaActual = $this->m_aula->getCapaActualAula($idAula);
                if($capaActual != 0 && $campo == 'tipo_ciclo'){
                    throw new Exception('No puede modificar el tipo de ciclo porque hay estudiantes matriculados');
                }
                if($campo == 'capa_max'){
                    if($valor < $capaActual ){
                        throw new Exception('La capacidad m&aacute;xima no puede ser menor a la actual');
                    }
                }
                if($campo == 'desc_aula2'){
                    $campo = 'desc_aula';
                    if($valor != NULL){
                        $valor = _simple_decrypt($valor);
                    }
                }
                
                if($campo == 'id_tutor' && $valor != null){
                    $idTutorRolPer = $this->m_utils->getById("aula", "id_tutor", "nid_aula", $idAula);;
                    $data = $this->m_aula->asignarTutor($valor, $idTutorRolPer);
                }
                $arrayUpdate = array($campo => $valor);
                $data = $this->m_aula->updateCampoDetalleAula($arrayUpdate, $idAula);
                //CAMBIA EL TIPO NOTA DEPENDIENDO SI ES INICIAL, PRIMARIA O SECUNDARIA
                if($campo == 'nid_sede'){
                    $arrayUpdate = array('nid_grado'    => null,
                                         'nid_nivel'    => null );
                    $data = $this->m_aula->updateCampoDetalleAula($arrayUpdate, $idAula);
                }
                if($campo == 'nid_nivel' && $valor == 1){
                    $arrayUpdate = array('nid_grado'    => null,
                                         'id_tipo_nota' => TIPO_NOTA_ALFABETICO);
                    $data = $this->m_aula->updateCampoDetalleAula($arrayUpdate, $idAula);
                } else if ($campo == 'nid_nivel' && $valor != 1 && $valor != NULL){
                    $arrayUpdate = array('nid_grado'    => null,
                                          'id_tipo_nota' => TIPO_NOTA_NUMERICO);
                    $data = $this->m_aula->updateCampoDetalleAula($arrayUpdate, $idAula);
                }
                
                $detAula    = $this->m_aula->getDetalleAulas($idAula);
                $sede       = $detAula['nid_sede'];
                $nivel      = $detAula['nid_nivel'];
                $grado      = $detAula['nid_grado'];
                $capamax    = $detAula['capa_max'];
                $year       = $detAula['year'];
                $ugel       = $detAula['nombre_letra'];
                $tipo_ciclo = $detAula['tipo_ciclo'];
                
                if( $sede != null && $nivel != null && $grado != null && $capamax != null && $year != null && $ugel != null && $tipo_ciclo != null){
                    $arrayUpdate = array('flg_acti' => FLG_ACTIVO);
                    $data = $this->m_aula->updateCampoDetalleAula($arrayUpdate, $idAula);
                } else if ( $sede == null || $nivel == null || $grado == null || $capamax == null || $year == null || $ugel == null || $tipo_ciclo == null){
                    if($this->m_aula->getCapaActualAula($idAula) == 0){
                        $arrayUpdate = array('flg_acti' => FLG_INACTIVO);
                        $data = $this->m_aula->updateCampoDetalleAula($arrayUpdate, $idAula);
                    }
                }
            
            }


            if($accion == 2){ // REGISTRAR
                if($campo == 'desc_aula2'){
                    $campo = 'desc_aula';
                    $valor = _simple_decrypt($valor);
                }
                if ($valor == ''){
                    $valor = NULL;
                }
                $arrayInsert = null;
                if( $this->_idRol != ID_ROL_ADMINISTRADOR ){
                    $idSede = _getSesion('id_sede_trabajo');
                    $arrayInsert = array($campo     => $valor,
                        "nid_sede" => $idSede
                    );
                } else {
                    $arrayInsert = array($campo     => $valor);
                }
                $data = $this->m_aula->insertAula($arrayInsert);
                $idAula = $data['idAula'];
                $dataUser = array("idAulaEdit"        => $idAula,
                                  "accionDetalleAula" => 1,
                                  
                );
                $this->session->set_userdata($dataUser);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
	        
	    echo json_encode(array_map('utf8_encode', $data));	
	}
	
	function soloLetras($in){
	    $permitidos = '/^[A-Z ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½]{1,50}$/i';
	    if(preg_match($permitidos,$in)) return true;
	    else return false;
	}
	
	function buscarAlumno(){
        $nombre  = _post("nombre");
        $idAu    = _getSesion('idAulaEdit');
        $alumnos = $this->m_alumno->getAlumnosByNombreAula($nombre, $idAu, null);
    	$fechas  = $this->m_matricula->getFechasReferenciaByTipo('T');
    	$data['tablaAlumnos']  = _createCardDetalleAlumnos($alumnos,$fechas);
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function abrirModalMatricular(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        if(_getSesion('accionDetalleAula') == 2){
	            throw new Exception('Debe crear el aula');
	        }
	        $idAu       = _getSesion('idAulaEdit');
	        $flg_acti  = $this->m_utils->getById("aula", "flg_acti", "nid_aula", $idAu);
	        $fechas = $this->m_matricula->getFechasReferenciaByTipo('M');
	        if($fechas == null){
	            throw new Exception('A&uacute;n no se ha configurado la fecha l&iacute;mite de matr&iacute;cula');
	        }
	        if($this->m_utils->getById("aula", "year", "nid_aula", $idAu) == date('Y')){
	            $fechaIni = explode('-', $fechas['fec_inicio']);
	            $fechaAct = explode('-', date("Y-m-d"));
	            $ok1 = 0;
	            if($fechaIni[1] == $fechaAct[1]){
	            	if($fechaIni[2] <= $fechaAct[2]){
	            		$ok1 = 1;
	            	}
	            } else if ($fechaIni[1] < $fechaAct[1]) {
	            	$ok1 = 1;
	            }
	            
	            if($ok1 == 1){
	                throw new Exception('La fecha configurada ya pas&oacute;');
	            }
	        }
	        if($flg_acti != FLG_ACTIVO){
	            throw new Exception('No ha completado los datos obligatorios del aula');
	        }
	        $cantDisponible    = $this->m_aula->getVacantesDisponibles($idAu);
	        if($cantDisponible == 0){
	            throw new Exception('No puede matricular, ha alcanzado su capacidad m&aacute;xima');
	        }
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAlumnosbyName(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try {
		    $idAu = _getSesion('idAulaEdit');
		    $name = _post('nombre');
		    $alumnos = null;
		    $alumnos = array();
		    $tipoCiclo = $this->m_utils->getById("aula", "tipo_ciclo", "nid_aula", $idAu);
		    if($tipoCiclo == null){
		    	throw new Exception('Debe seleccionar un tipo(Verano o Regular) en la informaci&oacute;n del aula');
		    }
	
		    $detalleAula = $this->m_aula->getDetalleAulas($idAu);
		    if($detalleAula['year'] == null || $detalleAula['nid_sede'] == null || $detalleAula['nid_nivel'] == null || $detalleAula['nid_grado'] == null){
		    	throw new Exception('Debe completar la informaci&oacute;n obligatoria del aula(a&ntilde;o, sede, nivel , grado)');
		    }
	
		    if($tipoCiclo == TIPO_CICLO_REGULAR){
		        $alumnos = $this->m_matricula->getAlumnosToCicloRegular($name, $idAu, $detalleAula['year'], $detalleAula['nid_sede'], $detalleAula['nid_nivel'], $detalleAula['nid_grado']);
		    } else if ($tipoCiclo == TIPO_CICLO_VERANO){
		        $alumnos = $this->m_matricula->getAlumnosToCicloVerano($name, $idAu, $detalleAula['year'], $detalleAula['nid_sede'], $detalleAula['nid_nivel'], $detalleAula['nid_grado']);
		    }
		    $data['tablaAlumno'] = _createTableBusquedaAlumnos($alumnos);
	    	$data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
		
	function asignAlumnos(){
	    $arrayEstudiantes = _post("estudiantes");
	    $idAu             = _getSesion('idAulaEdit');
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        if(is_array($arrayEstudiantes) != 1){
	            throw new Exception(ANP);
	        }
	        $cantDisponible    = $this->m_aula->getVacantesDisponibles($idAu);
	        if($cantDisponible == 0){
	            throw new Exception('No puede matricular, ha alcanzado su capacidad m&aacute;xima');
	        }
	        if(count($arrayEstudiantes) > $cantDisponible){
	            throw new Exception('La cantidad de estudiantes excede la capacidad m&aacute;xima, puede ingresar '.$cantDisponible.' estudiantes');
	        }
	        $detalleAula   = $this->m_aula->getDetalleAulas($idAu);
	        $tipoCiclo     = $detalleAula['tipo_ciclo'];
	        $year          = $detalleAula['year'];
	        $idSede        = $detalleAula['nid_sede'];
	        $idNivel       = $detalleAula['nid_nivel'];
	        $idGrado       = $detalleAula['nid_grado'];
	        
	        foreach ($arrayEstudiantes as $estudiantes){
	            $idEstudiantes = _simpleDecryptInt($estudiantes);
	            $name = _post('nombre');
	            
	            if($idEstudiantes == null || $idAu == null){
	                throw new Exception(ANP);
	            }
	            
	            $countCicloRegular = $this->m_aula->getCountCicloRegular($idEstudiantes,$year);
	            
	            if($tipoCiclo == TIPO_CICLO_REGULAR && $countCicloRegular == 1){
	                throw new Exception("El estudiante est&aacute; asignado a un Aula del ciclo regular ");
	            }
	            
	            //ASIGNAR AL Aï¿½O DEL AULA
	            $data = array(
	                "__id_persona"   => $idEstudiantes,
	                "__id_aula"      => $idAu,
	                "flg_acti"       => FLG_ACTIVO,//agregado por dfloresgonz 14.08.2016
	                "year_academico" => $year
	            );
	            $data = $this->m_matricula->asignarAlumnoEnAula($data);
	            
	            if($data['error'] == EXIT_SUCCESS){
	                $alumnos1 = null; 
	                $alumnos1 = array();
	            
	                if($tipoCiclo == TIPO_CICLO_REGULAR){
	                    $data = array( "estado" => ALUMNO_MATRICULADO);
	                    $data = $this->m_matricula->updateDetalleAlumno($idEstudiantes,$data);
	            
	                } else if ($tipoCiclo == TIPO_CICLO_VERANO){
	                    $estadoAlumno = $this->m_utils->getById("sima.detalle_alumno", "estado", "nid_persona", $idEstudiantes);
	            
	                    if($estadoAlumno == ALUMNO_NOPROMOVIDO_NIVELACION){
	                        $data = array( "estado"            => ALUMNO_VERANO,
	                                       "id_sede_ingreso"   => $idSede,
	                                       "id_nivel_ingreso"  => $idNivel,
	                                       "id_grado_ingreso"   => $idGrado);
	                        $data = $this->m_matricula->updateDetalleAlumno($idEstudiantes,$data);
	                    } else {
	                        $data = array( "id_sede_ingreso"   => $idSede,
	                                       "id_nivel_ingreso"  => $idNivel,
	                                       "id_grado_ingreso"   => $idGrado);
	                        $data = $this->m_matricula->updateDetalleAlumno($idEstudiantes,$data);
	                    }
	                }

	                $data = array("nid_persona"      => $idEstudiantes,
	                			"tipo_incidencia" => INCIDENCIA_MATRICULA,
	                			"id_aula"         => $idAu);
	                $data = $this->m_matricula->insertIncidenciaMatricula($data);
	                
	            }
	            if($data['error'] == EXIT_SUCCESS){
	            	$alumnos1 = null;
	            	$alumnos1 = array();
	            	if($tipoCiclo == TIPO_CICLO_REGULAR){
	            		$alumnos1 = $this->m_matricula->getAlumnosToCicloRegular($name, $idAu, $year, $idSede, $idNivel, $idGrado);
	            	} else if ($tipoCiclo == TIPO_CICLO_VERANO){
	            		$alumnos1 = $this->m_matricula->getAlumnosToCicloVerano($name, $idAu, $year, $idSede, $idNivel, $idGrado);
	            	}
	            	$table1 = _createTableBusquedaAlumnos($alumnos1);
	            	$data['tablaAlumno1'] = $table1;
	            	$alumnos    = $this->m_matricula->getAlumnosByAula($idAu);
		    	    $fechas     = $this->m_matricula->getFechasReferenciaByTipo('T');
		    	    $data['tablaAlumnos']  = _createCardDetalleAlumnos($alumnos,$fechas);
	            	$capaActual = $this->m_aula->getCapaActualAula($idAu);
	            	$data['capa_actual'] = $capaActual;
            	}
	        }
	        
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGradosByNivel(){
	    $idSede  = _simpleDecryptInt(_post('idsede'));
	    $idNivel = _simpleDecryptInt(_post('idnivel'));

	    $grados = __buildComboGradosByNivel_SinAula($idNivel,1);
	    $data['comboGrados'] = $grados;
	
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function abrirModalConfirmarDesmatricular(){
	    $idAlumno = _simpleDecryptInt(_post('idalumno'));
	    
	    $data['alumno'] = $this->m_utils->getNombrePersona($idAlumno);
	    $data['error'] = (_getSesion('accionDetalleAula') == 0) ? EXIT_ERROR : EXIT_SUCCESS;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function deleteAlumno(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idAl    = _simpleDecryptInt(_post('idalumno'));
	        $idAu    = _getSesion('idAulaEdit');
	        $observ  = _post('observacion');
	        if($idAl == null || $idAu == null){
	            throw new Exception(ANP);
	        }
	        
	        if(strlen(trim($observ)) == 0){
	            throw new Exception("Ingrese una observaci&oacute;n");
	        }
	
	        $data = array(
	            "__id_persona" => $idAl,
	            "__id_aula"    => $idAu
	        );
	
	        $data = $this->m_matricula->eliminarAlumnoDeAula($data);
	
	        if($data['error'] == EXIT_SUCCESS){
	            $estado   = $this->m_utils->getById("sima.detalle_alumno", "estado", "nid_persona", $idAl);
	            if($estado == ALUMNO_MATRICULADO){
	                $data = array( "estado" => ALUMNO_MATRICULABLE);
	                $data = $this->m_matricula->updateDetalleAlumno($idAl,$data);
	            }else if($estado == ALUMNO_VERANO){
	                $year             = $this->m_utils->getById("aula", "year", "nid_aula", $idAu);
	                $countCicloVerano = $this->m_aula->getCountCicloVerano($idAl,$year);
	                if($countCicloVerano == 0 ){
	                    $data = array( "estado" => ALUMNO_NOPROMOVIDO_NIVELACION);
	                    $data = $this->m_matricula->updateDetalleAlumno($idAl,$data);
	                }
	            }
	            if($data['error'] == EXIT_SUCCESS){
	                $data = array("nid_persona"     => $idAl,
            	                  "tipo_incidencia" => INCIDENCIA_DESMATRICULA,
            	                  "id_aula"         => $idAu,
	                               "observacion"    => $observ
	                );
	                $data = $this->m_matricula->insertIncidenciaMatricula($data);
	                if($data['error'] == EXIT_SUCCESS){
	                    $alumnos = $this->m_matricula->getAlumnosByAula($idAu);
			    	    $fechas = $this->m_matricula->getFechasReferenciaByTipo('T');
			    	    $data['tablaAlumnos']  = _createCardDetalleAlumnos($alumnos,$fechas);
	                    $capaActual = $this->m_aula->getCapaActualAula($idAu);
	                    $data['capa_actual'] = $capaActual;
	                }
	            }
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function abrirModalConfirmAsignarEstudiantes(){
		$arrayIdEstudiantes = _post('arrayIdEstudiantes') != null ? _post('arrayIdEstudiantes') : null;
		if(count($arrayIdEstudiantes) == 1){
			$data['nombreCompleto'] = $this->m_utils->getNombrePersona(_simpleDecryptInt($arrayIdEstudiantes[0]));
		}
        $idAu    = _getSesion('idAulaEdit');
        $data['aula'] = $this->m_utils->getById('aula', 'desc_aula', 'nid_aula', $idAu);

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
		        if($okRat == 0){//para el proximo año
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
	    $head_5      = array('data' => 'Monto Final (S/.)'       , 'class' => 'text-center');
	    $head_6      = array('data' => 'Beca'			   , 'class' => 'text-center');
	    $head_6_7      = array('data' => 'F. de Pago'	   , 'class' => 'text-center');
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
            			$row_cell_2           = array('data'   => 'Cuota Ingreso', 'class' => 'text-center');
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
	
	function buildComboCursos($data) {
	    $opcion = null;
	    foreach ($data as $var) {
	        $opcion	.= '<option value='._simple_encrypt($var->id_curso).'>'.$var->desc_curso.'</option>';
	    }
	    return $opcion;
	}
	
	function getDocentesByCursosAula(){
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    try {
	    	$idCurso = _post('valorCurso') != null ? _simpleDecryptInt(_post('valorCurso')) : null;

    	    $docentes   = $this->m_aula->getProfesoresByCursoAula(_getSesion('idAulaEdit'), $idCurso);
    	    $data['tableProfesores'] = _createTablaProfesoresAulaCurso($docentes);
	        if($data['tableProfesores'] != null){
	            $data['error']       = EXIT_SUCCESS;
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	    
	}
	
	function enviarFeedBack(){
	    $nombre  = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url     = _post('url');
	    $html = '<p>'.$url.'</p>';
	    $html .= '<p>'.$mensaje.'</p>';
	    $html .= '<p>'.$nombre.'</p>';
        $arrayInsertCorreo = array('correos_destino'         => CORREO_BASE,
                                   'asunto'                  => utf8_encode("¡Sugerencias a Smiledu!"),
                                   'body'                    => $html,
                                   'estado_correo'           => CORREO_PENDIENTE,
                                   'sistema'                 => 'SMILEDU');
        $dataCorreo = $this->m_utils->insertarEnviarCorreo($arrayInsertCorreo);
	}
}