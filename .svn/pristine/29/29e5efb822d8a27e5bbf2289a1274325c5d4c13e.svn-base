<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_reportes extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;

	function __construct() {
		parent::__construct();
		$this->output->set_header(CHARSET_ISO_8859_1);
		$this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->load->library('table');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
		$this->load->model('mf_matricula/m_matricula');
		$this->load->model('mf_reportes/m_reportes');
		$this->load->model('mf_aula/m_aula');
		_validate_uso_controladorModulos(ID_SISTEMA_MATRICULA, ID_PERMISO_REPORTES_MATRICULA, MATRICULA_ROL_SESS);
		$this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(MATRICULA_ROL_SESS);
	}
	
	public function index(){
	    $dataUser = array("previousPage" => 'c_reportes');
	    $this->session->set_userdata($dataUser);
	    
	    $data['comboReportes'] = __buildComboByGrupo(COMBO_REPORTES);
	    
	    $data['titleHeader'] =  'Reportes';
	    $data['ruta_logo'] = MENU_LOGO_MATRICULA;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_MATRICULA;
	    $data['nombre_logo'] = NAME_MODULO_MATRICULA;
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_MATRICULA, MATRICULA_FOLDER);
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_MATRICULA, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $menu = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    $this->load->view('v_reportes',$data);
	}
	
	public function TipoReporte(){
	    $tiporeporte = _simpleDecryptInt(_post('valreporte'));
	    $combo = null;
	    if($tiporeporte == TIPO_REPORTE_TUTORES){
	        $cmbYear = __createComboYear();
	        $combo .= __buildComboReportesByTipo('A&ntilde;o', "getSedesByYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte')", $cmbYear, 'selectYearReporte', null, 1);
	        $combo .= __buildComboReportesByTipo('Sede', "getGradoNivelBySedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte')", null, 'selectSedeReporte', null, 0);     
	        $combo .= __buildComboReportesByTipo('Grado - Nivel', "getReporteByTipoReporte(".TIPO_REPORTE_TUTORES.")", null, 'selectGradoNivelReporte', null, 0);
	    } else if($tiporeporte == TIPO_REPORTE_BIRTHDAY){
	        $cmbYear = __createComboYear();
	        $meses = __buildComboMeses();
	        $combo .= __buildComboReportesByTipo('A&ntilde;o', "getSedesByYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", $cmbYear, 'selectYearReporte', null, 1);
	        $combo .= __buildComboReportesByTipo('Sede', "getGradoNivelBySedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", null, 'selectSedeReporte', null, 0);     
	        $combo .= __buildComboReportesByTipo('Grado - Nivel', "getAulasByGradoNivelSedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", null, 'selectGradoNivelReporte', null, 0);
	        $combo .= __buildComboReportesByTipo('Aula', "habilitarCombo('selectAulaReporte','selectMesReporte'); getReporteByTipoReporte(".TIPO_REPORTE_BIRTHDAY.")", null, 'selectAulaReporte', null, 0);
	        $combo .= __buildComboReportesByTipo('Mes', "getReporteByTipoReporte(".TIPO_REPORTE_BIRTHDAY.")", $meses, 'selectMesReporte', 'disabled', 1);
	    } else if($tiporeporte == TIPO_REPORTE_AUlA){
	        $cmbYear   = __createComboYear();
	        $combo .= __buildComboReportesByTipo('A&ntilde;o', "getSedesByYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", $cmbYear, 'selectYearReporte', null, 1);
	        $combo .= __buildComboReportesByTipo('Sede', "getGradoNivelBySedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", null, 'selectSedeReporte', null, 0);     
	        $combo .= __buildComboReportesByTipo('Grado - Nivel', "getAulasByGradoNivelSedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", null, 'selectGradoNivelReporte', null, 0);
	        $combo .= __buildComboReportesByTipo('Aula', "habilitarRadioButton('selectAulaReporte', 'rbAulas')", null, 'selectAulaReporte', null, 0);
	        
	        $combo .= _getRadioButtonByTipo("Lista", "getReporteByTipoReporte(".TIPO_REPORTE_AUlA.")", "rbLista", "rbAulas", "disabled", _simple_encrypt(OPCION_REPORT_AULA_LISTA));
	        $combo .= _getRadioButtonByTipo("Firmas", "getReporteByTipoReporte(".TIPO_REPORTE_AUlA.")", "rbFirmas", "rbAulas", "disabled", _simple_encrypt(OPCION_REPORT_AULA_FIRMAS));
	        $combo .= _getRadioButtonByTipo("Consolidado", "getReporteByTipoReporte(".TIPO_REPORTE_AUlA.")", "rbConsolidado", "rbAulas", "disabled", _simple_encrypt(OPCION_REPORT_AULA_CONSOLIDADO));
	    } else if($tiporeporte == TIPO_REPORTE_ESTADOS){
	        $cmbYear    = __createComboYear();
	        $estados = __buildComboByGrupoNoEncryptId(COMBO_ESTADO_ALUMNO);
	        $combo  .= __buildComboReportesByTipo('A&ntilde;o', "getSedesByYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", $cmbYear, 'selectYearReporte', null, 1);
	        $combo  .= __buildComboReportesByTipo('Sede', "getGradoNivelBySedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", null, 'selectSedeReporte', null, 0);     
	        $combo  .= __buildComboReportesByTipo('Grado - Nivel', "habilitarCombo('selectGradoNivelReporte','selectEstadoReporte'); getReporteByTipoReporte(".TIPO_REPORTE_ESTADOS.")", null, 'selectGradoNivelReporte', null, 0);
	        $combo  .= __buildComboReportesByTipo('Estado', "getReporteByTipoReporte(".TIPO_REPORTE_ESTADOS.")", $estados, 'selectEstadoReporte', 'disabled', 1);
	    } else if($tiporeporte == TIPO_REPORTE_ALUMNOS){
	        $cmbYear   = __createComboYear();
	        $combo .= __buildComboReportesByTipo('A&ntilde;o', "getSedesByYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte');", $cmbYear, 'selectYearReporte', null, 1);
	        $combo .= __buildComboReportesByTipo('Sede', "getGradoNivelBySedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte');getReporteByTipoReporte(".TIPO_REPORTE_ALUMNOS.");", null, 'selectSedeReporte', null, 0);
	        $combo .= __buildComboReportesByTipo('Grado - Nivel', "getReporteByTipoReporte(".TIPO_REPORTE_ALUMNOS.",1)", null, 'selectGradoNivelReporte', null, 0);
	    } else if($tiporeporte == TIPO_REPORTE_FAMILIAR){
	        $cmbYear       = __createComboYear();
	        $comboReport6  = __buildComboReporte6();
	        $parentezco    = __buildComboByGrupo(COMBO_PARENTEZCO);
	        $combo .= __buildComboReportesByTipo('Parentesco', "selectParentezto('selectParentezcoReporte');", $parentezco, 'selectParentezcoReporte', "multiple", 0);
	        $combo .= __buildComboReportesByTipo('Busqueda', "selectTipoBusqueda()", $comboReport6, 'selectTipoBusquedaReporte', "disabled", 1);
	    } else if($tiporeporte == TIPO_REPORTE_DOCENTES){
	        $cmbYear = __createComboYear();
	        $cursos = __buildComboCursos();
	        $combo .= __buildComboReportesByTipo('Curso', "selectCurso('selectCursoReporte');", $cursos, 'selectCursoReporte', "multiple", 0);
	        $combo .= __buildComboReportesByTipo('A&ntilde;o', "getSedesByYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte')", $cmbYear, 'selectYearReporte', "disabled", 1);
	        $combo .= __buildComboReportesByTipo('Sede', "getGradoNivelBySedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte')", null, 'selectSedeReporte', "disabled", 0);
	        $combo .= __buildComboReportesByTipo('Grado - Nivel', "getReporteByTipoReporte(".TIPO_REPORTE_DOCENTES.")", null, 'selectGradoNivelReporte', "disabled", 0);
        } else if($tiporeporte == TIPO_REPORTE_TRASLADOS){
            $cmbYear = __createComboYear();
            $combo .= __buildComboReportesByTipo('A&ntilde;o', "getSedesByYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", $cmbYear, 'selectYearReporte', null, 1);
            $combo .= __buildComboReportesByTipo('Sede', "habilitarRadioButton('selectSedeReporte','rbTraslado')", null, 'selectSedeReporte', null, 0);
            
            $combo .= _getRadioButtonByTipo("Solicitado", "getReporteByTipoReporte(".TIPO_REPORTE_TRASLADOS.")", "rbSolicitado", "rbTraslado", "disabled", _simple_encrypt(OPCION_REPORTE_TRASLADO_SOLICITADO));
            $combo .= _getRadioButtonByTipo("Rechazado", "getReporteByTipoReporte(".TIPO_REPORTE_TRASLADOS.")", "rbRechazado", "rbTraslado", "disabled", _simple_encrypt(OPCION_REPORTE_TRASLADO_RECHAZADO));
            $combo .= _getRadioButtonByTipo("Aceptado", "getReporteByTipoReporte(".TIPO_REPORTE_TRASLADOS.")", "rbAceptado", "rbTraslado", "disabled", _simple_encrypt(OPCION_REPORTE_TRASLADO_ACEPTADO));
        } else if($tiporeporte == TIPO_REPORTE_RATIFICACION){
            // FRANCO - REPORTE PENDIENTE
            $cmbYear = __createComboYear();
            $combo   = __buildComboReportesByTipo('A&ntilde;o', "getSedesByYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", $cmbYear, 'selectYearReporte', null, 1);
            $combo  .= __buildComboReportesByTipo('Sede', "getGradoNivelBySedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte'); habilitarRadioButton('selectSedeReporte','rbRatificacion')", null, 'selectSedeReporte', null, 0);
            $combo  .= __buildComboReportesByTipo('Grado - Nivel', "getAulasByGradoNivelSedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", null, 'selectGradoNivelReporte', null, 0);
            $combo  .= __buildComboReportesByTipo('Aula', "selectEstadoRatificacion()", null, 'selectAulaReporte', null, 0);
            
            $combo .= _getRadioButtonByTipo("No iniciada,",                  "getReporteByTipoReporte(".TIPO_REPORTE_RATIFICACION.")", "rbNoIniciado",  "rbRatificacion", "disabled", _simple_encrypt(OPCION_REPORTE_RATIFICACION_NOINICIADA));
            $combo .= _getRadioButtonByTipo("Declaraci&oacute;n Jurada,",    "getReporteByTipoReporte(".TIPO_REPORTE_RATIFICACION.")", "rbDeclaracion", "rbRatificacion", "disabled", _simple_encrypt(OPCION_REPORTE_RATIFICACION_DECLARACIONJURADA));
            $combo .= _getRadioButtonByTipo("Ratificaci&oacute;n generada,", "getReporteByTipoReporte(".TIPO_REPORTE_RATIFICACION.")", "rbGenerada",    "rbRatificacion", "disabled", _simple_encrypt(OPCION_REPORTE_RATIFICACION_GENERADA));
            $combo .= _getRadioButtonByTipo("Ratificaci&oacute;n pagada,",   "getReporteByTipoReporte(".TIPO_REPORTE_RATIFICACION.")", "rbPagada",      "rbRatificacion", "disabled", _simple_encrypt(OPCION_REPORTE_RATIFICACION_PAGADA));
        }
	    
	    //$combo .= $this->getCheckboxByTipo("Lista", null, "cbLista", "disabled");
	    //$combo .= $this->getCheckboxByTipo("Firmas", null, "cbFirmas", "disabled");
	    //$combo .= $this->getCheckboxByTipo("Consolidado", null, "cbConsolidado", "disabled");
	    
	    $data['combos'] = $combo;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getSedesByYear(){
	    $year = _post('year');
	    if($this->_idRol != ID_ROL_ADMINISTRADOR){
	        $desc_sede = $this->m_utils->getById("sede", "desc_sede", "nid_sede", _getSesion("id_sede_trabajo"));
	        $data['comboSedes'] = '<option value="'._simple_encrypt(_getSesion("id_sede_trabajo")).'">'.ucfirst($desc_sede).'</option>';
	    } else {
	        $data['comboSedes'] = __buildComboSedesByYear($year);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}

    function getGradoNivelBySedeYear(){
	    $year    = _post('year');
	    $reporte    = _post('reporte') != null ? _simpleDecryptInt(_post('reporte')) : null;
	    $idSede  = _simpleDecryptInt(_post('idsede'));
	    $gradoNivel = __buildComboGradoNivelBySedeYear($idSede, $year);
	    $data['comboGradoNivel'] = $gradoNivel;
	    $data['reporte'] = $reporte;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getReporteByTipo(){
	    $tipo = _post('tipo');
	    $data = null;$data= array();
	    if($tipo == TIPO_REPORTE_TUTORES){
	        $year    = _post('year');
	        $idSede  = _simpleDecryptInt(_post('idsede'));
	        $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	        $gradoNivel   = explode('_', $idGradoNivel);
	        
	        $reporte = $this->m_reportes->reporteGetTutores($year, $idSede, $gradoNivel[1], $gradoNivel[0]);
	        $data['resultado'] = _createTableReporte1($reporte);
	    }else if($tipo == TIPO_REPORTE_BIRTHDAY){
	        $idAula  = _simpleDecryptInt(_post('idaula'));
	        $mes     = _simpleDecryptInt(_post('mes'));
	        
	        $reporte = $this->m_reportes->reporteGetAlumnosAulaBirthday($idAula, $mes);
	        $data['resultado'] = _createTableReporte2($reporte);
	    }else if($tipo == TIPO_REPORTE_AUlA){
	        $idAula  = _simpleDecryptInt(_post('idaula'));
	        $cb      = _simpleDecryptInt(_post('valorcb'));

	        $reporte = $this->m_reportes->reporteGetAlumnosAula($idAula);
	        if($cb == OPCION_REPORT_AULA_LISTA){
	            $data['resultado'] = _createTableReporte3_1($reporte);
	        }else if($cb == OPCION_REPORT_AULA_FIRMAS){
	            $data['resultado'] = _createTableReporte3_2($reporte);
	        }else if($cb == OPCION_REPORT_AULA_CONSOLIDADO){
	            $data['resultado'] = _createTableReporte3_3($reporte);
	        }
	    }else if($tipo == TIPO_REPORTE_ESTADOS){
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	        $gradoNivel   = explode('_', $idGradoNivel);
	        $estado       = (_post('estado') == NULL) ? NULL : _post('estado');
	        $reporte = $this->m_reportes->reporteGetAlumnosAulaEstado($idSede, $gradoNivel[1], $gradoNivel[0], $estado);
	        $data['resultado'] = _createTableReporte4($reporte);
	    }else if($tipo == TIPO_REPORTE_ALUMNOS){
	    	$year    = _post('year');
	        $idSede  = _simpleDecryptInt(_post('idsede'));
	        $idGradoNivel = (_post('idgradonivel') == null ) ? null : _simple_decrypt(_post('idgradonivel'));
	        if(_post('idgradonivel') != null){
	            $gradoNivel   = explode('_', $idGradoNivel);
	            $reporte = $this->m_reportes->reporteGetCantAlumnos($year, $idSede, $gradoNivel[1], $gradoNivel[0]);
	            $data['resultado'] = _createTableReporte5_2($reporte);
	        } else {
	            $reporte = $this->m_reportes->reporteGetCantAlumnos($year, $idSede, null, null);
	            $data['resultado'] = _createTableReporte5_1($reporte);
	        }
	    }else if($tipo == TIPO_REPORTE_FAMILIAR){
	        $idParentezcos  = _post('idparentezcos');
	        $idTipoBusqueda = _simpleDecryptInt(_post('idtipobusqueda'));
	        
	        if($idTipoBusqueda == OPCION_REPORTE_FAMILIAR_GRADO){
	            $idAula = _simpleDecryptInt(_post('idaula'));
	            $parentezcos = $this->getArrayObjectFromArray($idParentezcos, 1);
	            $reporte = $this->m_reportes->reporteGetFamiliaresByAula($idAula, $parentezcos);
	            $data['resultado'] = _createTableReporte6_1($reporte);
	        }else if($idTipoBusqueda == OPCION_REPORTE_FAMILIAR_DISTRITO){
	            $idDepartamento = _simple_decrypt(_post('iddepartamento'));
	            $idProvincia    = _simple_decrypt(_post('idprovincia'));
	            $idDistrito     = _simple_decrypt(_post('iddistrito'));
	            $parentezcos = $this->getArrayObjectFromArray($idParentezcos, 1);
	            $ubigeo = $idDepartamento.$idProvincia.$idDistrito;
	            $reporte = $this->m_reportes->reporteGetFamiliaresByDistrito($ubigeo, $parentezcos);
	            $data['resultado'] = _createTableReporte6_2($reporte);
	        }
	    }else if($tipo == TIPO_REPORTE_DOCENTES){
	        $idCursos     = _post('idcursos');
	        $year         = _post('year');
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $idGradoNivel = (_post('idgradonivel') == null ) ? null : _simple_decrypt(_post('idgradonivel'));
	        $gradoNivel   = explode('_', $idGradoNivel);
	        $cursos = $this->getArrayObjectFromArray($idCursos, 2);
	        $reporte = $this->m_reportes->reporteGetDocentes($cursos, $year, $idSede, $gradoNivel[0], $gradoNivel[1]);

	        $data['resultado'] = _createTableReporte7($reporte);
	        
	    }else if($tipo == TIPO_REPORTE_TRASLADOS){
	        $year         = _post('year');
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $cb           = _simple_decrypt(_post('valorcb'));
	        $reporte = $this->m_reportes->reporteGetTraslado($year,$idSede,$cb);
	        
	        $data['resultado'] = _createTableReporte8($reporte);
	        
	    } else if($tipo == TIPO_REPORTE_RATIFICACION){
	        $year         = _post('year');
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $gradoNivel   = _post('idgradonivel') == null ? null : explode('_', _simple_decrypt(_post('idgradonivel')));
            $idAula       = _post('idaula') != null ? _simpleDecryptInt(_post('idaula')) : null;
	        $cb           = _simple_decrypt(_post('valorcb'));
	        
	        if($cb == OPCION_REPORTE_RATIFICACION_NOINICIADA){
				if($gradoNivel != null){
	        		$reporte = $this->m_reportes->reporteGetRatificacion0($year, $idSede, $gradoNivel[0], $gradoNivel[1], $idAula);
	        	} else {
	        		$reporte = $this->m_reportes->reporteGetRatificacion0($year, $idSede, null, null, null);
	        	}
	        	$data['resultado'] = _createTableReporte9_1_2($reporte);
	        } else if($cb == OPCION_REPORTE_RATIFICACION_DECLARACIONJURADA){
	        	if($gradoNivel != null){
	        		$reporte = $this->m_reportes->reporteGetRatificacion1($year, $idSede, $gradoNivel[0], $gradoNivel[1], $idAula, 1);
	        	} else {
	        		$reporte = $this->m_reportes->reporteGetRatificacion1($year, $idSede, null, null, null, 1);
	        	}
	        	$data['resultado'] = _createTableReporte9_1_2($reporte);
	        } else if($cb == OPCION_REPORTE_RATIFICACION_GENERADA){
	        	if($gradoNivel != null){
	        		$reporte = $this->m_reportes->reporteGetRatificacion2($year, $idSede, $gradoNivel[0], $gradoNivel[1], ESTADO_POR_PAGAR, $idAula);
	        	} else {
	        		$reporte = $this->m_reportes->reporteGetRatificacion2($year, $idSede, null, null, ESTADO_POR_PAGAR, null);
	        	}
	        	$data['resultado'] = _createTableReporte9_3_4($reporte);
	        } else if($cb == OPCION_REPORTE_RATIFICACION_PAGADA){
	        	if($gradoNivel != null){
	        		$reporte = $this->m_reportes->reporteGetRatificacion2($year, $idSede, $gradoNivel[0], $gradoNivel[1], ESTADO_PAGADO, $idAula);
	        	} else {
	        		$reporte = $this->m_reportes->reporteGetRatificacion2($year, $idSede, null, null, ESTADO_PAGADO, null);
	        	}
	        	$data['resultado'] = _createTableReporte9_3_4($reporte);
	        }
	    } 
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getArrayObjectFromArray($data, $decrypt = null){
	    $arrayIds = array();
	    foreach ($data as $var){
	        $id = null;
	        if($decrypt == 1){
	            $id = _simple_decrypt($var);
	        }else{
	            $id = $this->encrypt->decode($var);
	        }
	        if($id != null){
	            array_push($arrayIds, $id);
	        }
	    }
	    return $arrayIds;
	}
	
	function abrirModalAlumnosSexo(){
	    $idAula = _simpleDecryptInt(_post('idaula'));
	    $sexo   = _simpleDecryptInt(_post('sexo'));
	    $alumnos = $this->m_reportes->getAlumnosAulaBySexo($idAula, $sexo);
	    $data['tablaAlumnos'] = _createTableAlumnosReportes($alumnos);
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getPDFByTipo(){
	    $tipo     = _post('tipo');
	    $html     = null;
	    $reporte  = null;
	    if($tipo == TIPO_REPORTE_TUTORES){
	        $year         = _post('year');
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	        $gradoNivel   = explode('_', $idGradoNivel);
	         
	        $reporte = $this->m_reportes->reporteGetTutores($year, $idSede, $gradoNivel[1], $gradoNivel[0]);
	        $html = _generarTablaHTMLReporte1($reporte);
	    }else if($tipo == TIPO_REPORTE_BIRTHDAY){
	        $idAula  = _simpleDecryptInt(_post('idaula'));
	        $mes     = _simpleDecryptInt(_post('mes'));
	        
	        $reporte = $this->m_reportes->reporteGetAlumnosAulaBirthday($idAula, $mes);
	        $html = _generarTablaHTMLReporte2($reporte);
	    }else if($tipo == TIPO_REPORTE_AUlA){
	        $idAula  = _simpleDecryptInt(_post('idaula'));
	        $cb      = _simpleDecryptInt(_post('valorcb'));
	        
	        $reporte = $this->m_reportes->reporteGetAlumnosAula($idAula);
	        if($cb == OPCION_REPORT_AULA_LISTA){
	            $html = _generarTablaHTMLReporte3_1($reporte);
	        }else if($cb == OPCION_REPORT_AULA_FIRMAS){
	            $html = _generarTablaHTMLReporte3_2($reporte);
	        }else if($cb == OPCION_REPORT_AULA_CONSOLIDADO){
	            $html = _generarTablaHTMLReporte3_3($reporte);
	        }
	    }else if($tipo == TIPO_REPORTE_ESTADOS){
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	        $gradoNivel   = explode('_', $idGradoNivel);
	        $estado       = (_post('estado') == NULL) ? NULL : _post('estado');
	        $reporte = $this->m_reportes->reporteGetAlumnosAulaEstado($idSede, $gradoNivel[1], $gradoNivel[0], $estado);
	        $html = _generarTablaHTMLReporte4($reporte);
	    }else if($tipo == TIPO_REPORTE_ALUMNOS){
	        $year    = _post('year');
	        $idSede  = _simpleDecryptInt(_post('idsede'));
	        $idGradoNivel = (_post('idgradonivel') == null ) ? null : _simple_decrypt(_post('idgradonivel'));
	        if(_post('idgradonivel') != null){
	            $gradoNivel   = explode('_', $idGradoNivel);
	            $reporte = $this->m_reportes->reporteGetCantAlumnos($year, $idSede, $gradoNivel[1], $gradoNivel[0]);
	            $html = _generarTablaHTMLReporte5_2($reporte);
	        } else {
	            $reporte = $this->m_reportes->reporteGetCantAlumnos($year, $idSede, null, null);
	            $html = _generarTablaHTMLReporte5_1($reporte);
	        }
	    }else if($tipo == TIPO_REPORTE_FAMILIAR){
	        $idParentezcos  = _post('idparentezcos');
	        $idTipoBusqueda = _simpleDecryptInt(_post('idtipobusqueda'));
	         
	        if($idTipoBusqueda == OPCION_REPORTE_FAMILIAR_GRADO){
	            $idAula = _simpleDecryptInt(_post('idaula'));
	            $parentezcos = $this->getArrayObjectFromArray($idParentezcos, 1);
	            
	            $reporte = $this->m_reportes->reporteGetFamiliaresByAula($idAula, $parentezcos);
	            $html = _generarTablaHTMLReporte6_1($reporte);
	        }else if($idTipoBusqueda == OPCION_REPORTE_FAMILIAR_DISTRITO){
	            $idDepartamento = _simple_decrypt(_post('iddepartamento'));
	            $idProvincia    = _simple_decrypt(_post('idprovincia'));
	            $idDistrito     = _simple_decrypt(_post('iddistrito'));
	            $parentezcos = $this->getArrayObjectFromArray($idParentezcos, 1);
	            $ubigeo = $idDepartamento.$idProvincia.$idDistrito;
	            
	            $reporte = $this->m_reportes->reporteGetFamiliaresByDistrito($ubigeo, $parentezcos);
	            $html = _generarTablaHTMLReporte6_2($reporte);
	        }
	    }else if($tipo == TIPO_REPORTE_DOCENTES){
	        $idCursos     = _post('idcursos');
	        $year         = _post('year');
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $idGradoNivel = (_post('idgradonivel') == null ) ? null : _simple_decrypt(_post('idgradonivel'));
	        $gradoNivel   = explode('_', $idGradoNivel);
	        $cursos = $this->getArrayObjectFromArray($idCursos, 2);
	        $reporte = $this->m_reportes->reporteGetDocentes($cursos, $year, $idSede, $gradoNivel[0], $gradoNivel[1]);

	        $html = _generarTablaHTMLReporte7($reporte);
	        
	    }else if($tipo == TIPO_REPORTE_TRASLADOS){
	        $year         = _post('year');
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $cb           = _simple_decrypt(_post('valorcb'));
	        
	        $reporte = $this->m_reportes->reporteGetTraslado($year,$idSede,$cb);

	        $html = _generarTablaHTMLReporte8($reporte);
	        
	    } else if($tipo == TIPO_REPORTE_RATIFICACION){
	        $year         = _post('year');
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $gradoNivel   = _post('idgradonivel') == null ? null : explode('_', _simple_decrypt(_post('idgradonivel')));
            $idAula       = _post('idaula') != null ? _simpleDecryptInt(_post('idaula')) : null;
	        $cb           = _simple_decrypt(_post('valorcb'));

	        if($cb == OPCION_REPORTE_RATIFICACION_NOINICIADA){
				if($gradoNivel != null){
	        		$reporte = $this->m_reportes->reporteGetRatificacion1($year, $idSede, $gradoNivel[0], $gradoNivel[1], $idAula, null);
	        	} else {
	        		$reporte = $this->m_reportes->reporteGetRatificacion1($year, $idSede, null, null, null, null);
	        	}
	        	$html = _generarTablaHTMLReporte9_1_2($reporte);
        	} else if($cb == OPCION_REPORTE_RATIFICACION_DECLARACIONJURADA){
        		if($gradoNivel != null){
        			$reporte = $this->m_reportes->reporteGetRatificacion1($year, $idSede, $gradoNivel[0], $gradoNivel[1], $idAula, 1);
        		} else {
        			$reporte = $this->m_reportes->reporteGetRatificacion1($year, $idSede, null, null, null, 1);
        		}
	        	$html = _generarTablaHTMLReporte9_1_2($reporte);
        	} else if($cb == OPCION_REPORTE_RATIFICACION_GENERADA){
        		if($gradoNivel != null){
        			$reporte = $this->m_reportes->reporteGetRatificacion2($year, $idSede, $gradoNivel[0], $gradoNivel[1], ESTADO_POR_PAGAR,  $idAula);
        		} else {
        			$reporte = $this->m_reportes->reporteGetRatificacion2($year, $idSede, null, null, null, null);
        		}
	        	$html = _generarTablaHTMLReporte9_3_4($reporte);
        	} else if($cb == OPCION_REPORTE_RATIFICACION_GENERADA){
        		if($gradoNivel != null){
        			$reporte = $this->m_reportes->reporteGetRatificacion2($year, $idSede, $gradoNivel[0], $gradoNivel[1], ESTADO_PAGADO, $idAula);
        		} else {
        			$reporte = $this->m_reportes->reporteGetRatificacion2($year, $idSede, null, null, null, null);
        		}
	        	$html = _generarTablaHTMLReporte9_3_4($reporte);
        	}
	    }
	    
	    if($reporte != null){
	        $this->load->library('m_pdf');
	        $nomFile     = __generateRandomString(8);
	        $file        = "uploads/modulos/matricula/documentos/".$nomFile.".pdf";
	        $pdf         = $this->m_pdf->load('','A4-L', 0, '', 15, 15, 16, 16, 9, 9, 'L');
	        $nombreCombo = $this->m_utils->getDescComboTipoByGrupoValor(COMBO_REPORTES, $tipo);
	        $desc        = utf8_encode($nombreCombo);
	        $pdf->SetFooter($desc.'|{PAGENO}|'.date('d/m/Y h:i:s a'));
	        $pdf->WriteHTML(utf8_encode(' <img src="'.RUTA_IMG.'logos_colegio/avantgardLogo.png" style="margin-top: -20px; width: 100px" />
	                                         <p style="margin-left:400px;margin-top:-50px;text-decoration: underline;font-size:15px">'.ucfirst($nombreCombo).'</p>
	                                         <img src="'.RUTA_IMG.'logos_colegio/logonslm.png" style="margin-bottom:10px;margin-left:900px;margin-top:-80px; width: 80px" /><br/><br/>'.
	                                         $html));
	        $pdf->Output("./".$file, 'F');
	        echo RUTA_SMILEDU.$file;
	    }
	}
	
	//NO LO ELIMINA PORQUE NO ENCUENTRA EL ARCHIVO
	function borrarPDF(){
	    $imagen = $this->input->post('ruta');
	    if(file_exists($imagen)) {
	        $imagen = './'.$this->input->post('ruta');
	        if (!unlink($imagen)){
	            echo ("No se borr&oacute; el archivo $imagen");
	        }else{
	            echo ("Se borr&oacute; $imagen");
	        }
	    }
	    echo null;
	}
	
	function getGrafico(){
	    $tipo = _post('tipo');
	    $data = array();
	    $i=0;
	    if($tipo == TIPO_REPORTE_BIRTHDAY){
	        $idAula  = _simpleDecryptInt(_post('idaula'));
	        $mes     = _simpleDecryptInt(_post('mes'));
	         
	        $reporte = $this->m_reportes->getGraficosReporteBirthday($idAula, $mes);
	        $cant = array();
	        $meses = array();
	        foreach ($reporte as $rep){
	            array_push($cant  , intval($rep->cant));
	            array_push($meses , utf8_encode($rep->mes));
	            $i++;
	        }
	         
	         
	        $data['cant'] = json_encode($cant);
	        $data['meses'] = json_encode($meses);
	        $data['count'] = $i;
	
	    } else if($tipo == TIPO_REPORTE_ALUMNOS){
	        $year    = _post('year');
	        $idSede  = _simpleDecryptInt(_post('idsede'));
	        $idGradoNivel = (_post('idgradonivel') == null ) ? null : _simple_decrypt(_post('idgradonivel'));
	        if(_post('idgradonivel') != null){
	            $gradoNivel   = explode('_', $idGradoNivel);
	            $reporte = $this->m_reportes->reporteGetCantAlumnos($year, $idSede, $gradoNivel[1], $gradoNivel[0]);
	            $i=0;
	            //$arrayCount = null;
	
	            $varones = array();
	            $mujeres = array();
	            $aula = array();
	            foreach ($reporte as $rep){
	                array_push($varones  , intval($rep->varones));
	                array_push($mujeres  , intval($rep->mujeres));
	                array_push($aula     , utf8_encode($rep->desc_aula));
	                $i++;
	            }
	            $data['varones'] = json_encode($varones);
	            $data['mujeres'] = json_encode($mujeres);
	            $data['aula']    = json_encode($aula);
	        } else {
	            $reporte = $this->m_reportes->reporteGetCantAlumnos($year, $idSede, null, null);
	            $i=0;
	            $varones = array();
	            $mujeres = array();
	            $aula = array();
	             
	            foreach ($reporte as $rep){
	                array_push($varones  , intval($rep->varones));
	                array_push($mujeres  , intval($rep->mujeres));
	                array_push($aula     , utf8_encode($rep->desc_aula));
	                $i++;
	            }
	            $data['varones'] = json_encode($varones);
	            $data['mujeres'] = json_encode($mujeres);
	            $data['aula']    = json_encode($aula);
	        }
	    } else if($tipo == TIPO_REPORTE_FAMILIAR){
	        $idParentezcos  = _post('idparentezcos');
	        $idTipoBusqueda = _simpleDecryptInt(_post('idtipobusqueda'));
	         
	        if($idTipoBusqueda == OPCION_REPORTE_FAMILIAR_GRADO){
	            $idAula = _simpleDecryptInt(_post('idaula'));
	            $parentescosArray = $this->getArrayObjectFromArray($idParentezcos, 1);
	            $reporte = $this->m_reportes->getGraficoReporteFamiliarParentescos($idAula, $parentescosArray);
	             
	            $cant = array();
	            $parentesco = array();
	            foreach ($reporte as $rep){
	                array_push($cant  , intval($rep->cant));
	                array_push($parentesco     , utf8_encode($rep->parentesco));
	            }
	             
	            $data['cant'] = json_encode($cant);
	            $data['parentesco'] = json_encode($parentesco);
	        }else if($idTipoBusqueda == OPCION_REPORTE_FAMILIAR_DISTRITO){
	            $idDepartamento = _simple_decrypt(_post('iddepartamento'));
	            $idProvincia    = _simple_decrypt(_post('idprovincia'));
	            $idDistrito     = _simple_decrypt(_post('iddistrito'));
	            $parentescosArray = $this->getArrayObjectFromArray($idParentezcos, 1);
	            $ubigeo = $idDepartamento.$idProvincia.$idDistrito;
	            $reporte = $this->m_reportes->getGraficoReporteFamiliarDistrito($ubigeo, $parentescosArray);
	             
	            $cant = array();
	            $parentesco = array();
	            foreach ($reporte as $rep){
	                array_push($cant  , intval($rep->cant));
	                array_push($parentesco     , utf8_encode($rep->parentesco));
	            }
	
	            $data['cant']       = json_encode($cant);
	            $data['parentesco'] = json_encode($parentesco);
	        }
	    }else if($tipo == TIPO_REPORTE_DOCENTES){
	        $idCursos     = _post('idcursos');
	        $year         = _post('year');
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $idGradoNivel = (_post('idgradonivel') == null ) ? null : _simple_decrypt(_post('idgradonivel'));
	        $gradoNivel   = explode('_', $idGradoNivel);
	        $cursos = $this->getArrayObjectFromArray($idCursos, 2);
	        $reporte = $this->m_reportes->getGraficoReporteDocentes($cursos, $year, $idSede, $gradoNivel[0], $gradoNivel[1]);
	        $cant = array();
	        $aulas = array();
	        foreach ($reporte as $rep){
	            array_push($cant  , intval($rep->cant));
	            array_push($aulas     , utf8_encode($rep->desc_aula));
	        }
	         
	        $data['cant']  = json_encode($cant);
	        $data['aulas'] = json_encode($aulas);
	    }else if($tipo == TIPO_REPORTE_TRASLADOS){
	        $year         = _post('year');
	        $idSede       = _simpleDecryptInt(_post('idsede'));
	        $cb           = _simple_decrypt(_post('valorcb'));
	
	        $reporte = $this->m_reportes->reporteGetTraslado($year,$idSede,$cb);
	        $i=0;
	        $intrasede = array();
	        $intersedes = array();
	        $niveles = array();
	
	        foreach ($reporte as $rep){
	            array_push($intrasede  , intval($rep->intrasede));
	            array_push($intersedes  , intval($rep->intersedes));
	            array_push($niveles     , utf8_encode($rep->desc_nivel));
	            $i++;
	        }
	         
	        $data['intrasede'] = json_encode($intrasede);
	        $data['intersedes'] = json_encode($intersedes);
	        $data['niveles']    = json_encode($niveles);
	    }
	
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getTablaAulasByGradoNivelSedeYear(){
	    $year    = _post('year');
	    $idSede  = _simpleDecryptInt(_post('idsede'));
	    $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	    $gradoNivel = explode('_', $idGradoNivel);
	    $data['comboAula']  = __buildComboAulasbyGradoYear($gradoNivel[1], $idSede, $gradoNivel[0], $year);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function mostrarDocentesAula(){
	    $idAula  = _simpleDecryptInt(_post('idaula'));
	    $alumnos = $this->m_reportes->getProfesoresCursosByAula($idAula);
	    $data['tablaDocentes'] = _createTableDocentes($alumnos);
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getComboReporte6(){
	    $tipoBusqueda = _simpleDecryptInt(_post("valbusqueda"));
	    $combo = null;
	    if($tipoBusqueda == OPCION_REPORTE_FAMILIAR_GRADO){
	        $cmbYear = __createComboYear();
	        $combo .= __buildComboReportesByTipo('A&ntilde;o', "getSedesByYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", $cmbYear, 'selectYearReporte', null, 1);
	        $combo .= __buildComboReportesByTipo('Sede', "getGradoNivelBySedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", null, 'selectSedeReporte', null, 0);
	        $combo .= __buildComboReportesByTipo('Grado - Nivel', "getAulasByGradoNivelSedeYear('selectYearReporte', 'selectSedeReporte', 'selectGradoNivelReporte', 'selectAulaReporte')", null, 'selectGradoNivelReporte', null, 0);
	        $combo .= __buildComboReportesByTipo('Aula', "getReporteByTipoReporte(".TIPO_REPORTE_FAMILIAR.")", null, 'selectAulaReporte', null, 0);
	    }else if($tipoBusqueda == OPCION_REPORTE_FAMILIAR_DISTRITO){
	        $departamentos = __buildComboUbigeoByTipo(null, null, 1);
	        $combo .= __buildComboReportesByTipo('Departamento', "getProvinciaPorDepartamento('selectDepartamentoReporte', 'selectProvinciaReporte', 'selectDistritoReporte', 2)", $departamentos, 'selectDepartamentoReporte', null, 1);
	        $combo .= __buildComboReportesByTipo('Provincia', "getDistritoPorProvincia('selectDepartamentoReporte', 'selectProvinciaReporte', 'selectDistritoReporte', 3)", null, 'selectProvinciaReporte', null, 0);
	        $combo .= __buildComboReportesByTipo('Distrito', "getReporteByTipoReporte(".TIPO_REPORTE_FAMILIAR.")", null,'selectDistritoReporte', null, 0);
	    }else if($tipoBusqueda == OPCION_REPORTE_FAMILIAR_PROFESION){
	        $combo = null;
	    }
	    $data['combos'] = $combo;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function mostrarHijosFamiliar(){
	    $idfamiliar  = _simpleDecryptInt(_post('idfamiliar'));
	    $hijos = $this->m_reportes->getHijosByFamiliar($idfamiliar);
	    $data['tablaHijos'] = $this->createTableHijos($hijos);

	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function createTableHijos($hijos){
	    $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tbHijosFamiliar">',//este id se lee en el js
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => '#', 'class' => 'text-left');
	    $head_1   = array('data' => 'Nombre', 'data-sortable' => 'true', 'class' => 'text-left');
	    $head_3   = array('data' => 'Fec.Nacimiento', 'data-sortable' => 'true', 'class' => 'text-left');
	    $head_4   = array('data' => 'DNI', 'data-sortable' => 'true', 'class' => 'text-right');
	    //$head_3   = array('data' => 'Accion');
	    $this->table->set_heading($head_0, $head_1, $head_3, $head_4);
	    $i = 1;
	    foreach ($hijos as $row){
	        $row_0 = array('data' => $i, 'class' => 'text-left');
		    $idAlumnEnc = _simple_encrypt($row->nid_persona);
	        $imageStudent = '<img alt="Student" src="'.RUTA_SMILEDU.'uploads/images/foto_perfil/estudiantes/'.$row->foto_persona.'" WIDTH=30 HEIGHT=30
		        class="img-circle m-r-10" onclick="goToViewAlumno(\''.$idAlumnEnc.'\')" style="cursor:pointer">
		        <p class="classroom-value classroom-link" style="display: inline" onclick="goToViewAlumno(\''.$idAlumnEnc.'\')" style="cursor:pointer">'.$row->nombrecompleto.'</p>';
	        
	        $row_1   = array('data' => $imageStudent, 'class' => 'text-left');
	        $row_3   = array('data' => $row->fec_naci, 'class' => 'text-left');
	        $row_4   = array('data' => $row->dni, 'class' => 'text-right');
	
	        $this->table->add_row($row_0, $row_1, $row_3, $row_4);
	        $i++;
	    }
	    
	    $table = $this->table->generate();
	    return $table;
	}

	function setIdSistemaInSession(){
	    $idSistema = $this->encrypt->decode($this->input->post('id_sis'));
	    $idRol     = $this->encrypt->decode($this->input->post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);
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