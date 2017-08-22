<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_avance_efqm extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;
    //private $_idEncuEFQM = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('m_crear_encuesta');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, ID_PERMISO_SEGUIMIENTO_EFQM, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
        //$this->_idEncuEFQM = $this->m_encuesta->getEncuestaAperturadaEFQM_PPFF();
    }
    
    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SENC, SENC_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader']      = 'Avance Encuesta';
        $data['ruta_logo']        = MENU_LOGO_SENC;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SENC;
        $data['nombre_logo']      = NAME_MODULO_SENC;
        //MENU
        $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_SENC, $this->_idUserSess);
        $data['apps']             = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']             = $this->load->view('v_menu', $data, true);
        
        /*if($this->_idEncuEFQM == null) {
            $data['msj'] = 'No hay una encuesta EFQM de padres de familia que esté Aperturada. Vuelve despu&eacute;s';
            $this->load->view('errors/V_no_show', $data);
        } else {*/
        $encuestasEFQM = $this->m_encuesta->getEncuestasEFQM_Aperturadas();
        $data['radiosEncus'] = $this->buildHTML_PickEncuEFQM($encuestasEFQM);
        $this->load->view('vf_encuesta/v_avance_efqm', $data);
        //}
    }
    
    function buildHTML_PickEncuEFQM($encuestasEFQM) {
        $html = null;
        foreach ($encuestasEFQM as $row) {
            $html .= '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-b-15" for="radio-'.$row['rownum'].'">
                          <input type="radio" id="radio-'.$row['rownum'].'" class="mdl-radio__button" name="radioEncus" value="'._encodeCI($row['id_encuesta']).'" >
                          <span class="mdl-radio__label">'.$row['titulo_encuesta'].'</span>
                      </label>';
        }
        return $html;
    }
    
    function getSedesByEncuesta() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEncuesta = _decodeCI(_post('idEncu'));
            if($idEncuesta == null) {
                throw new Exception(ANP);
            }
            $encuData = $this->m_utils->getCamposById('senc.encuesta', array('_id_tipo_encuesta','titulo_encuesta'), 'id_encuesta', $idEncuesta);
            $tipoEncuesta     = $encuData['_id_tipo_encuesta'];
            $data['encuesta'] = $encuData['titulo_encuesta'];
            if($tipoEncuesta == TIPO_ENCUESTA_ALUMNOS) {
                $data['sedes_tabla'] = $this->buildTablaAvanceSedesEstu($this->m_crear_encuesta->getSedesCantEstu(), $idEncuesta);
            } else if($tipoEncuesta == TIPO_ENCUESTA_PADREFAM) {
                $data['sedes_tabla'] = $this->buildTablaAvanceSedes($this->m_crear_encuesta->getSedesAvanceByEncuestaEFQM($idEncuesta));
            } else if($tipoEncuesta == TIPO_ENCUESTA_DOCENTE || $tipoEncuesta == TIPO_ENCUESTA_PERSADM) {
                $area = ($tipoEncuesta == TIPO_ENCUESTA_DOCENTE ? ID_AREA_ACADEMICA : 0);
                $data['sedes_tabla'] = $this->buildTablaAvanceSedesDocePersAdm($this->m_crear_encuesta->getSedesAvanceByEncuestaDocentePersAdmEFQM($area));
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
   
    function refreshSedes() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEncuesta = _decodeCI(_post('idEncuesta'));
            if($idEncuesta == null) {
                throw new Exception(ANP);
            }
            $encuData = $this->m_utils->getCamposById('senc.encuesta', array('_id_tipo_encuesta','titulo_encuesta'), 'id_encuesta', $idEncuesta);
            $tipoEncuesta = $encuData['_id_tipo_encuesta'];
            if($tipoEncuesta == TIPO_ENCUESTA_ALUMNOS) {
                $data['sedes_tabla'] = $this->buildTablaAvanceSedesEstu($this->m_crear_encuesta->getSedesCantEstu(), $idEncuesta);
            } else if($tipoEncuesta == TIPO_ENCUESTA_PADREFAM) {
                $data['sedes_tabla'] = $this->buildTablaAvanceSedes($this->m_crear_encuesta->getSedesAvanceByEncuestaEFQM($idEncuesta));
            } else if($tipoEncuesta == TIPO_ENCUESTA_DOCENTE || $tipoEncuesta == TIPO_ENCUESTA_PERSADM) {
                $area = ($tipoEncuesta == TIPO_ENCUESTA_DOCENTE ? ID_AREA_ACADEMICA : 0);
                $data['sedes_tabla'] = $this->buildTablaAvanceSedesDocePersAdm($this->m_crear_encuesta->getSedesAvanceByEncuestaDocentePersAdmEFQM($area));
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDetalleAvanceAula() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede = _decodeCI(_post('idSede'));
            $idEncuesta = _decodeCI(_post('idEncuesta'));
            if($idSede == null || $idEncuesta == null) {
                throw new Exception(ANP);
            }
            $encuData = $this->m_utils->getCamposById('senc.encuesta', array('_id_tipo_encuesta','titulo_encuesta'), 'id_encuesta', $idEncuesta);
            $tipoEncuesta = $encuData['_id_tipo_encuesta'];
            if($tipoEncuesta == TIPO_ENCUESTA_ALUMNOS) {
                $data['tablaAulas'] = $this->buildTablaAvanceAulasEstuEFQM($this->m_crear_encuesta->getAvanceAulasBySedeOnlyHecho($idSede), $idEncuesta);
            } else if($tipoEncuesta == TIPO_ENCUESTA_PADREFAM) {
                $data['tablaAulas'] = $this->buildTablaAvanceAulas($this->m_crear_encuesta->getAvanceAulasBySede($idSede));
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDetalleAvanceArea() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede = _decodeCI(_post('idSede'));
            $idEncuesta = _decodeCI(_post('idEncuesta'));
            if($idSede == null || $idEncuesta == null) {
                throw new Exception(ANP);
            }
            $encuData = $this->m_utils->getCamposById('senc.encuesta', array('_id_tipo_encuesta','titulo_encuesta'), 'id_encuesta', $idEncuesta);
            $tipoEncuesta = $encuData['_id_tipo_encuesta'];
            if($tipoEncuesta == TIPO_ENCUESTA_PERSADM) {
                $data['tablaAreas'] = $this->buildTablaAvanceAreasEFQM($this->m_crear_encuesta->getAvanceAreasPersAdm($idSede), $idEncuesta);
            } else if($tipoEncuesta == TIPO_ENCUESTA_DOCENTE) {
                $data['tablaAreas'] = $this->buildTablaAvanceAreasEFQM($this->m_crear_encuesta->getAvanceAreasDocente($idSede), $idEncuesta);
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaAvanceAreasEFQM($arryAreas, $idEncuesta) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   id="tbAreasAvance" data-pagination="true" data-page-size="15">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0  = array('data' => 'Área', 'class' => 'text-left');
        $head_1  = array('data' => 'Cant. Pers.', 'class' => 'text-right');
        $head_2  = array('data' => 'Cant. Encu.' , 'class' => 'text-right');
        $head_3  = array('data' => '%' , 'class' => 'text-right');
        $head_4  = array('data' => 'Avance' , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        foreach($arryAreas as $row) {
            $porcent  = ($row['cant_pers'] > 0) ? (round(($row['cant_hecho'] * 100 / $row['cant_pers']), 1)) : 0;
            $celda_0  = array('data' => _ucwords($row['desc_area']), 'data-id_area' => _encodeCI($row['id_area']) , 'class' => 'claseIdentifArea');
            $celda_1  = array('data' => $row['cant_pers']  , 'class' => 'text-right');
            $celda_2  = array('data' => $row['cant_hecho']  , 'class' => 'text-right');
            $celda_3  = array('data' => $porcent           , 'class' => 'text-right');
            $celda_4  = array('data' => '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="verPersonalEncu($(this));">
                                             <i class="mdi mdi-visibility"></i>
                                         </button>', 'class' => 'text-center');
            /////////////
            $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $celda_4);
        }
        return $this->table->generate();
    }
    
    function getPersonalEncuestado() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idArea     = _decodeCI(_post('idArea'));
            $idSede     = _decodeCI(_post('idSede'));
            $idEncuesta = _decodeCI(_post('idEncu'));
            if($idEncuesta == null || $idArea == null || $idSede == null) {
                throw new Exception(ANP);
            }
            $encuData = $this->m_utils->getCamposById('senc.encuesta', array('_id_tipo_encuesta'), 'id_encuesta', $idEncuesta);
            $tipoEncuesta     = $encuData['_id_tipo_encuesta'];
            $data['personal'] = $this->buildTablaPersonalEncuestado($this->m_crear_encuesta->getPersonalEncuestado($tipoEncuesta, $idArea, $idSede));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaPersonalEncuestado($areasResult) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   id="tbPersoEncu" data-pagination="true" data-page-size="10">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0  = array('data' => '#', 'class' => 'text-left');
        $head_1  = array('data' => 'Personal', 'class' => 'text-left');
        $head_2  = array('data' => '¿Realiz&oacute;?', 'class' => 'text-center');
        $head_3  = array('data' => 'Cargo', 'class' => 'text-left');
        $head_4  = array('data' => 'Usuario' , 'class' => 'text-left');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        foreach($areasResult as $row) {
            $realizo = null;
            if($row['realizo'] == '1') {//REALIZO ENCUESTA
                $realizo = '<button class="mdl-button mdl-js-button mdl-button--icon">
                                <i class="mdi mdi-check"></i>
                            </button>';
            }
            
            $celda_0  = array('data' => $row['rownum'], 'class' => 'text-left');
            $celda_1  = array('data' => $row['persona'], 'class' => 'text-left');
            $celda_2  = array('data' => $realizo , 'class' => 'text-center');
            $celda_3  = array('data' => $row['cargo'], 'class' => 'text-left');
            $celda_4  = array('data' => $row['usuario'], 'class' => 'text-left');
            /////////////
            $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $celda_4);
        }
        return $this->table->generate();
    }
    
    function buildTablaAvanceAulas($aulasAvanceArry) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   id="tbAulasAvance" data-pagination="true" data-page-size="15">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0  = array('data' => 'Aula');
        $head_1  = array('data' => 'Grado', 'class' => 'text-center');
        $head_2  = array('data' => 'Cant. Estud.', 'class' => 'text-center');
        $head_3  = array('data' => 'Cant. Encu.' , 'class' => 'text-center');
        $head_4  = array('data' => '%' , 'class' => 'text-center');
        $head_5  = array('data' => 'Avance' , 'class' => 'text-center');
        $val = 0;
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5);
        foreach($aulasAvanceArry as $row) {
            $val++;
            $celda_0  = array('data' => _ucwords($row['desc_aula']), 'data-id_aula' => _encodeCI($row['nid_aula']) , 'class' => 'claseIdentifAula '.$row['color_nivel']);
            $celda_1  = array('data' => $row['grado']      , 'class' => 'text-center '.$row['color_nivel']);
            $celda_2  = array('data' => $row['cant_estu']  , 'class' => 'text-center '.$row['color_nivel']);
            $celda_3  = array('data' => $row['cant_hecho'] , 'class' => 'text-center '.$row['color_nivel']);
            $celda_4  = array('data' => $row['porct']      , 'class' => 'text-center '.$row['color_nivel']);
            $celda_5  = array('data' => '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="verChecksAula($(this));">
                                             <i class="mdi mdi-visibility"></i>
                                         </button>'        , 'class' => 'text-center '.$row['color_nivel']);
            /////////////
            $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $celda_4, $celda_5);
        }
        return $this->table->generate();
    }
    
    function buildTablaAvanceAulasEstuEFQM($aulasAvanceArry, $idEncuesta) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   id="tbAulasAvance" data-pagination="true" data-page-size="15">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0  = array('data' => 'Aula');
        $head_1  = array('data' => 'Grado', 'class' => 'text-center');
        $head_2  = array('data' => 'Cant. Estud.', 'class' => 'text-right');
        $head_3  = array('data' => 'Cant. Encu.' , 'class' => 'text-right');
        $head_4  = array('data' => '%' , 'class' => 'text-right');

        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        foreach($aulasAvanceArry as $row) {
            $cantEncu = $this->m_crear_encuesta->getCantidadEncuestadosByAula_EFQM_Estu($idEncuesta, $row['nid_sede'], $row['nid_aula']);
            $porcent  = round(($cantEncu * 100 / $row['cant_estu']), 1);
            $candado = null;
            if($row['candado'] != null) {
                $candado = '<button class="mdl-button mdl-js-button mdl-button--icon">
                                <i class="mdi mdi-lock"></i>
                            </button>';
            }
            $celda_0  = array('data' => $candado._ucwords($row['desc_aula']), 'data-id_aula' => _encodeCI($row['nid_aula']) , 'class' => 'claseIdentifAula '.$row['color_nivel']);
            $celda_1  = array('data' => $row['grado']      , 'class' => 'text-center '.$row['color_nivel']);
            $celda_2  = array('data' => $row['cant_estu']  , 'class' => 'text-center '.$row['color_nivel']);
            $celda_3  = array('data' => $cantEncu          , 'class' => 'text-center '.$row['color_nivel']);
            $celda_4  = array('data' => $porcent           , 'class' => 'text-center '.$row['color_nivel']);
            /////////////
            $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $celda_4);
        }
        return $this->table->generate();
    }
    
    function buildTablaAvanceSedes($sedesAvanceArry) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   id="tbSedesAvance">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0  = array('data' => 'Sede'        , 'class' => 'text-left');
        $head_1  = array('data' => 'Cant. Estud.', 'class' => 'text-right');
        $head_2  = array('data' => 'Cant. Encu.' , 'class' => 'text-right');
        $head_3  = array('data' => '%'           , 'class' => 'text-right');
        $head_4  = array('data' => 'Avance'      , 'class' => 'text-center');
        $val = 0;
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $totalEstu      = 0;
        $totalCantHecho = 0;
        foreach($sedesAvanceArry as $row) {
            $val++;
            $totalEstu = $totalEstu + $row['cant_estu'];
            $totalCantHecho = $totalCantHecho + $row['cant_hecho'];
            $celda_0  = array('data' => $row['desc_sede'], 'data-id_sede' => _encodeCI($row['nid_sede']) , 'class' => 'claseIdentif');
            $celda_1  = array('data' => $row['cant_estu'], 'class' => 'text-right');
            $celda_2  = array('data' => $row['cant_hecho'], 'class' => 'text-right');
            $celda_3  = array('data' => $row['porct'], 'class' => 'text-right');
            /*$celda_4  = array('data' => '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="verDetalleAulas($(this));">
                                            <i class="mdi mdi-visibility"></i>
                                        </button>'.'<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="imprimirEncuestaFisicaSede($(this));">
                                            <i class="mdi mdi-print"></i>
                                        </button>');*/
            $idButton = "opciones_".$val;
            /////////////
            $btnVerAulas = '<li class="mdl-menu__item" onclick="verDetalleAulas($(this));"><i class="mdi mdi-visibility"></i> Aulas</li>';
            $btnImprList = '<li class="mdl-menu__item" onclick="imprimirEncuestaFisicaSede($(this));"><i class="mdi mdi-print"></i> Listado por Aula</li>';
            $btnImprEncu = '<li class="mdl-menu__item" onclick="imprimirEncuestaFisica($(this));"><i class="mdi mdi-print"></i> Imprimir Encuesta</li>';
            $botones = '<ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" id="opcionesEncuesta'.$val.'" for="'.$idButton.'">'
                        .$btnVerAulas.$btnImprList.$btnImprEncu.'
                        </ul>';
            $botonGeneral = '
	                             <button id="'.$idButton.'" class="mdl-button mdl-js-button mdl-button--icon" >
                                     <i class="mdi mdi-more_vert"></i>
                                 </button>
	                             '.$botones;
            /////////////
            $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $botonGeneral);
        }
        $celda_0  = array('data' => 'TOTAL');
        $celda_1  = array('data' => $totalEstu);
        $celda_2  = array('data' => $totalCantHecho);
        $celda_3  = array('data' => round((($totalCantHecho * 100 / $totalEstu) ), 1) );
        $celda_4  = array('data' => null);
        /////////////
        $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $celda_4);
        return $this->table->generate();
    }
    
    function buildTablaAvanceSedesEstu($sedesArry, $idEncuesta) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   id="tbSedesAvance">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0  = array('data' => 'Sede'        , 'class' => 'text-left');
        $head_1  = array('data' => 'Cant. Estud.', 'class' => 'text-right');
        $head_2  = array('data' => 'Cant. Encu.' , 'class' => 'text-right');
        $head_3  = array('data' => '%'           , 'class' => 'text-right');
        $head_4  = array('data' => 'Avance'      , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $totalEstu      = 0;
        $totalCantHecho = 0;
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        foreach($sedesArry as $row) {
            $cantEncu = $this->m_crear_encuesta->getCantidadEncuestadosBySede_EFQM_Estu($db, $idEncuesta, $row['nid_sede']);
            $totalEstu = $totalEstu + $row['cant_estu'];
            $totalCantHecho = $totalCantHecho + $cantEncu;
            $celda_0  = array('data' => $row['desc_sede'], 'data-id_sede' => _encodeCI($row['nid_sede']) , 'class' => 'claseIdentif');
            $celda_1  = array('data' => $row['cant_estu'], 'class' => 'text-right');
            $celda_2  = array('data' => ($cantEncu == null ? 0 : $cantEncu) );
            $celda_3  = array('data' => round((($cantEncu * 100) /  ($row['cant_estu'] == 0 ? 1 : $row['cant_estu'])  ), 1) , 'class' => 'text-right');
            $idButton = "opciones_".$row['rownum'];
            /////////////
            $btnVerAulas = '<li class="mdl-menu__item" onclick="verDetalleAulas($(this));"><i class="mdi mdi-visibility"></i> Aulas</li>';
            $botones = '<ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" id="opcionesEncuesta'.$row['rownum'].'" for="'.$idButton.'">'
                .$btnVerAulas.'
                        </ul>';
            $botonGeneral = '   <button id="'.$idButton.'" class="mdl-button mdl-js-button mdl-button--icon" >
                                     <i class="mdi mdi-more_vert"></i>
                                 </button>
	                             '.$botones;
            /////////////
            $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $botonGeneral);
        }
        $celda_0  = array('data' => 'TOTAL');
        $celda_1  = array('data' => $totalEstu);
        $celda_2  = array('data' => $totalCantHecho);
        $celda_3  = array('data' => ($totalEstu > 0 ? (  round((($totalCantHecho * 100 / $totalEstu) ), 1)  ) : 0  ));
        $celda_4  = array('data' => null);
        /////////////
        $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $celda_4);
        return $this->table->generate();
    }
    
    function buildTablaAvanceSedesDocePersAdm($sedesAvanceArry) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   id="tbSedesAvance">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0  = array('data' => 'Sede'        , 'class' => 'text-left');
        $head_1  = array('data' => 'Cant. Pers.' , 'class' => 'text-right');
        $head_2  = array('data' => 'Cant. Encu.' , 'class' => 'text-right');
        $head_3  = array('data' => '%'           , 'class' => 'text-right');
        $head_4  = array('data' => 'Avance'      , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $totalPers      = 0;
        $totalCantHecho = 0;
        foreach($sedesAvanceArry as $row) {
            $totalPers = $totalPers + $row['cant_pers'];
            $totalCantHecho = $totalCantHecho + $row['cant_hecho'];
            $celda_0  = array('data' => $row['desc_sede'], 'data-id_sede' => _encodeCI($row['nid_sede']) , 'class' => 'claseIdentif');
            $celda_1  = array('data' => $row['cant_pers'], 'class' => 'text-right');
            $celda_2  = array('data' => $row['cant_hecho'], 'class' => 'text-right');
            $celda_3  = array('data' => $row['porct'], 'class' => 'text-right');
            $idButton = "opciones_".$row['rownum'];
            /////////////
            $btnVerAreas = '<li class="mdl-menu__item" onclick="verDetalleSedes($(this));"><i class="mdi mdi-visibility"></i> Áreas</li>';
            $btnExpoList = '<li class="mdl-menu__item" onclick="imprimirEncuestaPersonalAvanceSede($(this));"><i class="mdi mdi-print"></i> Exportar Avance</li>';
            $botones = '<ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" id="opcionesEncuesta'.$row['rownum'].'" for="'.$idButton.'">'
                .$btnVerAreas.$btnExpoList.'
                        </ul>';
            $botonGeneral = '
	                             <button id="'.$idButton.'" class="mdl-button mdl-js-button mdl-button--icon" >
                                     <i class="mdi mdi-more_vert"></i>
                                 </button>
	                             '.$botones;
            /////////////
            $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $botonGeneral);
        }
        $celda_0  = array('data' => 'TOTAL');
        $celda_1  = array('data' => $totalPers);
        $celda_2  = array('data' => $totalCantHecho);
        $celda_3  = array('data' => round((($totalCantHecho * 100 / $totalPers) ), 1) );
        $celda_4  = array('data' => null);
        /////////////
        $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $celda_4);
        return $this->table->generate();
    }
    
    /**
     * Genera el pdf para imprimir la encuesta fisica por sede
     * @author dfloresgonz
     * @since  29.09.2016
     */
    function imprimirEncuestaBySede() {
        $idSede = _decodeCI(_post('idSede'));
        $idEncuesta = _decodeCI(_post('idEncu'));
        if($idSede == null || $idEncuesta == null) {
            throw new Exception(ANP);
        }
        $data['htmlBody'] = __getHTML_RubricaFisicaEFQM_Padres($idEncuesta, $idSede);
        $data['sede_desc'] = $this->m_utils->getById('sede', 'desc_sede', 'nid_sede', $idSede);
        $this->load->library('m_pdf');
        $pdfObj = $this->m_pdf->load('','A4-L', 7, 'Arial', 9, 9, 16, 16, 9, 9, 'L');
        $codigoEncuesta = $this->m_utils->getById("senc.encuesta", "desc_enc", "id_encuesta", $this->_idEncuEFQM);
        $codigoEncuesta = utf8_encode('Encuesta '.$codigoEncuesta);
        //$datosAula = $this->m_utils->getDatosResumenAula($idAula);
        
        $pdfObj->SetColumns(2);
        $pdfObj->KeepColumns = true;
        
        $pdfObj->SetFooter(/*utf8_encode($datosAula['descrip']).' - '.*/$codigoEncuesta.'||'/*.date('d/m/Y h:i:s a')*/);
        $data['pdfObj'] = $pdfObj;
        $this->load->view('vf_encuesta/v_pdf_encuesta_fisica', $data);
    }
    
    function imprimirListadoTutoresChecklist() {
        $idSede = _decodeCI(_post('idSede'));
        if($idSede == null) {
            throw new Exception(ANP);
        }
        $this->load->library('m_pdf');
        $pdfObj = $this->m_pdf->load('','A4-L', 8, 'Arial', 15, 15, 16, 16, 9, 9, 'L');
        
        $data['sede_desc'] = $this->m_utils->getById('sede', 'desc_sede', 'nid_sede', $idSede);
        $data['aulasBySede'] = $this->m_utils->getAulasTutorBySede($idSede);
        
        $codigoEncuesta = null;
        $pdfObj->SetFooter(/*utf8_encode($datosAula['descrip']).' - '.*/$codigoEncuesta.'||'/*.date('d/m/Y h:i:s a')*/);
        $data['pdfObj'] = $pdfObj;
        $this->load->view('vf_encuesta/v_pdf_fisica_sede', $data);
    }
    
    function imprimirListadoPersAdmDocente() {
        $idSede = _decodeCI(_post('idSede'));
        $idEncuesta = _decodeCI(_post('idEncu'));
        if($idSede == null || $idEncuesta == null) {
            throw new Exception(ANP);
        }
        $tipoEncuesta = $this->m_utils->getById('senc.encuesta', '_id_tipo_encuesta', 'id_encuesta', $idEncuesta);
        $data['personalBySede'] = $this->m_crear_encuesta->getPersonalEncuestadoBySede($idSede, $tipoEncuesta);
        $data['sede_desc'] = $this->m_utils->getById('sede', 'desc_sede', 'nid_sede', $idSede);
        $this->load->library('m_pdf');
        $pdfObj = $this->m_pdf->load('','A4', 9, 'Arial', 9, 9, 16, 16, 9, 9, 'P');
        $data['tipo_encu'] = ($tipoEncuesta == TIPO_ENCUESTA_DOCENTE) ? 'docente' : 'pers_adm';
        $data['tipoPersonal'] = ($tipoEncuesta == TIPO_ENCUESTA_DOCENTE) ? 'Docente' : 'Administrativo';
        $pdfObj->SetFooter(date('d/m/Y h:i:s a'));
        $data['pdfObj'] = $pdfObj;
        $this->load->view('vf_encuesta/v_pdf_avance_pers', $data);
    }
    
    //--------------------------------------------------------
    //--------------------------------------------------------
    
    function getEstudiantesChecks() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAula = _decodeCI(_post('idAula'));
            if($idAula == null) {
                throw new Exception(ANP);
            }
            $data['estudiantes'] = $this->buildTablaEstudiantes($this->m_crear_encuesta->getEstudiantesSinLlenarEncuestaPadres($idAula));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaEstudiantes($estudiantes) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   id="tbEstudiantes">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0  = array('data' => '#');
        $head_1  = array('data' => 'Estudiante', 'class' => 'text-left');
        $head_2  = array('data' => 'Vía', 'class' => 'text-center');
        $head_3  = array('data' => 'Enc. Física entregada' , 'class' => 'text-center', 'data-field' => 'checkbox_1');
        $head_4  = array('data' => 'Enc. Física recibida'  , 'class' => 'text-center', 'data-field' => 'checkbox_2');
        $val = 0;
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        foreach($estudiantes as $row) {
            $val++;
            $celda_0  = array('data' => $val, 'data-id_estu' => _encodeCI($row['nid_persona']) , 'class' => 'claseId');
            $imageStudent = '<img alt="Student" src="'.$row['foto_persona'].'" width=30 height=30
		                          class="img-circle m-r-10">
		                         <p class="classroom-value" style="display: inline" style="cursor:pointer">'.$row['estudiante'].'</p>';
            $celda_1  = array('data' => $imageStudent );
            $celda_2  = array('data' => $row['encuestado'], 'class' => 'text-center');
    
            $checkEntrega  = null;
            $checkRecibido = null;
            if($row['encuestado'] == null) {
                $disableEntrega = null;
    
                $onChange = 'onchange="cambiarEntregaEncuesta($(this));"';
                if($row['flg_recibido_encu_fisica'] == 'checked') {
                    $disableEntrega = 'disabled';
                    $onChange = null;
                }
                $checkEntrega = '<label for="entre_'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
								    <input type="checkbox" '.$row['flg_entrega_encu_fisica'].' '.$disableEntrega.' class="mdl-checkbox__input" id="entre_'.$val.'" '.$onChange.'>
								    <span class="mdl-checkbox__label"></span>
							    </label>';
                $disableRecibido = null;
    
                $onChange = 'onchange="cambiarRecibidaEncuesta($(this));"';
                if($row['flg_entrega_encu_fisica'] != 'checked') {
                    $disableRecibido = 'disabled';
                    $onChange = null;
                }
                $checkRecibido = '<label for="reci_'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
								      <input type="checkbox" '.$row['flg_recibido_encu_fisica'].' '.$disableRecibido.' class="mdl-checkbox__input" id="reci_'.$val.'" '.$onChange.'>
								      <span class="mdl-checkbox__label"></span>
							      </label>';
            }
            $celda_3 = array('data' => $checkEntrega , 'class' => 'text-center claseEntregado');
            $celda_4 = array('data' => $checkRecibido, 'class' => 'text-center claseRecibido');
            /////////////
            $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $celda_4);
        }
        return $this->table->generate();
    }
    
    function marcarEncuestaEntregada() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEstu = _decodeCI(_post('idEstu'));
            $idAula = _decodeCI(_post('idAula'));
            if($idEstu == null || $idAula == null) {
                throw new Exception(ANP);
            }
            $datos = $this->m_crear_encuesta->getDatosPersonaXAulaEncuesta($idEstu, $idAula);
            $newEntregaFlag = $datos['flg_entrega_encu_fisica'] == null ? 'checked' : null;
            //NO SE PUEDE MARCAR COMO ENTREGADO AL ESTUDIANTE SI YA LO MARCASTE COMO RECIBIDO
            if($newEntregaFlag == 'checked' && $datos['flg_recibido_encu_fisica'] != null) {
                throw new Exception(ANP);
            }
            //NO SE PUEDE QUITAR EL FLG ENTREGADO AL ESTUDIANTE SI YA ESTA MARCADO COMO RECIBIDO
            if($newEntregaFlag == null && $datos['flg_recibido_encu_fisica'] != null) {
                throw new Exception(ANP);
            }
            $data = $this->m_crear_encuesta->marcarEncuesta($idEstu, $idAula, array("flg_entrega_encu_fisica" => $newEntregaFlag));
            $data['result'] = $newEntregaFlag;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function marcarEncuestaRecibida() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEstu = _decodeCI(_post('idEstu'));
            $idAula = _decodeCI(_post('idAula'));
            if($idEstu == null || $idAula == null) {
                throw new Exception(ANP);
            }
            $datos = $this->m_crear_encuesta->getDatosPersonaXAulaEncuesta($idEstu, $idAula);
            $newRecibidoFlag = $datos['flg_recibido_encu_fisica'] == null ? 'checked' : null;
            //NO SE PUEDE MARCAR COMO RECIBIDO SI NO ESTA MARCADO COMO ENTREGADO
            if($newRecibidoFlag == 'check' && $datos['flg_entrega_encu_fisica'] != 'checked') {
                throw new Exception(ANP);
            }
            $data = $this->m_crear_encuesta->marcarEncuesta($idEstu, $idAula, array("flg_recibido_encu_fisica" => $newRecibidoFlag));
            $data['result'] = $newRecibidoFlag;
            $data['resultEntregado'] = $datos['flg_entrega_encu_fisica'];
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /*function buildTablaEstudiantes($estudiantes) {
        $tmpl = array('table_open'  => '<table border="1" style="border-collapse: collapse;">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0  = array('data' => '#');
        $head_1  = array('data' => 'Estudiante');
        $head_2  = array('data' => 'Vía');
        $head_3  = array('data' => 'Enc. Física entregada');
        $head_4  = array('data' => 'Enc. Física recibida');
        $val = 0;
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        foreach($estudiantes as $row) {
            $val++;
            $celda_0  = array('data' => $val);
            $celda_1  = array('data' => $row['estudiante'] );
            $celda_2  = array('data' => $row['encuestado']);
            $celda_3  = array('data' => $row['encuestado'] != null ? '-------' : null );s
            $celda_4  = array('data' => $row['encuestado'] != null ? '-------' : null);
            /////////////
            $this->table->add_row($celda_0, $celda_1, $celda_2, $celda_3, $celda_4);
        }
        return $this->table->generate();
    }*/
    
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE['smiledu']);
        $cookie_name2 = "smiledu";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
}