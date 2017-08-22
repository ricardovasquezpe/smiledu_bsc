<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_asig_alum_curso extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->helper('html');
        $this->load->model('../m_utils');
        $this->load->model('m_asig_alum_curso');
        $this->load->library('table');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);

        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_ASIGNAR_ALUMNO, NOTAS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);
    }

    public function index() {
        $idUserSess = $this->_idUserSess;
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_NOTAS, NOTAS_FOLDER);
        $data['titleHeader']      = 'Asignar alumnos';
        $data['ruta_logo']        = MENU_LOGO_NOTAS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
        $data['nombre_logo']      = NAME_MODULO_NOTAS;

        $rolSistemas           = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
        $data['apps']          = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']          = $this->load->view('v_menu', $data, true);
        $data['cmbCursos']     = __buildComboCursosUgelEquiv(null);
        $data['cmbGradoNivel'] = __buildComboGradoNivel_All();
        $data['cmbYears']      = __buildComboYearsAcademicos();
        $data['cmbSedes']      = __buildComboSedes(null);
        
        $data2 = _searchInputHTML('Busca tus Cursos');
        $data = array_merge($data, $data2);

        $this->load->view('v_asig_alum_curso', $data);
    }

    function getTbGrupos() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGrado'));
            $idCurso = _decodeCI(_post('idCurso'));
            $year    = _decodeCI(_post('year'));
            $idSede  = _decodeCI(_post('idSede'));
            if($idGrado == null || $idCurso == null || $year == null || $idSede == null) {
                throw new Exception(ANP);
            }

            $data['error']        = EXIT_SUCCESS;
            $data['tbGruposHtml'] = $this->tbGruposHTML($idCurso, $idGrado, $year, $idSede);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tbGruposHTML($idCurso, $idGrado, $year, $idSede) {
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination="true" id="tbGrupos" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);

        $head_0 = array('data' => '#'               , 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre de Grupo' , 'class' => 'text-left');
        $head_2 = array('data' => 'Capacidad'       , 'class' => 'text-right');
        $head_3 = array('data' => 'Aula'            , 'class' => 'text-left');
        $head_4 = array('data' => 'Acci&oacute;n'   , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $i = 1;

        $grupos = $this->m_asig_alum_curso->getGruposCursos($idCurso, $idGrado, $year, null, $idSede);
        if($grupos == null) {
            return;
        }
        foreach ($grupos as $row) {
            if($row['flg_pen_cambio'] == FLG_CAMBIO_PENDIENTE) {
                $icon_alert = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="getModalAnuncio($(this))" title="Alerta">
                               <i class="mdi mdi-error"></i>
                           </button>';
            } else { $icon_alert = ''; }
 
            $row_0 = $i;
            $row_1 = array('data' => $icon_alert." ".$row['nombre_grupo']          , 'class' => 'text-left');
            $row_2 = array('data' => $row['cant_alumno'].'/'.$row['limite_alumno'] , 'class' => 'text-right btnID');
            $row_3 = array('data' => $row['desc_aula'] , 'class' => 'text-left');
            
            $action = '<button class="mdl-button mdl-js-button mdl-button--icon" data-id_main = "'._encodeCI($row['nid_main']).'" onclick="getAulas($(this))" title="ver Alumnos">
                           <i class="mdi mdi-visibility"></i>
                       </button>
                       <button class="mdl-button mdl-js-button mdl-button--icon" data-id_main = "'._encodeCI($row['nid_main']).'" onclick="getModalAlumnoGrupo($(this))" title="ver Alumnos">
                           <i class="mdi mdi-supervisor_account"></i>
                       </button>';
            $row_4 = array('data' => $action ,'class' => 'text-center');
   
            $this->table->add_row($row_0, $row_1, $row_2, $row_3,$row_4);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function getTbAulas() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGrado'));
            $year    = _decodeCI(_post('year'));
            $idSede    = _decodeCI(_post('idSede'));
            if($idGrado == null || $year == null || $idSede == null) {
                throw new Exception(ANP);
            }      
            $data['error']       = EXIT_SUCCESS;
            $data['tbAulasHtml'] = $this->tbAulasHTML($idGrado, $year, $idSede);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tbAulasHTML($idGrado, $year, $idSede) {
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination="true" id="tbAulas" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        
        $head_0 = array('data' => '#'               , 'class' => 'text-left');
        $head_1 = array('data' => 'Aula'            , 'class' => 'text-left');
        $head_2 = array('data' => 'Capacidad'       , 'class' => 'text-right');
        $head_3 = array('data' => 'Acci&oacute;n'   , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $i = 1;
  
        $arrayAula = $this->m_asig_alum_curso->getAulas($idGrado, $year, $idSede);

        foreach ($arrayAula as $row) {
            $row_0 = $i;
            $row_1 = array('data' => $row['desc_aula'], 'class' => 'text-left');
            $row_2 = array('data' => $row['cant_alumno'].'/'.$row['capa_max']  , 'class' => 'text-right btnID');

            $action = '<button class="mdl-button mdl-js-button mdl-button--icon" data-id_aula="'._encodeCI($row['nid_aula']).'" onclick="getModalAlumnos($(this))" title="ver Alumnos">
                           <i class="mdi mdi-people_outline"></i>
                       </button>';            
            $row_3 = array('data' => $action ,'class' => 'text-center');
             
            $this->table->add_row($row_0, $row_1, $row_2, $row_3);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function getTbAlumnosGrupo() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $year    = _decodeCI(_post('year'));
            $idMain  = _decodeCI(_post('idMain'));
            $idCurso = _decodeCI(_post('idCurso'));
            if($year == null || $idMain ==  null || $idCurso == null) {
                throw new Exception(ANP);
            }
            $busqueda = '';
            $data['error']        = EXIT_SUCCESS;
            $data['tbAlumnosGrupoHtml'] = $this->tbAlumnosGrupoHTML($year, $idMain, $idCurso, $busqueda);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tbAlumnosGrupoHTML($year, $idMain, $idCurso, $busqueda) {
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                      data-pagination="true" id="tbAlumnoGrupo" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
    
        $head_0 = array('data' => '#'           , 'class' => 'text-left');
        $head_1 = array('data' => 'Alumno'      , 'class' => 'text-left');
        $head_2 = array('data' => 'Aula'        , 'class' => 'text-left');
        $head_3 = array('data' => 'Seleccionar' , 'class' => 'text-center', 'data-field' => 'checkbox');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $i    = 1;
        $val  = 0;
        $alum = $this->m_asig_alum_curso->getAlumnosGrupos($year, $idMain, $idCurso, $busqueda);
        foreach ($alum as $row) {
            if($row['flg_pen_cambio'] == FLG_CAMBIO_PENDIENTE) {
                $fondo = 'paintRowAlum';
                $check = '';
            } else {
                $fondo = '';
                $check = 'checked';
            }

            $imageStudent = '<img alt="Student"  src="'.$row['foto_persona'].'" width=30 height=30 class="img-circle m-r-10">
                             <p class="classroom-value" style="display: inline">'.$row['nombre_corto'].'</p>';

            $checkbox = '<label for="ch_'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect check_alumno">
                            <input type="checkbox" id="ch_'.$val.'" '.$check.'  class="mdl-checkbox__input chkbox" onclick="handleCheckAlumnoGrupo($(this))"
                                data-id_alumno="'._encodeCI($row['__id_alumno']).'" data-id_main = "'._encodeCI($idMain).'">
                         </label>';
            $aula     = '('.$row['desc_aula'].')';
            $val++;
            $row_0 = array('data' => $i            , 'class' => 'text-left '.$fondo);
            $row_1 = array('data' => $imageStudent , 'class' => 'text-left '.$fondo);
            $row_2 = array('data' => $aula         , 'class' => 'text-left '.$fondo);
            $row_3 = array('data' => $checkbox     , 'class' => 'text-center btnM '.$fondo);
         
            $this->table->add_row($row_0, $row_1, $row_2, $row_3);
            $i++; 
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function getTbAlumnosAula() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAula  = _decodeCI(_post('idAula'));
            $year    = _decodeCI(_post('year'));
            $idCurso = _decodeCI(_post('idCurso'));
            $idMain  = _decodeCI(_post('idMain'));

            if($idAula == null || $year == null || $idCurso == null || $idMain == null) {
                throw new Exception(ANP);
            }
            $busqueda = '';
            $data['error']        = EXIT_SUCCESS;
            $data['tbAlumnosHtml'] = $this->tbAlumnosAulaHTML($idAula, $year, $idCurso, $idMain, $busqueda);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tbAlumnosAulaHTML($idAula, $year, $idCurso, $idMain, $busqueda) {
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination="true" id="tbAlumnos" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
    
        $head_0 = array('data' => '#'           , 'class' => 'text-left');
        $head_1 = array('data' => 'Alumno'      , 'class' => 'text-left');
        $head_2 = array('data' => 'Seleccionar' , 'class' => 'text-center', 'data-field' => 'checkbox');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $i    = 1;
        $val  = 0;
        $alum = $this->m_asig_alum_curso->getEstudiantes($idAula, $year, $idCurso, $idMain, $busqueda);
        foreach ($alum as $row) {
            if($row['flg_pen_cambio'] == 1) {
                $fondo = 'paintGroupRowAlum';                
            } else {
                $fondo = '';
            
            }
            $imageStudent = '<img alt="Student" src="'.$row['foto_persona'].'" width=30 height=30 class="img-circle m-r-10">
                                 <p class="classroom-value" style="display: inline;">'.$row['nombre_corto'].'</p>';
            
            
            $checkbox = '<label for="chk_'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect check_alumno">
                                     <input type="checkbox" id="chk_'.$val.'" class="mdl-checkbox__input chkbox" onclick="handleCheckAlumno($(this))"
                                            data-id_alumno="'._encodeCI($row['nid_persona']).'">
                         </label>';
            
            $val++;

            
            $row_0 = array('data' => $i            , 'class' => 'text-left '.$fondo);
            $row_1 = array('data' => $imageStudent , 'class' => 'text-left '.$fondo); 
            $row_2 = array('data' => $checkbox     , 'class' => 'text-center btnM '.$fondo);
             
            $this->table->add_row($row_0, $row_1, $row_2);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function buscarAlumnoAsignar() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAula  = _decodeCI(_post('idAula'));
            $year    = _decodeCI(_post('year'));
            $idMain  = _decodeCI(_post('idMain'));
            $idCurso = _decodeCI(_post('idCurso'));
            $buscar  = _post('buscar');
    
            if($buscar == null || $idAula == null || $year == null || $idMain ==  null || $idCurso == null) {
                throw new Exception(ANP);
            }
    
            if(strlen($buscar) < 3) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tbAlumnosHtml'] = $this->tbAlumnosAulaHTML($idAula, $year, $idMain, $idCurso, $buscar);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buscarAlumnoGrupo() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $year    = _decodeCI(_post('year'));
            $idMain  = _decodeCI(_post('idMain'));
            $idCurso = _decodeCI(_post('idCurso'));
            $buscar  = _post('buscar');
    
            if($buscar == null || $year == null || $idMain ==  null || $idCurso == null) {
                throw new Exception(ANP);
            }
            if(strlen($buscar) < 3) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tbAlumnosGrupoHtml'] = $this->tbAlumnosGrupoHTML($year, $idMain, $idCurso, $buscar);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function asignarAlumno() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idMain      = _decodeCI(_post('idMain'));
            $arrayAlumno = _post('arrayAlumno');
            $idGrado     = _decodeCI(_post('idGrado'));
            $idCurso     = _decodeCI(_post('idCurso'));
            $year        = _decodeCI(_post('year'));
            $idSede      = _decodeCI(_post('idSede'));
            $totalPorIngresar = 0;
            if($idGrado == null || $idCurso == null || $year == null || $idMain == null) {
                throw new Exception(ANP);
            } 
            if($arrayAlumno == null || $idSede == null) {
                throw new Exception(ANP);
            }         
            if(!is_array($arrayAlumno)) {
                throw new Exception(ANP);
            }
            
            $valLimite = $this->m_asig_alum_curso->cantidadAlumGrupo($idMain);
            if($valLimite == null) {
                 throw new Exception(ANP);
            }
            $countArrayAlumno = count($arrayAlumno);
            $totalPorIngresar = $countArrayAlumno + $valLimite->cant_alumno;
           
                if($totalPorIngresar > $valLimite->limite_alumno) {
                    throw new Exception("El grupo ya se encuentra al l&iacute;mite, elija otro grupo por favor");    
            }
            
            foreach($arrayAlumno as $row) {      
                $idAlumno  = _decodeCI($row['__id_alumno']);
                $flgCambio = $this->m_asig_alum_curso->flgPendCambio($idCurso, $idAlumno, $idMain);

                if($flgCambio['flg_pen_cambio'] != null) {
                    throw new Exception("Esta asignado en un grupo");
                }
            }
               
            $data = $this->m_asig_alum_curso->asignarAlumno($arrayAlumno, $idMain);
            if($data['error'] == EXIT_SUCCESS) {
                $data['tbGruposHtml'] = $this->tbGruposHTML($idCurso, $idGrado, $year, $idSede);
            } 
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function desasigAlumnoGrupo() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idMain   = _decodeCI(_post('idMain'));
            $idAlumno = _decodeCI(_post('idAlumno'));
            $year     = _decodeCI(_post('year'));
            $idCurso  = _decodeCI(_post('idCurso'));
            $idGrado  = _decodeCI(_post('idGrado'));
            $idSede      = _decodeCI(_post('idSede'));
            $flgCambio = $this->m_asig_alum_curso->flg_cambio($idAlumno, $idMain);

            if($flgCambio == FLG_CAMBIO_PENDIENTE) {
                $arrayDato = array('flg_pen_cambio' => FLG_CAMBIO_EFECTUADO);
            } else {
                $arrayDato = array('flg_pen_cambio' => FLG_CAMBIO_PENDIENTE);
            }

            if($idAlumno == null || $idMain == null || $idSede == null) {
                throw new Exception(ANP);
            }
            $busqueda = '';
            $data = $this->m_asig_alum_curso->cambioAlumnoGrupo($idAlumno, $idMain, $arrayDato);
            $data['tbAlumnosGrupoHtml'] = $this->tbAlumnosGrupoHTML($year, $idMain, $idCurso, $busqueda);
            $data['tbGruposHtml']       = $this->tbGruposHTML($idCurso, $idGrado, $year, $idSede);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}