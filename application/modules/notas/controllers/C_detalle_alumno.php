<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_detalle_alumno extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('m_detalle_alumno');
        $this->load->library('table');
        
        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_REPORTE, NOTAS_ROL_SESS);
        $this->_idAlumno   = _getSesion('id_alumno');
        $this->_idAula     = _getSesion('id_aula');
        $this->_year       = _getSesion('year');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);
        if($this->_idAlumno == null || $this->_idAula == null || $this->_year == null) {
            $this->session->sess_destroy();
            Redirect(RUTA_SMILEDU, false);
       }
    }

    public function index() {
        $data['titleHeader']      = 'Detalle Alumno';
        $data['ruta_logo']        = MENU_LOGO_NOTAS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
        $data['nombre_logo']      = NAME_MODULO_NOTAS;
        $data['return']           = '';
        $data['barraSec'] = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                 <a href="#tab-1" class="mdl-layout__tab is-active">Notas</a>
                                 <a href="#tab-2" class="mdl-layout__tab"></a>
                                 <a href="#tab-3" class="mdl-layout__tab"></a>
                             </div>';
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos(_getSesion(NOTAS_ROL_SESS), ID_SISTEMA_NOTAS, NOTAS_FOLDER);
    	$rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $menu = $this->load->view('v_menu', $data, true);
        $data['menu']             = $menu;
        $data['cmbCursos']        = __buildComboCursosUgelEquiv(null);
        $data['cmbBimestres']     = __buildComboBimestres();
        $data['tableNotasCursos'] = $this->tbNotasCursos(null, null);
        $data['nombreAlumno']     = _getSesion('nom_alumno');
        $this->load->view('v_detalle_alumno', $data);
    }
    
    function tbNotasCursos($idBimestre, $idCurso) {
        $arrayNotas = $this->m_detalle_alumno->getNotasCursoBimestre($this->_idAlumno, $this->_year, $this->_idAula, $idBimestre, $idCurso); 
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbNotasCursos" data-show-columns="false">',
                      'table_close' => '</table>');  
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'    , 'class' => 'text-left');
        $head_1 = array('data' => 'Curso', 'class' => 'text-center');
        $head_2 = array('data' => 'Nota' , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $val = 0;
        foreach($arrayNotas as $row) {
            $val++;       
            $row_0 = array('data' => $val, 'class' => 'text-left');
            $row_1 = array('data' => $row['desc_curso'], 'class' => 'text-center');
            $row_2 = array('data' => $row['promedio']  , 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
        }
        $tabla = $this->table->generate();
        return $tabla;
    } 
    
    function getCursoNotas() {
        $idCurso    = _decodeCI(_post('idCurso'));
        $idBimestre = _decodeCI(_post('idBimestre'));     
        $idCur = ($idCurso    != null) ? $idCurso    : $idCurso    = null;
        $idBim = ($idBimestre != null) ? $idBimestre : $idBimestre = null;

        $data['tablaCursoNotas'] = $this->tbNotasCursos($idBim, $idCur);
        echo json_encode(array_map('utf8_encode', $data));
    }
}
