<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_g_propuesta_mejora extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('mf_graficos/m_g_propuesta_mejora');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('m_utils');
        $this->load->library('table');
    }
    
    public function getGraficoByPropuestaMejora(){
        $data['combos'] = null;
        $idTipoEncu = $this->encrypt->decode(_post("tipo_encuesta"));
        $idPropuesta = _post("propuesta_mejora");
        $idEncuesta = $this->encrypt->decode(_post("encuesta"));
        $data['arrayCount'] = json_encode(array(array()));
        _log($idEncuesta);
        if(is_array($idPropuesta)){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,$idTipoEncu,null,null,null,null,null,PROPUESTA);
        }
        
//         _log(print_r($data,true));
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getEncuestaByTipoEncuesta(){
        $idTipo = $this->encrypt->decode(_post('tipo_encuesta'));
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
    
    function getPropuestasMejora(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $encuesta = $this->encrypt->decode(_post('encuesta'));
            if($encuesta == null){
                throw new Exception('Seleccione una Encuesta');
            }
            $data['optProp'] = __buildComboPropuestasMejora($encuesta);
            $data['error']   = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getListasGraficos($result,$cantPart = null){
        $data          = null;
        $arrayCat      = array();
        $arrayCount    = array();
        $arrayName     = array();
        $arrayID       = array();
        $arrayGeneral  = array();
        $desc          = null;
        $preg          = null;
        $arrayCcat     = array();
        $sumTotal      = 0;
        $part          = null;
        foreach($result['retval'] AS $row){
            $sumTotal = $row['sum'];
            $part = $row['part'];
            $arrayCount = array();
            foreach($row['count'] AS $count){
                array_push($arrayCount , round(100*($count/$cantPart), 3));
                array_push($arrayCcat, $count);
            }
            foreach($row['desc_propuesta'] AS $rpta){
                array_push($arrayCat   , $rpta);
            }
            foreach($row['id_propuesta'] AS $rpta){
                array_push($arrayID   , _simple_encrypt($rpta));
            }
            array_push($arrayName  , $row['desc_encuesta']);
        }
        $arrayAux    = array();
        $arrayAuxCat = array();
        $totalPerc   = 0;
        for($i = 0; $i < count($arrayCount); $i++){
            array_push($arrayAux, array('y' => $arrayCount[$i], 'name' => $arrayCat[$i], 'id' => $arrayID[$i]));
            array_push($arrayAuxCat, $arrayCat[$i].' : '.$arrayCcat[$i]);
            $totalPerc += $arrayCount[$i];
        }
        $countNoLlenaron = 100 - $totalPerc;
        if($countNoLlenaron > 0){
            array_push($arrayAuxCat, 'No llenaron');
            array_push($arrayAux, array('y' => (100-$totalPerc) , 'name' => 'No llenaron' ));
        }
        
        $data['titulo']     = $cantPart.' encuestados';
        array_push($arrayGeneral, $arrayAux);
        $arrayName          = array_values(array_unique($arrayName));
        $data['arrayCount'] = json_encode($arrayGeneral);
        $data['arrayCat']   = json_encode($arrayAuxCat);
        $data['arrayid']    = json_encode($arrayID);
        $data['arrayName']  = json_encode($arrayName);
        return $data;
    }
    
    function getCombosByTipoEncuesta($tipoEncuesta){
        $combos = null;
        $sedes      = __buildComboSedes();
        if($tipoEncuesta == TIPO_ENCUESTA_ALUMNOS){
            $comboSede  = $this->getComboByTipo(SEDE, "getNivelesBySedeGrafico3", $sedes, "p-r-form-group");
            $comboNivel = $this->getComboByTipo(NIVEL, "getGradosByNivelSedeGrafico3", null, "p-l-form-group");
            $comboGrado = $this->getComboByTipo(GRADO, "getAulasByNivelGrafico3", null, "p-r-form-group");
            $comboAula  = $this->getComboByTipo(AULA,"getGraficoByAula3", null, "p-l-form-group");
            $combos     = $comboSede.$comboNivel.$comboGrado.$comboAula;
        }else if($tipoEncuesta == TIPO_ENCUESTA_DOCENTE){
            $comboSede  = $this->getComboByTipo(SEDE, "getNivelesBySedeGrafico3", $sedes, "p-r-form-group");
            $comboNivel = $this->getComboByTipo(NIVEL, "getAreasByNivelSedeGrafico3", null, "p-l-form-group");
            $comboArea  = $this->getComboByTipo(AREA, "getGraficoByAreaNivelSedeGrafico3", null, "p-r-form-group");
            $combos     = $comboSede.$comboNivel.$comboArea;
        }else if($tipoEncuesta == TIPO_ENCUESTA_PADREFAM){
            $comboSede  = $this->getComboByTipo(SEDE, "getNivelesBySedeGrafico3", $sedes, "p-r-form-group");
            $comboNivel = $this->getComboByTipo(NIVEL, "getGradosByNivelSedeGrafico3", null, "p-l-form-group");
            $comboGrado = $this->getComboByTipo(GRADO, "getAulasByNivelGrafico3", null, "p-r-form-group");
            $comboAula  = $this->getComboByTipo(AULA,"getGraficoByAula3", null, "p-l-form-group");
            $combos     = $comboSede.$comboNivel.$comboGrado.$comboAula;
        }else if($tipoEncuesta == TIPO_ENCUESTA_PERSADM){
            $comboSede  = $this->getComboByTipo(SEDE, "getNivelesBySedeGrafico3", $sedes, "p-r-form-group");
            $comboNivel = $this->getComboByTipo(NIVEL, "getAreasByNivelSedeGrafico3", null, "p-l-form-group");
            $comboArea  = $this->getComboByTipo(AREA, "getGraficoByAreaNivelSedeGrafico3", null, "p-r-form-group");
            $combos     = $comboSede.$comboNivel.$comboArea;
        }
    
        return $combos;
    }
    
    function getComboByTipo($tipo, $onClick, $data, $class){
        $result = null;
        if($tipo == SEDE){
            $encryptAuxValue = $this->encrypt->encode(0);
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectSedeGrafico3" name="selectSedeGrafico3" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
        	                    </select>
    	                    </div>
	                   </div>';    
        } else if ($tipo == NIVEL) {
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectNivelGrafico3" name="selectNivelGrafico3" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
        } else if ($tipo == GRADO) {
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectGradoGrafico3" name="selectGradoGrafico3" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
        } else if ($tipo == AULA) {
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectAulaGrafico3" name="selectAulaGrafico3" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
        	                    </select>
    	                    </div>
	                   </div>';
        }else if($tipo == DISCIPLINA){
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectDisciplinaGrafico3" name="selectDisciplinaGrafico3" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()">
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
        }else if($tipo == AREA){
            $result .= '<div class="col-sm-6 '.$class.'">
                            <div class="form-group">
                                <select id="selectAreaGrafico3" name="selectAreaGrafico3" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()" multiple>
        	                        '.$data.'
                                </select>
    	                    </div>
	                   </div>';
        }
        return $result;
    }
    
    /**
     * @author César Villarreal 09/03/2016
     * @param arrayIds $arrayEncrypt
     * @return retorna array con los ids desencriptados
     */
    function decryptArrayIdsInArray($arrayEncrypt){
        $arrayDecrypt = array();
        foreach($arrayEncrypt AS $idEncry){
            array_push($arrayDecrypt, $this->encrypt->decode($idEncry));
        }
        return $arrayDecrypt;
    }
    
    
    /**
     * @author César Villarreal 28/03/2016
     * @param arrayIdsPropuesta $idPropuesta
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
    function getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,$idTipoEncu,$sedes,$nivel,$grado,$area,$aula,$filtro){
        $data['combos'] = null;
        $year           = (is_array($grado) || is_array($sedes)) ? $this->m_encuesta->getYearFromEncuesta($idEncuesta) : null;
        //PREGUNTAS
        $propuestas = (is_array($idPropuesta)) ? __getArrayStringFromArray($idPropuesta) : null;
        //SEDES
        $sedeIdsDecry  = (is_array($sedes)) ? __getArrayObjectFromArray($sedes) : array();
        $sedesIds      = (is_array($sedes)) ? __getArrayStringFromArray($sedes) : null;
        //NIVELES
        $nivelIdsDecry = (is_array($nivel)) ? __getArrayObjectFromArray($nivel) : array();
        $nivelIds      = (is_array($nivel)) ? __getArrayStringFromArray($nivel) : null;
        //GRADOS
        $gradosIdsDecry = (is_array($grado)) ? __getArrayObjectFromArray($grado) : array();
        $gradosIds      = (is_array($grado)) ? __getArrayStringFromArray($grado) : null;
        //AULAS
        $aulasIds       = (is_array($aula)) ? __getArrayStringFromArray($aula) : null;
        //AREAS
        $areaIds = (is_array($area)) ? __getArrayStringFromArray($area) : null;
        $cant_part = $this->m_g_propuesta_mejora->getCantParticipantesByEncuesta($idEncuesta,$sedesIds,$nivelIds,$gradosIds);
        _log($filtro);
        if($filtro == PROPUESTA){
            $result = $this->m_g_propuesta_mejora->getGraficoPropuestaMejoraByPropuesta($propuestas,$idEncuesta);
            $data['combos'] = $this->getCombosByTipoEncuesta($idTipoEncu);
        } else if($filtro == SEDE){
            $result = $this->m_g_propuesta_mejora->getGraficoPropuestaMejoraBySede($propuestas,$idEncuesta,$sedesIds);
            $data['comboNiveles'] = __buildComboNivelesByMultiSedes($sedeIdsDecry,$year);
        } else if($filtro == NIVEL){
            $result = $this->m_g_propuesta_mejora->getGraficoPropuestaMejoraBySedeNivel($propuestas,$idEncuesta,$sedesIds,$nivelIds);
            $data['comboGrados'] = __buildComboGradosByMultiSedeNivel($sedeIdsDecry,$nivelIdsDecry,$year);
        } else if($filtro == GRADO){
            $result = $this->m_g_propuesta_mejora->getGraficoPropuestaMejoraByGrado($propuestas,$idEncuesta,$sedesIds,$nivelIds,$gradosIds);
            $data['comboAulas'] = __buildComboAulasMulti($sedeIdsDecry,$nivelIdsDecry,$gradosIdsDecry,$year);
        } else if($filtro == AULA){
            $result    = $this->m_g_propuesta_mejora->getGraficoPropuestaMejoraByAula($propuestas,$idEncuesta,$sedesIds,$nivelIds,$gradosIds,$aulasIds);
        } else if($filtro == AREA){
            $result = $this->m_g_propuesta_mejora->getGraficoPropuestaMejoraByArea($propuestas,$idEncuesta,$sedesIds,$nivelIds,$areaIds);
        } else if($filtro == NIVELDOC){
            $result = $this->m_g_propuesta_mejora->getGraficoPropuestaMejoraBySedeNivel($propuestas,$idEncuesta,$sedesIds,$nivelIds);
            $data['comboAreas'] = __buildComboAreasAcad();
        } else if($filtro == SEDE_AREA){
	        $result = $this->m_g_propuesta_mejora->getGraficoPropuestaMejoraBySede($propuestas,$idEncuesta,$sedesIds);
	        $data['comboAreas'] = __buildComboAreasGenerales();
	    } else if($filtro == AREA_ADM){
	        $result = $this->m_g_propuesta_mejora->getGraficoPropuestaMejoraByArea($propuestas,$idEncuesta,$sedesIds,$nivelIds,$areaIds);
	    }
        $data += $this->getListasGraficos($result,$cant_part);
        return $data;
    }
    
    function getNivelesBySedeGrafico(){
        $data['tipo'] = 0;
        $sedes = _post('sedes');
        $idPropuesta = _post('propuesta_mejora');
        $idEncuesta = $this->encrypt->decode(_post('encuesta'));
        $idTipoEncu = $this->encrypt->decode(_post('tipo_encu'));
        $data['comboNiveles'] = null;
        if(is_array($idPropuesta) && is_array($sedes) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,null,null,null,null,SEDE);
        } else if($sedes == null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,$idTipoEncu,null,null,null,null,null,PROPUESTA);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGradosByNivelSedeGrafico(){
        $data['tipo'] = 0;
        $sedes  = _post('sedes');
        $nivel  = _post('nivel');
        $idPropuesta = _post('propuesta_mejora');
        $idEncuesta = $this->encrypt->decode(_post('encuesta'));
        $data['comboGrados'] = null;
        if(is_array($idPropuesta) && is_array($sedes) && is_array($nivel) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,$nivel,null,null,null,NIVEL);
        } else if(is_array($idPropuesta) && is_array($sedes) && $nivel == null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,null,null,null,null,SEDE);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getAulasByGradoNivelSedeGrafico(){
        $data['tipo'] = 0;
        $sedes  = _post('sedes');
        $nivel  = _post('nivel');
        $grado  = _post('grado');
        $idPropuesta = _post('propuesta_mejora');
        $idEncuesta = $this->encrypt->decode(_post('encuesta'));
        if(is_array($idPropuesta) && is_array($sedes) && is_array($nivel) && is_array($grado) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,$nivel,$grado,null,null,GRADO);
        } else if(is_array($idPropuesta) && is_array($sedes) && is_array($nivel) && $grado == null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,$nivel,null,null,null,NIVEL);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGraficoByAula(){
        $data['tipo'] = 0;
        $sedes   = _post('sedes');
        $nivel   = _post('nivel');
        $grado   = _post('grado');
        $aula    = _post('aula');
        $idPropuesta = _post('propuesta_mejora');
        $idEncuesta = $this->encrypt->decode(_post('encuesta'));
        if(is_array($idPropuesta) && is_array($sedes) && is_array($nivel) && is_array($grado) && is_array($aula) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,$nivel,$grado,null,$aula,AULA);
        } else if(is_array($idPropuesta) && is_array($sedes) && is_array($nivel) && is_array($grado) && $aula == null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,$nivel,$grado,null,null,GRADO);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getAreasGraficoByNivel(){
        $data['tipo'] = 0;
        $sedes = _post('sedes');
        $nivel  = _post('nivel');
        $idPropuesta = _post('propuesta_mejora');
        $idEncuesta = $this->encrypt->decode(_post('encuesta'));
        $idTipoEncu = $this->encrypt->decode(_post('tipo_encu'));
        if(is_array($idPropuesta) && is_array($sedes) && is_array($nivel) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,$nivel,null,null,null,NIVELDOC);
        } else if(is_array($idPropuesta) && is_array($sedes) && $nivel == null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,$idTipoEncu,$sedes,$nivel,null,null,null,SEDE);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGraficoByArea(){
        $data['tipo'] = 0;
        $sedes = _post('sedes');
        $nivel  = _post('nivel');
        $idPropuesta = _post('propuesta_mejora');
        $areas = _post('area');
        $idEncuesta = $this->encrypt->decode(_post('encuesta'));
        if(is_array($idPropuesta) && is_array($sedes) && is_array($nivel) && is_array($areas) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,$nivel,null,$areas,null,AREA);
        } else if(is_array($idPropuesta) && is_array($sedes) && is_array($nivel)&& $areas == null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,$nivel,null,null,null,NIVELDOC);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getAreasBySedeGrafico(){
        $data['tipo'] = 0;
        $sedes = _post('sedes');
        $idPropuesta = _post('propuesta_mejora');
        $idEncuesta = $this->encrypt->decode(_post('encuesta'));
        $idTipoEncu = $this->encrypt->decode(_post('tipo_encu'));
        $data['comboNiveles'] = null;
        if(is_array($idPropuesta) && is_array($sedes) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,null,null,null,null,SEDE_AREA);
        } else if($sedes == null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,$idTipoEncu,null,null,null,null,null,PROPUESTA);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGraficoByAreaSede(){
        $data['tipo'] = 0;
        $sedes = _post('sedes');
        $idPropuesta = _post('propuesta_mejora');
        $idEncuesta = $this->encrypt->decode(_post('encuesta'));
        $idTipoEncu = $this->encrypt->decode(_post('tipo_encu'));
        $areas = _post('area');
        if(is_array($idPropuesta) && is_array($sedes) && is_array($areas) && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,null,null,$areas,null,AREA_ADM);
        } else if(is_array($idPropuesta) && is_array($sedes) && $areas == null && $idEncuesta != null){
            $data = $this->getDataGraficosBytipoFiltro($idPropuesta,$idEncuesta,null,$sedes,null,null,null,null,SEDE_AREA);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getComentarioByPropuesta(){
        $idProp = _simpleDecryptInt((_post('id_prop'))); 
        $idEnc  = _decodeCI(_post('id_encuesta'));
        
        $data  = null;
        $tabla = null;
        if($idProp != null && $idEnc != null){
            $comentarios  = $this->m_g_propuesta_mejora->getComentarioByPropuestaMejora($idProp, $idEnc);
            $data['desc'] = _ucwords($this->m_utils->getById('senc.propuesta_mejora', 'desc_propuesta', 'id_propuesta', $idProp));
            $tabla = $this->createTableComentarios(((isset($comentarios["retval"])) ? $comentarios["retval"] : array()));
        }
        $data['tablaComentario'] = $tabla;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function createTableComentarios($comentarios){
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_comentarios">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1  = array('data' => '#');
        $head_2  = array('data' => 'Comentario');
        $val = 0;
        $this->table->set_heading($head_1, $head_2);
        foreach($comentarios as $row){
            if(isset($row['propuestas']['comentario']) && $row['propuestas']['comentario'] != null) {
                $val++;
                $row_col1  = array('data' => $val);
                $row_col2  = array('data' => utf8_decode($row['propuestas']['comentario']));
                $this->table->add_row($row_col1, $row_col2);
            }
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
}