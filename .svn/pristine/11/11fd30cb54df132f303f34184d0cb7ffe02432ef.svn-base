<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('_getFotoFromCookie')) {
    function _getFotoFromCookie($cookieVal) {
        return _simple_decrypt($cookieVal);
    }
}

if(!function_exists('_validate_metodo_rol')) {
    /**
     * Metodo para validar que se le va a mostrar a cada tipo de rol
     * @param $rol rol de la persona en sesion
     * @return Boolean TRUE OR FALSE
     */
    function _validate_metodo_rol($rol) {
        $tof = false;
        if($rol == ID_ROL_PROMOTOR || $rol == ID_ROL_DIRECTOR || $rol == ID_ROL_ADMINISTRADOR) {
            $tof = true;
        }
        return $tof;
    }
}

if(!function_exists('__getOptionTipoEncuestaByIdSimpleDecrypt')) {
    function __getOptionTipoEncuestaByIdSimpleDecrypt($idTipoEncuesta,$tipoCryp = null) {
        $CI =& get_instance();
        $CI->load->model('m_encuesta');
        $categorias = $CI->m_encuesta->getTipoEncuestaById($idTipoEncuesta);
        $opt = null;
        foreach($categorias as $cat){
            if($tipoCryp == null){
                $idIndi  = _simple_encrypt($cat->id_tipo_encuesta);
            } else{
                $idIndi  = _encodeCI($cat->id_tipo_encuesta);
            }
            $opt .= '<option value="'.$idIndi.'">'.$cat->desc_tipo_encuesta.'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboTipoEncuestaSimpleEncrypt')) {
    function __buildComboTipoEncuestaSimpleEncrypt(){
        $CI =& get_instance();
        $CI->load->model('m_utils_senc');
        $categorias = $CI->m_utils_senc->getAllTipoEncuesta();
        $opt = null;
        foreach($categorias as $cat){
            $opt .= '<option value="'._simple_encrypt($cat->id_tipo_encuesta).'">'.$cat->desc_tipo_encuesta.'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildRadioAllTipoPreguntas')) {
    function __buildRadioAllTipoPreguntas($onclick,$tipoPregCheck){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $tipoPreg = $CI->m_utils->getAllTipoPreguntas();
        $radio = '';
        foreach ($tipoPreg as $row){
            $id_tipo_preg = _simple_encrypt($row->id_tipo_pregunta);
            $checked = ($tipoPregCheck == $row->id_tipo_pregunta) ? 'checked' : null;
            $radio .= '<div class="col-sm-5 col-sm-offset-1 col-xs-10 col-xs-offset-1"><label class="radio-inline radio-styled" style="margin-bottom: 10px">
    						      <input type="radio" name="radioVals" onchange="'.$onclick.'"  '.$checked.' value="'.$id_tipo_preg.'"><span>'.$row->desc_tipo_preg.'</span>
    					      </label></div>';
        }
        return $radio;
    }
}

if(!function_exists('__buildComboTipoEncuesta')) {
    function __buildComboTipoEncuesta(){
        $CI =& get_instance();
        $CI->load->model('m_utils_senc');
        $categorias = $CI->m_utils_senc->getAllTipoEncuesta();
        $opt = null;
        foreach($categorias as $cat){
            $idIndi  = $CI->encrypt->encode($cat->id_tipo_encuesta);
            $opt .= '<option value="'.$idIndi.'">'.$cat->desc_tipo_encuesta.'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboTipoEncuestado')) {
    function __buildComboTipoEncuestado(){
        $CI =& get_instance();
        $CI->load->model('m_utils_senc');
        $tEncuestados = $CI->m_utils_senc->getAllTipoEncuestado();
        $opt = null;
        foreach($tEncuestados as $tenc){
            $idIndi  = $CI->encrypt->encode($tenc->id_tipo_encuestado);
            $opt .= '<option value="'.$idIndi.'">'.$tenc->desc_tipo_enc.'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__getGraficoUnaOpcion')) {
    function __getGraficoUnaOpcion($result, $tipo = null, $desc_tipo_encuestado = null,$cantEnc){
        $arrayCat     = array();
        $arrayCount   = array();
        $arrayName    = array();
        $arrayGeneral = array();
        $arrayCcat    = array();
        $desc         = null;
        $sumTotal     = 0;
        foreach($result['retval'] AS $row){
            $sumTotal = $row['sum'];
            $arrayCount = array();
            foreach($row['count'] AS $count){
                array_push($arrayCcat  , $count);
                array_push($arrayCount , round(($count*100)/$sumTotal, 3));
            }
            foreach($row['desc_respuestas'] AS $rpta){
                array_push($arrayCat   , $rpta);
            }
            foreach($row['desc_preguntas'] AS $preg){
                $desc = ($preg);
            }
            array_push($arrayName  , $row['desc_encuesta']);
        }
        $arrayAux    = array();
        $arrayAuxCat = array();
        for($i = 0; $i < count($arrayCount); $i++){
            if($tipo == CARITAS){
                array_push($arrayAux, array('y' => $arrayCount[$i], 'name' => $arrayCat[$i], 'color' => json_decode(ARRAY_COLORES_CARITAS)->$arrayCat[$i]));
            } else{ 
                array_push($arrayAux, array('y' => $arrayCount[$i], 'name' => $arrayCat[$i]));
            }
            array_push($arrayAuxCat, $arrayCat[$i].' : '.$arrayCcat[$i]);
        }
//         $sumTotal = (isset($result['retval'][0]) && $tipo == CASILLAS_VERIFICACION) ? $row['cant_encu'] : $sumTotal;
        array_push($arrayGeneral, $arrayAux);
//         $sumTotal = (($cantEnc['flg_obligatorio'] == FLG_OBLIGATORIO && $sumTotal > $cantEnc['cant_encuestados']) ? $sumTotal : $cantEnc['cant_encuestados']);
//         $data['titulo']     = $desc.' - Total: '.(($cantEnc['flg_obligatorio'] == FLG_OBLIGATORIO) ? $sumTotal : $cantEnc['cant_encuestados']).' - '.$desc_tipo_encuestado;
        $data['titulo']     = $desc.' - Total: '.$sumTotal;
        $arrayName = array_values(array_unique($arrayName));
        $data['total']      = $sumTotal;
        $data['arrayCount'] = json_encode($arrayGeneral);
        $data['arrayCat']   = json_encode($arrayAuxCat);
        $data['arrayName']  = json_encode($arrayName);
        return $data;
    }
}

if(!function_exists('__getHTML_RubricaFisicaEFQM_Padres')) {
    function __getHTML_RubricaFisicaEFQM_Padres($idEncuesta, $idSede = null) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $logoColegio = '<img src="'.RUTA_IMG.'logos_colegio/logonslm.png" width="80" height="80">';
        $telefono = '2921460';
        if($idSede != null && $idSede == ID_SEDE_AVANTGARD) {
            $logoColegio = '<img src="'.RUTA_IMG.'logos_colegio/avantgard.png" width="60" height="60">';
            $telefono = '2325289';
        }
        $CI->load->model('mf_encuesta/m_encuesta');
        $html = null;
        $html .= '<div style="text-align: center">'.$logoColegio.'
                          
                              <h3 style="text-decoration: underline;">'.$CI->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta).'</h3>
                          </div>
                                  <br>';
        $html .= '<div style="padding: 5px;margin:5px;border:1px solid black;">
                    Estimado padre de familia:<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;La presente encuesta es parte del recojo de información para la Acreditación Institucional.<br><br>
                    Recuerda que la puedes hacer v&iacute;a Web, es m&aacute;s r&aacute;pida, Entra a <span style="font-weight: bold;">http://buhooweb.com/smiledu/padres</span><br>
                    Selecciona la sede donde estudia tu hija/o, y el mismo usuario/clave con el que ingresas a la intranet a ver notas.
                    Si tienes problemas comun&iacute;cate con soporte (net@nslm.edu.pe / '.$telefono.').<br><br>
                    De la sinceridad que muestre al responder las interrogantes, se obtendrán alternativas de solución para mejorar la calidad educativa en nuestra Institución.
                    <br><br>
                    <p style="font-weight: bold;font-size:10px;text-decoration: underline;">Indicaciones:</p>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Antes de contestar, leer detenidamente y meditar los enunciados y respuestas. Le solicitamos que no deje preguntas sin contestar, recuerde, no hay respuesta buena o mala.
                    <br><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Marque con un aspa (X) su respuesta en los recuadros.<br>
                    Necesitamos saber su opinión como padre de familia. Llenar de acuerdo a la percepción que tienen de la institución.
                    <br><br><br>
                    <p style="font-weight: bold;font-size:10px;">Indique su grado de satisfacción en relación a los siguientes temas:</p></div>';
        $tipoEnc = $CI->m_utils->getById("senc.encuesta", "_id_tipo_encuesta", "id_encuesta", $idEncuesta);
        if($tipoEnc == TIPO_ENCUESTA_LIBRE) {
            $html .= '- Usted es: (Marque solo una opci&oacute;n)<br>';
            $html .= '<div style="text-align: center;">';
            $tipoEncuestadoArray = $CI->m_encuesta->getCantTipoEncuestadoByIdEnc($idEncuesta);
            foreach ($tipoEncuestadoArray as $tipEncu) {
                $html .= '<input type="checkbox" id="encu_'.$tipEncu->abvr_tipo_enc.'">
                          <label for="encu_'.$tipEncu->abvr_tipo_enc.'">'.$tipEncu->desc_tipo_enc.'</label>&nbsp;&nbsp;&nbsp;';
            }
            $html .= '</div><br>';
        }
        //Categorias
        $categoriaArray = $CI->m_encuesta->getCategoriasByEncuesta($idEncuesta);
        $catIndex = 1;
        $arrayServicios = explode(';', SERVICIOS_COMPLEMENTARIOS);
        foreach($categoriaArray as $row) {
            $html .= '<p style="font-weight: bold;font-size:12px;">'.$catIndex.') '.$row->desc_cate.'</p>';
            //Preguntas x Categoria
            $preguntasArray = $CI->m_encuesta->getPreguntasByCategoria($idEncuesta, $row->_id_categoria, $arrayServicios, _getSesion('tipoEncuGlobal'));
            $pregIndex = 1;
            foreach ($preguntasArray as $rowPreg) {
                $textAyuda = null;
                /*if($rowPreg->id_tipo_pregunta == CINCO_CARITAS   || $rowPreg->id_tipo_pregunta == TRES_CARITAS ||
                 $rowPreg->id_tipo_pregunta == OPCION_MULTIPLE || $rowPreg->id_tipo_pregunta == LISTA_DESPLEGABLE ||
                 $rowPreg->id_tipo_pregunta == DOS_OPCIONES    || $rowPreg->id_tipo_pregunta == CUATRO_CARITAS) {
                 $textAyuda = '(Marque solo una opci&oacute;n)';
                 } else */
                if($rowPreg->_id_tipo_pregunta == CASILLAS_VERIFICACION) {
                    $textAyuda = '(Puede marcar varias opciones)';
                } else {
                    $textAyuda = '(Marque solo una opci&oacute;n)';
                }
                $asteriscoOblig = ($rowPreg->flg_obligatorio ==  FLG_OBLIGATORIO) ? '<span style="color:#ff0000;">(*)</span>' : null;
                $html .= '<p style="margin-left:22px;">'.$pregIndex.') '.$rowPreg->desc_pregunta.'&nbsp;&nbsp;'.$textAyuda.'&nbsp;&nbsp;'.$asteriscoOblig.'</p>';
                //Alternativas x Pregunta
                $alternativaArray = $CI->m_encuesta->getAlternativas($rowPreg->_id_pregunta,$idEncuesta);
                $html .= '<div style="text-align: center;">';
                foreach ($alternativaArray as $rowAlter) {
                    $idAlter = 'alter_'.$rowPreg->_id_pregunta.'_'.$rowAlter->_id_alternativa;
                    $html .= '<input type="checkbox" id="'.$idAlter.'">
                              <label for="'.$idAlter.'">'.$rowAlter->desc_alternativa.'</label>&nbsp;&nbsp;&nbsp;';
                }
                $html .= '</div>';
                $pregIndex++;
            }
            $catIndex++;
        }
        $html .= 'Propuestas de mejora:<br>
                 <textarea rows="11" cols="100"></textarea>';
        return $html;
    }
}