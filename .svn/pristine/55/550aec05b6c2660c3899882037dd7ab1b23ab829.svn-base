<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_g_comparar_preg extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('mf_graficos/m_g_comparar_preg');
        $this->load->model('mf_pregunta/m_pregunta');
        $this->load->model('m_utils_senc');
        $this->load->model('m_utils');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->library('Classes/PHPExcel.php');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, null, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
    }
   
	public function getPreguntasByTipoEncuesta(){
	    $tipoDeEncuestas = _post("tipo_encuesta");
        if(count($tipoDeEncuestas) != null){
            $arrayEnc = array();
            foreach ($tipoDeEncuestas as $var){
                $tipoEncuesta = _decodeCI($var);
                if($tipoEncuesta != null){
                    array_push($arrayEnc, $tipoEncuesta);
                }
            }
            $preguntas = null;
            if(_validate_metodo_rol(_getSesion(SENC_ROL_SESS))){
                $preguntas = $this->m_pregunta->getPreguntasByTipoEncuesta($arrayEnc);
            }else{
                $preguntas = $this->m_pregunta->getPreguntasByTipoEncuestaPersona($arrayEnc, _getSesion("nid_persona"));
            }
            $data['preguntas'] = $this->builtOptionPreguntas($preguntas);
        }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function builtOptionPreguntas($data){
	    $opt = null;
	    foreach($data as $preg){
	        $idPreg  = $this->encrypt->encode($preg->id_pregunta);
	        $opt .= '<option value="'.$idPreg.'">'.$preg->desc_pregunta.' ('.$preg->years.')</option>';
	    }
	    return $opt;
	}
	
	function getGraficoByPregunta(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_UPT;
	    try{
    	    $tipoEncuestas = _post("tipo_encuesta");
    	    $preguntas     = _post("preguntas");
    	    $satisfaccion  = _post("satisfaccion");
    	    if(count($tipoEncuestas) != null && count($preguntas) != null && $satisfaccion != null){
    	        $arrayTenc      = __getArrayStringFromArray($tipoEncuestas, 2);
    	        $arrayPreguntas = __getArrayStringFromArray($preguntas);
    	        if($satisfaccion == 0){
    	            $satisfaccion = 'satisfaccion';
    	        }else{
                    $satisfaccion = 'insatisfaccion';
                }
    	        $graf = $this->m_g_comparar_preg->getGraficoByPregunta($arrayPreguntas, $arrayTenc, $satisfaccion);
    	        $data += $this->createGrafico($graf);
    	        $arrayTencs = __getArrayObjectFromArray($tipoEncuestas, 2);
    	        $data['years'] = $this->buildComboYear($arrayTencs);
    	    }
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGraficoByYear(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_UPT;
	    try{
	        $tipoEncuestas = _post("tipo_encuesta");
	        $preguntas     = _post("preguntas");
	        $years         = _post("years");
	        $satisfaccion  = _post("satisfaccion");
	        if(count($tipoEncuestas) != null && count($preguntas) != null && count($years) != null && $satisfaccion != null){
	            $arrayTenc      = __getArrayStringFromArray($tipoEncuestas, 2);
	            $arrayPreguntas = __getArrayStringFromArray($preguntas);
	            $arrayYears     = __getArrayStringFromArray($years, 1);
	            if($satisfaccion == 0){
	                $satisfaccion = 'satisfaccion';
	            }else{
	                $satisfaccion = 'insatisfaccion';
	            }
	            $graf = $this->m_g_comparar_preg->getGraficoByYear($arrayPreguntas, $arrayTenc, $arrayYears, $satisfaccion);
	            $data += $this->createGrafico($graf);
	        }
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	//GRAFICOS POR NIVELES
	function getGraficoBySede($tipoEncuesta, $pregunta, $sedes, $satisfaccion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_UPT;
	    try{
	        $id_pregunta      = _decodeCI($pregunta);
	        $id_tipo_encuesta = _decodeCI($tipoEncuesta);
	        if($id_pregunta != null && $id_tipo_encuesta != null && count($sedes) != null && $satisfaccion != null){
	            $arraySedes = __getArrayStringFromArray($sedes, 1);
	            $graf  = $this->m_g_comparar_preg->getGraficoBySedes($id_pregunta, $id_tipo_encuesta, $arraySedes, $satisfaccion);
	            if(count($graf['retval']) > 0){
	                $data['porcentaje'] = $graf['retval'][0]['porcentaje'];
	            }
	        }
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function getGraficoByNivel($tipoEncuesta, $pregunta, $sedes, $niveles, $satisfaccion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_UPT;
	    try{
	        $id_pregunta      = _decodeCI($pregunta);
	        $id_tipo_encuesta = _decodeCI($tipoEncuesta);
	        if($id_pregunta != null && $id_tipo_encuesta != null && count($niveles) != null && count($sedes) != null && $satisfaccion != null){
	            $arrayNiveles = __getArrayStringFromArray($niveles, 1);
	            $arraySedes   = __getArrayStringFromArray($sedes, 1);
	            $graf  = $this->m_g_comparar_preg->getGraficoByNiveles($id_pregunta, $id_tipo_encuesta, $arraySedes, $arrayNiveles, $satisfaccion);
	            if(count($graf['retval']) > 0){
	                $data['porcentaje'] = $graf['retval'][0]['porcentaje'];
	            }
	        }
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function getGraficoByGrado($tipoEncuesta, $pregunta, $sedes, $niveles, $grados, $satisfaccion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_UPT;
	    try{
	        $id_pregunta      = _decodeCI($pregunta);
	        $id_tipo_encuesta = _decodeCI($tipoEncuesta);
	        if($id_pregunta != null && $id_tipo_encuesta != null && count($grados) != null && count($sedes) != null && count($niveles) != null && $satisfaccion != null){
	            $arraySedes   = __getArrayStringFromArray($sedes, 1);
	            $arrayNiveles = __getArrayStringFromArray($niveles, 1);
	            $arrayGrados  = __getArrayStringFromArray($grados, 1);
	            $graf  = $this->m_g_comparar_preg->getGraficoByGrados($id_pregunta, $id_tipo_encuesta, $arraySedes, $arrayNiveles, $arrayGrados, $satisfaccion);
	            if(count($graf['retval']) > 0){
	                $data['porcentaje'] = $graf['retval'][0]['porcentaje'];
	            }
	        }
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function getGraficoByAula($tipoEncuesta, $pregunta, $aulas, $satisfaccion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_UPT;
	    try{
	        $id_pregunta      = _decodeCI($pregunta);
	        $id_tipo_encuesta = _decodeCI($tipoEncuesta);
	        if($id_pregunta != null && $id_tipo_encuesta != null && count($aulas) != null && $satisfaccion != null){
	            $arrayAulas = __getArrayStringFromArray($aulas, 1);
	            $graf  = $this->m_g_comparar_preg->getGraficoByAulas($id_pregunta, $id_tipo_encuesta, $arrayAulas, $satisfaccion);
	            if(count($graf['retval']) > 0){
	                $data['porcentaje'] = $graf['retval'][0]['porcentaje'];
	            }
	        }
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function getGraficoByArea($tipoEncuesta, $pregunta, $sedes, $niveles, $areas, $satisfaccion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_UPT;
	    try{
	        $id_pregunta      = _decodeCI($pregunta);
	        $id_tipo_encuesta = _decodeCI($tipoEncuesta);
	        if($id_pregunta != null && $id_tipo_encuesta != null && count($areas) != null && count($sedes) != null && count($niveles) != null && $satisfaccion != null){
	            $arraySedes   = __getArrayStringFromArray($sedes, 1);
	            $arrayNiveles = __getArrayStringFromArray($niveles, 1);
	            $arrayAreas   = __getArrayStringFromArray($areas, 1);
	            $graf  = $this->m_g_comparar_preg->getGraficoByAreas($id_pregunta, $id_tipo_encuesta, $arraySedes, $arrayNiveles, $arrayAreas, $satisfaccion);
	            if(count($graf['retval']) > 0){
	                $data['porcentaje'] = $graf['retval'][0]['porcentaje'];
	            }
	        }
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function getGraficoByAreaSede($tipoEncuesta, $pregunta, $sedes, $areas, $satisfaccion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_UPT;
	    try{
	        $id_pregunta      = _decodeCI($pregunta);
	        $id_tipo_encuesta = _decodeCI($tipoEncuesta);
	        if($id_pregunta != null && $id_tipo_encuesta != null && count($areas) != null && count($sedes) != null && $satisfaccion != null){
	            $arraySedes   = __getArrayStringFromArray($sedes, 1);
	            $arrayAreas   = __getArrayStringFromArray($areas);
	            $graf  = $this->m_g_comparar_preg->getGraficoByAreasSedes($id_pregunta, $id_tipo_encuesta, $arraySedes, $arrayAreas, $satisfaccion);
	            if(count($graf['retval']) > 0){
	                $data['porcentaje'] = $graf['retval'][0]['porcentaje'];
	            }
	        }
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function getCombosByTipoEncuesta(){
	    $id_tipo_encuesta = _decodeCI(_post("tipo_encuesta"));
	    $combos    = null;
	    $tEncuesta = null;
	    if($id_tipo_encuesta == TIPO_ENCUESTA_ALUMNOS || $id_tipo_encuesta == TIPO_ENCUESTA_PADREFAM){
	        $sedes      = __buildComboSedes(true);
	        $comboSede  = $this->getComboByTipo(SEDE, "getNivelesBySedeGrafico2", $sedes,"p-r-form-group");
	        $comboNivel = $this->getComboByTipo(NIVEL, "getGradosByNivelGrafico2", null,"p-l-form-group");
	        $comboGrado = $this->getComboByTipo(GRADO, "getAulasByGradoGrafico2", null,"p-r-form-group");
	        $comboAula  = $this->getComboByTipo(AULA,"getGraficoByAulaGrafico2", null,"p-l-form-group");
	        $combos     = $comboSede.$comboNivel.$comboGrado.$comboAula;
	        $tEncuesta  = 1;
	    }else if($id_tipo_encuesta == TIPO_ENCUESTA_DOCENTE){
	        $sedes      = __buildComboSedes(true);
	        $comboSede  = $this->getComboByTipo(SEDE, "getNivelesBySedeGrafico2", $sedes,"p-r-form-group");
	        $comboNivel = $this->getComboByTipo(NIVEL, "getAreasGrafico2", null,"p-l-form-group");
	        $comboArea  = $this->getComboByTipo(AREA, "getGraficoByAreaGrafico2", null,"p-l-form-group");
	        $combos     = $comboSede.$comboNivel.$comboArea;
	        $tEncuesta  = 2;
	    }else if($id_tipo_encuesta == TIPO_ENCUESTA_LIBRE){
	        $tEncuesta   = 3;
	    }else{
	        $sedes      = __buildComboSedes(true);
	        $comboSede  = $this->getComboByTipo(SEDE, "getAreasBySedeGrafico2", $sedes,"p-r-form-group");
	        $comboArea  = $this->getComboByTipo(AREA, "getGraficoByAreaSedeGrafico2", null,"p-l-form-group");
	        $combos     = $comboSede.$comboArea;
	        $tEncuesta  = 2;
	    }
	    
	    $data['combos'] = $combos;
	    $data['tipo']   = $tEncuesta;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	/*FUNCIONES PARA LLENAR COMBOS Y TRAER GRAFICOS*/
	function getNivelesBySede(){
	    $sedes = _post("id_sede");
	    $year  = _post("year");
	    $data['tipo'] = 0;
	    if(count($sedes) > 0){
	        $sedesObj = __getArrayObjectFromArray($sedes, 1);
	        $data['niveles'] = $this->buildComboMultiNivelesBySede($sedesObj, $year);
	        $data['tipo'] = 1;
	    }
        $tipoEncuesta = _post("tipo_encuesta");
        $pregunta     = _post("pregunta");
        $satisfaccion = _post("satisfaccion");
        if($satisfaccion == 0){
            $satisfaccion = 'satisfaccion';
        }else{
            $satisfaccion = 'insatisfaccion';
        }
        $data += $this->getGraficoBySede($tipoEncuesta, $pregunta, $sedes, $satisfaccion);
        
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGradosByNivel(){
	    $sedes   = _post("id_sede");
	    $niveles = _post("id_nivel");
	    $year    = _post("year");
	    $data['tipo'] = 0;
	    if(count($niveles) > 0){
	        $sedesObj    = __getArrayObjectFromArray($sedes, 1);
	        $nivelesObj  = __getArrayObjectFromArray($niveles, 1); 
	        $data['grados'] = $this->buildComboMultiGradosByNiveles($sedesObj, $nivelesObj, $year);//dfloresgonz 02.10.16
	        $data['tipo'] = 1;
        }
        $tipoEncuesta  = _post("tipo_encuesta");
        $pregunta      = _post("pregunta");
        $satisfaccion = _post("satisfaccion");
        if($satisfaccion == 0){
            $satisfaccion = 'satisfaccion';
        }else{
            $satisfaccion = 'insatisfaccion';
        }
        $data += $this->getGraficoByNivel($tipoEncuesta, $pregunta, $sedes, $niveles, $satisfaccion);
        
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAulasByGrado(){
	    $sedes   = _post("id_sede");
	    $niveles = _post("id_nivel");
	    $grados  = _post("id_grado");
	    $year    = _post("year");
	    $data['tipo'] = 0;
	    if(count($grados) > 0){
	        $sedesObj   = __getArrayObjectFromArray($sedes, 1);
	        $nivelesObj = __getArrayObjectFromArray($niveles, 1);
	        $gradosObj  = __getArrayObjectFromArray($grados, 1);
	        $data['aulas'] = $this->buildComboMultiAulasByGrados($sedesObj, $nivelesObj, $gradosObj, $year);
	        $data['tipo'] = 1;
	    }
        $tipoEncuesta = _post("tipo_encuesta");
        $pregunta     = _post("pregunta");
        $satisfaccion = _post("satisfaccion");
        if($satisfaccion == 0){
            $satisfaccion = 'satisfaccion';
        }else{
            $satisfaccion = 'insatisfaccion';
        }
        $data += $this->getGraficoByGrado($tipoEncuesta, $pregunta, $sedes, $niveles, $grados, $satisfaccion);
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGraficoByAulaoAulas(){
	    $aulas         = _post("id_aula");
	    $tipoEncuestas = _post("tipo_encuesta");
	    $pregunta      = _post("pregunta");
	    $satisfaccion  = _post("satisfaccion");
	    if($satisfaccion == 0){
	        $satisfaccion = 'satisfaccion';
	    }else{
	        $satisfaccion = 'insatisfaccion';
	    }
	    $data['tipo']  = 0;
	    $data += $this->getGraficoByAula($tipoEncuestas, $pregunta, $aulas, $satisfaccion);
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGraficoByAreaoAreas(){
	    $id_sede       = _post("id_sede");
	    $id_nivel      = _post("id_nivel");
	    $areas         = _post("id_area");
	    $tipoEncuestas = _post("tipo_encuesta");
	    $pregunta      = _post("pregunta");
	    $satisfaccion  = _post("satisfaccion");
	    if($satisfaccion == 0){
	        $satisfaccion = 'satisfaccion';
	    }else{
	        $satisfaccion = 'insatisfaccion';
	    }
	    
	    $data['tipo'] = 0;
	    $data += $this->getGraficoByArea($tipoEncuestas, $pregunta, $id_sede, $id_nivel, $areas, $satisfaccion);
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAreas(){
	    $niveles  = _post("id_nivel");
	    $sedes    = _post("id_sede");
	    $data['tipo'] = 0;
	    if(count($niveles) > 0){
	        $data['areas'] = $this->buildComboAreasAcadSimpleEncrypt();
	        $data['tipo']  = 1;
	    }
	    $tipoEncuesta = _post("tipo_encuesta");
	    $pregunta     = _post("pregunta");
	    $satisfaccion = _post("satisfaccion");
	    if($satisfaccion == 0){
	        $satisfaccion = 'satisfaccion';
	    }else{
	        $satisfaccion = 'insatisfaccion';
	    }
	    $data += $this->getGraficoByNivel($tipoEncuesta, $pregunta, $sedes, $niveles, $satisfaccion);
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAreasBySede(){
	    $sedes = _post("id_sede");
	    $data['tipo'] = 0;
	    if(count($sedes) > 0){
	        $sedesObj      = __getArrayObjectFromArray($sedes, 1);
	        $data['areas'] = __buildComboAreasGenerales();
	        $data['tipo']  = 1;
	    }
	    $tipoEncuesta = _post("tipo_encuesta");
	    $pregunta     = _post("pregunta");
	    $satisfaccion = _post("satisfaccion");
	    if($satisfaccion == 0){
	        $satisfaccion = 'satisfaccion';
	    }else{
	        $satisfaccion = 'insatisfaccion';
	    }
	    $data += $this->getGraficoBySede($tipoEncuesta, $pregunta, $sedes, $satisfaccion);
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getDataByAreaSede(){
	    $id_sede       = _post("id_sede");
	    $areas         = _post("id_area");
	    $tipoEncuestas = _post("tipo_encuesta");
	    $pregunta      = _post("pregunta");
	    $satisfaccion  = _post("satisfaccion");
	    if($satisfaccion == 0){
	        $satisfaccion = 'satisfaccion';
	    }else{
	        $satisfaccion = 'insatisfaccion';
	    }
	     
	    $data['tipo'] = 0;
	    $data += $this->getGraficoByAreaSede($tipoEncuestas, $pregunta, $id_sede, $areas, $satisfaccion);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function getComboByTipo($tipo, $onClick, $data, $class){
	    $result = null;
	    if($tipo == SEDE){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectSedeGrafico2" name="selectSedeGrafico2" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.' 
        	                    </select>
    	                    </div>
	                   </div>';	        
	    }else if($tipo == NIVEL){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectNivelGrafico2" name="selectNivelGrafico2" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
	    }else if($tipo == GRADO){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectGradoGrafico2" name="selectGradoGrafico2" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
                                    <option>Seleccione Grado</option>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
	    }else if($tipo == AULA){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectAulaGrafico2" name="selectAulaGrafico2" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        <option>Seleccione Aula</option>
        	                        '.$data.' 
        	                    </select>
    	                    </div>
	                   </div>';
	    }else if($tipo == DISCIPLINA){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectDisciplinaGrafico2" name="selectDisciplinaGrafico2" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
                                    <option>Seleccione Disciplina</option>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
	    }else if($tipo == AREA){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectAreaGrafico2" name="selectAreaGrafico2" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
                                    <option>Seleccione Area</option>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
	    }	    
	    return $result;
	}
	
    function createGrafico($data){
	    $years         = array();
	    $allSatif      = array();
	    $oneSatif      = array();
	    $arraysatifOne = array();

	    $cons_tipo_encuesta      = null;
	    $cons_desc_pregunta      = null;
	    $cons_desc_tipo_encuesta = null;
	    $cons_pregunta           = null;
	    $cons_cantidad           = null;
	    $arrayParti              = array();
	    foreach ($data['retval'] as $row){
	        if(!in_array($row['year'], $years)){
	            array_push($years, $row['year']);
	        }
	    }
	    
	    sort($years);
	    $cons_years = $years;
	    $participantes = 0;
	    foreach ($data['retval'] as $var){
	        if($cons_tipo_encuesta == $var['id_tipo_encuesta'] && count($arraysatifOne) < count($years) && 
	           $cons_desc_pregunta == $var['desc_pregunta'] || $cons_tipo_encuesta == null){
	            $j = 1;
	            for($i = 0; $i < $j; $i++){
	                if(min($cons_years) == $var['year']){
	                    array_push($arraysatifOne, round($var['porcentaje'], 3));
	                    $i = $j;
	                }else{
	                    array_push($arraysatifOne, null);
	                    $j++;
	                }
	                $cons_tipo_encuesta      = $var['id_tipo_encuesta'];
	                $cons_desc_pregunta      = $var['desc_pregunta'];
	                $cons_desc_tipo_encuesta = $var['desc_tipo_encuesta'];
	                $cons_pregunta           = $var['id_pregunta'];
	                $cons_cantidad           = $var['count'];
	                unset($cons_years[0]);
	                $cons_years = array_values($cons_years);
	            }
	        }else{
	            array_push($oneSatif, $arraysatifOne);
	            array_push($oneSatif, $cons_desc_pregunta);
	            array_push($oneSatif, $cons_desc_tipo_encuesta);
	            array_push($oneSatif, $this->encrypt->encode($cons_pregunta));
	            array_push($oneSatif, $this->encrypt->encode($cons_tipo_encuesta));
	            array_push($oneSatif, $cons_cantidad);
	            array_push($oneSatif, $participantes);
	            array_push($allSatif, $oneSatif);
	            
	            
	            $cons_tipo_encuesta      = $var['id_tipo_encuesta'];
	            $cons_desc_pregunta      = $var['desc_pregunta'];
	            $cons_desc_tipo_encuesta = $var['desc_tipo_encuesta'];
	            $cons_pregunta           = $var['id_pregunta'];
	            $cons_cantidad           = $var['count'];
	            $arraysatifOne = array();
	            $oneSatif      = array();
	            
	            $cons_years = $years;
	            for($i = 0; $i < $j; $i++){
	                if(min($cons_years) == $var['year']){
	                    array_push($arraysatifOne, round($var['porcentaje'], 3));
	                    $i = $j;
	                }else{
	                    array_push($arraysatifOne, null);
	                    $j++;
	                }
	                $cons_tipo_encuesta      = $var['id_tipo_encuesta'];
	                $cons_desc_pregunta      = $var['desc_pregunta'];
	                $cons_desc_tipo_encuesta = $var['desc_tipo_encuesta'];
	                $cons_pregunta           = $var['id_pregunta'];
	                $cons_cantidad           = $var['count'];
	                 
	                unset($cons_years[0]);
	                $cons_years = array_values($cons_years);
	            }
	        }
	        $participantes = $var['part'];
	        array_push($arrayParti, $cons_cantidad.'/'.$participantes);
	    }
	    array_push($oneSatif, $arraysatifOne);
	    array_push($oneSatif, $cons_desc_pregunta);
	    array_push($oneSatif, $cons_desc_tipo_encuesta);
	    array_push($oneSatif, $this->encrypt->encode($cons_pregunta));
	    array_push($oneSatif, $this->encrypt->encode($cons_tipo_encuesta));
	    array_push($oneSatif, $cons_cantidad);
	    array_push($oneSatif, $participantes);
	    array_push($oneSatif, $arrayParti);
	    array_push($allSatif, $oneSatif);
	    
	    $graf['year']         = json_encode($years);
	    $graf['satisfaccion'] = json_encode($allSatif);
	    return $graf;
	}
	
	//LLENAR COMBOS MULTIPLES(SUMANDO)
	function buildComboSedesSimpleEncrypt(){
	    $sedes = $this->m_utils->getSedes();
	    $opcion = '';
	    foreach ($sedes as $sed){
	        $idSede  = _simple_encrypt($sed->nid_sede);
	        $opcion .= '<option value="'.$idSede.'">'._ucwords($sed->desc_sede).'</option>';
	    }
	    return $opcion;
	}
	
	function buildComboMultiNivelesBySede($sedes,$year){
	    $niveles = $this->m_utils_senc->getNivelesByMultiSedesYear($sedes,$year);
	    $opcion = '';
	    foreach ($niveles as $niv){
	        $idNivel = _simple_encrypt($niv->nid_nivel);
	        $opcion .= '<option value="'.$idNivel.'">'._ucwords($niv->desc_nivel).'</option>';
	    }
	    return $opcion;
	}
	
	function buildComboMultiGradosByNiveles($sedes, $niveles, $year) {
	    $grados = $this->m_utils_senc->getGradosByMultiNivelSede($sedes, $niveles, $year);//dfloresgonz 02.10.16
	    $opcion = null;
	    foreach ($grados as $gra) {
	        $idGrado = _simple_encrypt($gra->nid_grado);
	        $opcion .= '<option value="'.$idGrado.'">'._ucwords($gra->desc_grado).'</option>';
	    }
	    return $opcion;
	}
	
	function buildComboMultiAulasByGrados($sedes, $niveles, $grados, $year){
	    $aulas = $this->m_utils_senc->getAulasByGradoMultiYear($sedes, $niveles, $grados, $year);
	    $opcion = '';
	    foreach ($aulas as $au){
	        $idAula  = _simple_encrypt($au->nid_aula);
	        $opcion .= '<option value="'.$idAula.'">'._ucwords($au->desc_aula).'</option>';
	    }
	    return $opcion;
	}
	
	function buildComboAreasAcadSimpleEncrypt(){
	    $area = $this->m_utils->getAreasAcad();
	    $opcion = '';
	    foreach ($area as $areasac){
	        $idArea = _simple_encrypt($areasac->nid_area_academica);
	        $opcion .= '<option value="'.$idArea.'">'._ucwords($areasac->desc_area_academica).'</option>';
	    }
	    return $opcion;
	}
	
	function buildComboYear($tipoEncuesta){
	    $years = $this->m_encuesta->getYearFromTipoEncuesta($tipoEncuesta);
	    $opcion = '';
	    foreach ($years as $year){
	        $idYear = _simple_encrypt($year->year);
	        $opcion .= '<option value="'.$idYear.'">'.strtoupper($year->year).'</option>';
	    }
	    return $opcion;
	}
}