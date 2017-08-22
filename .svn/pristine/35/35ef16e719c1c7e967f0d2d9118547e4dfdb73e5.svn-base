<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_detalle_curso extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    private $_idMain  = null;
    private $_idAula  = null;
    private $_year    = null;
    private $_idCurso = null;
    private $_idGrado = null;
    private $_estudiantesArry = array();
    private $_fechas  = array();
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('m_detalle_curso');
        $this->load->library('table');
        
        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_MIS_CURSOS, NOTAS_ROL_SESS);
        $this->_idMain     = _getSesion('id_main');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);
        if($this->_idMain == null) {
            $this->session->sess_destroy();
            Redirect(RUTA_SMILEDU, false);
        }
        $aulaDatos = $this->m_detalle_curso->getDataAulaByMain($this->_idMain);
        $this->_idAula  = $aulaDatos['nid_aula'];
        $this->_year    = $aulaDatos['year'];
        $this->_idCurso = $aulaDatos['nid_curso'];
        $this->_idGrado = $aulaDatos['nid_grado'];
        
        $this->_estudiantesArry = $this->m_detalle_curso->getEstudiantesByCurso($this->_idMain);
    }

    public function index() {
        $data['titleHeader']      = 'Matem&aacute;ticas';
        $data['ruta_logo']        = MENU_LOGO_NOTAS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
        $data['nombre_logo']      = NAME_MODULO_NOTAS;
        $data['return']           = '';
        $data['barraSec'] = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                 <a href="#tab-1" class="mdl-layout__tab is-active">Calificar</a>
                                 <a href="#tab-2" class="mdl-layout__tab">Asistencia</a>
                                 <a href="#tab-3" class="mdl-layout__tab">Estudiantes</a>
                             </div>';
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos(_getSesion(NOTAS_ROL_SESS), ID_SISTEMA_NOTAS, NOTAS_FOLDER);
    	$rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $menu = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        
        $data['estuAsist']    = $this->buildEstudiantesForCalificar_HTML($this->_estudiantesArry);
        $data['estuList']     = $this->buildEstudiantesListado_HTML();
        $data['estudiantes']  = $this->buildHTML_Estudiantes();
        $data['cmbCicloAcad'] = __buildComboCiclosAcad();
        
        $data['cant_asist']   = $this->m_detalle_curso->checkIfHayAsistenciaHoy($this->_idMain);
        
        //COMBOS MATRIZ EVALUACION
        $data['cmbCompetencias'] = __buildComboCompetencias($this->_idGrado, $this->_idCurso, $this->_year);
        $this->load->view('v_detalle_curso', $data);
    }
    
    function getAsistenciasEvents() {
        $eventos = $this->m_detalle_curso->getListaEventosAsistencia($this->_idMain, $this->_idAula, $this->_year);
        $arry = array();
        $val = 1;
        foreach ($eventos as $event) {
            $rw = array();
            $rw['id']    = $val;
            $rw['title'] = 'Asistencia '.$event['fec_normal'];
            $rw['class'] = "event-success";
            $rw['start'] = $event['fecha_asistencia'].'000';
            array_push($arry, $rw);
            $val++;
        }
        echo json_encode($arry, JSON_NUMERIC_CHECK);
    }
    
    function getEstudiantesForAsistencia() {
        $data['estuAsist']    = $this->buildEstudiantesForCalificar_HTML($this->_estudiantesArry);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildEstudiantesForCalificar_HTML($estudiantesAsist) {
        $htmlFinal = null;
        foreach ($estudiantesAsist as $estu) {
            $cssEstado = $this->getCSS_Estado($estu['estado']);
            $htmlFinal .= '<div class="mdl-student_assitence aluID" data-alu_id="'._encodeCI($estu['nid_persona']).'" onclick="openModalForAsistencia($(this))">
                               <div class="mdl-card estado_color '.$cssEstado.'" data-estado_aux="'.$cssEstado.'">
                                   <div class="mdl-card__supporting-text br">
                                       <div class="assistance"></div>
                                       <img alt="Alumno" src="'.$estu['foto_persona'].'">
                                       <h2 class="nom_alum">'.explode(' ', $estu['nom_persona'])[0].'</h2>
                                       <h2 class="nom_alum2">'.strtoupper($estu['ape_pate_pers']).' '.substr($estu['ape_mate_pers'], 0, 1).'.</h2>
                                   </div>
                               </div>
                           </div>';
        }
        return $htmlFinal;
    }
    
    function getCSS_Estado($estado) {
        $cssEstado = null;
        switch ($estado) {
            case ASISTENCIA_PRESENTE     : $cssEstado = ASISTENCIA_PRESENTE_CSS;     break;
            case ASISTENCIA_TARDE        : $cssEstado = ASISTENCIA_TARDE_CSS;        break;
            case ASISTENCIA_TARDE_JUSTIF : $cssEstado = ASISTENCIA_TARDE_JUSTIF_CSS; break;
            case ASISTENCIA_FALTA        : $cssEstado = ASISTENCIA_FALTA_CSS;        break;
            case ASISTENCIA_FALTA_JUSTIF : $cssEstado = ASISTENCIA_FALTA_JUSTIF_CSS; break;
        }
        return $cssEstado;
    }
    
    function guardarAsistencia() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $tipoAsistencia = _decodeCI(_post('tipoAsist'));
            $idAlumno       = _decodeCI(_post('idAlumno'));
            if($tipoAsistencia == null || $idAlumno == null) {
                throw new Exception(ANP);
            }
            $tiposAsist = explode(';', ASISTENCIAS_TIPOS);
            if(!in_array($tipoAsistencia, $tiposAsist)) {
                throw new Exception(ANP);
            }
            //$newEstado = $tipoAsistencia[0];
            //VALIDAR SI EXISTE UPDATE SI NO INSERT
            if($this->m_detalle_curso->checkIfAsistenciaExiste($idAlumno, $this->_idAula, date('Y-m-d'), $this->_idMain)) {//UPDATE
                $arryUpdate = array(
                    "estado"          => $tipoAsistencia,
                    "flg_justificado" => null
                );
                $data = $this->m_detalle_curso->actualizarAsistencia($arryUpdate, $idAlumno, $this->_idAula, date('Y-m-d'), $this->_idMain);
            } else {//INSERT
                $arryInsert = array(
                    "__id_alumno"      => $idAlumno,
                    "__id_aula"        => $this->_idAula,
                    "__year_academico" => $this->_year,
                    "fecha_asistencia" => date('Y-m-d'),
                    "estado"           => $tipoAsistencia,
                    "__nid_main"       => $this->_idMain
                );
                $data = $this->m_detalle_curso->insertarAsistencia($arryInsert);
            }
            if($data['error'] == EXIT_SUCCESS) {
                $data['newEstado'] = $this->getCSS_Estado($tipoAsistencia);
                $index = $this->getEstudianteFromArray($idAlumno);
                $this->_estudiantesArry[$index]['estado'] = $tipoAsistencia;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getEstudianteFromArray($idPersona) {
        $index = 0;
        foreach ($this->_estudiantesArry as $estu) {
            if($estu['nid_persona'] == $idPersona) {
                return $index;
            }
            $index++;
        }
    }
    
    function buildEstudiantesListado_HTML() {
        $htmlFinal = null;
        foreach ($this->_estudiantesArry as $estu) {
            $htmlFinal .= '<li class="">
                               <div class="mdl-list__item">
                                   <span class="mdl-list__item-primary-content">
                                       <img alt="Alumno" src="'.$estu['foto_persona'].'" class="mdl-list__item-avatar">
                                       <span>'.$estu['nom_persona'].' '.$estu['ape_pate_pers'].'</span>
                                   </span>
                               </div>
                           </li>';
        }
        return $htmlFinal;
    }
    
    function usarInstrumento() {
        $idCompetencia = _decodeCI(_post('idCompetencia'));
        $idCapacidad   = _decodeCI(_post('idCapacidad'));
        $idIndicador   = _decodeCI(_post('idIndicador'));
        $idInstrumento = _decodeCI(_post('idInstrumento'));
        if($idCompetencia == null || $idCapacidad == null || $idIndicador == null || $idInstrumento == null) {
            throw new Exception(ANP);
        }
        $instru = explode(';', $idInstrumento);
        $idInstrumento = $instru[0];
        $correlativo   = $instru[1];
        $data['estuInstru'] = $this->buildEstudiante_ByInstrumento_HTML($idInstrumento, $idCompetencia, $idCapacidad, $idIndicador, $correlativo);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildEstudiante_ByInstrumento_HTML($idInstrumento, $idCompetencia, $idCapacidad, $idIndicador, $correlativo) {
        $htmlFinal = null;
        foreach ($this->_estudiantesArry as $estu) {
            $nota = $this->m_detalle_curso->getNotaByEstuInstru($this->_idMain, $idInstrumento, $idCompetencia, $idCapacidad, $idIndicador, $correlativo, $estu['nid_persona']);
            $cssNota = ($nota == null) ? null : (($nota <= 10.49) ? 'mdl-student_red' : (($nota >= 10.50 && $nota <= 16.49) ? 'mdl-student_ambar' : 'mdl-student_green' ));
            $cssEstado = $this->getCSS_Estado($estu['estado']);
            $promedioResult = $this->m_detalle_curso->getPromedioValorByInstruByEstu($estu['nid_persona'], $this->_idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
            $onClickEvent = 'onclick="openModalVerInstrumento($(this))" ';
            $onClickEventAwards = 'onclick="openModalExtras($(this));addMdlAwards();" ';
            if($estu['estado'] == ASISTENCIA_FALTA) {
                $onClickEvent = null;
                $onClickEventAwards = null;
            }
            $cantAwards = $this->m_detalle_curso->getCountAwardsPositivos($this->_idMain, $estu['nid_persona']);
            $id = _encodeCI($estu['nid_persona']);
            $htmlFinal .= '     <div class="mdl-card mdl-note '.$cssNota.' card_award '.$cssEstado.'" data-alu_id2="'.$id.'" >
                                   <div class="mdl-card__supporting-text br-t aluID" data-alu_id="'.$id.'" '.$onClickEvent.'>
                                       <img alt="Alumno" src="'.$estu['foto_persona'].'">
                                       <h2 class="nom_alum">'.explode(' ', $estu['nom_persona'])[0].' '.strtoupper($estu['ape_pate_pers']).' '.substr($estu['ape_mate_pers'], 0, 1).'.'.'</h2>
                                       <div class="mdl-value">'.$promedioResult.'</div>
                                   </div>
                                   <div class="mdl-card__actions">
                                        <span class="mdl-student__option">
                                            <button class="mdl-button mdl-js-button mdl-button--icon" '.$onClickEventAwards.'>
                                                <i class="mdi mdi-thumb_up"></i>
                                            </button>
                                            <label class="awards-points">'.$cantAwards.'</label>
                                        </span>
                                   </div>
                                   <div class="mdl-card__menu">
                                        <div class="mdl-button__circle nota_css">'.$nota.'</div>  
                                   </div>
                               </div>';
        }
        return $htmlFinal;
    }
    
    function getAsistenciaByFecha() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $fecha = _post('fecha');
            $fecha = date("Y-m-d", ($fecha / 1000) );
            $asistencia = $this->m_detalle_curso->getAsistenciaByFecha($fecha, $this->_idAula, $this->_idMain);
            $data['tbAsistentes'] = $this->buildHTML_AsistenciaByFecha($asistencia);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildHTML_AsistenciaByFecha($asistencia) {
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                 data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                 id="tbAsistFech">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'           , 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre'      , 'class' => 'text-left');
        $head_2 = array('data' => 'Asistencia'  , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $i = 1;
        foreach ($asistencia as $row){
            $imageStudent = '<img alt="Student" src="'.$row['foto_persona'].'" width=25 height=25
		                          class="img-circle m-r-10">
		                         <p class="classroom-value" style="display: inline" style="cursor:pointer">'.$row['nombre_estudiante'].'</p>';
            $row_0 = $i;
            $row_1 = array('data' => $imageStudent , 'class' => 'text-left');
            $cssEstado = $this->getCSS_Estado($row['estado']);
            $row_2 = array('data' => '<span class="label-circle '.$cssEstado.'"></span>', 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function buildHTML_Estudiantes() {
        $estudiantes = $this->m_detalle_curso->getEstudiantesByCursoAll($this->_idMain);
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                 data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                 id="tbEstus">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Selec.', 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $i = 1;
        foreach ($estudiantes as $row) {
            $imageStudent = '<img alt="Student" src="'.$row['foto_persona'].'" width=25 height=25
		                          class="img-circle m-r-10">
		                         <p class="classroom-value" style="display: inline">'.$row['ape_pate_pers'].' '.$row['ape_mate_pers'].' '.$row['nom_persona'].'</p>';
            $row_0 = array('data' => $i, 'class' => 'text-left');
            $row_1 = array('data' => $imageStudent, 'class' => 'text-left');
            $radio = '  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="radio_estud_'.$i.'">
                            <input type="radio" class="mdl-radio__button" name="radioVals" data-id_estu="'._encodeCI($row['nid_persona']).'" id="radio_estud_'.$i.'">
                            <span class="mdl-radio__label"></span>
                        </label>';
            $row_2 = array('data' => $radio, 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function getCapacidadesByCompetencia() {
        $idCompetencia = _decodeCI(_post('idCompetencia'));
        if($idCompetencia == null) {
            $data['optCapacidad'] = null;
        }
        $data['optCapacidad'] = __buildComboCapacidades($idCompetencia, $this->_idGrado, $this->_idCurso, $this->_year);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getIndicadoresByCapacidad() {
        $idCompetencia = _decodeCI(_post('idCompetencia'));
        $idCapacidad   = _decodeCI(_post('idCapacidad'));
        if($idCompetencia == null || $idCapacidad == null) {
            $data['optIndicador'] = null;
        }
        $data['optIndicador'] = __buildComboIndicadores($idCompetencia, $idCapacidad, $this->_idGrado, $this->_idCurso, $this->_year);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getInstrumentosByIndicador() {
        $idCompetencia = _decodeCI(_post('idCompetencia'));
        $idCapacidad   = _decodeCI(_post('idCapacidad'));
        $idIndicador   = _decodeCI(_post('idIndicador'));
        if($idCompetencia == null || $idCapacidad == null || $idIndicador == null) {
            $data['optInstrumentos'] = null;
        }
        $data['optInstrumentos'] = __buildComboInstrumentos($this->_idGrado, $this->_idCurso, $idCompetencia, $idCapacidad, $idIndicador, $this->_idMain);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////// GRAFICOS ASISTENCIA /////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function getGraficosAsistencia() {
        $this->_fechas       = $this->m_detalle_curso->getFechasHeatMapAsistencia($this->_idMain);

        $data['pie']        = $this->getEstructuraGraficoAsitPie();
        
        $data['linea']      = $this->getEstructuraGraficoLineaAsist();
        $data['lineaCateg'] = $this->getJsonCategorias();
        
        $data['sexoBarras'] = $this->getEstructuraGraficoBarraAsistSexo();
        
        $data['fechasHeat']  = $this->getFechasHeatMapAsistencia();
        $data['heatMapData'] = $this->getDataHeatMapAsistencia();
        
        $data['fecIni'] = /*_fecha_tabla(*/$this->_fechas[(count($this->_fechas) - 1)]['fecha']/*, 'd/m/Y')*/;
        $data['fecFin'] = /*_fecha_tabla(*/$this->_fechas[0]['fecha']/*, 'd/m/Y')*/;
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGraficosAsistenciaFiltroFechas() {
        $data['error'] = EXIT_ERROR;
        try {
            $fecIni = _post('fecIni');
            $fecFin = _post('fecFin');
            $fecIni = implode('-', array_reverse(explode('/', $fecIni )));
            $fecFin = implode('-', array_reverse(explode('/', $fecFin )));
            if(!__validFecha2($fecIni) || !__validFecha2($fecFin)) {
                throw new Exception('El formato de las fechas es incorrecto');
            }
           
            //Validar orden
            if($fecIni > $fecFin) {
                throw new Exception('La fecha inicial tiene que ser antes o igual que la final');
            }
            //////////////////////////// OK ////////////////////////////
            $tipo = _post('graf');
            if($tipo == null) {
                $data['pie']        = $this->getEstructuraGraficoAsitPie($fecIni, $fecFin);
                
                $data['linea']      = $this->getEstructuraGraficoLineaAsist($fecIni, $fecFin);
                $data['lineaCateg'] = $this->getJsonCategorias($fecIni, $fecFin);
                
                $data['sexoBarras'] = $this->getEstructuraGraficoBarraAsistSexo($fecIni, $fecFin);
                $data['error'] = EXIT_SUCCESS;
            } else {
                if($tipo == '1') {
                    $data['pie']        = $this->getEstructuraGraficoAsitPie($fecIni, $fecFin);
                    $data['error'] = EXIT_SUCCESS;
                } else if($tipo == '2') {
                    $data['linea']      = $this->getEstructuraGraficoLineaAsist($fecIni, $fecFin);
                    $data['lineaCateg'] = $this->getJsonCategorias($fecIni, $fecFin);
                    $data['error'] = EXIT_SUCCESS;
                } else if($tipo == '3') {
                    $data['sexoBarras'] = $this->getEstructuraGraficoBarraAsistSexo($fecIni, $fecFin);
                    $data['error'] = EXIT_SUCCESS;
                } else {
                    $this->_fechas       = $this->m_detalle_curso->getFechasHeatMapAsistencia($this->_idMain);
                    $data['fechasHeat']  = $this->getFechasHeatMapAsistencia();
                    $data['heatMapData'] = $this->getDataHeatMapAsistencia();
                    $data['error'] = EXIT_SUCCESS;
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////// GRAFICO PIE ASISTENCIA //////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function getEstructuraGraficoAsitPie($fecIni = null, $fecFin = null) {//pie_data_asist
        $fecIni = ($fecIni == null) ? $this->_fechas[(count($this->_fechas) - 1)]['fecha'] : $fecIni;
        $fecFin = ($fecFin == null) ? $this->_fechas[0]['fecha']                           : $fecFin;
        $arr  = $this->m_detalle_curso->getPieForAsistByMain($this->_idMain, $fecIni, $fecFin);
        $arry = array();
        $colores = array(ASISTENCIA_PRESENTE => '#8BC34A', ASISTENCIA_TARDE => '#FFEB3B', ASISTENCIA_TARDE_JUSTIF => '#673AB7', ASISTENCIA_FALTA => '#757575', ASISTENCIA_FALTA_JUSTIF => '#FF9800' );
        foreach ($arr as $row) {
            if($row['estado'] == null) {
                continue;
            }
            $rw['name']            = $row['estado'];
            $rw['y']               = $row['cant_asist'];
            $rw['color']           = $colores[$row['estado']];
            $rw['events']['click'] = '';
            $rw['selected']        = false;
            array_push($arry, $rw);
        }
        return json_encode($arry, JSON_NUMERIC_CHECK);
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////// GRAFICO LINEA ASISTENCIA ////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function getEstructuraGraficoLineaAsist($fecIni = null, $fecFin = null) {//linea_data_asist
        $fecIni = ($fecIni == null) ? $this->_fechas[(count($this->_fechas) - 1)]['fecha'] : $fecIni;
        $fecFin = ($fecFin == null) ? $this->_fechas[0]['fecha']                           : $fecFin;
        $arr = $this->m_detalle_curso->getAsistLineaGraph($this->_idMain, $fecIni, $fecFin);
        $arry = array();
        $colores = array(ASISTENCIA_PRESENTE => '#8BC34A', ASISTENCIA_TARDE => '#FFEB3B', ASISTENCIA_TARDE_JUSTIF => '#673AB7', ASISTENCIA_FALTA => '#757575', ASISTENCIA_FALTA_JUSTIF => '#FF9800' );
        foreach ($arr as $row) {
            $rw['name']  = $row['estado'];
            $rw['data']  = explode(',', $row['cant_asist']);
            $rw['color'] = $colores[$row['estado']];
            array_push($arry, $rw);
        }
        return json_encode($arry, JSON_NUMERIC_CHECK);
    }
    
    function getJsonCategorias($fecIni = null, $fecFin = null) {
        $fecIni = ($fecIni == null) ? $this->_fechas[(count($this->_fechas) - 1)]['fecha'] : $fecIni;
        $fecFin = ($fecFin == null) ? $this->_fechas[0]['fecha']                           : $fecFin;
        $cates = $this->m_detalle_curso->getCategoriasGrafLinea($this->_idMain, $fecIni, $fecFin);
        $arry = array();
        foreach ($cates as $row) {
            array_push($arry, $row['fecha_asistencia']);
        }
        return json_encode($arry, JSON_NUMERIC_CHECK);
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////// GRAFICO BARRAS ASISTENCIA //////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function getEstructuraGraficoBarraAsistSexo($fecIni = null, $fecFin = null) {
        $fecIni = ($fecIni == null) ? $this->_fechas[(count($this->_fechas) - 1)]['fecha'] : $fecIni;
        $fecFin = ($fecFin == null) ? $this->_fechas[0]['fecha']                           : $fecFin;
        $data = $this->m_detalle_curso->getDataForGrafBarrasBySexo($this->_idMain, $fecIni, $fecFin);
        $arry = array();
        foreach ($data as $row) {
            $rw['name'] = $row['sexo'];
            $rw['data'] = array($row['falta'], $row['falta_just'], $row['presente'], $row['tardanza'], $row['tardanza_justif']);
            $rw['color'] = $row['color'];
            array_push($arry, $rw);
        }
        return json_encode($arry, JSON_NUMERIC_CHECK);
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////// HEAT MAP ASISTENCIA ////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
        
    function getFechasHeatMapAsistencia() {
        $arry = array();
        $days = explode(';', DIAS_SEMANA);
        foreach ($this->_fechas as $row) {
            $dayofweek = date('w', strtotime($row['fecha']));
            $fecDesc = $days[$dayofweek].' '.date('d/m',strtotime($row['fecha']) );
            array_push($arry, $fecDesc);
        }
        return json_encode($arry, JSON_NUMERIC_CHECK);
    }
    
    function getDataHeatMapAsistencia() {
        $data = $this->m_detalle_curso->getDataHeatMapAsistencia($this->_idMain, $this->_fechas);
        $arry = array();
        $idx  = 0;
        foreach ($data as $row) {
            array_push($arry, array($idx, 0, $row['falta_dia1']));
            array_push($arry, array($idx, 1, $row['falta_dia2']));
            array_push($arry, array($idx, 2, $row['falta_dia3']));
            array_push($arry, array($idx, 3, $row['falta_dia4']));
            array_push($arry, array($idx, 4, $row['falta_dia5']));
            $idx++;
        }
        return json_encode($arry, JSON_NUMERIC_CHECK);
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////// DETALLE DEL ESTUDIANTE /////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function getDetalleEstudiante() {
        try {
            $idEstudiante = _decodeCI(_post('idEstu'));
            if($idEstudiante == null) {
                throw new Exception(ANP);
            }
            $data = $this->m_detalle_curso->getAlumno($idEstudiante);
            $data['foto_persona'] = $data['foto_persona'].'?lastmod='.(rand(0, 10));
            $data['tablaHistoria'] = $this->buildHTML_HistoriaEstudiante($idEstudiante);
            //Familiares
            $codFam  = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", $idEstudiante);
            if($codFam != null) {
                $familiares = $this->m_detalle_curso->getFamiliaresByCodFam($codFam);
                $data['tablaFamiliares'] = $this->buildHTML_FamiliaresEstudiante($familiares);
            }
            /*$data['dataLineRegre'] = $this->getDataLineaRegresionAsistByEstu($idEstudiante);
            $data['dataRadar']     = $this->getDataRadarAsistByEstu($idEstudiante);
            $data['pieEstu']       = $this->getEstructuraGraficoAsitByEstu_Pie($idEstudiante);
            $data['rankEstu']      = $this->buildHTML_RankingEstudiantesAsist($idEstudiante);*/
            $data['tbCursosEstu']  = $this->buildHTML_CursosByEstudiante($idEstudiante);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDetalleEstudianteAsistencia() {
        try {
            $idEstudiante = _decodeCI(_post('idEstu'));
            if($idEstudiante == null) {
                throw new Exception(ANP);
            }
            $data['dataLineRegre'] = $this->getDataLineaRegresionAsistByEstu($idEstudiante);
            $data['dataRadar']     = $this->getDataRadarAsistByEstu($idEstudiante);
            $data['pieEstu']       = $this->getEstructuraGraficoAsitByEstu_Pie($idEstudiante);
            $data['rankEstu']      = $this->buildHTML_RankingEstudiantesAsist($idEstudiante);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildHTML_HistoriaEstudiante($idEstudiante) {
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                 id="tbEstusHistoria">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'A&ntilde;o'  , 'class' => 'text-left');
        $head_1 = array('data' => 'Sede Grado'  , 'class' => 'text-left');
        $head_2 = array('data' => 'Aula'        , 'class' => 'text-left');
        $head_3 = array('data' => 'Promedio'    , 'class' => 'text-right');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $historia = $this->m_detalle_curso->getHistorialEstudiante($idEstudiante);
        foreach ($historia as $row) {
            $row_0 = array('data' => $row['year_academico'], 'class' => 'text-left');
            $row_1 = array('data' => $row['sede'], 'class' => 'text-left');
            $row_2 = array('data' => $row['desc_aula'], 'class' => 'text-left');
            $row_3 = array('data' => $row['promedio_final'], 'class' => 'text-right');
            $this->table->add_row($row_0, $row_1, $row_2, $row_3);
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function buildHTML_FamiliaresEstudiante($familiares) {
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                 id="tbEstusFamiliares">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'Nombres'         , 'class' => 'text-left');
        $head_1 = array('data' => 'Doc. Identidad'  , 'class' => 'text-left');
        $head_2 = array('data' => 'Correo'          , 'class' => 'text-left');
        $head_3 = array('data' => '&#191;Apoderado?', 'class' => 'text-center');
        $head_4 = array('data' => '&#191;Resp. Eco?', 'class' => 'text-center');
        $head_5 = array('data' => 'Parentesco'      , 'class' => 'text-left');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5);
        foreach ($familiares as $row) {
            $row_0 = array('data' => $row['nombre_completo'], 'class' => 'text-left');
            $row_1 = array('data' => $row['tipo_doc'].' - '.$row['nro_doc_identidad'], 'class' => 'text-left');
            $row_2 = array('data' => $row['email'], 'class' => 'text-left');
            $row_3 = array('data' => $row['apoderado'], 'class' => 'text-center');
            $row_4 = array('data' => $row['resp_economico'], 'class' => 'text-center');
            $row_5 = array('data' => $row['parentesco'], 'class' => 'text-left');
            $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5);
        }
        $table = $this->table->generate();
        return $table;
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////  DATA LINEA DE REGRESION ///////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function getDataLineaRegresionAsistByEstu($idEstudiante) {
        $data = $this->m_detalle_curso->getCantPresenteByEstu($idEstudiante, $this->_idMain, _getYear());
        $arry = array();
        foreach ($data as $row) {
            array_push($arry, array($row['fecha_asistencia'].'000', $row['cnt']));
        }
        return json_encode($arry, JSON_NUMERIC_CHECK);//substr(json_encode($arry, JSON_NUMERIC_CHECK), 1, -1);
    }
    
    function getDataRadarAsistByEstu($idEstudiante) {
        $data = $this->m_detalle_curso->getDataRadar($idEstudiante, $this->_idMain);
        $arry = array();
        foreach ($data as $row) {
            array_push($arry, $row['cant']);
        }
        return json_encode($arry, JSON_NUMERIC_CHECK);
    }
    
    ///////////////////////////////////////// ESTUDIANTE /////////////////////////////////////////////////
    //////////////////////////////////////// GRAFICO PIE ASISTENCIA //////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function getEstructuraGraficoAsitByEstu_Pie($idEstudiante) {
        $arr  = $this->m_detalle_curso->getDataRadar($idEstudiante, $this->_idMain);
        $arry = array();
        $colores = array(ASISTENCIA_PRESENTE => '#8BC34A', ASISTENCIA_TARDE => '#FFEB3B', ASISTENCIA_TARDE_JUSTIF => '#673AB7', ASISTENCIA_FALTA => '#757575', ASISTENCIA_FALTA_JUSTIF => '#FF9800', 'SIN ASIGNAR' => '#000000' );
        foreach ($arr as $row) {
            $rw['name']            = $row['estado_asist'];
            $rw['y']               = $row['cant'];
            $rw['color']           = $colores[$row['estado_asist']];
            $rw['events']['click'] = '';
            $rw['selected']        = false;
            array_push($arry, $rw);
        }
        return json_encode($arry, JSON_NUMERIC_CHECK);
    }
    
function buildHTML_RankingEstudiantesAsist($idEstudiante) {
        $rankEstu = $this->m_detalle_curso->getListadoEstuCantAsistencias($this->_idMain);
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                 id="tbRankEstuAsist">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'                                                                   , 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre'                                                              , 'class' => 'text-left');
        $head_2 = array('data' => '<span class="label-circle '.ASISTENCIA_PRESENTE_CSS.'"></span>'      , 'class' => 'text-center');
        $head_3 = array('data' => '<span class="label-circle '.ASISTENCIA_FALTA_CSS.'"></span>'         , 'class' => 'text-center');
        $head_4 = array('data' => '<span class="label-circle '.ASISTENCIA_TARDE_CSS.'"></span>'         , 'class' => 'text-center');
        $head_5 = array('data' => '<span class="label-circle '.ASISTENCIA_TARDE_JUSTIF_CSS.'"></span>'  , 'class' => 'text-center');
        $head_6 = array('data' => '<span class="label-circle '.ASISTENCIA_FALTA_JUSTIF_CSS.'"></span>'  , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        $i = 1;
        foreach ($rankEstu as $row) {
            $bold = null;
            if($idEstudiante == $row['nid_persona']) {
                $bold = 'font-weight:bold';
            }
            $imageStudent = '<img alt="Student" src="'.$row['foto_persona'].'" width=25 height=25
		                          class="img-circle m-r-10">
		                     <p class="classroom-value" style="display: inline"><span style="'.$bold.'">'.$row['estudiante'].'</span></p>';
            $row_0 = array('data' => $i, 'class' => 'text-left');
            $row_1 = array('data' => $imageStudent, 'class' => 'text-left row_index', 'data-activo' => $bold);
            $row_2 = array('data' => '<span style="'.$bold.'">'.$row['cant_temprano'].'</span>', 'class' => 'text-center');
            $row_3 = array('data' => '<span style="'.$bold.'">'.$row['cant_falta'].'</span>', 'class' => 'text-center');
            $row_4 = array('data' => '<span style="'.$bold.'">'.$row['cant_tarde'].'</span>', 'class' => 'text-center');
            $row_5 = array('data' => '<span style="'.$bold.'">'.$row['cant_tarde_justif'].'</span>', 'class' => 'text-center');
            $row_6 = array('data' => '<span style="'.$bold.'">'.$row['cant_falta_justif'].'</span>', 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5, $row_6);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function buildHTML_CursosByEstudiante($idEstudiante) {
        $cursosEstu = $this->m_detalle_curso->getCursosByEstudiante($this->_idGrado, _getYear(), $idEstudiante);
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                 id="tbCursosEstu">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'Docente', 'class' => 'text-left');
        $head_1 = array('data' => 'Curso'  , 'class' => 'text-left');
        $head_2 = array('data' => 'Nota'   , 'class' => 'text-right');
        $this->table->set_heading($head_0, $head_1, $head_2);
        foreach ($cursosEstu as $row) {
            $imageDocente = null;
            if($row['nid_persona'] != null) {
                $imageDocente = '<img alt="Student" src="'.$row['foto_docente'].'" width=25 height=25
		                               class="img-circle m-r-10">
		                         <p class="classroom-value" style="display: inline">'.$row['docente_nombres'].'</p>';
            }
            $row_0 = array('data' => $imageDocente, 'class' => 'text-left');
            $row_1 = array('data' => $row['desc_curso'], 'class' => 'text-left');
            $row_2 = array('data' => '--', 'class' => 'text-right');
            $this->table->add_row($row_0, $row_1, $row_2);
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function buscarInstrumentos() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $buscar  = _post('buscar');
            if($buscar == null) {
                throw new Exception(ANP);
            }
            if(strlen($buscar) < 3) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tablaInstru'] = $this->buildHTML_Instrumentos($buscar);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildHTML_Instrumentos($busqueda) {
        $i = 1;
        $instrumentos = $this->m_detalle_curso->getInstrumentosBusqueda($busqueda);
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination="true" id="tbInstru" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'     , 'class' => 'text-left');
        $head_1 = array('data' => 'Instrumento', 'class' => 'text-left');
        $head_2 = array('data' => 'Autor', 'class' => 'text-left');
        $head_3 = array('data' => 'Uso / Visitas / Likes' , 'class' => 'text-center');
        $head_4 = array('data' => 'Previo' , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        foreach ($instrumentos as $row) {
            $radio = '  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="radio_inst_'.$i.'" >
                            <input type="radio" class="mdl-radio__button" name="radioInstru" data-id_instru="'._simple_encrypt($row['id_instrumento']).'" id="radio_inst_'.$i.'">
                            <span class="mdl-radio__label"></span>
                        </label>';
            $i++;
            $row_1 = array('data' => $radio, 'class' => 'text-left');
            $row_2 = array('data' => $row['nombre_instrumento'], 'class' => 'text-left');
            $row_3 = array('data' => $row['autor'], 'class' => 'text-left');
            $row_4 = array('data' => $row['rank'], 'class' => 'text-center');
            $row_5 = array('data' => '<button class="mdl-button mdl-js-button mdl-button--icon"><i class="mdi mdi-remove_red_eye"></i></button>' , 'class' => 'text-center');
            $this->table->add_row($row_1, $row_2, $row_3, $row_4, $row_5);
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function asignarInstrumento() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idInstru  = _simpleDecryptInt(_post('idInstru'));
            $concepto  = utf8_decode(trim(_post('concepto')));
            $idCompetencia = _decodeCI(_post('idCompetencia'));
            $idCapacidad   = _decodeCI(_post('idCapacidad'));
            $idIndicador   = _decodeCI(_post('idIndicador'));
            $idBimestre    = _decodeCI(_post('idBimestre'));
            if($idInstru == null || $idBimestre == null) {
                throw new Exception(ANP);
            }
            if(strlen($concepto) == 0) {
                throw new Exception(ANP);
            }
            if($idCompetencia == null || $idCapacidad == null || $idIndicador == null) {
                throw new Exception(ANP);
            }
            //VALIDAR QUE EL INDICADOR PERTENEZA A LA CAPACIDAD / COMPETENCIA / CURSO
            
            $correlativo = $this->m_detalle_curso->getNextCorrelativoInstrumento($this->_idGrado, $this->_idCurso, $idCompetencia, $idCapacidad, $idIndicador,
                                                                                 $this->_idMain , $idInstru);
            $arryInsert = array(
                "_id_grado"        => $this->_idGrado,
                "_id_curso"        => $this->_idCurso,
                "_year_acad"       => _getYear(),
                "_id_competencia"  => $idCompetencia,
                "_id_capacidad"    => $idCapacidad,
                "_id_indicador"    => $idIndicador,
                "_id_main"         => $this->_idMain,
                "_id_instrumento"  => $idInstru,
                "correlativo"      => $correlativo,
                "concepto_evaluar" => $concepto,
                "_id_ciclo_acad"   => $idBimestre,
                "audi_user_regi"   => _getSesion('nid_persona')
            );
            $data = $this->m_detalle_curso->asignarInstrumento($arryInsert);
            if($data['error'] == EXIT_SUCCESS) {
                $data['optInstrumentos'] = __buildComboInstrumentos($this->_idGrado, $this->_idCurso, $idCompetencia, $idCapacidad, $idIndicador, $this->_idMain);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getInstrumentoToEvaluarByEstu() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEstudiante  = _decodeCI(_post('idEstudiante'));
            $idInstrumento = _decodeCI(_post('idInstrumento'));
            $idCompetencia = _decodeCI(_post('idCompetencia'));
            $idCapacidad   = _decodeCI(_post('idCapacidad'));
            $idIndicador   = _decodeCI(_post('idIndicador'));
            if($idEstudiante == null || $idInstrumento == null || $idCompetencia == null || $idCapacidad == null || $idIndicador == null) {
                throw new Exception(ANP);
            }
            $instru = explode(';', $idInstrumento);
            $idInstrumento = $instru[0];
            $correlativo   = $instru[1];
            $instrumento = $this->m_detalle_curso->getInstrumentoToEvaluar($idEstudiante, $this->_idMain, $idInstrumento, $idIndicador, $correlativo);
            $data['instrumento'] = $this->buildHTML_InstrumentoByEstu($instrumento, $idInstrumento);
            $data['notaInstru'] = $this->m_detalle_curso->calcularNotaInstrumento($idEstudiante, $this->_idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
            $data['colorGeneral'] = ($data['notaInstru'] <= 10.49) ? 'mdl-color-text--red-500' : (($data['notaInstru'] >= 10.50 && $data['notaInstru'] <= 16.49) ? 'mdl-color-text--amber-500' : 'mdl-color-text--green-500' );
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildHTML_InstrumentoByEstu($instrumento, $idInstrumento) {
        $html = null;
        $opciones = $this->m_detalle_curso->getOpcioneByInstrumento($idInstrumento);
        $val = 0;
        foreach ($instrumento as $inst) {
            $opcionesHtml = '';
            $abvrOpc = null;
            foreach ($opciones as $opc) {
                $check = null;
                $check = $inst['id_opcion'] == $opc['id_opcion'] ? 'checked' : null;
                if($abvrOpc == null) {
                    $abvrOpc = $inst['id_opcion'] == $opc['id_opcion'] ? $opc['abvr_opcion'] : null;
                }
                $opcionesHtml .= '  <li class="list-group-item">
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="radio_'.$inst['_id_aspecto'].$val.'" data-id_aspecto="'._encodeCI($inst['_id_aspecto']).'" data-id_opcion="'._encodeCI($opc['id_opcion']).'" data-abvr="'.$opc['abvr_opcion'].'">
                                            <input type="radio" '.$check.' id="radio_'.$inst['_id_aspecto'].$val.'" name="radio_'.$inst['_id_aspecto'].'" value="'.$opc['valor'].'" onclick="registRptaInstru($(this));" class="mdl-radio__button">
                                            <span class="mdl-radio__label">'.$opc['desc_opcion'].'</span>
                                        </label>
                                    </li>';
                $val++;
            }
            $html .= '<div class="collapse-card">
                          <div class="collapse-card__heading">
                              <h4 class="collapse-card__title">
                                  '.'<span class="estado_rpta">('.$abvrOpc.')</span> '.$inst['desc_aspecto'].'
                              </h4>
                          </div>
                          <div class="collapse-card__body"><ul class="list-group">'.$opcionesHtml.'</ul></div>
                      </div>';
        }
        return $html;
    }
    
    function registrarRptaInstruByEstu() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEstudiante  = _decodeCI(_post('idEstudiante'));
            $idInstrumento = _decodeCI(_post('idInstrumento'));
            $idAspecto     = _decodeCI(_post('idApecto'));
            $idOpcion      = _decodeCI(_post('idOpcion'));
            $opcion        = _post('opcion');
            $idCompetencia = _decodeCI(_post('idCompetencia'));
            $idCapacidad   = _decodeCI(_post('idCapacidad'));
            $idIndicador   = _decodeCI(_post('idIndicador'));
            if($idCompetencia == null || $idCapacidad == null || $idIndicador == null) {
                throw new Exception(ANP);
            }
            if($idEstudiante == null || $idInstrumento == null || $idAspecto == null || $idOpcion == null || $opcion == null) {
                throw new Exception(ANP);
            }
            $instru = explode(';', $idInstrumento);
            $idInstrumento = $instru[0];
            $correlativo   = $instru[1];
            //1. validar que la opcion pertenezca a instrumento_x_opcion
            
            //2. buscar si ya existe para insertar o actualizar
            $respuestas = $this->m_detalle_curso->getRptasJSONByEstu($idEstudiante, $this->_idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
            if(count($respuestas) == 0) { // ------------ CREAR ------------
                $jsonString = '{ "resultados" : [ {"_id_aspecto":'.$idAspecto.', "valor":"'.$opcion.'", "_id_opcion" : '.$idOpcion.' }  ] }';
                if($this->m_detalle_curso->checkIfExisteEvaluacionEstu($this->_idMain, $idInstrumento, $idCompetencia, $idCapacidad, $idIndicador, $correlativo, $idEstudiante) == 1) {
                    $this->m_detalle_curso->updateJSON_StringRptas($idEstudiante, $this->_idMain, $jsonString, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
                } else {
                    $arryInsert = array(
                        "_id_grado"             => $this->_idGrado,
                        "_id_curso"             => $this->_idCurso,
                        "_year_acad"            => _getYear(),
                        "_id_competencia"       => $idCompetencia,
                        "_id_capacidad"         => $idCapacidad,
                        "_id_indicador"         => $idIndicador,
                        "_id_main"              => $this->_idMain,
                        "_id_instrumento"       => $idInstrumento,
                        "correlativo"           => $correlativo,
                        "_id_estudiante"        => $idEstudiante,
                        "_id_docente_evaluador" => _getSesion('nid_persona'),
                        "nota_numerica"         => 0,
                        "result_json_instru"    => $jsonString
                    );
                    $this->m_detalle_curso->registrarEvaluacionEstudianteInstru($arryInsert);
                    $data['notaInstru'] = $this->m_detalle_curso->calcularNotaInstrumento($idEstudiante, $this->_idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
                    $this->m_detalle_curso->updateNota_Rptas($idEstudiante, $this->_idMain, $data['notaInstru'], $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
                }
            } else {
                $encontroEnRptas = false;
                foreach ($respuestas as $rpta) {
                    $rpta = (array) json_decode($rpta['json_array_elements']);
                    if($idAspecto == $rpta['_id_aspecto']) { // ------------ UPDATE ------------
                        if($rpta['valor'] == $opcion) {
                            throw new Exception('No hay cambios');
                        }
                        $this->m_detalle_curso->replaceJSON_StringRpta($idEstudiante, $this->_idMain, $idAspecto, $rpta['valor'], $opcion, $rpta['_id_opcion'], $idOpcion, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
                        $encontroEnRptas = true;
                        break;
                    }
                }
                if(!$encontroEnRptas) {
                    $jsonString = $this->m_detalle_curso->getRptasJSON_AS_String_ByEstu($idEstudiante, $this->_idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
                    $posit = strrpos(substr($jsonString, 0, -1), "}") + 1;//Traer el indice de la penultima llave } y sumarle 1
                    $jsonString = substr_replace($jsonString, ' , { "_id_aspecto":'.$idAspecto.', "valor":"'.$opcion.'", "_id_opcion" : '.$idOpcion.' } ', $posit, 0);
                    $this->m_detalle_curso->updateJSON_StringRptas($idEstudiante, $this->_idMain, $jsonString, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
                }
            }
            if(!isset($data['notaInstru']) ) {
                $data['notaInstru'] = $this->m_detalle_curso->calcularNotaInstrumento($idEstudiante, $this->_idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
                $this->m_detalle_curso->updateNota_Rptas($idEstudiante, $this->_idMain, $data['notaInstru'], $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
            }
            if(isset($data['notaInstru'])) {
                $data['colorGeneral'] = ($data['notaInstru'] <= 10.49) ? 'mdl-student_red' : (($data['notaInstru'] >= 10.50 && $data['notaInstru'] <= 16.49) ? 'mdl-student_ambar' : 'mdl-student_green' );
                $data['promedioResult'] = $this->m_detalle_curso->getPromedioValorByInstruByEstu($idEstudiante, $this->_idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function marcarAsistenciaGeneral() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $cantAsist  = $this->m_detalle_curso->checkIfHayAsistenciaHoy($this->_idMain);
            $permisoHoy = $this->m_detalle_curso->puedeDictarHoyDiaEspecial($this->_idMain);
            $dayofweek = date('w', strtotime( date('Y-m-d') ) );
            
            $cond1 = ($cantAsist == 0 && $dayofweek != DIA_DOMINGO_CODE && $dayofweek != DIA_SABADO_CODE );
            $cond2 = ($cantAsist == 0 && $permisoHoy);
            if( $cond1 || $cond2 ) {// NO HAY ASISTENCIAS PARA HOY, GENERAR LAS ASISTENCIAS A TEMPRANO
                $data = $this->m_detalle_curso->insertarAsistenciasPorDefecto($this->_idAula, $this->_idMain);
                $data['estuAsist'] = $this->buildEstudiantesForCalificar_HTML($this->m_detalle_curso->getEstudiantesByCurso($this->_idMain));
            } else {
                $data['msj'] = 'Ya ha marcado asistencia o no lo puede hacer el d&iacute;a de hoy';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getAwards() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEstudiante = _decodeCI(_post('id_estudiante'));
            if($idEstudiante == null) {
                throw new Exception(ANP);
            }
            $data['positivos'] = $this->buildHTML_Awards(AWARDS_POSITIVO, 1);
            $data['negativos'] = $this->buildHTML_Awards(AWARDS_NEEDS_WORK, 0);
            $awards = $this->m_detalle_curso->getStudentAwardsByMain($this->_idMain, $idEstudiante);
            $data['awards_estu'] = $this->buildHTML_StudentAwards($awards);
            $data['error']     = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function refreshGetAwards() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEstudiante = _decodeCI(_post('id_estu'));
            if($idEstudiante == null) {
                throw new Exception(ANP);
            }
            $awards = $this->m_detalle_curso->getStudentAwardsByMain($this->_idMain, $idEstudiante);
            $data['awards_estu'] = $this->buildHTML_StudentAwards($awards);
            $data['error']     = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildHTML_Awards($tipoAward, $suma) {
        $awards = $this->m_detalle_curso->getAwards($tipoAward);
        $html = null;
        foreach ($awards as $awa) {
            $html .= ' <div class="mdl-card mdl-awards" onclick="giveAward($(this));" data-id_award="'._encodeCI($awa['id_award']).'">
                          <img alt="'.$awa['ruta_icono'].'" src="'.RUTA_IMG.'meritos/'.$awa['ruta_icono'].'.png">
                          <br>
                          <label>'.$awa['desc_award'].'</label>    
                          <div class="awards-points">'.$suma.'</div>
                       </div>';
        }
        return $html;
    }
    
    function giveAward() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAward      = _decodeCI(_post('idAward'));
            $idEstudiante = _decodeCI(_post('id_estu'));
            if($idAward == null || $idEstudiante == null) {
                throw new Exception(ANP);
            }
            if($this->m_detalle_curso->checkHasAwardsEstudiante($this->_idMain, $idEstudiante)) {//INSERT
                $jsonString = '{ "awards" : [ { "id_award":'.$idAward.', "fec_registro":"'.date('Y-m-d h:i:s A').'", "id_main" : '.$this->_idMain.', "id_estudiante" : '.$idEstudiante.', "audi_usua_regi" : '._getSesion('nid_persona').', "audi_pers_regi" : "'._getSesion('nombre_abvr').'" }  ] }';
                $arryInsert = array(
                    "_id_main"               => $this->_idMain,
                    "_id_estudiante"         => $idEstudiante,
                    "awards_estudiante_json" => $jsonString
                );
                $data = $this->m_detalle_curso->registrarAward_Estudiante($arryInsert);
            } else {//UPDATE
                $jsonString = $this->m_detalle_curso->getAwardsJSON_AS_String_ByEstu($this->_idMain, $idEstudiante);
                $posit = strrpos(substr($jsonString, 0, -1), "}") + 1;//Traer el indice de la penultima llave } y sumarle 1
                $jsonString = substr_replace($jsonString, ' , { "id_award":'.$idAward.', "fec_registro":"'.date('Y-m-d h:i:s A').'", "id_main" : '.$this->_idMain.', "id_estudiante" : '.$idEstudiante.', "audi_usua_regi" : '._getSesion('nid_persona').', "audi_pers_regi" : "'._getSesion('nombre_abvr').'" } ', $posit, 0);
                $data = $this->m_detalle_curso->updateJSON_String_Awards($this->_idMain, $idEstudiante, $jsonString);
            }
            $data['cant_awards'] = $this->m_detalle_curso->getCountAwardsPositivos($this->_idMain, $idEstudiante);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildHTML_StudentAwards($awards) {
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-page-size="10"
		                                     data-pagination="true" id="tbAwardsEstu" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '', 'class' => 'text-left');
        $head_2 = array('data' => '', 'class' => 'text-left');
        $head_3 = array('data' => '', 'class' => 'text-left');
        $head_4 = array('data' => '', 'class' => 'text-left');
        $this->table->set_heading($head_1, $head_2, $head_3, $head_4);
        foreach ($awards as $row) {
            $row_1 = array('data' => '<img alt="Award" width="25" height="25" src="'.RUTA_IMG.'meritos/'.$row['ruta_icono'].'.png">', 'class' => 'text-left');
            $row_2 = array('data' => $row['desc_award'], 'class' => 'text-left');
            $row_3 = array('data' => $row['pers_regi'], 'class' => 'text-left');
            $row_4 = array('data' => _fecha_tabla($row['fec_registro'], 'd/m h:i A') , 'class' => 'text-left');
            $this->table->add_row($row_1, $row_2, $row_3, $row_4);
        }
        $table = $this->table->generate();
        return $table;
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     
	function logOut(){
	    $logedUser = _getSesion('usuario');
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}

    function cambioRol() {
        $idRol = _simple_decrypt(_post('id_rol'));
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'schoowl');
        $dataUser = array(
            "id_rol" => $idRol,
            "nombre_rol" => $nombreRol
        );
        $this->session->set_userdata($dataUser);
        $result['url'] = base_url() . "c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }


    function setIdSistemaInSession() {
        $idSistema = _decodeCI(_post('id_sis'));
        $idRol = _decodeCI(_post('rol'));
        if ($idSistema == null || $idRol == null) {
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema, $idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function enviarFeedBack() {
        $nombre = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje, $url, $nombre);
    }

    function mostrarRolesSistema() {
        $idSistema = _decodeCI(_post('sistema'));
        $roles = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'), $idSistema);
        $result = '<ul>';
        foreach ($roles as $rol) {
            $idRol = _encodeCI($rol->nid_rol);
            $result .= '<li style="cursor:pointer" onclick="goToSistema(\'' . _post('sistema') . '\', \'' . $idRol . '\')">' . $rol->desc_rol . '</li>';
        }
        $result .= '</ul>';
        $data['roles'] = $result;
        
        echo json_encode(array_map('utf8_encode', $data));
    }
}