<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_upload_excel extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->load->library('Classes/PHPExcel.php');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('m_crear_encuesta');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, ID_PERMISO_ADMIN_ENC, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
    }
    
    public function index() {
        //$this->load->view('v_upload_excel');
        $this->load->view('v_consultar_encuesta');
    }
    
    function subirExcelEncuesta() {
        $start = microtime(true);
        $data['error'] = ERROR_MONGO;
        $data['msj']   = null;
        try {
            $idEncuesta = _simpleDecryptInt(_post('idEncuestaGlobal'));
            $arryInfo   = json_decode(_post('client_info'));//Info dispositivo cliente
            //Que desencripte bien
            if($idEncuesta == null) {
                throw new Exception(ANP);
            }
            //Que el id encuesta este APERTURADA
            $estado = $this->m_utils->getById('senc.encuesta', 'flg_estado', 'id_encuesta', $idEncuesta);
            if($estado != ENCUESTA_APERTURADA) {
                throw new Exception(ANP);
            }
            //if(isset($_POST["itFileXLS"])) {
                if(!empty($_FILES["itFileXLS"]["tmp_name"])) {
                    $ext = pathinfo($_FILES["itFileXLS"]["name"], PATHINFO_EXTENSION);
                    if($ext != 'xlsm' && $ext != 'xls' && $ext != 'xlsx') {
                        throw new Exception('El archivo no tiene la extensión requerida');
                    }
                    $file = 'excel_'.__generateRandomString(5).date("dmhis").'.'.$ext;
                    $config['upload_path']   = EXCEL_PATH;
                    $config['allowed_types'] = '*';
                    $config['max_size']	     = EXCEL_MAX_SIZE;
                    $config['file_name']     = $file;
            
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('itFileXLS')){
                        throw new Exception($this->upload->display_errors());
                    }
                    //LEER EL EXCEL
                    $upload_data = $this->upload->data();
                    $nombreArchivoNew = EXCEL_PATH_BD.$upload_data['file_name'];
                    $data['archivo'] = $nombreArchivoNew;
                    $data['error'] = SUCCESS_MONGO;
                    ////////////////////////////////////////
                    $inputFileName = './'.$nombreArchivoNew;
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    if(count($cell_collection) == 0) {
                        throw new Exception('Debe subir un excel con los datos requeridos');
                    }
                    $arrayGeneralFinal    = array();
                    $arrayXEncuesta       = array();
                    $arrayPropuestaM      = array();
                    $arrayFinalPropM      = array();
                    $arrayComenPropM      = array();
                    $arrayFinalComenPropM = array();
                    
                    //Validar si el ID de encuesta que viene de la vista es igual al que esta embebido en el excel
                    $idEncuestaCryptedExcel = $objPHPExcel->getSheet(3)->getCell('A1')->getValue();
                    $idEncuestaCryptedExcel = _decodeCI($idEncuestaCryptedExcel);
                    if($idEncuestaCryptedExcel == null) {
                        throw new Exception(ANP.'1');
                    }
                    if($idEncuestaCryptedExcel != $idEncuesta) {
                        throw new Exception(ANP.'2');
                    }
                    //SON IGUALES LOS IDS TODO BIEN
                    $idTipoEncuesta = $this->m_encuesta->getIdEncbyDesc($idEncuestaCryptedExcel);
                    if($idTipoEncuesta == null) {
                        throw new Exception(ANP.'3');
                    }
                    //FIN DE VALIDACIONES
                    foreach ($cell_collection as $cell) {
                        $columnName = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $rowNumber  = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        $decode_value = utf8_decode($data_value);
                        $A = $objPHPExcel->getActiveSheet()->getCell('A'.$rowNumber)->getValue();
                        $B = $objPHPExcel->getActiveSheet()->getCell('B'.$rowNumber)->getValue();
                        $C = $objPHPExcel->getActiveSheet()->getCell('C'.$rowNumber)->getValue();
                        $D = $objPHPExcel->getActiveSheet()->getCell('D'.$rowNumber)->getValue();
                        //$E = $objPHPExcel->getActiveSheet()->getCell('E'.$rowNumber)->getValue();
                        $vacio = ($A == null && $rowNumber < 5);
                        $vacio2 = ($A == null && $rowNumber > 5 && $B == null && $C == null && $D == null );
                    
                        //
                        //(substr($cell, 0, 1)
                        if ($rowNumber == 1) {
                    
                        } else if(!$vacio && !$vacio2) {
                            if($rowNumber >= 5 && $columnName != 'A') {
                                $idPreguntas = $objPHPExcel->getActiveSheet()->getCell($columnName.'3')->getValue();
                                //lOGICA
                                if($columnName == 'B'){
                                    if(count($arrayXEncuesta) != 0) {
                                        array_push($arrayGeneralFinal, $arrayXEncuesta);
                                    }
                                    if(count($arrayPropuestaM) != 0) {
                                        array_push($arrayFinalPropM, $arrayPropuestaM);
                                    }
                                    $arrayXEncuesta =  array();
                                    $arrayPropuestaM = array();
                                    if(count($arrayComenPropM) != 0) {
                                        array_push($arrayFinalComenPropM, $arrayComenPropM);
                                    }
                                    $arrayComenPropM = array();
                                }
                                if($idPreguntas == null) {//PROPUESTA DE MEJORA
                                    $porciones = explode(",", $decode_value);
                                    foreach ($porciones as $rowDescPropM) {
                                        $cantPalabras = explode(" ", trim($rowDescPropM));
                                        if(count($cantPalabras) > 5 || $cantPalabras == null || strlen($rowDescPropM) > 100){
                                            throw new Exception('Solo se permiten 5 palabras o un máximo de 100 caracteres en total');
                                        }
                                        $idAlterPropM = $this->m_encuesta->getIdAlterPropMbyDesc(trim($rowDescPropM));//verificar si existe la Propuesta de Mejora
                    
                                        if($idAlterPropM == null){// no existe entonces insertar en PG
                                            $arrayInsert = array("desc_propuesta" => strtoupper(trim($rowDescPropM)),
                                                                 "_id_encuesta"   => $idEncuesta,
                                                                 "count"          => 0,
                                                                 "flg_estado"     => ESTADO_ACTIVO);
                                            $data = $this->m_encuesta->insertDescProp($arrayInsert);
                                            $idAlterPropM = $data['id_propInsert'];
                                        }
                                        $dataIdPro['id_propuesta'] = $idAlterPropM;
                                        array_push($arrayPropuestaM, $dataIdPro);//llenando el array con los id de propuesta
                                    }
                                }
                                if($idPreguntas == CODIGO_COMENTARIO_CELDA) {//COMENTARIO DE PROPUESTA DE MEJORA
                                    $propComen = utf8_decode($data_value);
                                    $propComen = str_replace("\"", "'", $propComen);
                                    array_push($arrayComenPropM, $propComen);
                                }
                                $idAlternativas = $this->m_encuesta->getIdAlterbyIdPreg($idEncuesta, $idPreguntas, $decode_value);//alternativas por pregunta
                                array_push($arrayXEncuesta, array('id_pregunta' => $idPreguntas, 'respuesta' => $idAlternativas['_id_alternativa']));
                                //_log($rowNumber.'  ..  '.$columnName.'   pusheo!! '.count($arrayXEncuesta));
                            }
                        }
                    }/** FIN DEL FOREACH */
                    
                    array_push($arrayGeneralFinal, $arrayXEncuesta);
                    array_push($arrayFinalPropM, $arrayPropuestaM); 
                    array_push($arrayFinalComenPropM, $arrayComenPropM);
//                     _log(print_r($arrayGeneralFinal,true));
//                     _log(print_r($arrayFinalPropM,true));
//                     _log(print_r($arrayFinalComenPropM,true));
                    $SNGAula = array("nid_sede" => 0, "nid_grado" => 0, "nid_nivel" => 0, "nid_aula" => 0, "nid_area" => 0);
                    
                    $id_tipo_Enc = $this->m_encuesta->getIdTipoEncbyIdEnc($idEncuesta);
                    
                    //dfloresgonz 29.09.2016 --- CAMBIO PARA PONER EL LEER EL ID DEL AULA AL MIGRAR
                    if($id_tipo_Enc == TIPO_ENCUESTA_ALUMNOS || $id_tipo_Enc == TIPO_ENCUESTA_PADREFAM) {//SEDE - NIVEL - GRADO
                        $idAula = $objPHPExcel->getActiveSheet()->getCell('B2')->getValue();
                        if($idAula == null) {
                            throw new Exception('No ha puesto el ID de Aula');
                        }
                        $IdsAula = $this->m_utils->getDatosIDs_Aula($idAula);
                        if($IdsAula == null) {
                            throw new Exception('ID de aula incorrecto. No existe');
                        }
                        /*if($IdsAula['flg_acti'] != FLG_ACTIVO) {
                            throw new Exception('ID de aula incorrecto, se encuentra desactivada');
                        }*/
                        $SNGAula['nid_sede']  = $IdsAula['nid_sede'];
                        $SNGAula['nid_nivel'] = $IdsAula['nid_nivel'];
                        $SNGAula['nid_grado'] = $IdsAula['nid_grado'];
                        $SNGAula['nid_aula']  = $idAula;
                        ///////////----------------//////////////-------------/////////////////
                        $contEstuSinLlenrEncu = $this->m_crear_encuesta->getCountEstudiantesSinLlenarEncEntregFisico($idAula);
                        //LA CANTIDAD DE ESTU. QUE SE LES ENTREGO ENCUESTAS Y LA RETORNARON NO ES IGUAL A LA QUE ESTA EN EL EXCEL
                        if($contEstuSinLlenrEncu != count($arrayGeneralFinal)) {
                            throw new Exception('La cantidad de estudiantes que entregaron encuesta física con la que hay en el excel no coinciden.');
                        }
                    }
                    //-------------------- FIN ------------------------------------------------------
                    $contador = 0;
                    $arryInfo['id_encuesta'] = $idEncuesta;
                    $arryInfo['tipo_encuestado'] = _getSesion('tipoEncuestadoLibre');
                    $arryInfo['nid_sede']  = isset($SNGAula['nid_sede'])  ? $SNGAula['nid_sede']  : null;
                    $arryInfo['nid_nivel'] = isset($SNGAula['nid_nivel']) ? $SNGAula['nid_nivel'] : null;
                    $arryInfo['nid_grado'] = isset($SNGAula['nid_grado']) ? $SNGAula['nid_grado'] : null;
                    $arryInfo['nid_aula']  = isset($SNGAula['nid_aula'])  ? $SNGAula['nid_aula']  : null;
                    $arryInfo['nid_area']  = isset($SNGAula['nid_area'])  ? $SNGAula['nid_area']  : null;
                    foreach ($arrayGeneralFinal as $rowFinal) {
                        /**BEGIN JSONB*/
                        $respuestas       = null;
                        $propuestasMejora = null;
                        $sizeArry = count($rowFinal);
                        if(isset($arrayFinalPropM[$contador])) {
                            $propSize = count($arrayFinalPropM[$contador]);
                        }
                        foreach($rowFinal as $row) {
                            if($row['respuesta'] != null) {
                                $respuesta = $this->m_utils->getById('senc.alternativa','desc_alternativa', 'id_alternativa', $row['respuesta']);
                                $pregunta  = $this->m_utils->getById('senc.preguntas', 'desc_pregunta', 'id_pregunta', $row['id_pregunta']);
                                $respuestas .= '{
                                                "id_pregunta"    : '.$row['id_pregunta'].',
                                                "desc_pregunta"  : "'.$pregunta.'",
                                                "id_respuesta"   : '.$row['respuesta'].',
                                                "respuesta"      : "'.strtoupper($respuesta).'",
                                                "count"          : 1
                                            },';
                            }
                        }
                        if(isset($arrayFinalPropM[$contador])) {
                            foreach($arrayFinalPropM[$contador] as $row) {
                                $desc_propuesta = $this->m_utils->getById('senc.propuesta_mejora','desc_propuesta', 'id_propuesta', $row['id_propuesta']);
                                $propuestasMejora .= '{
                                                      "id_propuesta"   : '.$row['id_propuesta'].',
                                                      "desc_propuesta" : "'.$desc_propuesta.'",
                                                      "count"          : 1
                                                  },';
                            }
                        }
                        $respuestas       = rtrim(trim($respuestas), ",");
                        $propuestasMejora = rtrim(trim($propuestasMejora), ",");
                        $arryInfo['respuestas_jsonb'] = '{ "preguntas" : [ '.$respuestas.' ] }';
                        $arryInfo['propuestas_jsonb'] = '{ "propuestas" : [ '.$propuestasMejora.' ] }';
                        if(isset($arrayFinalPropM[$contador])) {//dfloresgonz agrego xq salio error offset [0]
                            if(isset($arrayFinalComenPropM[$contador][0])) {
                                $arryInfo['comentario'] = utf8_decode(trim($arrayFinalComenPropM[$contador][0]));
                            }
                        }
                        /**END*/
                        $arryInfo['id_encuesta'] = $idEncuesta;
                        $arryInfo['browser'] = 'ENCUESTA FÍSICA';
                        $idDeviceInfo = $this->saveClientDeviceInfo($arryInfo);_log('$idDeviceInfo:::'.$idDeviceInfo);
                        if($idDeviceInfo == null) {
                            throw new Exception($idDeviceInfo['msj']);
                        }
                        $data = $this->m_encuesta->insertRptaMongoDB($rowFinal, $idTipoEncuesta, $SNGAula, $idEncuesta, $idDeviceInfo, array());
                        $data = $this->m_encuesta->llenaEncSatistaccion($rowFinal, $idTipoEncuesta, $SNGAula, $idEncuesta, array(), $id_tipo_Enc);
                        $data = $this->m_encuesta->llenaEncInsatistaccion($rowFinal, $idTipoEncuesta, $SNGAula, $idEncuesta, array(), $id_tipo_Enc);
                        if(isset($arrayFinalPropM[$contador])) {
                            $data = $this->m_encuesta->insertPropuMejora($arrayFinalPropM[$contador], $idTipoEncuesta, $SNGAula,$idEncuesta,array());
                        }
                        
                        $comentario = (isset($arrayFinalComenPropM[$contador][0])) ? $arrayFinalComenPropM[$contador][0] : null;
                        if(isset($arrayFinalPropM[$contador])) {
                            $data = $this->m_encuesta->insertPropuestaMejoraComentario($arrayFinalPropM[$contador], $idTipoEncuesta,$SNGAula,$idEncuesta, $idDeviceInfo, array(), $comentario);
                        }
                        $contador++;
                    }
                    if($data['error'] == SUCCESS_MONGO) {
                        $rollBackCantEnc = $data['arrayRollBack'];
                        $data = $this->m_encuesta->aumentaCantEnc($idEncuesta, count($arrayGeneralFinal));
                        if($data['error'] == EXIT_SUCCESS) {
                            //--------------- ACTUALIZAR EL FLG_ENCUESTA DE LOS ESTUDIANTES --------------------
                            if($idAula != null) {
                                $cntActualizados = $this->m_crear_encuesta->actualizarEstudiantesFlgEncuestaFisica($idAula);
                                if($cntActualizados != $contEstuSinLlenrEncu) {
                                    throw new Exception('Hubo un error al actualizar la cantidad de estudiantes');
                                }
                            }
                            $data['newCantEncus'] = $this->m_utils->getById('senc.encuesta', 'cant_encuestados', 'id_encuesta', $idEncuesta);
                            //--------------------------------- FIN -------------------------------------------//
                            $data['msj'] = 'Se subió el excel';
                        } else {
                            $data = $this->m_encuesta->executeRollBack($rollBackCantEnc);
                        }
                    } else {
                        $data = $this->m_encuesta->executeRollBack($data['arrayRollBack']);
                    }
                    //borrar archivo excel
                    if (!unlink('./'.$inputFileName)) {
                        $data['error'] = EXIT_ERROR;
                        throw new Exception('(CX-001)');
                    }
                } else { 
                    $data['error'] = EXIT_ERROR;
                    throw new Exception('¡Debes seleccionar un archivo excel!');
                }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        unset($data['arrayRollBack']);
        
        $time_elapsed_secs = microtime(true) - $start;
        $unidMedida = 'segundo(s)';
        if($time_elapsed_secs >= 60) {
            $time_elapsed_secs = $time_elapsed_secs / 60;
            $unidMedida = 'minuto(s)';
        }
        _log('FISICO FINALIZO OK en '.(round($time_elapsed_secs, 2)).' '.$unidMedida);
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function saveClientDeviceInfo($arryInfo) {
        $this->load->library('user_agent');
    
        $arryInfo['fecha']     = date('d/m/Y H:i:s');
        $arryInfo['id_address'] = $this->input->ip_address();
        $arryInfo['_browser']   = ($this->agent->is_browser()) ? $this->agent->browser().' '.$this->agent->version() : 'Otro';
        $arryInfo['sist_oper_ci'] = $this->agent->platform();
        $arryInfo['es_mobile']  = $this->agent->is_mobile() ? 'SI' : 'NO';
        $arryInfo['redirect']  = $this->agent->is_referral() ? 'SI' : 'NO';
    
        return $this->m_encuesta->insertDeviceInfoEncuestado($arryInfo);
    }
}