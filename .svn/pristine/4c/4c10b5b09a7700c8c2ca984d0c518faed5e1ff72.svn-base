<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_g_pregunta extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('mf_graficos/m_g_encuesta');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('mf_pregunta/m_pregunta');
        $this->load->model('m_utils');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, null, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
    }
	
    function getEncuestaByTipoEncuesta(){
        $idTipo = $this->encrypt->decode($this->input->post('tipo_encuesta'));
        $opt = null;
        if($idTipo != null){
            if(_validate_metodo_rol(_getSesion(SENC_ROL_SESS))){
	            $opt = __buildComboEncuestaByTipo($idTipo);
	        }else{
	            $opt = __buildComboEncuestaByTipoPersona($idTipo, _getSesion("nid_persona"));
	        } 
        }
        $data['optEnc'] = $opt;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildComboPreguntasByEncuesta($data){
        $opt = null;
        foreach($data as $preg){
            $idEncuesta  = _simple_encrypt($preg->id_pregunta);
            $opt .= '<option value="'.$idEncuesta.'">'.$preg->desc_pregunta.'</option>';
        }
        return $opt;
    }
    
    function getPreguntasByEncuesta(){
        $idEnc  = $this->input->post('encuesta');_log($idEnc);
        $idTipo = $this->encrypt->decode($this->input->post('tipo_encuesta'));_log($idTipo);
        $opt        = null;
        $optTipoEnc = null;
        $graficos   = array();
        $optNiveles = null;
        $combotencuestado = 0;
        if($idEnc != null){
            $encuestasArr = __getArrayObjectFromArray($idEnc);
            $preguntas    = $this->m_pregunta->getPreguntasTipoByIdEncuestas($encuestasArr);
            $encuestasStr = __getArrayStringFromArray($idEnc);
            
            $opt        = $this->buildComboPreguntasByEncuesta($preguntas);
            $graficos   = $this->getGraficoByPreguntas($preguntas, $encuestasStr,null,null);
            if($idTipo == TIPO_ENCUESTA_LIBRE){
                $optTipoEnc = $this->buildComboTipoEncuestadosByEnc($encuestasArr);
                $combotencuestado = 1;
            } else{
                $optNiveles = $this->getCombosByTipoEncuesta($idTipo,4);
            }
        }
        
        $data['optEncTipo'] = $optTipoEnc;
        $data['optPreg']    = $opt;
        $data['optNiveles'] = $optNiveles;
        $data['preguntas']  = json_encode($graficos, JSON_FORCE_OBJECT);
        $data['tipoencuestado'] = $combotencuestado;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGraficoEncuestaByPregunta(){
        $idPregunta = $this->input->post('pregunta');
        $idEncuesta = $this->input->post('encuesta');
        $idTipoEncu = $this->encrypt->decode($this->input->post('tipo_encu'));
        $graficos  = array();
        if(is_array($idPregunta) && count($idPregunta) > 0 && $idEncuesta != null  && $idTipoEncu != null){
            $preguntas = (is_array($idPregunta)) ? __getArrayObjectFromArray($idPregunta, 1) : null;
            $encuestas = __getArrayObjectFromArray($idEncuesta);
            $preguntasArray = $this->m_g_encuesta->getPreguntasById($encuestas, $preguntas);
            $encuestas = __getArrayStringFromArray($idEncuesta);
            
            $tipoEncuestado = 'Encuestados';
            $graficos  = $this->getGraficoByPreguntas($preguntasArray, $encuestas, $tipoEncuestado,null);
        }
        $data['preguntas'] = json_encode($graficos, JSON_FORCE_OBJECT);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGraficoByPreguntas($data, $idEncuesta, $tipoEncuestado,$sedes){
        $graficos = array();
        foreach($data as $preg){
            $cantEnc = $this->m_encuesta->getCantParticipanesPregObli(explode(',', $idEncuesta),$preg->_id_tipo_pregunta);
            if($preg->_id_tipo_pregunta == CINCO_CARITAS ||$preg->_id_tipo_pregunta == TRES_CARITAS ||$preg->_id_tipo_pregunta == CUATRO_CARITAS){
                if($tipoEncuestado != null && $tipoEncuestado != 'Encuestados'){
                    $result = $this->m_g_encuesta->getGraficoEncuestadoEncuestas($preg->id_pregunta, $idEncuesta, $tipoEncuestado,$sedes);
                }else{
                    $result = $this->m_g_encuesta->getGraficoPreguntasByEncuestas($preg->id_pregunta,$idEncuesta,$sedes);
                    $tipoEncuestado = 'Encuestados';
                }
                if(count($result['retval']) >= 1){
                    $grafico = __getGraficoUnaOpcion($result, CARITAS, $tipoEncuestado,$cantEnc);
                    $grafico['tipo_grafico'] = 'column';
                    $grafico['pregunta']     = _simple_encrypt($preg->id_pregunta, CLAVE_ENCRYPT);
                    array_push($graficos, $grafico);
                }
            }else if($preg->_id_tipo_pregunta == LISTA_DESPLEGABLE || $preg->_id_tipo_pregunta == DOS_OPCIONES 
                     || $preg->_id_tipo_pregunta == OPCION_MULTIPLE){
                if($tipoEncuestado != null && $tipoEncuestado != 'Encuestados'){
                    $result = $this->m_g_encuesta->getGraficoEncuestadoEncuestas($preg->id_pregunta, $idEncuesta, $tipoEncuestado,$sedes);
                }else{
                    $result  = $this->m_g_encuesta->getGraficoEncuestaTipoByPregunta($preg->id_pregunta,$idEncuesta,$sedes);    
                    $tipoEncuestado = 'Encuestados';
                }
                if(count($result['retval']) >= 1){
                    $grafico = __getGraficoUnaOpcion($result, null, $tipoEncuestado,$cantEnc);
                    $grafico['tipo_grafico'] = 'pie';
                    $grafico['pregunta']     = _simple_encrypt($preg->id_pregunta, CLAVE_ENCRYPT);
                    array_push($graficos, $grafico);
                }
            } else if($preg->_id_tipo_pregunta == CASILLAS_VERIFICACION){
                if($tipoEncuestado != null && $tipoEncuestado != 'Encuestados'){
                    $result = $this->m_g_encuesta->getGraficoEncuestaTipoByTipoPregCheck($preg->id_pregunta, $idEncuesta, $tipoEncuestado,$sedes);
                }else{
                    $result  = $this->m_g_encuesta->getGraficoEncuestaTipoByTipoPregCheck($preg->id_pregunta,$idEncuesta,null,$sedes);
                    $tipoEncuestado = 'Encuestados';
                }
                if(count($result['retval']) >= 1){
                    $grafico = __getGraficoUnaOpcion($result, CASILLAS_VERIFICACION, $tipoEncuestado,$cantEnc);
                    $grafico['tipo_grafico'] = 'pie';
                    $grafico['pregunta']     = _simple_encrypt($preg->id_pregunta, CLAVE_ENCRYPT);
                    array_push($graficos, $grafico);
                }
            }
        }
        return $graficos;
    }
    
    function getGraficobyTipoEncuestado(){
        $idPregunta       = $this->input->post('pregunta');
        $idEncuesta       = $this->input->post('encuesta');
        $idTipoEncuestado = $this->encrypt->decode($this->input->post('tencuestado'));
        $graficos = array();
        $desc_tipo_enc = $this->m_utils->getById("senc.tipo_encuestado", "desc_tipo_enc", "id_tipo_encuestado", $idTipoEncuestado, "senc");
        $encuestas  = __getArrayObjectFromArray($idEncuesta);
        $encuestas1 = __getArrayStringFromArray($idEncuesta);
        if($idPregunta == null || count($idPregunta) == 0){
            $preguntas = $this->m_g_encuesta->getPreguntasTipoByIdEncuestas($encuestas);
            $graficos  = $this->getGraficoByPreguntas($preguntas, $encuestas1, $desc_tipo_enc,null);
        }else{
            $preguntas = (is_array($idPregunta)) ? __getArrayObjectFromArray($idPregunta, 1) : null;
            $preguntasArray = $this->m_g_encuesta->getPreguntasById($encuestas, $preguntas);
            $graficos  = $this->getGraficoByPreguntas($preguntasArray, $encuestas1, $desc_tipo_enc,null);
        }
        $inicialDesc = substr($desc_tipo_enc,0, 1);
        $idTipoEncu = ($inicialDesc == PADRE) ? TIPO_ENCUESTA_PADREFAM : (($inicialDesc == DOCENTE)
                                              ? TIPO_ENCUESTA_DOCENTE  : (($inicialDesc == ESTUDIANTE)
                                              ? TIPO_ENCUESTA_ALUMNOS  : (($inicialDesc == PERSONAL_ADMINISTRATIVO)
                                              ? TIPO_ENCUESTA_PERSADM  : null)));
        $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEncu,'4');
        $data['preguntas'] = json_encode($graficos, JSON_FORCE_OBJECT);
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
	
	function getCombosByTipoEncuesta($tipoEncuesta,$tab=1){
	    $combos = null;
	    $sedes  = __buildComboSedes();
	    $tipoEncuestados = __buildComboTipoEncuestado();
	    if($tipoEncuesta == TIPO_ENCUESTA_ALUMNOS || $tipoEncuesta == TIPO_ENCUESTA_PADREFAM){
	        $comboSede  = $this->getComboByTipo(SEDE, "getNivelesBySedeGrafico".$tab, $sedes, "p-r-form-group",$tab);
	        $comboNivel = $this->getComboByTipo(NIVEL, "getGradosByNivelSedeGrafico".$tab, null, "p-l-form-group",$tab);
	        $comboGrado = $this->getComboByTipo(GRADO, "getAulasByNivelGrafico".$tab, null, "p-r-form-group",$tab);
	        $comboAula  = $this->getComboByTipo(AULA,'getGraficoByAula'.(($tab == 1) ? null : $tab), null, "p-l-form-group",$tab);
	        $combos     = $comboSede.$comboNivel.$comboGrado.$comboAula;
	    } else if($tipoEncuesta == TIPO_ENCUESTA_DOCENTE){
	        $comboSede  = $this->getComboByTipo(SEDE, "getNivelesBySedeGrafico".$tab, $sedes, "p-r-form-group",$tab);
	        $comboNivel = $this->getComboByTipo(NIVEL, "getAreasByNivelSedeGrafico".$tab, null, "p-l-form-group",$tab);
	        $comboArea  = $this->getComboByTipo(AREA, "getGraficoByAreaNivelSedeGrafico".$tab, null, "p-l-form-group",$tab);
	        $combos     = $comboSede.$comboNivel.$comboArea;
	    } else if($tipoEncuesta == TIPO_ENCUESTA_PERSADM){
	        $comboSede  = $this->getComboByTipo(SEDE, "getAreasBySedeGrafico".$tab, $sedes, "p-r-form-group",$tab);
	        $comboArea  = $this->getComboByTipo(AREA, "getGraficoByAreaSedeGrafico".$tab, null,"p-l-form-group",$tab);
	        $combos     = $comboSede.$comboArea;
	    } else if($tipoEncuesta == TIPO_ENCUESTA_LIBRE) {//ENCUESTA LIBRE
	        $comboTipoEnc = $this->getComboByTipo(TIPO_ENCUESTADO, 'getNivelesByTipoEnc'.(($tab == 1) ? null : $tab), $tipoEncuestados, null,$tab);
	        $combos     = $comboTipoEnc;
	    }
	    return $combos;
	}
	
	function getComboByTipo($tipo, $onClick, $data, $class,$id){
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
                                <select id="selectNivelGrafico'.$id.'" name="selectNivelGrafico'.$id.'" data-actions-box="true" disabled data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
	    } else if($tipo == GRADO){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectGradoGrafico'.$id.'" name="selectGradoGrafico'.$id.'" data-actions-box="true" disabled data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
	    } else if($tipo == AULA){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectAulaGrafico'.$id.'" name="selectAulaGrafico'.$id.'"  data-actions-box="true" disabled data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
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
                                <select id="selectAreaGrafico'.$id.'" name="selectAreaGrafico'.$id.'" data-actions-box="true" data-live-search="true" disabled class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
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
	
	function getGraficobySede(){
	    $idPregunta       = _post('pregunta');
	    $idEncuesta       = _post('encuesta');
	    $sedes            = _post('sedes');
	    $tipo_encuesta    = _decodeCI(_post('tipo_encuesta'));
	    $idTipoEncuestado = _decodeCI(_post('tencuestado'));
	    $graficos = array();
	    $desc_tipo_enc = ($tipo_encuesta == TIPO_ENCUESTA_LIBRE) ?  $this->m_utils->getById("senc.tipo_encuestado", "desc_tipo_enc", "id_tipo_encuestado", $idTipoEncuestado, "senc") : null;
	    //ENCUESTAS
	    $encuestas  = __getArrayObjectFromArray($idEncuesta);
	    $encuestas1 = __getArrayStringFromArray($idEncuesta);
	    //SEDE
	    $sedesArr = (is_array($sedes)) ? __getArrayObjectFromArray($sedes) : array();
	    $sedesStr = (is_array($sedes)) ? __getArrayStringFromArray($sedes) : null;
	    if($idPregunta == null || count($idPregunta) == 0 || count($sedesArr) == 0){
	        
	        $preguntas = $this->m_g_encuesta->getPreguntasTipoByIdEncuestas($encuestas);
	        $graficos  = $this->getGraficoByPreguntas($preguntas, $encuestas1, $desc_tipo_enc,$sedesStr);
	    }else{
	        $preguntas = (is_array($idPregunta)) ? __getArrayObjectFromArray($idPregunta, 1) : null;
	        $preguntasArray = $this->m_g_encuesta->getPreguntasById($encuestas, $preguntas);
	        $graficos  = $this->getGraficoByPreguntas($preguntasArray, $encuestas1, $desc_tipo_enc,$sedesStr);
	    }
	    $idTipoEncu = ($desc_tipo_enc == PADRE) ? TIPO_ENCUESTA_PADREFAM : (($desc_tipo_enc == DOCENTE)
	                                            ? TIPO_ENCUESTA_DOCENTE : (($desc_tipo_enc == ESTUDIANTE)
	                                            ? TIPO_ENCUESTA_ALUMNOS : (($desc_tipo_enc == PERSONAL_ADMINISTRATIVO)
	                                            ? TIPO_ENCUESTA_PERSADM : null)));
	    $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEncuestado,'4');
	    $data['preguntas'] = json_encode($graficos, JSON_FORCE_OBJECT);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAreasBySedeGraficos(){
	    $idPregunta       = _post('pregunta');
	    $idEncuesta       = _post('encuesta');
	    $sedes            = _post('sedes');
	    $tipo_encuesta    = _decodeCI(_post('tipo_encuesta'));
	    $idTipoEncuestado = _decodeCI(_post('tencuestado'));
	    $graficos         = array();
	    $desc_tipo_enc    = ($tipo_encuesta == TIPO_ENCUESTA_LIBRE) ?  $this->m_utils->getById("senc.tipo_encuestado", "desc_tipo_enc", "id_tipo_encuestado", $idTipoEncuestado, "senc") : null;
	    //ENCUESTAS
	    $encuestas  = __getArrayObjectFromArray($idEncuesta);
	    $encuestas1 = __getArrayStringFromArray($idEncuesta);
	    //SEDE
	    $sedesArr   = __getArrayObjectFromArray($sedes);
	    $sedesStr   = __getArrayStringFromArray($sedes);
	    if($idPregunta == null || count($idPregunta) == 0 || count($sedesArr) == 0){
	        $preguntas = $this->m_g_encuesta->getPreguntasTipoByIdEncuestas($encuestas);
	        $graficos  = $this->getGraficoByPreguntas($preguntas, $encuestas1, $desc_tipo_enc,$sedesStr);
	    }else{
	        $preguntas = (is_array($idPregunta)) ? __getArrayObjectFromArray($idPregunta, 1) : null;
	        $preguntasArray = $this->m_g_encuesta->getPreguntasById($encuestas, $preguntas);
	        $graficos  = $this->getGraficoByPreguntas($preguntasArray, $encuestas1, $desc_tipo_enc,$sedesStr);
	    }
	    $idTipoEncu = ($desc_tipo_enc == PADRE) ? TIPO_ENCUESTA_PADREFAM : (($desc_tipo_enc == DOCENTE)
	                                            ? TIPO_ENCUESTA_DOCENTE  : (($desc_tipo_enc == ESTUDIANTE)
	                                            ? TIPO_ENCUESTA_ALUMNOS  : (($desc_tipo_enc == PERSONAL_ADMINISTRATIVO)
	                                            ? TIPO_ENCUESTA_PERSADM  : null)));
	    $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEncuestado,'4');
	    $data['preguntas'] = json_encode($graficos, JSON_FORCE_OBJECT);
	    echo json_encode(array_map('utf8_encode', $data));
	}
}