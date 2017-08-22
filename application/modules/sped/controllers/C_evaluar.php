<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_evaluar extends CI_Controller {
    
    private $_idRubricaEval = null;
    private $_idEvaluacion  = null;
    private $_idUserSess    = null;
    private $_idRol         = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->load->model('m_evaluar');
        $this->load->model('m_rubrica');
        $this->load->model('m_utils');
        $this->load->library('table');
        $this->load->helper('html');
        
        _validate_uso_controladorModulos(ID_SISTEMA_SPED, ID_PERMISO_AGENDA, SPED_ROL_SESS);
        $this->_idRubricaEval = _getSesion('id_rubrica_eval');
        $this->_idEvaluacion  = _getSesion('id_evaluacion');
        $this->_idUserSess    = _getSesion('nid_persona');
        $this->_idRol         = _getSesion(SPED_ROL_SESS);
        if(!$this->m_evaluar->checkIfPendiente()) {
            Redirect('sped/c_agenda', 'refresh');
        }
    }

    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SPED, SPED_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader'] = 'Evaluaci&oacute;n';
        $data['ruta_logo'] = MENU_LOGO_SPED;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SPED;
        $data['nombre_logo'] = NAME_MODULO_SPED;
        $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_SPED, $this->_idUserSess);
        $data['apps']   = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        
        $data['tbRubrica'] = $this->buildRubricaEvaluar();
        
        //MENU Y CABECERA
        $menu         = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        
        $data['fabFinalizar'] = null;
        $actual = $this->m_evaluar->checkFichaFinalizada($this->_idRubricaEval, $this->_idEvaluacion)['actual'];
        $data['fabFinalizar'] = $this->getHTMLFab(($actual == 0) ? true : false);
        
        //Nota Final para mostrar En el FAB
        $data['notaFinal'] = $this->m_evaluar->getPuntajeTotalByEvaluacionFicha($this->_idEvaluacion);
        $data['colorGeneral'] = ($data['notaFinal'] <= 10.49) ? 'mdl-color-text--red-500' : (($data['notaFinal'] >= 10.50 && $data['notaFinal'] <= 16.49) ? 'mdl-color-text--amber-500' : 'mdl-color-text--green-500' );
        
        $this->load->view('v_evaluar', $data);
    }

    function cambioRol() {
        $idRolEnc = $this->input->post('id_rol');
        $idRol = _simple_decrypt($idRolEnc);
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'schoowl');
        $dataUser = array("id_rol"     => $idRol,
                          "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $idRol = $this->session->userdata('nombre_rol');
        $result['url'] = base_url()."c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }
    
    function buildRubricaEvaluar() {
        $rubrica = null;
        $criterios = $this->m_rubrica->getCriteriosByRubrica($this->_idRubricaEval);
        foreach($criterios as $row) {
            $indicadores = $this->m_evaluar->getIndicadoresByCriterioRubrica($this->_idEvaluacion, $this->_idRubricaEval, $row->nid_criterio);
            $rubrica .= $this->htmlCriterio($row->desc_criterio, $indicadores, _encodeCI($row->nid_criterio), $row->nid_criterio);
        }
        return $rubrica;
    }
    
    function htmlCriterioAux($desc, $indicadores, $idCriterioCrypt, $idCriterio) {
        $nroIndi = 0;
        $indiHTML = null;
        $valorSuma = 0;
        $indisAplic = 0;//_log(':::: '.print_r($indicadores, true));
        foreach ($indicadores as $indi) {
            $nroIndi++;
            $idIndiCrypt = _encodeCI($indi->id_indicador);
            $divCrit = 'divCrit'.$idCriterio;
            if ($indi->valor == VALOR_NO_APLICA) {
                $labelColorIndi = 'label-default';
                $valorMostrar   = DESC_NO_APLICA;
                $valorReal     = VALOR_NO_APLICA;
                $valorSuma = $valorSuma + 0;
            } else {
                $labelColorIndi = ($indi->nota_vigesimal <= 10.49) ? 'label-danger' : (($indi->nota_vigesimal >= 10.50 && $indi->nota_vigesimal <= 16.49) ? 'label-warning' : 'label-success' );
                $valorMostrar   = ($indi->valor != null) ? (round($indi->valor, 2)) : '--';
                $valorReal      = $indi->valor;
                $valorSuma = $valorSuma + $indi->valor;
                $indisAplic++;
            }
            $res = '<div class="label '.$labelColorIndi.'" style="margin: 10px 10px 10px 25px "
                         id="divIndicador'.$nroIndi.$indi->id_criterio.'" data-valor_radio_real="'.$valorReal.'">'.$valorMostrar.'</div>';
            $indiHTML .= '<tr style="border: 7.5px solid transparent; cursor: pointer " onclick="verValores(\''.$idCriterioCrypt.'\',$(this), \''.$divCrit.'\', \''.$idIndiCrypt.'\', \'divIndicador'.$nroIndi.$indi->id_criterio.'\');"
                              data-indipk="'._encodeCI($indi->id_indicador).'" data-critpk="'._encodeCI($indi->id_criterio).'">
                             <td>'.$res.'</td><td><div id="divdivIndicador'.$nroIndi.$indi->id_criterio.'">'.$indi->desc_indicador.'</div></td></tr>';
        }
        //Esto va al inicio del metodo pero se pone despues porque se necesita el foreach para hacer el calculo
        $promCrit = round($valorSuma, 1);
        
        $maxValor = $this->m_rubrica->getMaxValorByRubricaFactor_Leyenda($this->_idRubricaEval, $idCriterio);
        $vigesimalFactor = $promCrit * 20 / ($maxValor * $indisAplic);
        $labelColor = ($vigesimalFactor <= 10.49) ? 'label-danger' : (($vigesimalFactor >= 10.50 && $vigesimalFactor <= 16.49) ? 'label-warning' : 'label-success' );
        $tablaValorDesc = '<table><tr><td><div style="margin-right: 10px" id="divCrit'.$idCriterio.'" class="label '.$labelColor.'">'.($promCrit).'</td><td id="tddivCrit'.$idCriterio.'"><strong>'.$desc.'</strong></td></tr></table>';
        
        $html = $tablaValorDesc;
        $html .= '<table>'.$indiHTML.'</table>';
        return $html;
    }
    
    function htmlCriterio($desc, $indicadores, $idCriterioCrypt, $idCriterio) {
        $html = $this->htmlCriterioAux($desc, $indicadores, $idCriterioCrypt, $idCriterio);
        return '<div id="div_'.$idCriterio.'" class="divFactor">'.$html.'</div>';
    }
    
    function getValores() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $data['tablaValores'] = null;
        try {
            $idCriterio  = _decodeCI(_post('idCriterio'));
            $idIndicador = _decodeCI(_post('idIndicador'));
            if($idCriterio == null || $idIndicador == null) {
                throw new Exception(ANP);
            }
            $valores = $this->m_evaluar->getPosiblesValoresCriterioEvaluar($this->_idEvaluacion, $this->_idRubricaEval, $idCriterio, $idIndicador);
            if(count($valores) > 0) {
                $data['tablaValores'] = $this->buildTablaValores($valores);
            } else {
                $data['replicarFlg'] = 1;
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablaValores($valores) {
        $tmpl = array('table_open'  => '<table id="tbVals" data-toggle="table" class="table borderless">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'   , 'class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center');
        $head_1 = array('data' => 'Sel.', 'class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center');
        $head_2 = array('data' => 'Descripci&oacute;n', 'class' => 'col-xs-9 col-sm-9 col-md-9 col-lg-9 text-left');
        $head_3 = array('data' => 'Valor', 'class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $val = 0;
        foreach($valores as $row) {
            $val++;
            $radio = '  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-b-15 '.$row->color_radio_button.'" for="radio-'.$val.'">
                            <input type="radio" id="radio-'.$val.'" class="mdl-radio__button" name="radioVals" value="'.$row->valor.'">
                            <span class="mdl-radio__label"></span>
                        </label>';
            $row_col0  = array('data' => $val);
            $row_col1  = array('data' => $radio);
            $row_col2  = array('data' => $row->desc_leyenda);
            $row_col3  = array('data' => round($row->valor, 2));
            $this->table->add_row($row_col0, $row_col1, $row_col2, $row_col3);
        }
        $radio = '  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-b-15" for="radio-100">
                        <input type="radio" id="radio-100" class="mdl-radio__button" name="radioVals" value="-1">
                        <span class="mdl-radio__label"></span>
                    </label>';
        $row_col0  = array('data' => ($val + 1) );
        $row_col1  = array('data' => $radio);
        $row_col2  = array('data' => 'No aplica');
        $row_col3  = array('data' => '--');
        $this->table->add_row($row_col0, $row_col1, $row_col2, $row_col3);
        return $this->table->generate();
    }
    
    function guardarValor() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idCriterio  = _decodeCI(_post('idCriterio'));
            $idIndicador = _decodeCI(_post('idIndi'));
            if($idCriterio == null || $idIndicador == null) {
                throw new Exception(ANP);
            }
            $valor = _post('valor');
            $valoresPosibles = array();
            //$valores = $this->m_evaluar->getPosiblesValoresCriterio(_getSesion('id_rubrica_eval'), $idCriterio, $idIndicador);
            $valores = $this->m_evaluar->getPosiblesValoresCriterioEvaluar($this->_idEvaluacion, $this->_idRubricaEval, $idCriterio, $idIndicador);
            foreach ($valores as $val) {
                array_push($valoresPosibles, $val->valor);
            }
            array_push($valoresPosibles, VALOR_NO_APLICA);
            if(!in_array($valor, $valoresPosibles) ) {
                throw new Exception('El valor no es el correcto');
            }
            //Validar al menos 1 subfactor con un valor distinto a NO APLICA
            $cantNoAplica = $this->m_evaluar->getCantSubFactoresNoAplica($this->_idEvaluacion, $idCriterio);
            $cantSubFact  = $this->m_rubrica->getCantidadSubFactoresByFactor($this->_idRubricaEval, $idCriterio);
            if($valor == VALOR_NO_APLICA) {
                $cantNoAplica++;
            }
            if($cantNoAplica == $cantSubFact ) {
                throw new Exception('No puede asignar NO APLICA a todos los subfactores.');
            }
            //
            if($valor != VALOR_NO_APLICA) {
                $maxVal = max($valoresPosibles);
                $vigecimal = ($valor * 20) / $maxVal;
                $labelColor = ($vigecimal <= 10.49) ? 'label-danger' : (($vigecimal >= 10.50 && $vigecimal <= 16.49) ? 'label-warning' : 'label-success' );
            } else {
                $labelColor = 'label-default';
            }
            $data = $this->m_evaluar->registrarValorIndicador($this->_idEvaluacion, $this->_idRubricaEval, $idCriterio, $idIndicador, $valor);
            if($data['error'] == EXIT_SUCCESS) {
                $data['cssIndiPromedio'] = $labelColor;
                $data['mostrarFab'] = $this->getHTMLFab(($data['terminoFicha'] == 0) ? true : false);
                
                $indicadores = $this->m_evaluar->getIndicadoresByCriterioRubrica($this->_idEvaluacion, $this->_idRubricaEval, $idCriterio);
                $desc = $this->m_utils->getById('sped.criterio', 'desc_criterio', 'nid_criterio', $idCriterio, null);
                $data['critTabla'] = $this->htmlCriterioAux($desc, $indicadores, _encodeCI($idCriterio), $idCriterio);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function reactivarSubFactor() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idFactor    = _decodeCI(_post('idFactor'));
            $idSubFactor = _decodeCI(_post('idSubFactor'));
            if($idFactor == null || $idSubFactor == null) {
                throw new Exception(ANP);
            }
            $data = $this->m_evaluar->reactivarSubFactor($this->_idEvaluacion, $this->_idRubricaEval, $idFactor, $idSubFactor);
            if($data['error'] == EXIT_SUCCESS) {
                $valores = $this->m_evaluar->getPosiblesValoresCriterioEvaluar($this->_idEvaluacion, $this->_idRubricaEval, $idFactor, $idSubFactor);
                $data['tablaValores'] = $this->buildTablaValores($valores);
                
                $indicadores = $this->m_evaluar->getIndicadoresByCriterioRubrica($this->_idEvaluacion, $this->_idRubricaEval, $idFactor);
                $desc = $this->m_utils->getById('sped.criterio', 'desc_criterio', 'nid_criterio', $idFactor, null);
                $data['critTabla'] = $this->htmlCriterioAux($desc, $indicadores, _encodeCI($idFactor), $idFactor);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarEvaluacionFin() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $rubri = $this->m_evaluar->checkFichaFinalizada($this->_idRubricaEval, $this->_idEvaluacion);
            if($rubri['actual'] != 0) {
                throw new Exception('La evaluaci&oacute;n tiene indicadores pendientes');
            }
            $nota_vigesimal = $this->m_evaluar->getPuntajeTotalByEvaluacionFicha($this->_idEvaluacion);
            $ptje_final = $nota_vigesimal * 100 / 20;
            $arrayUpdate  = array('estado_evaluacion' => EJECUTADO,
                                  'ptje_final'        => $ptje_final,
                                  'nota_vigesimal'    => $nota_vigesimal,
                                  'fecha_evaluacion'  => date('Y-m-d h:i:s'));
            $data = $this->m_evaluar->ejecutarEvaluacion($this->_idEvaluacion, $arrayUpdate);
            if($data['error'] == EXIT_ERROR) {
                throw new Exception('No se pudo terminar la evaluaci&oacute;n');
            }
            $data['msj'] = 'Se termin&oacute; la evaluaci&oacute;n';
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function subirArchivos() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if(!isset($_FILES["file"])) {
                throw new Exception('Hubo un error al subir su evidencia.');
            }
            $filesize = $_FILES["file"]["size"];
            if($filesize > __getPostMaxFileSize()) {
                throw new Exception('Su archivo es muy pesado. No puede exceder los '.ini_get('post_max_size').'Bs');
            }
            $file     = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            if (!is_dir("uploads/modulos/sped/evidencias/")) {
                mkdir("uploads/modulos/sped/evidencias/", 0777);
            }
            $result = 0;
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = $this->_idEvaluacion.'_'.__generateRandomString(7).'_'.date("dmhis");
            $rutaCompletaSinExt = "uploads/modulos/sped/evidencias/".$nombreArchivo;
            $ruta = "uploads/modulos/sped/evidencias/".$nombreArchivo.'.'.$ext;
            //////////////////////////////////////////////////////////////
            $rutaMostrar = null;
            if (utf8_decode($file) && move_uploaded_file($_FILES["file"]["tmp_name"], $ruta)) {
                if(strpos($filetype, 'video') === false) {
                     
                } else {
                    $rutaCompleta = RUTA_SMILEDU_FISICA.$ruta;
                    $comandoTiempo = RUTA_BASE_FFMPEG."/ffprobe -v error -show_entries format=duration -of default=nw=1:nk=1 $rutaCompleta";
                    $time = exec($comandoTiempo);
                    $durationMiddle = $time/2;
                    $minutes = $durationMiddle/60;
                    $horas = '00';
                    if($minutes >= 60){
                        $horas = floor(($minutes / 60));
                        $minutes = $minutes - ($horas * 60);
                    }
                    $horas = strlen($horas) == 1 ? '0'.$horas : $horas;
            
                    $realMinutes = floor($minutes);
                    $realMinutes = strlen($realMinutes) == 1 ? '0'.$realMinutes : $realMinutes;
            
                    $realSeconds = round(($minutes-$realMinutes)*60);
                    $realSeconds = strlen($realSeconds) == 1 ? '0'.$realSeconds : $realSeconds;
            
                    $instanciaTiempo = $horas.':'.$realMinutes.':'.$realSeconds;
            
                    $rutaMostrar = RUTA_VIDEO_THUMB.'thumb_video_'.$nombreArchivo.'.png';
                    $rutaThumbnail = RUTA_SMILEDU_FISICA.$rutaMostrar;
                    $comandoThumbnail = RUTA_BASE_FFMPEG."/ffmpeg -i $rutaCompleta -ss $instanciaTiempo -vframes 1 $rutaThumbnail";
                    //_log('$comandoThumbnail: '.$comandoThumbnail);
                    exec($comandoThumbnail);
                }
                $dataimg = array (
                    "nombre_archivo"  => utf8_decode($file),
                    "tipo_archivo"    => $filetype,
                    "id_evaluacion"   => $this->_idEvaluacion,
                    "ruta_archivo"    => $ruta,
                    "thumbnail_video" => $rutaMostrar);
                $data = $this->m_evaluar->insertEvidencia($dataimg);
            }
        } catch (Exception $e) {
            if(file_exists($ruta)) {
                $ruta = './'.$ruta;
                unlink($ruta);
            }
            $data['msj'] = $e->getMessage();
            header("HTTP/1.0 666 ".$data['msj'], TRUE, NULL);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getEvidenciasEvaluacion() {
        $evidencias = $this->m_evaluar->getEvidenciasEvaluacion($this->_idEvaluacion);
        $tab = '<div id="evidDivs">';
        foreach($evidencias as $row) {
            $idEvidencia = $this->encrypt->encode($row->id_evidencia);
            if(strpos($row->tipo_archivo, 'image') === false) {
                if(strpos($row->tipo_archivo, 'video') === false) {
                    //nothing
                } else {
                    $tab .=' <div class="col-sm-6 col-md-4">
                                <a href="'.RUTA_SMILEDU.$row->ruta_archivo.'" title="Borrar" type="'.$row->tipo_archivo.'"
                                    data-poster="'.RUTA_SMILEDU.$row->thumbnail_video.'" data-pk="'.$idEvidencia.'"
                                    data-sources=\'[{"href": "'.RUTA_SMILEDU.$row->ruta_archivo.'", "type": "'.$row->tipo_archivo.'"}]\'>
                                        <img id="'.$idEvidencia.'" src="'.RUTA_SMILEDU.$row->thumbnail_video.'" class="img-responsive">
        					     </a>
                              </div>';
                }
            } else {
                $tab .=' <div class="col-xs-6 col-sm-4 col-md-3">   
                            <a href="'.RUTA_SMILEDU.$row->ruta_archivo.'" title="Borrar" data-pk="'.$idEvidencia.'">
        					     <img id="'.$idEvidencia.'" src="'.RUTA_SMILEDU.$row->ruta_archivo.'" class="img-responsive">
        					 </a> </div>';
            }
        }
        $tab .= '</div>';
        $data['divFotos'] = $tab;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function borrarEvidencia() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEvidencia = _decodeCI(_post('idEvidencia'));
            if($idEvidencia == null) {
                throw new Exception(ANP);
            }
            $archivo = $this->m_evaluar->validarArchivo($idEvidencia, $this->_idEvaluacion);
            if($archivo == null) {
                throw new Exception(ANP);
            }
            $data = $this->m_evaluar->borrarEvidencia($idEvidencia);
            if($data['error'] == EXIT_SUCCESS) {
                if(file_exists('./'.$archivo['ruta_archivo'])) {
                    if (!unlink('./'.$archivo['ruta_archivo'])) {
                        throw new Exception('Hubo un error al borrar la evidencia');
                    }
                }
                if($archivo['thumbnail_video'] != null) {
                    if(file_exists('./'.$archivo['thumbnail_video'])) {
                        if (!unlink('./'.$archivo['thumbnail_video'])) {
                            throw new Exception('Hubo un error al borrar el thumbnail');
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function verDocente() {
        $datos = $this->m_evaluar->getDatosDocenteEvaluado($this->_idEvaluacion);
        $datos['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $datos));
    }
    
    function getTema() {
        $datos['tema'] = $this->m_utils->getById('sped.evaluacion', 'tema', 'id_evaluacion', $this->_idEvaluacion);
        $datos['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $datos));
    }
    
    function grabarTema() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $tema = utf8_decode(trim(_post('tema')));
            $data = $this->m_evaluar->grabarTema($tema, $this->_idEvaluacion);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /*function actualizarEvaluacion() {
        $idsSubida = $this->input->post('idsEvids');log_message('error', '$idsSubida: '.$idsSubida);
        $arryEvids = explode("_", $idsSubida);
        $arryEvids = implode(",", $arryEvids);
        $this->m_evaluar->actualizarEvaluacion($this->session->userdata('id_evaluacion'), '{'.$arryEvids.'}');
    }*/
    
    function getHTMLFab($flgFinalizar) {
        $finalizar = null;
        if($flgFinalizar) {
            /*return '<li>
                        <a href="javascript:void(0);" data-mfb-label="Finalizar Evaluaci�n" class="mfb-component__button--child">
                            <i class="mfb-component__child-icon md md-edit" onclick="abrirCerrarModal(\'modalTerminarFicha\');" style="font-size: 20px;padding-top: 1px;color:white;margin-top: -6px;"></i>
                        </a>
                    </li>';*/
            return 'onclick="abrirCerrarModal(\'modalTerminarFicha\');"';
        }
        return null;
    }
    
    function setIdSistemaInSession(){
        $idSistema = $this->encrypt->decode($this->input->post('id_sis'));
        $idRol     = $this->encrypt->decode($this->input->post('rol'));
        if($idSistema == null || $idRol == null){
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function logOut() {
        $this->session->sess_destroy();
        unset($_COOKIE['schoowl']);
        $cookie_name2 = "schoowl";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        redirect(RUTA_SMILEDU, 'refresh');
    }
}