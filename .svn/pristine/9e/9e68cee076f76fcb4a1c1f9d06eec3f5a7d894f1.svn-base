<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_graficos extends CI_Controller {
    
    private $_idRol      = null;
    private $_idUserSess = null;
    private $_idSede     = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('m_grafico');
        $this->load->model('m_graficos_new');
        $this->load->model('m_rubrica');
        $this->load->model('m_utils');
        $this->load->helper('cookie');
        _validate_uso_controladorModulos(ID_SISTEMA_SPED, ID_PERMISO_GRAFICOS, SPED_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SPED_ROL_SESS);
        $this->_idSede = ($this->_idRol == ID_ROL_SUBDIRECTOR ? _getSesion('id_sede_trabajo') : 0);
    }
    
    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SPED, SPED_FOLDER);

        ////Modal Popup Iconos///
        $data['titleHeader']     = 'Gr&aacute;ficos';
        $data['ruta_logo'] = MENU_LOGO_SPED;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SPED;
        $data['nombre_logo'] = NAME_MODULO_SPED;
        $data['barraSec']        = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                       <a href="#tab-1" class="mdl-layout__tab is-active" onclick="changeIdModal(\'modalDocentes\')">Desempe&ntilde;o de Docentes</a>
                                       <a href="#tab-2" class="mdl-layout__tab" onclick="changeIdModal(\'modalEvaluadores\')">Desempe&ntilde;o de Evaluadores</a>
                                    </div>';
        $rolSistemas            = $this->m_utils->getSistemasByRol(ID_SISTEMA_SPED, $this->_idUserSess);
        $data['apps']             = __buildModulosByRol($rolSistemas, $this->_idUserSess);

        //MENU Y CABECERA
        $menu         = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        //DATOS
        $arryIndicadores = $this->m_grafico->getAllIndicadores();
        $data['optIndicadores'] = __buildComboIndicadoresDocentes($arryIndicadores);
        $data['optDocentes']    = __buildComboByRol(ID_ROL_DOCENTE);
        $data['optEvaluadores'] = __buildComboByRol(ID_ROL_SUBDIRECTOR);
        $data['doce1Graf']      = $this->getDataTopSubFactores(true);
        $data['doce2Graf']      = $this->getDataLowSubFactores(true);
        $data['doce3Graf']      = $this->getDataGaugesPromediosSedeGrupoEduc(true);
        $data['doce4Graf']      = $this->getDataBarrasByArea(true);
        $data['doce5Graf']      = $this->getDataTopDocentes(true);
        $data['doce6Graf']      = $this->getDataLowDocentes(true);
        $this->load->view('v_graficos', $data);
    }
    
    function getDataGraficoEvaluadoresCantidad($return = null) {
        $datos = $this->m_graficos_new->getEvaluadoresCantidadEvas();
        $arry = array(array('Evaluador', 'Cant. Evaluaciones', 'ID'));
        foreach ($datos as $row) {
            array_push($arry, array($row['evaluador'], $row['cantidad'], $row['id_evaluador']));
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
function getDataGraficoEvaluadoresTipoVisita($return = null) {
        $datos = $this->m_graficos_new->getTipoVisitaEvaluaciones($this->_idSede);
        $arry = array(array('visita', VISITA_OPINADA, VISITA_SEMI_OPINADA, VISITA_NO_OPINADA));
        foreach ($datos as $row) {
            $arryDinamic = array($row['evaluador']);
            $nota = explode(',', $row['nota_vigesimal']);
            $cont = 1;
            foreach ($nota as $ev) {
                $tipoVista = null;
                switch ($cont) {
                    case 1: $tipoVista = VISITA_OPINADA;break;
                    case 2: $tipoVista = VISITA_SEMI_OPINADA;break;
                    case 3: $tipoVista = VISITA_NO_OPINADA;break;
                }
                array_push($arryDinamic, array('v'           => $ev, 
                                               'tipo_visita' => $tipoVista,
                                               'idEvaluador' => $row['id_evaluador']));
                $cont++;
            }
            array_push($arry, $arryDinamic);
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);

        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDataGraficoCantidadEvasByFechas($return = null) {
        $datos = $this->m_graficos_new->getCantidadEvasByFechas();
        $evaluadores = $this->m_graficos_new->getEvaluadores();
        $cabeceras = array('Fecha');
        foreach ($evaluadores as $eva) {
            array_push($cabeceras, array($eva['evaluador'], $eva['id_evaluador']));
        }
        $arry = array($cabeceras);
        foreach ($datos as $row) {
            $arryDinamic = array($row['fecha']);
            $cantEvas = explode(',', $row['cants']);
            foreach ($cantEvas as $ev) {
                array_push($arryDinamic, $ev);
            }
            array_push($arry, $arryDinamic);
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDataGraficoCantDocentes($return = null) {
        $datos = $this->m_graficos_new->getCantDocentes($this->id_sede_evaluador);
        $arry = array(array('Evaluado', 'Cant. Evaluaciones', 'ID'));
        foreach ($datos as $row) {
            array_push($arry, array($row['evaluado'], intval($row['cantidad']), $row['id_evaluado']));
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDataGraficoLowCantidadDocentes($return = null) {
        $datos = $this->m_graficos_new->getCantLowDocentes($this->id_sede_evaluador);
        $arry = array(array('Evaluado', 'Cant. Evaluaciones', 'ID'));
        foreach ($datos as $row) {
            array_push($arry, array($row['evaluado'], intval($row['cantidad']), $row['id_evaluado']));
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDataGraficoEstadoEvaluacionesCant($return = null) {
        $datos = $this->m_graficos_new->getEstadoEvaluacionesCant();
        $arry = array(array('Estados', PENDIENTE, EJECUTADO , NO_EJECUTADO, INJUSTIFICADO, POR_JUSTIFICAR, JUSTIFICADO));
        foreach ($datos as $row) {
            $arryDinamic = array($row['evaluador']);
            $cantEvas = explode(',', $row['cants']);
            foreach ($cantEvas as $ev) {
                array_push($arryDinamic, $ev);
            }
            array_push($arry, $arryDinamic);
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDataTopSubFactores($return = null) {
        $datos = $this->m_graficos_new->getTop_Low_SubFactores('DESC', $this->_idSede);
        $arry = array(array('Subfactores', 'Nota'));
        foreach ($datos as $dat) {
            array_push($arry, array(utf8_encode($dat['desc_indicador']), array('v' => $dat['val'], 'id' => $dat['id_subfactor'] )));
            //array_push($arry, array(utf8_encode($dat['desc_indicador']), array('v' => $dat['val'], 'id' => $dat['id_subfactor'] )));
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDataTopDocentes($return = null) {
        $datos = $this->m_graficos_new->getTopDocentes($this->_idSede);
        $arry = array(array('Nombre', 'Nota'));
        foreach ($datos as $dat) {
            array_push($arry, array(utf8_encode($dat['evaluado']), array('v' => $dat['promedio'], 'id' => $dat['id_evaluado'])));
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDataLowDocentes($return = null) {
        $datos = $this->m_graficos_new->getTop_Low_Docentes($this->_idSede);
        $arry = array(array('Nombre', 'Nota'));
        foreach ($datos as $dat) {
            array_push($arry, array(utf8_encode($dat['evaluado']), array('v' => $dat['promedio'], 'id' => $dat['id_evaluado'])));
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDataLowSubFactores($return = null) {
        $datos = $this->m_graficos_new->getTop_Low_SubFactores('ASC', $this->_idSede);
        $arry = array(array('Subfactores', 'Nota'));
        foreach ($datos as $dat) {
            array_push($arry, array(utf8_encode($dat['desc_indicador']),  array('v' => $dat['val'], 'id' => $dat['id_subfactor'] )));
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDataGaugesPromediosSedeGrupoEduc($return = null) {
        $datos = $this->m_graficos_new->getGaugesPromediosSedeGrupoEduc($this->_idSede);
        $arry = array(array('Sede', 'Nota'));
        foreach ($datos as $dat) {
            array_push($arry, array(utf8_encode($dat['label']), $dat['nota']));
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDataBarrasByArea($return = null) {
        $datos = $this->m_graficos_new->getBarrasEvasByArea($this->_idSede);
        $arry = array(array(utf8_encode('Área'), 'Cant. Eval.'));
        foreach ($datos as $dat) {
            array_push($arry, array(utf8_encode($dat['desc_area']), array('v' => $dat['cant_evas'], 'id' => $dat['id_area'] ) ));
        }
        $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        if($return == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data['datos'];
        }
    }
    
    function getDetalleDoceCantEvas() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idArea = _post('idArea');
            if($idArea == null) {
                throw new Exception(ANP);
            }
            if($this->_idSede != 0) {
                $datos = $this->m_graficos_new->getDocentes_y_CantEvasByArea($this->_idSede, $idArea);
            } else {
                $datos = $this->m_graficos_new->getDocentes_y_CantEvasByArea_Directivos($idArea);
            }
            $arry = array( array('Docente', 'Cant. Evals.') );
            foreach ($datos as $row) {
                array_push($arry, array( utf8_encode($row['docente']),array('v' => $row['cant_evas'], 'id' => $row['id_persona'] )) );
            }
            $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDetalleSubFactLow() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSubF = _post('idSubF');
            if($idSubF == null) {
                throw new Exception(ANP);
            }
            $datos = $this->m_graficos_new->getPromSubFLow($this->_idSede, $idSubF);
            $arry = array( array('Num Eval.','Nota', 'Promedio') );
            foreach ($datos as $row) {
                array_push($arry, array( ($row['orden']), $row['nota_vigesimal'], $row['promedio'] ) );
            }
            $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDetalleSubFact() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSubF1 = _post('idSubF1');
            if($idSubF1 == null) {
                throw new Exception(ANP);
            }
            $datos = $this->m_graficos_new->getPromSubFLow($this->_idSede, $idSubF1);
            $arry = array( array('Num Eval.','Nota', 'Promedio') );
            foreach ($datos as $row) {
                array_push($arry, array( ($row['orden']), $row['nota_vigesimal'], $row['promedio'] ) );
            }
            $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDataSubFactores_vs_Docentes() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $selectIndi  = _post('selectedIndi');
            $selectDoc   = _post('selectedDoc');
            $fecIni      = _post('fecInicio');
            $fecFin      = _post('fecFin');
            if(!is_array($selectIndi) || count($selectIndi) <= 0) {
                throw new Exception(ANP);
            }
            if(!is_array($selectDoc) || count($selectDoc) <= 0) {
                throw new Exception(ANP);
            }
            if(!_validateDate($fecIni)) {
                throw new Exception(ANP);
            }
            if(!_validateDate($fecFin)) {
                throw new Exception(ANP);
            }
            $fecIni = implode('-', array_reverse(explode('/', $fecIni )));
            $fecFin = implode('-', array_reverse(explode('/', $fecFin )));
            $subFactoresIDS = null;
            foreach($selectIndi AS $indi) {
                $subFactoresIDS .= _decodeCI($indi).',';
            }
            $subFactoresIDS = rtrim(trim($subFactoresIDS), ",");
            //////////////////////////////////////////////////////////////////////
            $docentesIDS = null;
            foreach($selectDoc AS $doce) {
                $docentesIDS .= _decodeCI($doce).',';
            }
            $docentesIDS = rtrim(trim($docentesIDS), ",");
            //////////////////////////////////////////////////////////////////////
            $datos = $this->m_graficos_new->getSubFactores_vs_docentes($this->_idSede, $docentesIDS, $subFactoresIDS, $fecIni, $fecFin);
            $subFactors = $this->m_rubrica->getSubFactores($subFactoresIDS);
            $cabeceras = array('Docente');
            foreach ($subFactors as $eva) {
                array_push($cabeceras, utf8_encode($eva['desc_indicador']));
            }
            array_push($cabeceras, 'Promedio');
            $arry = array($cabeceras);
            foreach ($datos as $row) {
                $arryDinamic = array($row['docente']);
                $cantEvas = explode(',', $row['cants']);
                foreach ($cantEvas as $ev) {
                    array_push($arryDinamic, $ev);
                }
                array_push($arry, $arryDinamic);
            }
            $data['datos'] = json_encode($arry, JSON_NUMERIC_CHECK);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGraficosEvas() {
        $data = array();
        $data['datos']  = $this->getDataGraficoEvaluadoresTipoVisita(true);
        $data['datos1'] = $this->getDataGraficoEvaluadoresCantidad(true);
        $data['datos2'] = $this->getDataGraficoCantidadEvasByFechas(true);
        $data['datos3'] = $this->getDataGraficoEstadoEvaluacionesCant(true);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTablaDocFechaLow() {
        $id_subfactor     = $this->input->post('idSubF');
        $orden            = $this->input->post('orden');
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        try{
            if($id_subfactor == null){
                throw new Exception(ANP);
            }
            $arrayDetaDocentesLow = $this->m_graficos_new->getEvaDocentesFechaLow($this->_idSede, $id_subfactor, $orden);
            if($arrayDetaDocentesLow == array()) {
                throw new Exception('No Hay Registros');
            }
            $data['tabla']                  = $this->buildTablaDocFechaLow($arrayDetaDocentesLow);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaDocFechaLow($datos) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_conta">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Fecha'                   , 'class' => 'text-center');
        $head_2 = array('data' => 'Docente'                 , 'class' => 'text-center');
        $head_3 = array('data' => 'Sede docente'            , 'class' => 'text-center');
        $head_4 = array('data' => 'Evaluador'               , 'class' => 'text-center');
        $head_5 = array('data' => 'Tipo visita'             , 'class' => 'text-center');
        $head_6 = array('data' => 'Horario'   , 'class' => 'text-center');
        $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        $val = 1;
        foreach ($datos as $row) {
            $row_cell_1 = array('data' => _fecha_tabla($row['fecha_evaluacion'], 'd/m/Y') , 'class' => 'text-center');
            $row_cell_2 = array('data' => $row['docente']       , 'class' => 'text-center');
            $row_cell_3 = array('data' => $row['sede']          , 'class' => 'text-center');
            $row_cell_4 = array('data' => $row['evaluador']     , 'class' => 'text-center');
            $row_cell_5 = array('data' => $row['tipo_visita']   , 'class' => 'text-center');
            $row_cell_6 = array('data' => $row['horario']       , 'class' => 'text-center');
            $val++;
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getTablaDocFechaAscendente() {
        $id_subfactor     = $this->input->post('idSubF1');
        $orden            = $this->input->post('orden');
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        try{
            if($id_subfactor == null){
                throw new Exception(ANP);
            }
            $arrayDetaDocentesAscendente = $this->m_graficos_new->getEvaDocentesFechaLow($this->_idSede, $id_subfactor, $orden);
            if($arrayDetaDocentesAscendente == array()) {
                throw new Exception('No Hay Registros');
            }
            $data['tabla']                  = $this->buildTablaDocFechaAscendente($arrayDetaDocentesAscendente);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaDocFechaAscendente($datos) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_conta">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Fecha'                   , 'class' => 'text-center');
        $head_2 = array('data' => 'Docente'                 , 'class' => 'text-center');
        $head_3 = array('data' => 'Sede docente'            , 'class' => 'text-center');
        $head_4 = array('data' => 'Evaluador'               , 'class' => 'text-center');
        $head_5 = array('data' => 'Tipo visita'             , 'class' => 'text-center');
        $head_6 = array('data' => 'Horario'                 , 'class' => 'text-center');
        $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        $val = 1;
        foreach ($datos as $row) {
            $row_cell_1 = array('data' => _fecha_tabla($row['fecha_evaluacion'], 'd/m/Y') , 'class' => 'text-center');
            $row_cell_2 = array('data' => $row['docente']                                 , 'class' => 'text-center');
            $row_cell_3 = array('data' => $row['sede']                                    , 'class' => 'text-center');
            $row_cell_4 = array('data' => $row['evaluador']                               , 'class' => 'text-center');
            $row_cell_5 = array('data' => $row['tipo_visita']                             , 'class' => 'text-center');
            $row_cell_6 = array('data' => $row['horario']                                 , 'class' => 'text-center');
            $val++;
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getTablaDetaEvaDocentes1() {
        $id_evaluado     = $this->input->post('id');
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        try{
            if($id_evaluado == null){
                throw new Exception(ANP);
            }
            $arrayDetaEvaDocentes1 = $this->m_graficos_new->getDetaEvaDocentes1($this->_idSede, $id_evaluado);
            $data['nombrePersona'] = $this->m_utils->getById('persona', 'nom_persona', 'nid_persona', $id_evaluado);
            if($arrayDetaEvaDocentes1 == array()) {
                throw new Exception('No Hay Registros');
            }
            $data['tabla']                  = $this->buildTablaDetaEvaDocente1($arrayDetaEvaDocentes1);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaDetaEvaDocente1($datos) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_conta">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Horario'             , 'class' => 'text-center');
        $head_2 = array('data' => 'Sede docente'        , 'class' => 'text-center');
        $head_3 = array('data' => 'Tipo visita'         , 'class' => 'text-center');
        $head_4 = array('data' => 'Nota'                , 'class' => 'text-center');
        $head_5 = array('data' => 'Evaluador'           , 'class' => 'text-center');
        $this->table->set_heading($head_1, $head_2, $head_3,$head_4, $head_5);
        $val = 1;
        foreach ($datos as $row) {
            $labelColor = ($row['nota_vigesimal'] <= 10.49) ? 'danger' : (($row['nota_vigesimal'] >= 10.50 && $row['nota_vigesimal'] <= 16.49) ? 'warning' : 'success' );
            $row_cell_1 = array('data' => $row['horario']                                                             , 'class' => 'text-center');
            $row_cell_2 = array('data' => $row['sede']                                                                , 'class' => 'text-center');
            $row_cell_3 = array('data' => $row['tipo_visita']                                                         , 'class' => 'text-center');
            $row_cell_4 = array('data' =>'<span class="label label-'.$labelColor.'">'.$row['nota_vigesimal'].'</span>', 'class' => 'text-center');
            $row_cell_5 = array('data' => $row['evaluador']                                                           , 'class' => 'text-center');
            $val++;
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3,$row_cell_4, $row_cell_5);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getTablaDetaEvaDocentes2() {
        $id_evaluado2     = $this->input->post('id');
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        try{
            if($id_evaluado2 == null){
                throw new Exception(ANP);
            }
            $arrayDetaEvaDocentes2 = $this->m_graficos_new->getDetaEvaDocentes1($this->_idSede, $id_evaluado2);
            $data['nombrePersona'] = $this->m_utils->getById('persona', 'nom_persona', 'nid_persona', $id_evaluado2);
            if($arrayDetaEvaDocentes2 == array()) {
                throw new Exception('No Hay Registros');
            }
            $data['tabla']                  = $this->buildTablaDetaEvaDocente2($arrayDetaEvaDocentes2);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaDetaEvaDocente2($datos) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_conta">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Horario'             , 'class' => 'text-center');
        $head_2 = array('data' => 'Sede docente'        , 'class' => 'text-center');
        $head_3 = array('data' => 'Tipo visita'         , 'class' => 'text-right');
        $head_4 = array('data' => 'Nota'                , 'class' => 'text-right');
        $head_5 = array('data' => 'Evaluador'           , 'class' => 'text-center');
        $this->table->set_heading($head_1, $head_2, $head_3,$head_4, $head_5);
        $val = 1;
        foreach ($datos as $row) {
            $labelColor = ($row['nota_vigesimal'] <= 10.49) ? 'danger' : (($row['nota_vigesimal'] >= 10.50 && $row['nota_vigesimal'] <= 16.49) ? 'warning' : 'success' );
            $row_cell_1 = array('data' => $row['horario']                                                             , 'class' => 'text-center');
            $row_cell_2 = array('data' => $row['sede']                                                                , 'class' => 'text-center');
            $row_cell_3 = array('data' => $row['tipo_visita']                                                         , 'class' => 'text-center');
            $row_cell_4 = array('data' =>'<span class="label label-'.$labelColor.'">'.$row['nota_vigesimal'].'</span>', 'class' => 'text-center');
            $row_cell_5 = array('data' => $row['evaluador']                                                           , 'class' => 'text-center');
            $val++;
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3,$row_cell_4, $row_cell_5);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getTablaDetaEvaArea() {
        $id_persona     = $this->input->post('id');
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        try{
            if($id_persona == null){
                throw new Exception(ANP);
            }
            $arrayDetaEvaArea = $this->m_graficos_new->getDetaEvaDocentes1($this->_idSede, $id_persona);
            $data['nombrePersona'] = $this->m_utils->getById('persona', 'nom_persona', 'nid_persona', $id_persona);
            if($arrayDetaEvaArea == array()) {
                throw new Exception('No Hay Registros');
            }
            $data['tabla']                  = $this->buildTablaDetaEvaArea($arrayDetaEvaArea);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaDetaEvaArea($datos) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_conta">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Horario'             , 'class' => 'text-center');
        $head_2 = array('data' => 'Sede docente'        , 'class' => 'text-center');
        $head_3 = array('data' => 'Tipo visita'         , 'class' => 'text-center');
        $head_4 = array('data' => 'Nota'                , 'class' => 'text-center');
        $head_5 = array('data' => 'Evaluador'           , 'class' => 'text-center');
        $this->table->set_heading($head_1, $head_2, $head_3,$head_4, $head_5);
        $val = 1;
        foreach ($datos as $row) {
            $labelColor = ($row['nota_vigesimal'] <= 10.49) ? 'danger' : (($row['nota_vigesimal'] >= 10.50 && $row['nota_vigesimal'] <= 16.49) ? 'warning' : 'success' );
            $row_cell_1 = array('data' => $row['horario']                                                             , 'class' => 'text-center');
            $row_cell_2 = array('data' => $row['sede']                                                                , 'class' => 'text-center');
            $row_cell_3 = array('data' => $row['tipo_visita']                                                         , 'class' => 'text-center');
            $row_cell_4 = array('data' =>'<span class="label label-'.$labelColor.'">'.$row['nota_vigesimal'].'</span>', 'class' => 'text-center');
            $row_cell_5 = array('data' => $row['evaluador']                                                           , 'class' => 'text-center');
            $val++;
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3,$row_cell_4, $row_cell_5);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getTablaDetaEvaTipoVisita() {
        $id_evaluador    = $this->input->post('idEvaluador');
        $tipo_visita     = $this->input->post('tipoVisita');
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        try{
            if($id_evaluador == null){
                throw new Exception(ANP);
            }
            $arrayDetaEvaTipoVisita = $this->m_graficos_new->getDetaEvaTipoVisita($tipo_visita, $id_evaluador);
            $data['nombrePersona']  = $this->m_utils->getById('persona', 'nom_persona', 'nid_persona', $id_evaluador);
            
            $data['tabla'] = $this->buildTablaDetaEvaTipoVisita($arrayDetaEvaTipoVisita);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaDetaEvaTipoVisita($datos) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_conta">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Fecha'               , 'class' => 'text-center');
        $head_2 = array('data' => 'Docente'            , 'class' => 'text-center');
        $head_3 = array('data' => 'Nota'                , 'class' => 'text-center');
        $head_4 = array('data' => 'Horario'             , 'class' => 'text-center');
        $this->table->set_heading($head_1, $head_2, $head_3,$head_4);
        $val = 1;
        foreach ($datos as $row) {
            $labelColor = ($row['nota_vigesimal'] <= 10.49) ? 'danger' : (($row['nota_vigesimal'] >= 10.50 && $row['nota_vigesimal'] <= 16.49) ? 'warning' : 'success' );
            $row_cell_1 = array('data' => $row['fecha_evaluacion']                                                    , 'class' => 'text-center');
            $row_cell_2 = array('data' => $row['evaluado']                                                            , 'class' => 'text-left');
            $row_cell_3 = array('data' =>'<span class="label label-'.$labelColor.'">'.$row['nota_vigesimal'].'</span>', 'class' => 'text-center');
            $row_cell_4 = array('data' => $row['horario']                                                             , 'class' => 'text-center');
            $val++;
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3,$row_cell_4);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getTablaDetaEvaHechasXHacer() {
        $id_evaluador    = $this->input->post('idEvaluador');
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        try{
            if($id_evaluador == null){
                throw new Exception(ANP);
            }
            $arrayDetaEvafecha = $this->m_graficos_new->getDetaEvaHechasXHacer($id_evaluador);
            $data['nombrePersona']  = $this->m_utils->getById('persona', 'nom_persona', 'nid_persona', $id_evaluador);
    
            $data['tabla'] = $this->buildTablaDetaEvaHechasXHacer($arrayDetaEvafecha);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaDetaEvaHechasXHacer($datos) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_conta">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Cantidad de evaluaciones por hacer' , 'class' => 'text-center');
        $head_2 = array('data' => 'Cantidad de evaluaciones hechas'    , 'class' => 'text-center');
        $this->table->set_heading($head_1, $head_2);
        $val = 1;
        foreach ($datos as $row) {
            $row_cell_1 = array('data' => $row['total']      , 'class' => 'text-center');
            $row_cell_2 = array('data' => $row['ejecutadas'] , 'class' => 'text-center');
            $val++;
            $this->table->add_row($row_cell_1, $row_cell_2);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
}