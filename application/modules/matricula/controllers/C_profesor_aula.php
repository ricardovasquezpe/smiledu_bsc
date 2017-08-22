<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_profesor_aula extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
		$this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
		$this->load->library('table');
		$this->load->model('m_utils');
		$this->load->model('mf_aula/m_aula');
		$this->load->model('mf_persona/m_persona');
		$this->load->model('mf_matricula/m_matricula');
	}
	
	public function index(){
	    $data['tablaAula'] = $this->createTablaAula(array());
	    
	    $sedes = $this->createComboSedes();
	    $data['sedes'] = $sedes;
	    $data['docentes'] = __buildComboByRol(ID_ROL_DOCENTE, 'simple');
	    $cursos = $this ->createComboCursos();
	    $data['cursos'] = $cursos;
	    
	    //ENVIAMOS LA DATA A LA VISTA
	    $idRol = _getSesion('id_rol');
	    $rolSistemas   = $this->m_utils->getSistemasByRol($idRol);
	    $data['apps']    = $this->lib_utils->createSistemas_x_rol($rolSistemas);
		$data['arbolPermisosMantenimiento'] = $this->lib_utils->buildArbolPermisos($idRol);
        $menu = $this->load->view('v_menu1', $data, true);
        $data['menu'] = $menu;
	    $this->load->view('v_profesor_aula',$data);
	}
	
	function getNivelesBySede(){
	    //CAPTURAMOS EL VALOR ENVIADO POR AJAX
	    $idSedeEncryptado = _post('idsede');
	
	    //DESENCRIPTAMOS EL VALOR SELECCIONADO
	    $idSede = _simpleDecryptInt($idSedeEncryptado);
	    //HACES TUS METODOS
	    $niveles = $this->createComboNivelesBySede($idSede);
	
	    //REGRESAS AL AJAX TUS VARIABLES EN UN ARRAY
	    $data['comboNiveles'] = $niveles;
	
	    //UNICAMENTE PARA RETORNAR VALORES DEL CONTROLADOR AL JAVASCRIPT (APLICATION -> PUBLIC)
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGradosByNivel(){
	    $idSede  = _simpleDecryptInt(_post('idsede'));
	    $idNivel = _simpleDecryptInt(_post('idnivel'));
	
	    $grados = $this->createComboGradosbyNivel($idNivel,$idSede);
	    
	    $data['comboGrados'] = $grados;
	
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAulasByGrado(){
	    $idSede  = _simpleDecryptInt(_post('idsede'));
	    $idNivel = _simpleDecryptInt(_post('idnivel'));
	    $idGrado = _simpleDecryptInt(_post('idgrado'));
	
	    $aulas = $this->m_aula->getAllAulasByGradoProfesor($idSede,$idNivel,$idGrado);
	    $data['tablaAula'] = $this->createTablaAula($aulas);
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function createTablaAula($data){
	    $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tbAula">',
	                 'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0_1 = array('data' => '#');
	    $head_0   = array('data' => 'Nombre');
	    $head_1   = array('data' => 'Matriculados');
	    $head_2   = array('data' => 'Vacantes');
	    $head_3   = array('data' => 'Cant. Profesores');
	    $head_4   = array('data' => 'Edicion');
	    $this->table->set_heading($head_0_1, $head_0, $head_1, $head_2, $head_3, $head_4);
	    $cont = 0;
	    foreach ($data as $row){
	        $cont++;
	        $idAulaEnc=_simple_encrypt($row->nid_aula);
	        $row_0 = array('data' => $cont);
	        $row_1 = array('data' => $row->desc_aula);
	        $row_2 = array('data' => '<a style="color: rgba(0, 0, 0, 0.54);text-decoration: none;" href="javascript:void(0)" onclick="verAlumnosAula(\''.$idAulaEnc.'\')">'.$row->capa_actual.'/'.$row->capa_max.'</a>');
	        $row_3 = array('data' => $row->capa_max-$row->capa_actual);
	        $row_4 = array('data' => '<a style="color: rgba(0, 0, 0, 0.54);text-decoration: none;" href="javascript:void(0)" onclick="getDocentesCursosByAula(\''.$idAulaEnc.'\')">'.$row->num_profesores.'</a>');
	        $row_5 = array('data' => '<button onclick="abrilModalAgregarProfesores(\''.$idAulaEnc.'\')">AGREGAR</button>');
	        
	        $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5);
	    }
	    $table = $this->table->generate();
	    return $table;
	}
	
	function getDocentesCursosByAula(){
	    $idAula   = _simpleDecryptInt(_post('idaula'));
	    $docentes = $this->m_persona->getProfesoresCursosByAula($idAula);
	    $data['tablaDocentes'] = $this->createTablaProfesoresAulaCurso($docentes);
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function createTablaProfesoresAulaCurso($data){
	    $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                 data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                 id="tbProfesorAulaCurso">',
	                'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => 'N�');
	    $head_1 = array('data' => 'Curso');
	    $head_2 = array('data' => 'Docente');
	    $this->table->set_heading($head_0, $head_1, $head_2);
	    $cont = 1;
	    foreach ($data as $row){
	        $row_0 = array('data' => $cont);
	        $row_1 = array('data' => $row->desc_curso);
	        $row_2 = array('data' => $row->nombrecompleto);
	        $this->table->add_row($row_0, $row_1, $row_2);
	    }
	    $table = $this->table->generate();
	    return $table;
	}
	
	function getAlumnosAula(){
	    $idAula   = _simpleDecryptInt(_post('idaula'));
	    $docentes = $this->m_matricula->getAlumnosByAulaLista($idAula);
	    $data['tablaAlumnos'] = $this->createTableAlumnos($docentes);
	
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function createTableAlumnos($alumnos){
	    $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tbAlumnosAula">',//este id se lee en el js
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0_1 = array('data' => '#', 'style' => 'text-align:left');
	    $head_0   = array('data' => 'Nombre', 'style' => 'text-align:left', 'data-sortable' => 'true');
	    $head_1   = array('data' => 'Ap.Paterno', 'style' => 'text-align:left', 'data-sortable' => 'true');
	    $head_2   = array('data' => 'Ap.Materno', 'style' => 'text-align:left', 'data-sortable' => 'true');
	    $this->table->set_heading($head_0_1, $head_0, $head_1, $head_2);
	    $i = 1;
	    foreach ($alumnos as $row){
	        $row_0_1 = array('data' => $i);
	        $row_0   = array('data' => utf8_decode($row->nom_persona));
	        $row_1   = array('data' => utf8_decode($row->ape_pate_pers));
	        $row_2   = array('data' => utf8_decode($row->ape_mate_pers));
	        $this->table->add_row($row_0_1, $row_0, $row_1, $row_2);
	        $i++;
	    }
	    $table = $this->table->generate();
	    return $table;
	}
	
	function insertarProfesor(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try {
	        $idDocente = _simpleDecryptInt(_post('iddocente'));
	        $idCurso   = _simpleDecryptInt(_post('idcurso'));
	        $idAula    = _simpleDecryptInt(_post('idaula'));
	        if($idDocente == null || $idCurso == null || $idAula == null){
	            throw new Exception(ANP);
	        }
	        $dataInsert = array(
	            '_id_persona' => $idDocente,
	            '_id_curso'   => $idCurso,
	            '_id_aula'    => $idAula,
	        );
	         
	        $data = $this->m_persona->insertDocenteAulaCurso($dataInsert);
	         
	        if($data['error'] == EXIT_SUCCESS){
	            $idSede  = _simpleDecryptInt(_post('idsede'));
	            $idNivel = _simpleDecryptInt(_post('idnivel'));
	            $idGrado = _simpleDecryptInt(_post('idgrado'));
	
	            $aulas = $this->m_aula->getAllAulasByGradoProfesor($idSede,$idNivel,$idGrado);
	            $data['tablaAula'] = $this->createTablaAula($aulas);
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	//COMBOS
	function createComboSedes(){
	    $sedes = $this->m_utils->getSedes();
	    $opcion = '';
	    foreach ($sedes as $sed){
	        $idSede = _simple_encrypt($sed->nid_sede);
	        $opcion .= '<option value="'.$idSede.'">'.strtoupper($sed->desc_sede).'</option>';
	    }
	    return $opcion;
	}
	
	function createComboNivelesBySede($idSede){
	    $sedes = $this->m_utils->getNivelesBySede($idSede);
	    $opcion = '';
	    foreach ($sedes as $sed){
	        $idNivel = _simple_encrypt($sed->nid_nivel);
	        $opcion .= '<option value="'.$idNivel.'">'.$sed->desc_nivel.'</option>';
	    }
	    return $opcion;
	}
	
	function createComboGradosbyNivel($idNivel,$idSede){
	    $grados = $this->m_utils->getGradosByNivel($idNivel, $idSede);
	    $opcion = '';
	    foreach ($grados as $grad){
	        $opcion .= '<option value="'._simple_encrypt($grad->nid_grado).'">'.strtoupper($grad->desc_grado).'</option>';
	    }
	    return $opcion;
	}
	
	function createComboAulasbyGrado($idNivel,$idSede, $idGrado){
	    $aulas = $this->m_matricula->getAulasByGrado($idNivel, $idSede, $idGrado);
	    $opcion = '';
	    foreach ($aulas as $aul){
	        $opcion .= '<option value="'._simple_encrypt($aul->nid_aula).'">'.strtoupper($aul->desc_aula).'</option>';
	    }
	    return $opcion;
	}

	function createComboCursos(){
	    $cursos = $this->m_utils->getCursos();
	    $opcion = '';
	    foreach ($cursos as $var) {
	        $opcion	.= '<option value='._simple_encrypt($var->id_curso).'>'.strtoupper($var->curso).'</option>';
	    }
	    return $opcion;
	}
}