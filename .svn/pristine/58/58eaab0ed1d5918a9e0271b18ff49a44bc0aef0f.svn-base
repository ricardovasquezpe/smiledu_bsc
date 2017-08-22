<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_reporte extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->helper('html');
        $this->load->model('../m_utils');
        $this->load->model('m_reporte');
        $this->load->model('m_tutoria');
        $this->load->library('table');
        
        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_REPORTE, NOTAS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);    
    }
    
    public function index() {
        $data['titleHeader']      = 'Aulas';
        $data['ruta_logo']        = MENU_LOGO_NOTAS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
        $data['nombre_logo']      = NAME_MODULO_NOTAS;
        
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos(_getSesion(NOTAS_ROL_SESS), ID_SISTEMA_NOTAS, NOTAS_FOLDER);
        $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
        $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $menu = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;

        $data['cmbReportes'] = __buildComboByGrupo(COMBO_REPORTES_NOTAS);
        $this->load->view('v_reporte', $data);
    }
        
    function tipoReporte() {
        $cmbYear  = __buildComboYearsAcademicos();
        $cmbGrado = __buildComboGradoNivel_All();
        $cmbSede  = __buildComboSedes(); 
    	$tiporeporte = _simpleDecryptInt(_post('valorReport'));    
    	
    	if($tiporeporte == TIPO_REPORTE_CURSO_GRADO) {
    		$cmbSede = null; 		
    		$cmbBim  = null;
    		$data['tipo'] = CURSO_GRADO;
    	}

    	if($tiporeporte == TIPO_REPORTE_ORDEN_MERITO) {
    		$cmbSede = $this->combosPruebaHTML('cmbSede', 'Selec. Sede', 'selectButton', $cmbSede);
    		$data['tipo'] = ORDEN_MERITO;
    	}
    	
    	if($tiporeporte == TIPO_PROFESOR_POR_AULA) {
    		$cmbSede = $this->combosPruebaHTML('cmbSede', 'Selec. Sede', 'selectButton', $cmbSede);
    		$data['tipo'] = PROFESOR_POR_AULA;
    	} 
    	$cmbYear  = $this->combosPruebaHTML('cmbYear', 'Selec. A&ntilde;o', 'selectButton', $cmbYear);
    	$cmbGrado = $this->combosPruebaHTML('cmbGrado', 'Selec. Grado', 'selectButton', $cmbGrado);

    	$data['cmbSede']  = $cmbSede;
    	$data['cmbYear']  = $cmbYear;
    	$data['cmbGrado'] = $cmbGrado;
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function combosPruebaHTML($idName, $title, $class, $cmbYear) {
        $htmlCombo = '<div class="col-sm-12 mdl-input-group mdl-input-group__only">
						  <select id="'.$idName.'" name="'.$idName.'" class="form-control '.$class.'" data-live-search="true" title="'.$title.'">
        		             <option value="">'.$title.'</option>'
                                .$cmbYear.
                         '</select>
		    	      </div>';
        return $htmlCombo;
    }
        
    function tbCursos() {
    	$idGrado = _decodeCI(_post('idGrado'));
    	$year    = _decodeCI(_post('year'));
    	$arrayCursos = $this->m_tutoria->getCursosLibreta($idGrado, $year);

    	$data['table'] = _createTableCursos($arrayCursos);
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tbOrdMerito() {
    	$idGrado = _decodeCI(_post('idGrado'));
    	$year    = _decodeCI(_post('year'));
    	$idAula  = _decodeCI(_post('idAula'));
    	$idSede  = _decodeCI(_post('idSede'));
    	$idCiclo = _decodeCI(_post('idCiclo'));

    	list($cmbAula, $cmbBim) = $this->cmbAulaBimGrado($idGrado, $idSede, $year, $idAula);
    	$data['cmbAula'] = $cmbAula;
    	$data['cmbBim']  = $cmbBim;
  
    	$idGrado = $this->nullIds($idGrado);
    	$idAula  = $this->nullIds($idAula);
    	$idCiclo = $this->nullIds($idCiclo);
    	$idSede  = $this->nullIds($idSede);
    	if($year != null) {
    	    $arrayAlumno = $this->m_tutoria->alumnoPromedioOrdenMerito($year, $idSede, $idGrado, $idAula, $idCiclo);
    	    list($table, $foto, $nom, $grado) = _createTableOrdenMerito($arrayAlumno);
    	    $data['table'] = $table; 
    	}   	
    	echo json_encode(array_map('utf8_encode', $data));	 
    }
    
    function tbProfesores() {
    	$idGrado = _decodeCI(_post('idGrado'));
    	$year    = _decodeCI(_post('year'));
    	$idAula  = _decodeCI(_post('idAula'));
    	$idSede  = _decodeCI(_post('idSede'));
   
    	list($cmbAula, $cmbBim) = $this->cmbAulaBimGrado($idGrado, $idSede, $year, $idAula);
    	$data['cmbAula'] = $cmbAula;
    	$data['cmbBim']  = $cmbBim;
  
    	$idGrado = $this->nullIds($idGrado);
    	$idAula  = $this->nullIds($idAula);
    	$idSede  = $this->nullIds($idSede);
    	if($year != null) {
    	    $arrayProf = $this->m_tutoria->getProfesores($idAula, $idSede, $idGrado, $year);
    	    $data['table'] = _createTableProfesor($arrayProf);
    	}   	
    	echo json_encode(array_map('utf8_encode', $data));	 
    }
    
    function cmbAulaBimGrado($idGrado, $idSede, $year, $idAula) {
        if($idGrado != null && $idSede != null && $year != null) {
            $cmbAula      = __buildComboAulasYearSede($idGrado, $idSede, $year);
            $cmbCicloAcad = __buildComboBimestres();
            $cmbAula = $this->combosPruebaHTML('cmbAula', 'Selec. Aula', 'selectButton2', $cmbAula);
            $cmbBim  = $this->combosPruebaHTML('cmbBim', 'Selec. Bimestre', 'selectButton2', $cmbCicloAcad);
        } else {
            $cmbAula = null;
            $cmbBim  = null;
        }
        return array($cmbAula, $cmbBim);
    }
    
    function nullIds($id) {
        $id = ($id == '') ? $id = null : $id;
        return $id;
    }
    
    function getContPDF() {
        $idGrado     = _decodeCI(_post('idGrado'));
        $year        = _decodeCI(_post('year'));
        $idAula      = _decodeCI(_post('idAula'));
        $idSede      = _decodeCI(_post('idSede'));
        $idCiclo     = _decodeCI(_post('idCiclo'));
        $tiporeporte = _simpleDecryptInt(_post('valorReport'));   
         
        $idGrado = $this->nullIds($idGrado);
        $idAula  = $this->nullIds($idAula);
        $idCiclo = $this->nullIds($idCiclo);
        $idSede  = $this->nullIds($idSede);
    	if($tiporeporte == TIPO_REPORTE_CURSO_GRADO) {
    	    $arrayCursos = $this->m_tutoria->getCursosLibreta($idGrado, $year);
    	    $data['table']    = _createTableCursos($arrayCursos);
    	    $data['tipoText'] = "CURSO POR GRADO";
    	}

    	if($tiporeporte == TIPO_REPORTE_ORDEN_MERITO) {
    	    $arrayAlumno = $this->m_tutoria->alumnoPromedioOrdenMerito($year, $idSede, $idGrado, $idAula, $idCiclo);
    	    list($table, $foto, $nom, $grado) = _createTableOrdenMerito($arrayAlumno, 1, $idGrado);
    	    $data['table']     = $table;
    	    $data['nomAlumno'] = $nom;
    	    $data['grado']     = $grado; 
    	    $data['tipoText'] = "ORDEN DE MÉRITO";
    	    $data['count'] = count($arrayAlumno);
     	}
    	
    	if($tiporeporte == TIPO_PROFESOR_POR_AULA) {
    	    $arrayProf = $this->m_tutoria->getProfesores($idAula, $idSede, $idGrado, $year);
    	    $data['tipoText'] = "PROFESOR POR AULA";  
    	    $data['table']    = _createTableProfesor($arrayProf);
    	} 
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function generarPdf() {
        $contTabla = _post('contTabla');
        $contTipo  = _post('contTipo');
        $count     = _post('contCount');
        $nombre    = _post('nomAlumno');
        $grado     = _post('grado');
        $this->load->library('m_pdf');
        
        $pdf =  ($count <= 18) ?  $this->m_pdf->load('en-GB-x','A3-L','','',5,5,5,5,0,0) : $this->m_pdf->load('en-GB-x','A4-L','','',5,5,5,5,0,0);
        $data['pdfObj'] = $pdf;
        $data['tipo']   = $contTipo;
        $data['tabla']  = $contTabla;
        $data['nombre'] = $nombre;
        $data['grado']  = $grado;
        $data['count']  = $count;
        $this->load->view('v_pdf_reporte', $data);
        
        $pdf->Output("mi_libreta.pdf", 'D');
    }
}