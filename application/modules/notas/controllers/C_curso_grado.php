<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_curso_grado extends CI_Controller {

    private $_idRol     = null;
    private $_idUsuario = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->helper('html');
        $this->load->model('../m_utils');
        $this->load->model('m_curso_grado');
        $this->load->library('table');
        
        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_CURSOS_GRADO, NOTAS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);
    }

    public function index() {
        $data['titleHeader'] = 'Curso por grado';
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_NOTAS, NOTAS_FOLDER);  
	    $data['ruta_logo']        = MENU_LOGO_NOTAS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
	    $data['nombre_logo']      = NAME_MODULO_NOTAS;
                
    	$rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu'] = $this->load->view('v_menu', $data, true);
        
        //SECCION DE CREACION DE COMBOS
        $data['cmbAreas']        = __buildComboAreasAcademicas();
        $data['cmbTipoCurso']    = __buildComboTipoCurso();
        $data['cmbYears']        = __buildComboYearsAcademicos();
        $data['cmbGradoNivel']   = __buildComboGradoNivel_All();
        $this->load->view('v_curso_grado', $data);
    }

    function selectCursoxGrado() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGrado'));
            $idAnio  = _decodeCI(_post('idAnio'));
            if($idGrado == null || $idAnio == null) {
                throw new Exception(ANP);
            }                  
           $data['error'] = EXIT_SUCCESS; 
           $data['tablaCurs_Grado'] = $this->tablaselectCursoxGrado($idGrado, $idAnio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function selectEquivalencia() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idCurso = _simpleDecryptInt(_post('idCurso'));
            $idGrado = _decodeCI(_post('idGrado'));
            $idAnio  = _decodeCI(_post('idAnio'));
            if($idCurso == null || $idGrado == null || $idAnio == null) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tablaCurs_Equivalencia'] = $this->tablaselectEquivalencia($idCurso, $idGrado, $idAnio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function deleteCursosxGrado() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idCurso = _simpleDecryptInt(_post('idCurso'));
            $idGrado = _decodeCI(_post('idGrado'));
            $idAnio  = _decodeCI(_post('idAnio'));
            $data = $this->m_curso_grado->deleteCursosxGrado($idCurso, $idGrado, $idAnio);
            $data['tablaCurs_Grado'] = $this->tablaselectCursoxGrado($idGrado, $idAnio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tablaselectCursoxGrado($idGrado, $idAnio) {
        $arrayCursoxGrado = $this->m_curso_grado->getCursosxGrado($idGrado, $idAnio);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-page-size="5"
			                                   data-search="false" id="tbCursosByGrado" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        
        $head_0 = array('data' => 'Curso Ugel'  , 'class' => 'text-left');
        $head_1 = array('data' => 'Peso'        , 'class' => 'text-right');
        $head_2 = array('data' => 'Orden'       , 'class' => 'text-center');
        $head_3 = array('data' => 'Equiv.' , 'class' => 'text-center');
        $head_4 = array('data' => 'Acci&oacute;n'    , 'class' => 'text-center');
        
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $val = 0;
        foreach($arrayCursoxGrado as $array) {
            /*$issetCursosEquiv = $this->m_curso_grado->getEquivalencia($array->_id_curso_ugel, $idGrado, $idAnio);
            if($issetCursosEquiv == null) {
                $row_3 = array('data' => '-', 'class' => 'text-center');                
            } else {
                $actions = '<i class="mdi mdi-check">';
                $row_3 = array('data' => $actions, 'class' => 'text-center' );
            }*/
            
            $val++;
            //onclick="editarCursoxGrado($(this))"
            $row_3 = array('data' => $array->cant_cursos_equiv, 'class' => 'text-center');
            $actions = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="mostrarCursosEquivalentes($(this))">
                            <i class="mdi mdi-visibility"></i>
                        </button>
                        <button class="mdl-button mdl-js-button mdl-button--icon" data-toggle="modal" data-target="#modalSubirPaquete" data-paquete-text="N&uacute;mero m&aacute;ximo de colaboradores : 70" data-paquete-subdesc="debes solicitar al paquete Upper.">
                            <i class="mdi mdi-create"></i>
                        </button>
                        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="eliminarModal($(this))">
                            <i class="mdi mdi-delete"></i>
                        </button>';
            //ORDEN
            $disabledUp    = ($val == 1) ? 'disabled' : null;
            $disabledDown  = ($val == count($arrayCursoxGrado)) ? 'disabled' : null;
            $botonArriba = '<button class="mdl-button mdl-js-button mdl-button--icon up"   attr-orden="'.$val.'" attr-direccion="1" '.$disabledUp.'><i class="mdi mdi-arrow_drop_up"></i></button>';
            $botonAbajo  = '<button class="mdl-button mdl-js-button mdl-button--icon down" attr-orden="'.$val.'" attr-direccion="0" '.$disabledDown.'><i class="mdi mdi-arrow_drop_down"></i></button>';
            $row_2 = array('data' => $botonArriba.$botonAbajo);
            $row_0 = array('data' => $array->desc_curso, 'class' => 'text-left btnID', 'data-id_curso' => _simple_encrypt($array->_id_curso_ugel));
            $row_1 = array('data' => $array->peso, 'class' => 'text-center btnEditar', 'data-peso' => $array->peso);
            $row_4 = array('data' => $actions, 'class' => 'text-center');
            $this->table->add_row($row_0,$row_1,$row_2, $row_3, $row_4);
        }
        $tabla = $this->table->generate();
        return $tabla;
    } 
    
    function deleteEquivalencia() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idCursoEquiv = _simpleDecryptInt(_post('idCursoEquiv'));
            $idCurso      = _simpleDecryptInt(_post('idCurso'));
            $idGrado      = _decodeCI(_post('idGrado'));
            $idAnio       = _decodeCI(_post('idAnio'));
            
            if($idCursoEquiv == null || $idCurso == null || $idGrado == null || $idAnio == null) {
                throw new Exception(ANP);
            }
            $data = $this->m_curso_grado->deleteCursoEquivalencia($idCursoEquiv,$idCurso, $idGrado, $idAnio);
            $data['tablaCurs_equiv'] = $this->tablaselectEquivalencia($idCurso, $idGrado, $idAnio);
            $data['tablaCurs_Grado'] = $this->tablaselectCursoxGrado($idGrado, $idAnio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tablaselectEquivalencia($idCursoUgel, $idGrado, $idYear) {
        $arrayEquivalencia = $this->m_curso_grado->getEquivalencia($idCursoUgel, $idGrado, $idYear);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbCursosEquiv" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'Cursos Equivalentes', 'class' => 'text-left');
        $head_1 = array('data' => 'Peso'               , 'class' => 'text-center');
        $head_2 = array('data' => 'Orden'              , 'class' => 'text-center');
        $head_3 = array('data' => 'Acci&oacute;n'        , 'class' => 'text-center');
    
        $this->table->set_heading($head_0, $head_1, $head_2,$head_3);
        $val = 0;
        
        if($arrayEquivalencia != null) {
            foreach($arrayEquivalencia as $array) {
                $val++;
                // onclick="editarCursoEquiv($(this))"
                $actions = '<button class="mdl-button mdl-js-button mdl-button--icon" data-toggle="modal" data-target="#modalSubirPaquete" data-paquete-text="N&uacute;mero m&aacute;ximo de colaboradores : 70" data-paquete-subdesc="debes solicitar al paquete Upper.">
                                <i class="mdi mdi-create"></i>
                            </button>
                            <button class="mdl-button mdl-js-button mdl-button--icon" onclick="eliminar_modalEquivalencia($(this))">
                                <i class="mdi mdi-delete"></i>
                            </button>';
                //ORDEN
                $disabledUp    = ($val == 1) ? 'disabled' : null;
                $disabledDown  = ($val == count($arrayEquivalencia)) ? 'disabled' : null;
                $botonArriba   = '<button class="mdl-button mdl-js-button mdl-button--icon up2"   attr-orden="'.$val.'" attr-direccion="1" '.$disabledUp.'><i class="mdi mdi-arrow_drop_up"></i></button>';
                $botonAbajo    = '<button class="mdl-button mdl-js-button mdl-button--icon down2" attr-orden="'.$val.'" attr-direccion="0" '.$disabledDown.'><i class="mdi mdi-arrow_drop_down"></i></button>';
                $row_2 = array('data' => $botonArriba.$botonAbajo);
            
                $row_0 = array('data' => $array->desc_curso_equiv, 'class' => 'text-left btnEquivID'     , 'data-id_curso_equiv' => _simple_encrypt($array->_id_curso_equiv));
                $row_1 = array('data' => $array->peso            , 'class' => 'text-center  btnPesoEquiv', 'data-peso_equiv'     => $array->peso);
                $row_3 = array('data' => $actions                , 'class' => 'text-center');
                $this->table->add_row($row_0, $row_1, $row_2, $row_3);
            }
        }
        $tabla = $this->table->generate();
        return $tabla;
    }

    function registrarCurso() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $descCurso = utf8_decode(__only1whitespace(trim(_post('descCurso'))));
            $idArea    = _decodeCI(_post('idArea'));
            $abvrCurso = utf8_decode(trim(_post('abvrCurso')));
            $tipoCurso = _decodeCI(_post('tipoCurso'));
             
            if(strlen($descCurso) == 0) {
                throw new Exception('Ingrese el nombre del curso');
            }
            if(strlen($abvrCurso) == 0) {
                throw new Exception('Ingrese la abreviatura del curso');
            }        
            if($idArea == null && $tipoCurso == TIPO_CURSO_EQUIV) {
                throw new Exception('Seleccione el &aacute;rea acad&eacute;mica');
            }
            if($tipoCurso == null) {
                throw new Exception('Seleccione el tipo de curso');
            }
            $arryInsert = array();
            if($tipoCurso == CURSO_UGEL) {
                $countCurso = $this->m_curso_grado->validarCursoUgel($descCurso);
                if($countCurso >= 1) {
                    throw new Exception('Este curso ya existe');
                }
                $idCursoNew = $this->m_curso_grado->traerId();
                $arryInsert = array(
                    'id_curso'            => $idCursoNew,
                    'desc_curso'          => __mayusc($descCurso),
                    'abvr'                => $abvrCurso,
                    '_id_area_especifica' => $idArea
                );
                $data = $this->m_curso_grado->registrarCurso('cursos', $arryInsert);
            } else if($tipoCurso == CURSO_EQUIV) {
                $countCurso = $this->m_curso_grado->validarCursoEquivalente($descCurso);
                if($countCurso >= 1) {
                    throw new Exception('Este curso ya existe');
                }
                $arryInsert = array(
                    'desc_curso_equiv' => __mayusc($descCurso),
                    'abvr_curso_equiv' => $abvrCurso
                );
                $data = $this->m_curso_grado->registrarCurso('curso_equivalente', $arryInsert);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getCursosPorAsignar() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGrado'));
            $idAnio  = _decodeCI(_post('idAnio'));
            if($idGrado == null || $idAnio == null) {
                throw new Exception(ANP);
            }
            /////////////////////////////
            $arrayTabla = $this->m_curso_grado->getCursosUgel($idGrado, $idAnio);
            $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-page-size="10"
			                                   data-search="false" id="tbCursosAsig" data-show-columns="false">',
                          'table_close' => '</table>');
            $this->table->set_template($tmpl);
            
            $head_0 = array('data' => 'Curso'        , 'class' => 'text-left');
            $head_1 = array('data' => '&Aacute;rea'  , 'class' => 'text-left');
            $head_2 = array('data' => 'Acci&oacute;n','class' => 'text-center', 'data-field' => 'checkbox');
            
            $this->table->set_heading($head_0, $head_1, $head_2);
            $val = 0;
            foreach($arrayTabla as $array) {//
                $val++;
                $checkbox = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="chk_'.$val.'" >
                                 <input type="checkbox" id="chk_'.$val.'" class="mdl-checkbox__input" onclick="handleCheckAsigCurso($(this))"
                                        data-id_curso="'._simple_encrypt($array['id_curso']).'" >
                             </label>';
                 
                $row_0 = array('data' => $array['desc_curso'], 'class' => 'text-left');
                $row_1 = array('data' => $array['desc_area'] , 'class' => 'text-left');
                $row_2 = array('data' => $checkbox, 'class' => 'text-center');
                $this->table->add_row($row_0, $row_1, $row_2);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tablaCursAsignar'] = $this->table->generate();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getCursosEquivalentesModal() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {      
            $idCurso = _simpleDecryptInt(_post('idCurso'));
            $idGrado = _decodeCI(_post('idGrado'));
            $idAnio  = _decodeCI(_post('idAnio'));
            if($idCurso == null || $idGrado == null || $idAnio == null) {
                throw new Exception(ANP);
            }
            $arrayTabla = $this->m_curso_grado->getCursosEquivalentes($idCurso, $idGrado, $idAnio);
            $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-page-size="10" 
			                                   data-search="false" id="tbCursosequivalentes" data-show-columns="false">',
                          'table_close' => '</table>');
            $this->table->set_template($tmpl);
            
            $head_0 = array('data' => 'Curso Equivalente', 'class' => 'text-left');
            $head_1 = array('data' => 'Abreviatura', 'class' => 'text-left');
            $head_2 = array('data' => 'Acci&oacute;n','class' => 'text-center', 'data-field' => 'checkbox');
            $this->table->set_heading($head_0,$head_1,$head_2);
            $val = 0;
            foreach($arrayTabla as $array) {
                $val++;
                $checkbox = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="chk-'.$val.'" >
                                <input type="checkbox" id="chk-'.$val.'" class="mdl-checkbox__input" onclick="handleCheckAsigCursosEquivalentes($(this))"
                                    data-id_cursoEquiv="'._encodeCI($array['id_curso_equiv']).'">
                             </label>';
                
                $row_0 = array('data' => $array['desc_curso_equiv'], 'class' => 'text-left');
                $row_1 = array('data' => $array['abvr_curso_equiv'], 'class' => 'text-left');
                $row_2 = array('data' => $checkbox, 'class' => 'text-center');
                $this->table->add_row($row_0, $row_1, $row_2);
            }
            $data['tablaCursEquivAsignar'] = $this->table->generate(); 
            $data['error'] = EXIT_SUCCESS;        
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function asignarCursoUgel() {
        $orden         = 1;
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGradoGlobal'));
            $idAnio  = _decodeCI(_post('idAnioGlobal'));
            $orden = $this->m_curso_grado->getOrdenCursoByGradoMax($idGrado, $idAnio);
            if($idGrado == null || $idAnio == null) {
                throw new Exception(ANP);
            }
            $arrayCursoAsig = _post('arrayCursoAsig');
            if(!is_array($arrayCursoAsig) ) {
                throw new Exception(ANP);
            }
            if(count($arrayCursoAsig) == 0) {
                throw new Exception('Seleccione cursos');
            }
            ////////////////////////////////////////////////////////////////////////////////
            $arrayGeneral = array();
            foreach ($arrayCursoAsig as $arrayCurso) {
                $nOrden = $orden + 1;
                $idCurso = _simpleDecryptInt($arrayCurso['id_curso']);
                if($idCurso == null) {
                    throw new Exception(ANP);
                }
                $arrayDatos = array(
                    "_id_curso_ugel"   => $idCurso,
                    "_id_grado"        => $idGrado,
                    "year_acad"        => $idAnio,
                    "orden"            => $nOrden
                );
                array_push($arrayGeneral, $arrayDatos);
                $orden++;
            }
            $data = $this->m_curso_grado->insertar_cursos_ugel($arrayGeneral);
            $data['error'] = EXIT_SUCCESS;
            $data['tablaCurs_Grado'] = $this->tablaselectCursoxGrado($idGrado, $idAnio);
            
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function asignarCursoEquivUgel() {
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        try {
            $idAnio   = _decodeCI(_post('idAnioGlobal'));
            $idCurso  = _simpleDecryptInt(_post('idCursoGlobal'));
            $idGrado  = _decodeCI(_post('idGradoGlobal'));
            $orden = $this->m_curso_grado->getOrdenEquivMax($idCurso, $idGrado, $idAnio);
            if($idGrado == null || $idAnio == null || $idCurso == null) {
                throw new Exception(ANP);
            }
            $arrayCursoEquivAsig = _post('arrayCursoEquivAsig');    
            if(!is_array($arrayCursoEquivAsig) ) {
                throw new Exception(ANP);
            }
            if(count($arrayCursoEquivAsig) == 0) {
                throw new Exception('Seleccione cursos equivalentes');
            }
            ////////////////////////////////////////////////////////////////////////////////
            $arrayGeneral = array();
    
            foreach ($arrayCursoEquivAsig as $arrayEquivCurso) {
                $nOrden = $orden+1;
                $idCursoEquiv = _decodeCI($arrayEquivCurso['id_cursoequiv']);
                if($idCursoEquiv == null) {
                    throw new Exception(ANP);
                }
                $arrayDatos = array(
                    "_id_curso_ugel"   => $idCurso,
                    "_id_grado"        => $idGrado,
                    "_year_acad"       => $idAnio,
                    "_id_curso_equiv"  => $idCursoEquiv,
                    "orden"            => $nOrden
                );
                array_push($arrayGeneral, $arrayDatos);
                $orden++;
            }
            $data = $this->m_curso_grado->insertar_cursosEquiv($arrayGeneral);
            $data['error'] = EXIT_SUCCESS;
            $data['tablaCurs_Equivalencia'] = $this->tablaselectEquivalencia($idCurso, $idGrado, $idAnio);
            $data['tablaCurs_Grado'] = $this->tablaselectCursoxGrado($idGrado, $idAnio);
    
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function changeOrdenCursoUgel() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $orden     = _post('orden');
            $idCurso   = _simpleDecryptInt(_post('idCurso'));
            $direccion = _post('direccion');
            $idAnio   = _decodeCI(_post('idAnioGlobal'));
            $idGrado  = _decodeCI(_post('idGradoGlobal'));

            if($direccion !== ORDEN_SUBIR && $direccion !== ORDEN_BAJAR){
                throw new Exception(ANP);
            }
            if($idCurso == null || $idAnio == null || $idGrado == null) {
                throw new Exception(ANP);
            }
            //VERIFICAR QUE EL CURSO / GRADO / ANIO EXISTAN EN CURSO_UGEL_X_GRADO
            //
            $idCursoChange = $this->m_curso_grado->getCursoACambiarOrden($idGrado, $idAnio, $orden,$idCurso ,$direccion);
            if($idCursoChange == null){
                throw new Exception(ANP);
            }
            
            //ACTUALIZA EL SIGUIENTE O ANTERIOR CURSO
            $arrayUpdate1 = array(
                '_id_grado'     => $idGrado,
                'year_acad'     => $idAnio,
                'orden'         => $orden,
                'idCursoChange' => $idCursoChange
            );
            //CAMBIA EL NUEVO ORDEN PARA EL CURSO SELECCIONADO
            $orden = ($direccion == ORDEN_SUBIR) ? $orden - 1 : $orden + 1 ;
            $arrayUpdate2 = array(
                '_id_grado'     => $idGrado,
                'year_acad'     => $idAnio,
                'orden'         => $orden,
                'idCursoChange' => $idCurso
            );
            $data = $this->m_curso_grado->updateCurso_Orden($arrayUpdate1, $arrayUpdate2);
            if($data['error'] == EXIT_SUCCESS) {
                $data['tablaCurs_Grado'] = $this->tablaselectCursoxGrado($idGrado, $idAnio);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeOrdenCursoEquiv() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $orden        =  _post('orden');
            $idCurso      =  _simple_decrypt(_post('idCursoGlobal'));
            $idCursoEquiv =  _simpleDecryptInt(_post('idCursoEquiv'));
            $direccion    =  _post('direccion');
            $idAnio       =  _decodeCI(_post('idAnioGlobal'));
            $idGrado      =  _decodeCI(_post('idGradoGlobal'));
            
            if($direccion !== ORDEN_SUBIR && $direccion !== ORDEN_BAJAR){
                throw new Exception(ANP);
            }
            if($idCursoEquiv == null || $idAnio == null || $idGrado == null || $idCurso == null) {
                throw new Exception(ANP);
            }
            //VERIFICAR QUE EL CURSO / GRADO / ANIO EXISTAN EN CURSO_UGEL_X_GRADO
            //
            $idCursoEquivChange = $this->m_curso_grado->getCursoEquivCambiarOrden($idGrado, $idAnio, $orden, $idCurso, $direccion);
            if($idCursoEquivChange == null){
                throw new Exception(ANP);
            }
            //ACTUALIZA EL SIGUIENTE O ANTERIOR CURSO
            $arrayUpdate1 = array(
                '_id_grado'           => $idGrado,
                '_year_acad'          => $idAnio,
                'orden'               => $orden,
                '_id_curso_ugel'      => $idCurso,
                'idCursoEquivChange'  => $idCursoEquivChange
            );
            //CAMBIA EL NUEVO ORDEN PARA EL CURSO SELECCIONADO
            $orden = ($direccion == ORDEN_SUBIR) ? $orden - 1 : $orden + 1 ;
            $arrayUpdate2 = array(
                '_id_grado'           => $idGrado,
                '_year_acad'          => $idAnio,
                'orden'               => $orden,
                '_id_curso_ugel'      => $idCurso,
                'idCursoEquivChange'  => $idCursoEquiv
            );
            $data = $this->m_curso_grado->updateCursoEquiv_Orden($arrayUpdate1, $arrayUpdate2);
            if($data['error'] == EXIT_SUCCESS) {
                $data['tablaCurs_Equivalencia'] = $this->tablaselectEquivalencia($idCurso, $idGrado, $idAnio);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function actualizarCursoxGrado() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idCurso = _simpleDecryptInt(_post('idCurso'));
            $idGrado = _decodeCI(_post('idGrado'));
            $idAnio  = _decodeCI(_post('idAnio'));
            $peso    = _post('peso');
            
            if($peso > 9) {
                throw new Exception('El peso no debe exceder de 9');            
            }
            if($peso <= 0) {
                throw new Exception('El peso no puede ser cero ni n&uacute;mero negativo');
            }
            if($idCurso == null || $idAnio == null || $idGrado == null || $peso == null) {
                throw new Exception(ANP);
            }
            $data = array(
                'peso' => $peso
            );
            $data = $this->m_curso_grado->actualizarCursoxGrado($idCurso, $idGrado, $idAnio, $data);
            $data['tablaCurs_Grado'] = $this->tablaselectCursoxGrado($idGrado, $idAnio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function actualizarCursoEquiv() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idCursoEquiv = _simpleDecryptInt(_post('idCursoEquiv'));
            $idCurso      = _simpleDecryptInt(_post('idCurso'));
            $idGrado      = _decodeCI(_post('idGrado'));
            $idAnio       = _decodeCI(_post('idAnio'));
            $peso         = _post('peso');
            
            if($peso > 9) {
                throw new Exception('El peso no debe exceder de 9');
            }            
            if($peso <= 0) {
                throw new Exception('El peso no puede ser cero ni n&uacute;mero negativo');
            }
            if($idCursoEquiv == null || $idCurso == null || $idGrado == null || $idAnio == null) {
                throw new Exception(ANP);
            }
            
            $data = array(
                'peso' => $peso
            );

            $data = $this->m_curso_grado->actualizarCursoEquiv($idCursoEquiv, $idCurso, $idGrado, $idAnio, $data);
            $data['tablaCurs_equiv'] = $this->tablaselectEquivalencia($idCurso, $idGrado, $idAnio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }    
    //////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	function logOut() {
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
    
    function getRolesByUsuario() {
        $idPersona = _getSesion('id_persona');
        $idRol     = _getSesion('id_rol');
        $roles     = $this->m_usuario->getRolesByUsuario($idPersona, $idRol);
        $return = null;
        foreach ($roles as $var) {
            $check = null;
            $class = null;
            if ($var->check == 1) {
                $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
                $class = 'active';
            }
            $idRol = _simple_encrypt($var->nid_rol);
            $return .= "<li class='" . $class . "'>";
            $return .= '<a href="javascript:void(0)" onclick="cambioRol(\'' . $idRol . '\')"><span class="title">' . $var->desc_rol . $check . '</span></a>';
            $return .= "</li>";
        }
        $dataUser = array(
            "roles_menu" => $return
        );
        $this->session->set_userdata($dataUser);
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