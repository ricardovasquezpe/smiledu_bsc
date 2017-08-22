<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_senc_graficos extends CI_Controller {
    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_graficos/M_senc_graficos');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('mf_pregunta/m_pregunta');
        $this->load->model('mf_graficos/m_g_encuesta');
        $this->load->model('mf_graficos/m_g_comparar_preg');
        $this->load->model('mf_graficos/m_g_propuesta_mejora');
        $this->load->model('m_utils');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, null, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
    }
    
    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SENC, SENC_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader'] = 'Gráficos 2';
        $data['barraSec'] = '  <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                   <a href="#grafico1" class="mdl-layout__tab is-active" onclick="tabAction(1)">Preguntas por Encuesta</a>
                                   <a href="#grafico2" class="mdl-layout__tab" onclick="tabAction(2)">Satisfacci&oacute;n</a>
                                   <a href="#grafico3" class="mdl-layout__tab" onclick="tabAction(3)">Propuesta de Mejora</a>
                                   <a href="#grafico4" class="mdl-layout__tab" onclick="tabAction(4)">Reporte por Encuesta</a>
                                   <a href="#grafico5" class="mdl-layout__tab" onclick="tabAction(5)">Ranking de preguntas</a>
	                               <a href="#grafico6" class="mdl-layout__tab" onclick="tabAction(6)">Tutor&iacute;a</a>
                               </div>';
        $data['ruta_logo']        = MENU_LOGO_SENC;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SENC;
        $data['nombre_logo']      = NAME_MODULO_SENC;
        //MENU
        $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_SENC, $this->_idUserSess);
        $data['apps']               = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']             = $this->load->view('v_menu', $data, true);
        //NECESARIO
        if(_validate_metodo_rol(_getSesion(SENC_ROL_SESS))){
            $data['tipo_encuesta'] = __buildComboTipoEncuesta();
        }else{
            $tipo_encuestas = array(TIPO_ENCUESTA_LIBRE);
            $data['tipo_encuesta'] = __getOptionTipoEncuestaByIdSimpleDecrypt($tipo_encuestas,1);
        }
        $data['tipo_encuestados'] = __buildComboTipoEncuestado();
        ///////////
        $this->session->set_userdata(array('tab_active_config' => null));
        $this->load->view('vf_grafico/V_senc_graficos',$data);
    }
    
    function getEncuestaByTipoEncuesta() {
        $data['error'] = EXIT_ERROR;
        try {
            $idTipo = _decodeCI(_post('tipo_encuesta'));
            $opt = null;
            if($idTipo == null) {
                throw new Exception(null);
            }
            if(_validate_metodo_rol(_getSesion(SENC_ROL_SESS))) {
                $opt = __buildComboEncuestaByTipo($idTipo);
            } else {
                $opt = __buildComboEncuestaByTipoPersona($idTipo, $this->_idUserSess);
            }
            $data['optEnc'] = $opt;
        } catch (Exception $e) {
            $data['optEnc'] = null;
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getPreguntasByEncuesta(){
        $data['error'] = EXIT_ERROR;
        try {
        $idEnc  = _post('encuesta');
        $idTipo = _decodeCI(_post('tipo_encuesta'));
        if($idTipo == null || $idEnc == null) {
            throw new Exception(null);
        }
        $encuestasArr = __getArrayObjectFromArray($idEnc);
        $preguntas    = $this->m_pregunta->getPreguntasTipoByIdEncuestas($encuestasArr);
        $opt        = $this->buildComboPreguntasByEncuesta($preguntas);_logLastQuery();
        if($idTipo == TIPO_ENCUESTA_LIBRE) {
            $data['optEncTipo'] = $this->buildComboTipoEncuestadosByEnc($encuestasArr);
        } else {
            $data['optNiveles'] = $this->getCombosByTipoEncuesta($idTipo, 4);
        }
        $graficos   = array();
        $data['optPreg']    = $opt;
        $data['preguntas']  = json_encode($graficos, JSON_FORCE_OBJECT);
        } catch (Exception $e) {
            $data['optEnc'] = null;
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    

    function getCombosByTipoEncuesta($tipoEncuesta, $tab = 1) {
        if($tipoEncuesta == TIPO_ENCUESTA_LIBRE) {
            $tipoEncuestados = __buildComboTipoEncuestado();
            $comboTipoEnc = $this->getComboByTipo(TIPO_ENCUESTADO, 'getNivelesByTipoEnc'.(($tab == 1) ? null : $tab), $tipoEncuestados, null, $tab);
            return $comboTipoEnc;
        }
        ///////////////////////////////////////////
        $combos = null;
        $sedes  = __buildComboSedes();
        if($tipoEncuesta == TIPO_ENCUESTA_ALUMNOS || $tipoEncuesta == TIPO_ENCUESTA_PADREFAM) {
            $comboSede  = $this->getComboByTipo(SEDE, "getNivelesBySedeGrafico".$tab, $sedes, "p-r-form-group", $tab);
            $comboNivel = $this->getComboByTipo(NIVEL, "getGradosByNivelSedeGrafico".$tab, null, "p-l-form-group", $tab);
            $comboGrado = $this->getComboByTipo(GRADO, "getAulasByNivelGrafico".$tab, null, "p-r-form-group", $tab);
            $comboAula  = $this->getComboByTipo(AULA,'getGraficoByAula'.(($tab == 1) ? null : $tab), null, "p-l-form-group", $tab);
            $combos     = $comboSede.$comboNivel.$comboGrado.$comboAula;
        } else if($tipoEncuesta == TIPO_ENCUESTA_DOCENTE) {
            $comboSede  = $this->getComboByTipo(SEDE, "getNivelesBySedeGrafico".$tab, $sedes, "p-r-form-group", $tab);
            $comboNivel = $this->getComboByTipo(NIVEL, "getAreasByNivelSedeGrafico".$tab, null, "p-l-form-group", $tab);
            $comboArea  = $this->getComboByTipo(AREA, "getGraficoByAreaNivelSedeGrafico".$tab, null, "p-l-form-group", $tab);
            $combos     = $comboSede.$comboNivel.$comboArea;
        } else if($tipoEncuesta == TIPO_ENCUESTA_PERSADM) {
            $comboSede  = $this->getComboByTipo(SEDE, "getAreasBySedeGrafico".$tab, $sedes, "p-r-form-group", $tab);
            $comboArea  = $this->getComboByTipo(AREA, "getGraficoByAreaSedeGrafico".$tab, null,"p-l-form-group", $tab);
            $combos     = $comboSede.$comboArea;
        }
        return $combos;
    }
    

    function buildComboPreguntasByEncuesta($data){
        $opt = null;
        foreach($data as $preg){
            $idEncuesta  = _simple_encrypt($preg->id_pregunta);
            $opt .= '<option value="'.$idEncuesta.'">'.$preg->desc_pregunta.'</option>';
        }
        return $opt;
    }
    
    function getComboByTipo($tipo, $onClick, $data, $class, $id){
        $result = null;
        if($tipo == SEDE){
            $encryptAuxValue = $this->encrypt->encode(0);
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectSedeGrafico'.$id.'" name="selectSedeGrafico'.$id.'" data-actions-box="true" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
        	                    </select>
    	                    </div>
	                   </div>';
        } else if($tipo == NIVEL){
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectNivelGrafico'.$id.'" name="selectNivelGrafico'.$id.'" data-actions-box="true" enable data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
        } else if($tipo == GRADO){
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectGradoGrafico'.$id.'" name="selectGradoGrafico'.$id.'" data-actions-box="true" enable data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
        } else if($tipo == AULA){
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectAulaGrafico'.$id.'" name="selectAulaGrafico'.$id.'"  data-actions-box="true" enable data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
        	                    </select>
    	                    </div>
	                   </div>';
        } else if($tipo == DISCIPLINA){
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectDisciplinaGrafico'.$id.'" name="selectDisciplinaGrafico'.$id.'" data-actions-box="true" disabled data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()">
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
        } else if($tipo == AREA){
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectAreaGrafico'.$id.'" name="selectAreaGrafico'.$id.'" data-actions-box="true" data-live-search="true" enable class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
        } else if($tipo == TIPO_ENCUESTADO){
            $result .= '<div class="col-sm-12 '.$class.'">
                            <div class="form-group">
                                <select id="selectTipoEncuestadoGrafico'.$id.'" name="selectTipoEncuestadoGrafico'.$id.'" data-actions-box="true" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()">
                                    <option value="0">Seleccione un tipo de encuestado</option>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
        }
        return $result;
    }
    
    function getGraficoEncuestaByPregunta(){
        $data['error'] = EXIT_ERROR;
        try {
        $sedes      = _post('sedes');
        $idPregunta = _post('pregunta');
        $idEncuesta = _post('encuesta');
        $idTipoEncu = _decodeCI(_post('tipo_encu'));
        if($idPregunta == null || $idEncuesta == null || $idTipoEncu) {
            throw new Exception(null);
        }
            $preguntas = (is_array($idPregunta)) ? __getArrayObjectFromArray($idPregunta, 1) : null;
            $encuestas = __getArrayObjectFromArray($idEncuesta);
            $preguntasArray = $this->m_g_encuesta->getPreguntasById($encuestas, $preguntas);
            $encuestas = __getArrayStringFromArray($idEncuesta);
            $tipoEncuestado = 'Encuestados';
        $graficos  = array();
        $data['preguntas'] = json_encode($graficos, JSON_FORCE_OBJECT);
        } catch (Exception $e) {
            $data['optEnc'] = null;
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    

    function getNivelesBySedeGrafico(){
        $data['error'] = EXIT_ERROR;
        try {
        $data['tipo'] = 0;
        $sedes = _post('sedes');
        $idPregunta = _post('pregunta');
        $idEncuesta = _decodeCI(_post('encuesta'));
        $idTipoEncu = _decodeCI(_post('tipo_encu'));
        $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
        if($idPregunta == null || $idEncuesta == null || $idTipoEncu == null || $sedes == null) {
            throw new Exception(null);
        }
        $data['comboNiveles'] = null;
        if(is_array($idPregunta) && is_array($sedes) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,null,null,null,null,SEDE,$tipoEncuestado);
        } else if($sedes == null){
            $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,$idTipoEncu,null,null,null,null,null,PREGUNTA,$tipoEncuestado);
        }
        } catch (Exception $e) {
            $data['optEnc'] = null;
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    

    function getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,$idTipoEncu,$sedes,$nivel,$grado,$area,$aula,$filtro,$tipoEncuestado = null){
        $id_tipo_encuesta = _decodeCI(_post("tipo_encu"));
        $result['retval'] = array();
        $data['combos'] = null;
        $year           = (is_array($grado) || is_array($sedes)) ? $this->m_encuesta->getYearFromEncuesta($idEncuesta) : null;
        //PREGUNTAS
        $preguntas = (is_array($idPregunta)) ? $this->getArrayStringFromArray($idPregunta) : null;
        //SEDES
        $sedeIdsDecry  = (is_array($sedes)) ? $this->decryptArrayIdsInArray($sedes) : array();
        $sedesIds      = (is_array($sedes)) ? $this->getArrayStringFromArray($sedes) : null;
        //NIVELES
        $nivelIdsDecry = (is_array($nivel)) ? $this->decryptArrayIdsInArray($nivel) : array();
        $nivelIds      = (is_array($nivel)) ? $this->getArrayStringFromArray($nivel) : null;
        //GRADOS
        $gradosIdsDecry = (is_array($grado)) ? $this->decryptArrayIdsInArray($grado) : array();
        $gradosIds      = (is_array($grado)) ? $this->getArrayStringFromArray($grado) : null;
        //AULAS
        $aulasIds       = (is_array($aula)) ? $this->getArrayStringFromArray($aula) : null;
        //AREAS
        $areaIds = (is_array($area)) ? $this->getArrayStringFromArray($area) : null;
        //DESC TIPO ENCUESTADO
        $descTipoEncuestado = ($tipoEncuestado != null) ? $this->m_utils->getById('senc.tipo_encuestado', 'desc_tipo_enc', 'id_tipo_encuestado', $tipoEncuestado) : null;
        if($filtro == PREGUNTA){
            $result = $this->m_g_encuesta->getGraficoEncuestaByPregunta($preguntas,$idEncuesta);
            $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEncu);
        } else if($filtro == SEDE && $id_tipo_encuesta != TIPO_ENCUESTA_PERSADM) {
            $result = $this->m_g_encuesta->getGraficoEncuestaBySede($preguntas,$idEncuesta,$sedesIds,$descTipoEncuestado);
            $data['comboNiveles'] = __buildComboNivelesByMultiSedes($sedeIdsDecry,$year);
        } else if($filtro == NIVEL){
            $result = $this->m_g_encuesta->getGraficoEncuestaByNivel($preguntas,$idEncuesta,$sedesIds,$nivelIds,$descTipoEncuestado);
            $data['comboGrados'] = __buildComboGradosByMultiSedeNivel($sedeIdsDecry,$nivelIdsDecry, $year);
        } else if($filtro == GRADO){
            $result = $this->m_g_encuesta->getGraficoEncuestaByGrado($preguntas,$idEncuesta,$sedesIds,$nivelIds,$gradosIds,$descTipoEncuestado);
            $data['comboAulas'] = __buildComboAulasMulti($sedeIdsDecry,$nivelIdsDecry,$gradosIdsDecry,$year);
        } else if($filtro == AULA){
            $result    = $this->m_g_encuesta->getGraficoEncuestaByAula($preguntas,$idEncuesta,$sedesIds,$nivelIds,$gradosIds,$aulasIds,$descTipoEncuestado);
        } else if($filtro == AREA){
            $result = $this->m_g_encuesta->getGraficoEncuestaByArea($preguntas,$idEncuesta,$sedesIds,$nivelIds,$areaIds,$descTipoEncuestado);
        } else if($filtro == NIVELDOC){
            $result = $this->m_g_encuesta->getGraficoEncuestaByNivel($preguntas,$idEncuesta,$sedesIds,$nivelIds,$descTipoEncuestado);
            $data['comboAreas'] = __buildComboAreasEspecificas();
        } else if($filtro == SEDE_AREA){
            $result = $this->m_g_encuesta->getGraficoEncuestaBySede($preguntas,$idEncuesta,$sedesIds,$descTipoEncuestado);
            $data['comboAreas'] = __buildComboAreasGenerales();
        } else if($filtro == SEDE && $id_tipo_encuesta == TIPO_ENCUESTA_PERSADM) {
            $result = $this->m_g_encuesta->getGraficoEncuestaBySedeArea($preguntas,$idEncuesta,$sedesIds,$areaIds,$descTipoEncuestado);
            $data['comboAreas'] = __buildComboAreasGenerales();
        } else if($filtro == TIPO_ENCUESTADO) {
            $result = $this->m_g_encuesta->getGraficoEncuestaByTipoEncuestado($preguntas,$idEncuesta,$descTipoEncuestado);
            $descTipoEncuestado = strtoupper(substr($descTipoEncuestado, 0,1));
            $idTipoEncu = ($descTipoEncuestado == PADRE) ? TIPO_ENCUESTA_PADREFAM : (($descTipoEncuestado == DOCENTE) ? TIPO_ENCUESTA_DOCENTE : (($descTipoEncuestado == ESTUDIANTE) ? TIPO_ENCUESTA_ALUMNOS : (($descTipoEncuestado == PERSONAL_ADMINISTRATIVO) ? TIPO_ENCUESTA_PERSADM : null)));
            $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEncu);
        }
        //$data            += $this->getListasGraficos($result);
        // 	    $data['refresh'] = _encodeCI($filtro);
        return $data;
    }
    
    function getArrayStringFromArray($data){
        $arrayIds = null;
        foreach ($data as $var){
            $id = _decodeCI($var);
            if($id != null){
                $arrayIds .= $id.',';
            }
        }
        $arrayIds = substr($arrayIds,0,(strlen($arrayIds)-1));
        return $arrayIds;
    }
    
    function decryptArrayIdsInArray($arrayEncrypt){
        $arrayDecrypt = array();
        foreach($arrayEncrypt AS $idEncry){
            array_push($arrayDecrypt, _decodeCI($idEncry));
        }
        return $arrayDecrypt;
    }
    
    function getAreasGraficoByNivel(){
        $data['error'] = EXIT_ERROR;
        try {
        $data['tipo'] = 0;
        $sedes = _post('sedes');
        $nivel  = _post('nivel');
        $pregunta = _post('pregunta');
        $idEncuesta = $this->encrypt->decode(_post('encuesta'));
        $idTipoEncu = $this->encrypt->decode(_post('tipo_encu'));
        if($idEncuesta == null || $idTipoEncu == null || $sedes == null || $pregunta == null || $nivel == null) {
            throw new Exception(null);
        }
        if(is_array($pregunta) && is_array($sedes) && is_array($nivel) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($pregunta,$idEncuesta,null,$sedes,$nivel,null,null,null,NIVELDOC);
        } else if(is_array($pregunta) && is_array($sedes) && $nivel == null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($pregunta,$idEncuesta,$idTipoEncu,$sedes,$nivel,null,null,null,SEDE);
        }
        }catch (Exception $e) {
            $data['optEnc'] = null;
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGraficoTutoriaBySedeNivelGrado(){
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        $idEncuesta     = _decodeCI(_post('encuesta'));
        $tipoEncuestado = _decodeCI(_post('tipo_encu'));
        $data['series'] = json_encode(array());
        $idSede         = _post('sedes');
        $idNivel        = _post('niveles');
        $idGrado        = _post('grados');
        try{
            if($idEncuesta == null){
                throw new Exception('Seleccione una encuesta');
            }
            if(!is_array($idSede)){
                throw new Exception('Seleccione una sede');
            }
            //SEDES
            $sedeIdsDecry  = $this->decryptArrayIdsInArray($idSede);
            $sedesIds      = $this->getArrayStringFromArray($idSede);
            if(!is_array($idNivel)){
                throw new Exception('Seleccione una nivel');
            }
            //NIVELES
            
            $nivelesIdsDecry = $this->decryptArrayIdsInArray($idNivel);
            $nivelesIds      = $this->getArrayStringFromArray($idNivel);
            $descTipoEncuestado   = ($tipoEncuestado != null) ? $this->m_utils->getById('senc.tipo_encuestado', 'desc_tipo_enc', 'id_tipo_encuestado', $tipoEncuestado , 'senc') : null;
            $tituloEncuesta = ucwords($this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta));
            if(!is_array($idGrado)){
                $result              = $this->m_g_encuesta->getDatosGraficoTutoriaBySedesNiveles($idEncuesta,$descTipoEncuestado,$sedesIds,$nivelesIds);
                $data['series']      = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$tituloEncuesta);
                $data['comboAulas']  = null;
                throw new Exception('Seleccione una grado');
            }
            //GRADOS
            $gradosIdsDecry = $this->decryptArrayIdsInArray($idGrado);
            $gradosIds      = $this->getArrayStringFromArray($idGrado);
            //YEAR
            $year           = $this->m_encuesta->getYearFromEncuesta($idEncuesta);
            /////////////////////
            $titulo = (count($gradosIdsDecry) == 1) ? $tituloEncuesta.' - '.$this->m_utils->getById('grado', 'desc_grado', 'nid_grado', $gradosIdsDecry[0]) :
            $tituloEncuesta;
            $result               = $this->m_g_encuesta->getDatosGraficoTutoriaBySedesNivelesGrados($idEncuesta,$descTipoEncuestado,$sedesIds,$nivelesIds,$gradosIds);
            $data['series']       = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$titulo);
            $data['comboAulas']   = __buildComboAulasMulti($sedeIdsDecry, $nivelesIdsDecry, $gradosIdsDecry, $year);
            $data['error']        = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildSeriesTutoriaByEncuesta($result,$idEncuesta,$titulo){
        $arrayCount      = array();
        $arrayCategorias = array();
        $arrayColor      = array();
        $arrayName       = array();
        $count           = 0;
        if(isset($result[0])){
            $alternativas = $this->m_encuesta->getAlternativas($result[0]['_id'] ,$idEncuesta);
            foreach($alternativas as $row){
                $arrayCount[$row->desc_alternativa] = array();
            }
        }
        $count = 1;
        foreach($result as $row){
            foreach($row['datos'] as $datos){
                if(isset($arrayCount[($datos['desc_respuesta'])])){
                    array_push($arrayCount[($datos['desc_respuesta'])] , array('name' => ($row['desc_pregunta'].' - '.$datos['respuesta']) , 'y' => ($datos['respuesta']*100/$row['total'])));
                    array_push($arrayName, ($datos['desc_respuesta']));
                    // 	                if($count == 1){
                    $color = ( ($datos['desc_respuesta'] == 'NUNCA O CASI NUNCA') ? '#404040' :
                        (($datos['desc_respuesta'] == 'POCAS VECES')       ? '#C0504D' :
                            (($datos['desc_respuesta'] == 'VARIAS VECES')      ? '#9BBB59' : '#4F81BD') ));
                    array_push($arrayColor, $color);
                    // 	                }
                }
            }
            foreach(array_keys($arrayCount) as $key){
                if(count($arrayCount[$key]) != $count) array_push($arrayCount[$key], null);
            }
            $count++;
            array_push($arrayCategorias, ($row['desc_pregunta']));
        }
        foreach(array_keys($arrayCount) as $key){
            $flg_delete = true;
            foreach($arrayCount[$key] as $elem){
                if($elem != null){
                    $flg_delete = false;
                    break;
                }
            }
            if($flg_delete == true){
                unset($arrayCount[$key]);
            }
        }
        $data['arrayCount']      = json_encode(array_values($arrayCount));
        $data['arrayCategorias'] = json_encode($arrayCategorias);
        $data['arrayColor']      = json_encode(array_values(array_unique($arrayColor)));
        $data['arrayName']       = json_encode(array_values(array_unique($arrayName)));
        $data['titulo']          = utf8_encode($titulo);
        return json_encode($data);
    }
    
    function getGraficoTutoriaBySedeNivel(){
        $data['error']  = EXIT_ERROR;
        $data['msj']    = null;
        $idEncuesta     = _decodeCI(_post('encuesta'));
        $tipoEncuestado = _decodeCI(_post('tipo_encu'));
        $data['series'] = json_encode(array());
        $idSede         = _post('sedes');
        $idNivel        = _post('niveles');
        try{
            $tituloEncuesta = ucwords($this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta));
            $descTipoEncuestado  = ($tipoEncuestado != null) ? $this->m_utils->getById('senc.tipo_encuestado', 'desc_tipo_enc', 'id_tipo_encuestado', $tipoEncuestado , 'senc') : null;
            if($idEncuesta == null){
                throw new Exception('Seleccione una encuesta');
            }
            if(!is_array($idSede)){
                throw new Exception('Seleccione una sede');
            }
            $year           = $this->m_encuesta->getYearFromEncuesta($idEncuesta);
            //SEDES
            $sedeIdsDecry    = $this->decryptArrayIdsInArray($idSede);
            $sedesIds        = $this->getArrayStringFromArray($idSede);
            if(!is_array($idNivel)){
                $result               = $this->m_g_encuesta->getDatosGraficoTutoriaBySedes($idEncuesta,$descTipoEncuestado,$sedesIds);
                $data['series']       = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$tituloEncuesta);
                $data['comboGrados']  = null;
                throw new Exception('Seleccione una nivel');
            }
            //NIVELES
            $nivelesIdsDecry = $this->decryptArrayIdsInArray($idNivel);
            $nivelesIds      = $this->getArrayStringFromArray($idNivel);
            ///////
            $titulo = (count($nivelesIdsDecry) == 1) ? $tituloEncuesta.' - '.$this->m_utils->getById('nivel', 'desc_nivel', 'nid_nivel', $nivelesIdsDecry[0]) :
            $tituloEncuesta;
            $result              = $this->m_g_encuesta->getDatosGraficoTutoriaBySedesNiveles($idEncuesta,$descTipoEncuestado,$sedesIds,$nivelesIds);
            $data['series']      = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$titulo);
            $data['comboGrados'] = __buildComboGradosByMultiSedeNivel($sedeIdsDecry, $nivelesIdsDecry,$year);
            $data['error']       = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getAreasBySedeGrafico(){
        $data['tipo'] = 0;
        $sedes = _post('sedes');
        $idPregunta = _post('pregunta');
        $idEncuesta = _decodeCI(_post('encuesta'));
        $idTipoEncu = _decodeCI(_post('tipo_encu'));
        $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
        $data['comboAreas'] = null;
        if(is_array($idPregunta) && is_array($sedes) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,null,null,null,null,SEDE,$tipoEncuestado);
        } else if($sedes == null){
            $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,$idTipoEncu,null,null,null,null,null,PREGUNTA,$tipoEncuestado);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    

    function buildComboTipoEncuestadosByEnc($encuestas){
        $encuestados = $this->m_encuesta->getTipoEncuestadosByEncuesta($encuestas);
        $opt = null;
        foreach($encuestados as $row){
            $idCrypt = _encodeCI($row->id_tipo_encuestado);
            $opt .= '<option value = "'.$idCrypt.'" > '.$row->desc_tipo_enc.'</option>';
        }
        return $opt;
    }
    
    function getGraficobyTipoEncuestado(){
        $idPregunta       = $this->input->post('pregunta');
        $idEncuesta       = $this->input->post('encuesta');
        $idTipoEncuestado = $this->encrypt->decode(_post('tencuestado'));
        $graficos = array();
        $desc_tipo_enc = $this->m_utils->getById("senc.tipo_encuestado", "desc_tipo_enc", "id_tipo_encuestado", $idTipoEncuestado, "senc");
        $encuestas  = __getArrayObjectFromArray($idEncuesta);
        $encuestas1 = __getArrayStringFromArray($idEncuesta);
        if($idPregunta == null || count($idPregunta) == 0){
            $preguntas = $this->m_g_encuesta->getPreguntasTipoByIdEncuestas($encuestas);
            //$graficos  = $this->getGraficoByPreguntas($preguntas, $encuestas1, $desc_tipo_enc,null);
        }else{
            $preguntas = (is_array($idPregunta)) ? __getArrayObjectFromArray($idPregunta, 1) : null;
            $preguntasArray = $this->m_g_encuesta->getPreguntasById($encuestas, $preguntas);
            //$graficos  = $this->getGraficoByPreguntas($preguntasArray, $encuestas1, $desc_tipo_enc,null);
        }
        $inicialDesc = substr($desc_tipo_enc,0, 1);
        $idTipoEncu = ($inicialDesc == PADRE) ? TIPO_ENCUESTA_PADREFAM : (($inicialDesc == DOCENTE)
            ? TIPO_ENCUESTA_DOCENTE : (($inicialDesc == ESTUDIANTE)
                ? TIPO_ENCUESTA_ALUMNOS : (($inicialDesc == PERSONAL_ADMINISTRATIVO)
                    ? TIPO_ENCUESTA_PERSADM : null)));
        $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEncu,'4');
        $data['preguntas'] = json_encode($graficos, JSON_FORCE_OBJECT);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
}