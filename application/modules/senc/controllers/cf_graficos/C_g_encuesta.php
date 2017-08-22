<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_g_encuesta extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('mf_graficos/m_g_encuesta');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('m_utils');
        $this->load->model('m_utils_senc');
        $this->load->library('table');
        $this->load->model("mf_pregunta/m_pregunta");
    }
	/**
	 * @author Cesar 03/03/2016
	 * @name getDataGraficoEncuestaByPregunta
	 * @return Listas para generar los gráficos
	 */
	function getDataGraficoEncuestaByPregunta(){
	    $data['combos'] = null;
	    $idPregunta = _post('pregunta');
	    $idEncuesta = _decodeCI(_post('encuesta'));
	    $idTipoEncu = _decodeCI(_post('tipo_encu'));
	    $data['arrayCount'] = json_encode(array(array()));
	    if(is_array($idPregunta) && count($idPregunta) > 0 && $idEncuesta != null  && $idTipoEncu != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,$idTipoEncu,null,null,null,null,null,PREGUNTA);
	        $data['refresh'] = _encodeCI(PREGUNTA);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getEncuestaByTipoEncuesta(){
	    $idTipo = _decodeCI(_post('tipo_encuesta'));
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
	
	function getPreguntasByEncuesta(){
	    $idEnc = _decodeCI(_post('encuesta'));
	    $opt = null;
	    if($idEnc != null){
	        $opt = $this->buildComboPreguntasCaritasByEncuesta($idEnc);
	    }
	    $data['optPreg'] = $opt;
	    echo json_encode(array_map('utf8_encode', $data));
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
                                <select id="selectNivelGrafico'.$id.'" name="selectNivelGrafico'.$id.'" data-actions-box="true" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
	    } else if($tipo == GRADO){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectGradoGrafico'.$id.'" name="selectGradoGrafico'.$id.'" data-actions-box="true" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
	    } else if($tipo == AULA){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectAulaGrafico'.$id.'" name="selectAulaGrafico'.$id.'"  data-actions-box="true" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
        	                    </select>
    	                    </div>
	                   </div>';
	    } else if($tipo == DISCIPLINA){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectDisciplinaGrafico'.$id.'" name="selectDisciplinaGrafico'.$id.'" data-actions-box="true" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()">
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
	    } else if($tipo == AREA){
	        $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectAreaGrafico'.$id.'" name="selectAreaGrafico'.$id.'" data-actions-box="true" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
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
	
	function getListasGraficos($result){
	    $arrayCat   = array();
	    $arrayCount = array();
	    $arrayName  = array();
	    $arrayGeneral = array();
	    $descEncuesta = null;
	    $desc = null;
	    $descAux = null;
	    $preg = null;
	    $arrayCcat = array();
	    $sumTotal = 0;
	    $countPreg = 0;
	    foreach($result['retval'] AS $row){
	        $sumTotal = $row['sum'];
	        $arrayCount = array();
	        foreach($row['count'] AS $count){
	            array_push($arrayCcat, $count);
	            array_push($arrayCount , round(($count*100)/$sumTotal, 3));
	        }
	        foreach($row['desc_respuestas'] AS $rpta){
	            array_push($arrayCat   , $rpta);
	        }
	        foreach($row['desc_preguntas'] AS $preg){
	            if($countPreg > 0){
	                $descAux .= ($countPreg+1).') '.$preg.'<br/>';
	            }else{
	                $desc .= ($countPreg+1).') '.$preg.'<br/>';
	            }
	            $countPreg++;
	        }
	        $descEncuesta = $row['desc_encuesta'];
	        array_push($arrayName  , $row['desc_preguntas']);
	    }
	    $arrayAux = array();
	    $arrayAuxCat = array();
	    for($i = 0; $i < count($arrayCount); $i++){
	        array_push($arrayAux, array('y' => $arrayCount[$i], 'name' => $arrayCat[$i], 'color' => json_decode(ARRAY_COLORES_CARITAS)->$arrayCat[$i]));
	        array_push($arrayAuxCat, $arrayCat[$i].' : '.$arrayCcat[$i]);
	    }
	    array_push($arrayGeneral, $arrayAux);
	    $titulo             = null;
	    $data['titulo']     = $desc;
	    $data['tituloAux']  = $desc.$descAux;
	    $data['cPreg']      = $countPreg;
	    $data['encuesta']   = utf8_encode($descEncuesta);
	    $arrayName          = array_values(array_unique($arrayName));
	    $data['arrayCount'] = json_encode($arrayGeneral);
	    $data['arrayCat']   = json_encode($arrayAuxCat);
	    $data['arrayName']  = json_encode($arrayName);
	    return $data;
	}
	
	function getNivelesBySedeGrafico(){
	    $data['tipo'] = 0;
	    $sedes = _post('sedes');
	    $idPregunta = _post('pregunta');
	    $idEncuesta = _decodeCI(_post('encuesta'));
	    $idTipoEncu = _decodeCI(_post('tipo_encu'));
	    $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
	    $data['comboNiveles'] = null;
	    if(is_array($idPregunta) && is_array($sedes) && $idEncuesta != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,null,null,null,null,SEDE,$tipoEncuestado);
	    } else if($sedes == null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,$idTipoEncu,null,null,null,null,null,PREGUNTA,$tipoEncuestado);
	    }
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	/**
	 * @author César Villarreal 09/03/2016
	 * @param arrayIds $arrayEncrypt
	 * @return retorna array con los ids desencriptados
	 */
	function decryptArrayIdsInArray($arrayEncrypt){
	    $arrayDecrypt = array();
	    foreach($arrayEncrypt AS $idEncry){
	        array_push($arrayDecrypt, _decodeCI($idEncry));
	    }
	    return $arrayDecrypt;
	}
	function getGradosByNivelSedeGrafico(){
	    $data['tipo'] = 0;
	    $sedes = _post('sedes');
	    $nivel  = _post('nivel');
	    $idPregunta = _post('pregunta');
	    $idEncuesta = _decodeCI(_post('encuesta'));
	    $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
	    $data['comboGrados'] = null;
	    if(is_array($idPregunta) && is_array($sedes) && is_array($nivel) && $idEncuesta != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,$nivel,null,null,null,NIVEL,$tipoEncuestado);
	    } else if(is_array($idPregunta) && is_array($sedes) && $nivel == null && $idEncuesta != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,null,null,null,null,SEDE,$tipoEncuestado);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	/**
	 * @author César Villarreal 09/03/2016
	 * @param arrayIds $data
	 * @return retorna string con Ids desencriptados concatenados con una ','
	 */
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
	
	function getAulasByGradoNivelSedeGrafico(){
	    $data['tipo'] = 0;
	    $sedes   = _post('sedes');
	    $nivel   = _post('nivel');
	    $grado   = _post('grado');
	    $idPregunta = _post('pregunta');
	    $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
	    $idEncuesta = _decodeCI(_post('encuesta'));
	    if(is_array($idPregunta) && is_array($sedes) && is_array($nivel) && is_array($grado) && $idEncuesta != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,$nivel,$grado,null,null,GRADO,$tipoEncuestado);
        } else if(is_array($idPregunta) && is_array($sedes) && is_array($nivel) && $grado == null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,$nivel,null,null,null,NIVEL,$tipoEncuestado);
	    }	    
	    echo json_encode(array_map('utf8_encode', $data));
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
	
	function getGraficoByAula(){
	    $data['tipo'] = 0;
	    $sedes  = _post('sedes');
	    $nivel  = _post('nivel');
	    $grado  = _post('grado');
	    $aula   = _post('aula');
	    $idEncuesta = _decodeCI(_post('encuesta'));
	    $idPregunta = _post('pregunta');
	    $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
	    if(is_array($idPregunta) && is_array($sedes) && is_array($nivel) && is_array($grado) && is_array($aula) && $idEncuesta != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,$nivel,$grado,null,$aula,AULA,$tipoEncuestado);
        } else if(is_array($idPregunta) && is_array($sedes) && is_array($nivel) && is_array($grado) && $aula == null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,$nivel,$grado,null,null,GRADO,$tipoEncuestado);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAreasGraficoByNivel(){
	    $data['tipo'] = 0;
	    $sedes = _post('sedes');
	    $nivel = _post('nivel');
	    $idPregunta = _post('pregunta');
	    $idEncuesta = _decodeCI(_post('encuesta'));
	    $idTipoEncu = _decodeCI(_post('tipo_encu'));
	    $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
	    if(is_array($idPregunta) && is_array($sedes) && is_array($nivel) && $idEncuesta != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,$nivel,null,null,null,NIVELDOC,$tipoEncuestado);
        } else if(is_array($idPregunta) && is_array($sedes) && $nivel == null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,$idTipoEncu,$sedes,$nivel,null,null,null,SEDE,$tipoEncuestado);
	    }

	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAreasBySedeGraficos(){
	   $data['tipo'] = 0;
	    $sedes = _post('sedes');
	    $idPregunta     = _post('pregunta');
	    $idEncuesta     = _decodeCI(_post('encuesta'));
	    $idTipoEncu     = _decodeCI(_post('tipo_encu'));
	    $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
	    $data['comboNiveles'] = null;
	    if(is_array($idPregunta) && is_array($sedes) && $idEncuesta != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,null,null,null,null,SEDE_AREA,$tipoEncuestado);
	    } else if($sedes == null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,$idTipoEncu,null,null,null,null,null,PREGUNTA,$tipoEncuestado);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGraficoByAreaSede(){
	    $data['tipo'] = 0;
	    $sedes = _post('sedes');
	    $idPregunta = _post('pregunta');
	    $areas = _post('area');
	    $idEncuesta = _decodeCI(_post('encuesta'));
	    $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
	    if(is_array($idPregunta) && is_array($sedes) && is_array($areas) && $idEncuesta != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,null,null,$areas,null,AREA_ADM,$tipoEncuestado);
	    } else if(is_array($idPregunta) && is_array($sedes) && $areas != null && $idEncuesta != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,null,null,null,null,SEDE_AREA,$tipoEncuestado);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGraficoByArea(){
	    $data['tipo'] = 0;
	    $sedes = _post('sedes');
	    $nivel = _post('nivel');
	    $idPregunta = _post('pregunta');
	    $areas = _post('area');
	    $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
	    $idEncuesta = _decodeCI(_post('encuesta'));
	    if(is_array($idPregunta) && is_array($sedes) && is_array($nivel) && is_array($areas) && $idEncuesta != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,$nivel,null,$areas,null,AREA,$tipoEncuestado);
        } else if(is_array($idPregunta) && is_array($sedes) && is_array($nivel)&& $areas != null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,null,$sedes,$nivel,null,null,null,NIVELDOC,$tipoEncuestado);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	/**
	 * @author César Villarreal 28/03/2016
	 * @param arrayIdsPreguntas $idPregunta
	 * @param idEncuestaSeleccionada $idEncuesta
	 * @param idTipoEncSeleccionada $idTipoEncu
	 * @param arrayIdsSedes $sedes
	 * @param arrayIdsNivel $nivel
	 * @param arrayIdsGrado $grado
	 * @param arrayIdsArea $area
	 * @param arrayIdsAula $aula
	 * @param tipoFiltroSeleccionado $filtro
	 * @return Retorna array con los datos a mostrar
	 */
	function getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,$idTipoEncu,$sedes,$nivel,$grado,$area,$aula,$filtro,$tipoEncuestado = null){
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
	    $descTipoEncuestado = ($tipoEncuestado != null) ? $this->m_utils->getById('senc.tipo_encuestado', 'desc_tipo_enc', 'id_tipo_encuestado', $tipoEncuestado , 'senc') : null;
	    if($filtro == PREGUNTA){
	        $result = $this->m_g_encuesta->getGraficoEncuestaByPregunta($preguntas,$idEncuesta);
	        $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEncu);
	    } else if($filtro == SEDE){
	        $result = $this->m_g_encuesta->getGraficoEncuestaBySede($preguntas,$idEncuesta,$sedesIds,$descTipoEncuestado);
	        $data['comboNiveles'] = __buildComboNivelesByMultiSedes($sedeIdsDecry,$year);
	    } else if($filtro == NIVEL){
	        $result = $this->m_g_encuesta->getGraficoEncuestaByNivel($preguntas,$idEncuesta,$sedesIds,$nivelIds,$descTipoEncuestado);
	        $data['comboGrados'] = __buildComboGradosByMultiSedeNivel($sedeIdsDecry,$nivelIdsDecry, $year);//dfloresgonz 02.10.16 columna $year
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
	    } else if($filtro == AREA_ADM){
	        $result = $this->m_g_encuesta->getGraficoEncuestaBySedeArea($preguntas,$idEncuesta,$sedesIds,$areaIds,$descTipoEncuestado);
	    } else if($filtro == TIPO_ENCUESTADO){
	        $result = $this->m_g_encuesta->getGraficoEncuestaByTipoEncuestado($preguntas,$idEncuesta,$descTipoEncuestado);
	        $descTipoEncuestado = strtoupper(substr($descTipoEncuestado, 0,1));
            $idTipoEncu = ($descTipoEncuestado == PADRE) ? TIPO_ENCUESTA_PADREFAM : (($descTipoEncuestado == DOCENTE) ? TIPO_ENCUESTA_DOCENTE : (($descTipoEncuestado == ESTUDIANTE) ? TIPO_ENCUESTA_ALUMNOS : (($descTipoEncuestado == PERSONAL_ADMINISTRATIVO) ? TIPO_ENCUESTA_PERSADM : null)));
	        $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEncu);
	    }
	    $data            += $this->getListasGraficos($result);
	    $data['refresh'] = _encodeCI($filtro);
	    return $data;
	}
	
	function getDataPreguntasGlobales(){ 
	    $data = null;
	    $tipo        = _post('tipo');
	    $top         = _post('top');
	    $idEncuesta  = _decodeCI(_post('idEncuesta'));
	    $coleccion   = _post('satis');
	    $data        = $this->buildDataPreguntasGlobales($tipo,$top,$idEncuesta,$coleccion);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildDataPreguntasGlobales($tipo,$top,$idEncuesta,$coleccion){
	    $data = null;
	    if($idEncuesta != null){
    	    $nombre_coleccion = ($coleccion == 0) ? 'satisfaccion' : 'insatisfaccion';
    	    $nombre_columna   = ($coleccion == 0) ? 'Satisfacción' : 'Insatisfacción';
    	    $arrayData = array_reverse($this->m_g_encuesta->getDataPreguntasGlobalesEncuesta($top,$idEncuesta,$nombre_coleccion));
    	    if($tipo == 'tabla'){
    	        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   data-show-columns="false" data-search="false" id="tb_preg_global">',
    	                      'table_close' => '</table>');
    	        $this->table->set_template($tmpl);
    	        $head_0 = array('data' => '#');
    	        //$head_1 = array('data' => 'Encuesta');
    	        $head_2 = array('data' => 'Pregunta','class' => 'text-left');
    	        $head_3 = array('data' => 'Detalle');
    	        $head_4 = array('data' => $nombre_columna);
    	        $this->table->set_heading($head_0/*,$head_1*/,$head_2,$head_3,$head_4);
    	        $cont = 0;
    	        foreach($arrayData as $row){
    	            $cont++;
    	            $row_0 = array('data' => $cont);
    	            //$row_1 = array('data' => $row['desc_encuesta']);
    	            $row_2 = array('data' => ucfirst(utf8_decode($row['desc_pregunta'])));
    	            $row_3 = array('data' => $row['detalle']);
    	            $row_4 = array('data' => round($row['porcentaje'], 3).'%');
    	            $this->table->add_row($row_0/*,$row_1*/,$row_2,$row_3,$row_4);
    	        }
    	        //Pinta la tabla y activa los botones = 1 | No activa nada y no pinta la tabla = 0
    	        $data['flg_table'] = (count($arrayData) > 0 ) ? '1' : '0';
    	        $data['tabla'] = $this->table->generate(); 
    	    } else if($tipo == 'pie' || $tipo == 'column'){
    	        $arrayCate     = array();
    	        $arrayGeneral  = array();
    	        $arrayGeneralG = array();
    	        foreach($arrayData as $row){
    	            array_push($arrayCate    , /*$row['desc_encuesta'] . ' - ' .*/ ucfirst($row['desc_pregunta']));
    	            array_push($arrayGeneral , array('name' => /*$row['desc_encuesta'] . ' - ' .*/ ucfirst($row['desc_pregunta']) , 'y' => $row['porcentaje']));
    	        }
    	        array_push($arrayGeneralG , $arrayGeneral);
    	        $data['arrayGeneral'] = json_encode($arrayGeneralG);
    	        $data['arrayCate']    = json_encode($arrayCate);
    	    }
    	    $data['error'] = EXIT_SUCCESS;
    	    return $data;
	    } else{
	        $data['error'] = EXIT_ERROR;
	        return $data;
	    }
	}
	
	function buildComboPreguntasCaritasByEncuesta($encuesta){
	    $preguntas = $this->m_pregunta->getPreguntasCaritasByIdEncuesta($encuesta);
	    $opt = null;
	    foreach($preguntas as $preg){
	        $idEncuesta  = $this->encrypt->encode($preg->id_pregunta);
	        $opt .= '<option value="'.$idEncuesta.'">'.$preg->desc_pregunta.'</option>';
	    }
	    return $opt;
	}
	
	function getNivelesGraficoByTipoEnc(){
	    $data['combos'] = null;
	    $idPregunta     = _post('pregunta');
	    $idEncuesta     = _decodeCI(_post('encuesta'));
	    $idTipoEncu     = _post('tipo_encu');
	    $tipoEncuestado = _decodeCI(_post('tipoEncuestado'));
	    $data['arrayCount'] = json_encode(array(array()));
	    if(is_array($idPregunta) && count($idPregunta) > 0 && $idEncuesta != null  && $idTipoEncu != null && $tipoEncuestado != null){
	        $data = $this->getDataGraficosBytipoFiltro($idPregunta,$idEncuesta,$idTipoEncu,null,null,null,null,null,TIPO_ENCUESTADO,$tipoEncuestado);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGraficoTutoriaByEncuesta(){
	    $data['error']   = EXIT_ERROR;
	    $data['msj']     = null;
	    $data['combos']  = null;
	    $data['series']  = json_encode(array());
	    $idEncuesta = _decodeCI(_post('encuesta'));
	    $idTipoEnc  = _decodeCI(_post('tipo_enc'));
	    try{
	        if($idEncuesta == null){
	            throw new Exception('Seleccione una encuesta');
	        }
	        $titulo = _ucwords($this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta));
	        if($idTipoEnc == null){
	            $data['combos'] = null;
	            throw new Exception('Seleccione una encuesta');
	        }
	        $result = $this->m_g_encuesta->getDatosGraficoTutoriaByEncuesta($idEncuesta);
	        $data['series'] = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$titulo);
	        $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEnc,'6');
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e){
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
	
	function getGraficoTutoriaByTipoEnc(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $idEncuesta     = _decodeCI(_post('encuesta'));
	    $tipoEncuestado = _decodeCI(_post('tipo_encu'));
	    $data['series'] = json_encode(array());
	    try{
	        $titulo = ucwords($this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta));
	        if($idEncuesta == null){
	            throw new Exception('Seleccione una encuesta');
	        }
	        if($tipoEncuestado == null){
	            $result         = $this->m_g_encuesta->getDatosGraficoTutoriaByEncuesta($idEncuesta);
	            $data['series'] = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$titulo);
	            $data['combos'] = null;
	            throw new Exception('Seleccione un tipo de encuestado');
	        }
	        $descTipoEncuestado = ($tipoEncuestado != null) ? $this->m_utils->getById('senc.tipo_encuestado', 'desc_tipo_enc', 'id_tipo_encuestado', $tipoEncuestado , 'senc') : null;
	        $result = $this->m_g_encuesta->getDatosGraficoTutoriaByTipoEnc($idEncuesta,$descTipoEncuestado);
	        $data['series']  = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$titulo);
	        //COMBOS
	        $descTipoEncuestado = strtoupper(substr($descTipoEncuestado, 0,1));
	        $idTipoEncu = ($descTipoEncuestado == PADRE) ? TIPO_ENCUESTA_PADREFAM : (($descTipoEncuestado == DOCENTE) ? TIPO_ENCUESTA_DOCENTE : (($descTipoEncuestado == ESTUDIANTE) ? TIPO_ENCUESTA_ALUMNOS : (($descTipoEncuestado == PERSONAL_ADMINISTRATIVO) ? TIPO_ENCUESTA_PERSADM : null)));
	        $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEncu,'6');
	        ////////
	        $data['error'] = EXIT_SUCCESS;
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGraficoTutoriaBySede(){
	    $data['error']  = EXIT_ERROR;
	    $data['msj']    = null;
	    $idSede         = _post('sedes');
	    $idEncuesta     = _decodeCI(_post('encuesta'));
	    $tipoEncuestado = _decodeCI(_post('tipo_encu'));
	    $data['series'] = json_encode(array());
	    try{
	        $tituloEncuesta = ucwords($this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta));
	        $descTipoEncuestado = ($tipoEncuestado != null) ? $this->m_utils->getById('senc.tipo_encuestado', 'desc_tipo_enc', 'id_tipo_encuestado', $tipoEncuestado , 'senc') : null;
	        if($idEncuesta == null){
	            throw new Exception('Seleccione una encuesta');
	        }
	        if(!is_array($idSede)){
	            $result               = $this->m_g_encuesta->getDatosGraficoTutoriaByTipoEnc($idEncuesta,$descTipoEncuestado);
	            $data['series']       = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$tituloEncuesta);
	            $data['comboNiveles'] = null;
	            throw new Exception('Seleccione una sede');
	        }
	        //SEDES
	        $sedeIdsDecry  = $this->decryptArrayIdsInArray($idSede);
	        $sedesIds      = $this->getArrayStringFromArray($idSede);
	        ///////
	        $titulo = (count($sedeIdsDecry) == 1) ? $tituloEncuesta.' - '.$this->m_utils->getById('sede', 'desc_sede', 'nid_sede', $sedeIdsDecry[0]) : 
	                                                $tituloEncuesta;
	        $year          = $this->m_encuesta->getYearFromEncuesta($idEncuesta);
	        $result = $this->m_g_encuesta->getDatosGraficoTutoriaBySedes($idEncuesta,$descTipoEncuestado,$sedesIds);
	        $data['series']  = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$titulo);
	        $data['comboNiveles'] = __buildComboNivelesByMultiSedes($sedeIdsDecry,$year);
	        $data['error'] = EXIT_SUCCESS;
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
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
	        $tituloEncuesta = ucwords($this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta));
	        $descTipoEncuestado   = ($tipoEncuestado != null) ? $this->m_utils->getById('senc.tipo_encuestado', 'desc_tipo_enc', 'id_tipo_encuestado', $tipoEncuestado , 'senc') : null;
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
	        ////////
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
	
	
	function getGraficoTutoriaBySedeNivelGradoAula(){
	    $data['error']  = EXIT_ERROR;
	    $data['msj']    = null;
	    $idEncuesta     = _decodeCI(_post('encuesta'));
	    $tipoEncuestado = _decodeCI(_post('tipo_encu'));
	    $idSede         = _post('sedes');
	    $idNivel        = _post('niveles');
	    $idGrado        = _post('grados');
	    $idAula         = _post('aulas');
	    $data['series'] = json_encode(array());
	    try{
	        $tituloEncuesta = ucwords($this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta));
	        $descTipoEncuestado   = ($tipoEncuestado != null) ? $this->m_utils->getById('senc.tipo_encuestado', 'desc_tipo_enc', 'id_tipo_encuestado', $tipoEncuestado , 'senc') : null;
	        if($idEncuesta == null){
	            throw new Exception('Seleccione una encuesta');
	        }
	        if(!is_array($idSede)){
	            throw new Exception('Seleccione una sede');
	        }
	        //SEDES
	        $sedeIdsDecry    = $this->decryptArrayIdsInArray($idSede);
	        $sedesIds        = $this->getArrayStringFromArray($idSede);
	        if(!is_array($idNivel)){
	            throw new Exception('Seleccione una nivel');
	        }
	        //NIVELES
	        $nivelesIdsDecry = $this->decryptArrayIdsInArray($idNivel);
	        $nivelesIds      = $this->getArrayStringFromArray($idNivel);
	        if(!is_array($idGrado)){
	            throw new Exception('Seleccione un grado');
	        }
	        //GRADOS
	        $gradosIdsDecry  = $this->decryptArrayIdsInArray($idGrado);
	        $gradosIds       = $this->getArrayStringFromArray($idGrado);
	        if(!is_array($idAula)){
	            $result               = $this->m_g_encuesta->getDatosGraficoTutoriaBySedesNivelesGrados($idEncuesta,$descTipoEncuestado,$sedesIds,$nivelesIds,$gradosIds);
	            $data['series']       = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$tituloEncuesta);
	            throw new Exception('Seleccione una aula');
	        }
	        //AULAS
	        $aulasIdsDecry   = $this->decryptArrayIdsInArray($idAula);
	        $aulasIds        = $this->getArrayStringFromArray($idAula);
	        //YEAR
	        $year            = $this->m_encuesta->getYearFromEncuesta($idEncuesta);
	        ////////
	        $titulo = (count($aulasIdsDecry) == 1) ? $this->m_g_encuesta->getTituloGraficoTutoriaByAula($aulasIdsDecry[0]) :
	                                                 $tituloEncuesta;
	        $result               = $this->m_g_encuesta->getDatosGraficoTutoriaBySedesNivelesGradosAulas($idEncuesta,$descTipoEncuestado,$sedesIds,$nivelesIds,$gradosIds,$aulasIds);
	        $data['series']       = $this->buildSeriesTutoriaByEncuesta($result['retval'],$idEncuesta,$titulo);
	        $data['error']        = EXIT_SUCCESS;
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function refreshGrafico1(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $encuesta        = _decodeCI(_post('encuesta'));
	        $preguntas       = _post('pregunta');
	        $tipoEncuestado  = _decodeCI(_post('tipoEncuestado'));
	        $sede            = _post('sedes');
	        $nivel           = _post('nivel');
	        $grados          = _post('grados');
	        $aula            = _post('aula');
	        $area            = _post('area');
	        $refresh         = _decodeCI(_post('refreshGrafico1'));
	        
	        $data = $this->getDataGraficosBytipoFiltro($preguntas,$encuesta,null,$sede,$nivel,$grados,$area,$aula,$refresh,$tipoEncuestado);
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	
}



