<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_cons_eval extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->load->model('m_cons_eval');
        $this->load->model('m_evaluar');
        $this->load->model('m_rubrica');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('m_utils');
        
        _validate_uso_controladorModulos(ID_SISTEMA_SPED, ID_PERMISO_CONSULTAR_EVALUACIONES, SPED_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SPED_ROL_SESS);
    }
    
    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SPED, SPED_FOLDER);
        $data['tbEvas'] = $this->buildTabla_Evaluaciones();
        
        ////Modal Popup Iconos///
        $data['titleHeader'] = 'Evaluaciones';
	    $data['ruta_logo'] = MENU_LOGO_SPED;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SPED;
	    $data['nombre_logo'] = NAME_MODULO_SPED;
        $rolSistemas   = $this->m_utils->getSistemasByRol(ID_SISTEMA_SPED, $this->_idUserSess);
        $data['apps']    = __buildModulosByRol($rolSistemas, $this->_idUserSess);

        //MENU Y CABECERA
        $menu         = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        $this->load->view('v_cons_eval',$data);
    }
    
    function cambioRol() {
        $idRolEnc = $this->input->post('id_rol');
        $idRol = _simple_decrypt($idRolEnc);
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'schoowl');
        $dataUser = array("id_rol"     => $idRol,
                          "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $idRol     = $this->session->userdata('nombre_rol');
        $result['url'] = base_url()."c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }
    
    function getRubrica() {
        $idEvaluacion = _decodeCI(_post('idEval'));
        if($idEvaluacion == null) {
            throw new Exception(ANP);
        }
        $data['htmlBody'] = $this->buildRubricaLlena($idEvaluacion);
        $this->load->library('m_pdf');
        $pdfObj = $this->m_pdf->load('','A4-L', 0, '', 15, 15, 16, 16, 9, 9, 'L');
        $desc = utf8_encode('R&uacute;brica de Evaluaci&oacute;n');
        $pdfObj->SetFooter($desc.'|{PAGENO}|'.date('d/m/Y h:i:s a'));
        $data['cabecera'] = '<h2 style="text-align: center;">R&uacute;brica de evaluaci&oacute;n</h2>';
        $datos = $this->m_evaluar->getDatosAlFinalizar($idEvaluacion);
        $data['cabecera'] .= '<table border="0">';
        $data['cabecera'] .= '<tr><td><strong>1.</strong></td><td><strong>Docente</strong></td><td>'.$datos['docente'].'</td></tr>';
        $data['cabecera'] .= '<tr><td><strong>2.</strong></td><td><strong>Evaluador</strong></td><td>'.$datos['evaluador'].'</td></tr>';
        $data['cabecera'] .= '<tr><td><strong>3.</strong></td><td><strong>Curso</strong></td><td>'.$datos['curso'].'</td></tr>';
        $data['cabecera'] .= '<tr><td><strong>4.</strong></td><td><strong>Aula</strong></td><td>'.$datos['aula'].'</td></tr>';
        $data['cabecera'] .= '<tr><td><strong>5.</strong></td><td><strong>Fecha</strong></td><td>'.$datos['fec_visita'].'</td></tr></table><br>';
        $data['pdfObj'] = $pdfObj;
        $this->load->view('v_pdf_rubrica', $data);
    }
    
    function buildTabla_Evaluaciones() {
        $listaEvaluaciones = $this->m_cons_eval->getEvaluacionesEjecutadas($this->_idRol, $this->_idUserSess);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbEvaluaciones" data-show-columns="true">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $pers = null;
        if($this->_idRol == ID_ROL_SUBDIRECTOR || $this->_idRol == ID_ROL_ADMINISTRADOR) {
            $pers = 'Docente';
        } else {
            $pers = 'Evaluador';
        }
        $head_0 = array('data' => 'ID.');
        $head_1 = array('data' => 'Fecha');
        $head_2 = array('data' => 'Evaluador', 'data-visible' => ($this->_idRol == ID_ROL_SUBDIRECTOR ? 'false' : 'true') );
        $head_3 = array('data' => 'Docente'  , 'data-visible' => ($this->_idRol == ID_ROL_DOCENTE     ? 'false' : 'true') );
        $head_4 = array('data' => 'Tipo');
        $head_5 = array('data' => 'Nota');
        $head_6 = array('data' => 'Aula');
        $head_7 = array('data' => 'Curso');
        $head_8 = array('data' => 'Acci&oacute;n','class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7, $head_8);
        foreach($listaEvaluaciones as $row) {
            $color = ($row->nota_vigesimal <= 10.4) ? 'danger' : ( ($row->nota_vigesimal > 10.4 && $row->nota_vigesimal <= 14.4) ? 'warning' : 'success' );
            $accion = null;
            $idEval = _encodeCI($row->id_evaluacion);
            $row_0  = array('data' => $row->id_evaluacion, 'class' => 'classPK', 'data-pk' => $idEval);
            $row_1  = array('data' => _fecha_tabla($row->fecha_evaluacion, 'd/m/Y'), 'class');
            $row_2  = array('data' => $row->evaluador, 'class');
            $row_3  = array('data' => $row->docente, 'class');
            $row_4  = array('data' => $row->tipo_visita, 'class');
            $row_5  = array('data' =>'<span class="label label-'.$color.'">'.$row->nota_vigesimal.'</span>');
            $row_6  = array('data' => $row->aula, 'class');
            $row_7  = array('data' => $row->curso, 'class');
            $nombreMensajear = ($this->_idRol == ID_ROL_SUBDIRECTOR) ? $row->docente : ( ($this->_idRol == ID_ROL_DOCENTE) ? $row->evaluador : 'Mensajes' );
            $esElEvaluador = ($row->id_evaluador == $this->_idUserSess);
            $opcionSubir = ($this->_idRol == ID_ROL_SUBDIRECTOR && $esElEvaluador) ? '<li class="mdl-menu__item" onclick="abrirModalSubirEvidencias($(this));" ><i class="mdi mdi-file_upload"></i> Subir Evidencias</li>' : null;
            $menuOpciones = '<ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect text-left" for="'.$idEval.'">
                                    <li class="mdl-menu__item '.$row->css_hay_evid.'" onclick="verEvidencias($(this));" ><i class="mdi mdi-burst_mode"></i> Ver Evidencias</li>
                                    '.$opcionSubir.'
                                    <li class="mdl-menu__item" onclick="verRubrica($(this));" ><i class="mdi mdi-assignment"></i> Ver Resultados</li>
                                    <li class="mdl-menu__item '.$row->css_hay_msjs.'" onclick="verMensajes($(this),\''.$nombreMensajear.'\');" ><i class="mdi mdi-forum"></i> Mensajes</li>
                                </ul>';
            $btnMsjs = null;
            if($row->notificar > 0) {
                $nro = $row->notificar;
                if($nro > 9) {
                    $nro = '9+';
                }
                $btnMsjs = '<span id="notif_'.$row->id_evaluacion.'" class="badge"> '.$nro.'</span>';
                //$btnMsjs .= '<span id="notif_'.$row->id_evaluacion.'" class="label label-danger" style="margin-left : -10px;">'.$nro.'</span></div></span>';
            }
            $opciones = '   <button id="'.$idEval.'" class="mdl-button mdl-js-button mdl-button--icon '.$row->css_tres_punt.'" >
                                <i class="mdi mdi-more_vert"></i>
                            </button>
                            '.$btnMsjs.'
                            '.$menuOpciones;
            
            $row_8 = array('data' => $opciones);
            
            $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5, $row_6, $row_7, $row_8);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function buildRubricaLlena($idEvaluacion) {
        $criterios = $this->m_cons_eval->getCriteriosEvaluacion($idEvaluacion);
        $tmpl      = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;">',
                           'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $left = 'text-align:left';$right = 'text-align:right';$center = 'text-align:center';
        $notaFinal = 0;
        $cantCrits = 0;
        foreach($criterios as $row) {
            $cantCrits++;
            $vals = $this->m_cons_eval->getPosiblesValoresCriterio($idEvaluacion, $row->id_rubrica, $row->id_criterio);
            $valores = "Valores: ".$vals;
            $notaFinal = $notaFinal + $row->valor;
            $maxValorFactor = round( ($this->m_rubrica->getCantidadSubFactoresByFactor($row->id_rubrica, $row->id_criterio) * $row->max_valor), 1);
            $ptje = round( (($row->valor * 20) / $maxValorFactor) );
            $bgColor = ($ptje <= 10.49) ? '#ff0000' : (($ptje >= 10.50 && $ptje <= 16.49) ? '#fdff00' : '#0e9a01' );
            
            $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2 color="white">'.$cantCrits.'</FONT>'  , 'style' => 'border: 1px;font-weight:bold;', 'bgcolor' => '#9c9c9c');
            $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2 color="white">&nbsp;'.$row->desc_criterio.'</FONT>' , 'style' => 'border: 1px;font-weight:bold;', 'bgcolor' => '#9c9c9c');
            $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2 color="black">'.$row->valor.' / '.$maxValorFactor.' - ('.$ptje.')</FONT>', 'style' => 'border: 1px;font-weight:bold;'.$center, 'bgcolor' => $bgColor);
            $row_col3  = array('data' => '<FONT FACE="Arial" SIZE=2 color="white">'.$valores.'</FONT>', 'style' => 'border: 1px;font-weight:bold;', 'bgcolor' => '#9c9c9c');
            $this->table->add_row($row_col0, $row_col1, $row_col2, $row_col3);
            $indicadores = $this->m_cons_eval->getIndicadoresByCriterioEval($idEvaluacion, $row->id_criterio);
            $idxSubFactor = 0;
            foreach ($indicadores as $indi) {
                $idxSubFactor++;
                $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$idxSubFactor.'</FONT>', 'style' => 'border: 1px');
                $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2>&nbsp;'.$indi->desc_indicador.'</FONT>');
                $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2>'.($indi->flg_no_aplica == null ? ($indi->valor_indi) : 'N.A.').'</FONT>', 'style' => $center);
                $row_col3  = array('data' => '<FONT FACE="Arial" SIZE=2>&nbsp;'.$indi->desc_leyenda.'</FONT>');
                $this->table->add_row($row_col0, $row_col1, $row_col2, $row_col3);
            }
        }
        $promFinal = $notaFinal;
        $ptjeFinal = ($promFinal * 100) / 20;
        $bgColor = ($promFinal <= 10.49) ? '#ff0000' : (($promFinal >= 10.50 && $promFinal <= 16.49) ? '#fdff00' : '#0e9a01' );
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3 color="white">#</FONT>'                ,'style' => $left, 'bgcolor' => '#000000');
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3 color="white">Resultado Global</FONT>' ,'style' => $left, 'bgcolor' => '#000000');
        $head_2 = array('data' => '<FONT FACE="Arial" SIZE=3>'.round($promFinal, 2).' - '.round($ptjeFinal, 2).' %'.'</FONT>'  ,'style' => $center, 'bgcolor' => $bgColor);
        $head_3 = array('data' => '<FONT FACE="Arial" SIZE=3 color="white">Descripci&oacute;n</FONT>'      ,'style' => $center, 'bgcolor' => '#000000');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
        
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getEvidenciasEvaluacion() {
        $idEval = _decodeCI(_post('idEval'));
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if($idEval == null) {
                throw new Exception(ANP);
            }
            $evidencias = $this->m_evaluar->getEvidenciasEvaluacion($idEval);
            $tab = '<div id="evidDivs">';
            foreach($evidencias as $row) {
                $idEvidencia = _decodeCI($row->id_evidencia);
                if(strpos($row->tipo_archivo, 'image') === false) {
                    if(strpos($row->tipo_archivo, 'video') === false) {
                    } else {
                        $tab .=' <div class="col-sm-4" style="padding-bottom:20px">
                                     <a href="'.RUTA_SMILEDU.$row->ruta_archivo.'" title="Borrar" type="'.$row->tipo_archivo.'"
                                        data-poster="'.RUTA_SMILEDU.$row->thumbnail_video.'" data-pk="'.$idEvidencia.'"
                                        data-sources=\'[{"href": "'.RUTA_SMILEDU.$row->ruta_archivo.'", "type": "'.$row->tipo_archivo.'"}]\'>
                                            <img id="'.$idEvidencia.'" src="'.RUTA_SMILEDU.$row->thumbnail_video.'" height="150" width="150">
    					             </a>
                                 </div>';
                    }
                } else {
                    $tab .='<div class="col-sm-4" style="padding-bottom:20px">
                                <a href="'.RUTA_SMILEDU.$row->ruta_archivo.'" title="Borrar" data-pk="'.$idEvidencia.'">
    					            <img id="'.$idEvidencia.'" src="'.RUTA_SMILEDU.$row->ruta_archivo.'"  width="100%"">
    					        </a>
    					    </div>';
                }
            }
            $tab .= '</div>';
            $data['error'] = EXIT_SUCCESS;
            $data['divFotos'] = $tab;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function insertMensaje() {
        $idEval = _decodeCI(_post('idEval'));
        $msj    = _post('msj');
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if($this->_idRol != ID_ROL_SUBDIRECTOR && $this->_idRol != ID_ROL_DOCENTE) {
                throw new Exception('No tienes permitido enviar mensajes >:( ');
            }
            if($idEval == null || $msj == null || trim($msj) == "") {
                throw new Exception(ANP);
            }
            $msj = trim($msj);
            $msjsHTML = null;
            $data = $this->m_cons_eval->insertarMensaje($idEval, $msj);
            if($data['error'] == EXIT_SUCCESS) {
                $msjsHTML .=   '<li>
                    				<div class="chat">
                    					<div class="chat-avatar">
    										<img data-toggle="tooltip" data-placement="bottom" data-original-title="'._getSesion('nombre_abvr').'" 
    										     class="img-circle"    src="'._getSesion('foto_usuario').'" alt="">
    									</div>
                    					<div class="chat-body">
                    						'.$msj.'
                    						<small>'.date('d/m/Y h:i:s A').'</small>
                    					</div>
                    				</div>
                    			</li>';
            }
            $data['mensajes'] = $msjsHTML;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function insertMensajeAdj() {
        $idEval = _decodeCI(_post('idEval'));
        $msj    = _post('msj');
        $img64  = _post('img64');
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if($this->_idRol != ID_ROL_SUBDIRECTOR && $this->_idRol != ID_ROL_DOCENTE) {
                throw new Exception('No tienes permitido enviar mensajes >:( ');
            }
            if(trim($img64) == '') {
                throw new Exception(ANP);
            }
            if(!__checkBase64_image($img64)) {
                throw new Exception('Solo puede adjuntar archivos de tipo imagen.');
            }
            $msj = trim($msj);
            $msj = '<a href="'.$img64.'">
                        <img src="'.$img64.'" width="90" data-galeria="S">
                    </a><br>'.$msj;
            $data = $this->m_cons_eval->insertarMensaje($idEval, $msj);
            $msjsHTML = null;
            if($data['error'] == EXIT_SUCCESS) {
                $msjsHTML = '<li>
                				<div class="chat">
                					<div class="chat-avatar">
										<img data-toggle="tooltip" data-placement="bottom" data-original-title="'._getSesion('nombre_abvr').'" 
										     class="img-circle" src="'._getSesion('foto_usuario').'" alt="">
									</div>
                					<div class="chat-body">
                						'.$msj.'
                						<small>'.date('d/m/Y h:i:s A').'</small>
                					</div>
                				</div>
                			</li>';
            }
            $data['mensajes'] = $msjsHTML;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getMensajesEva() {
        $idEval = _decodeCI(_post('idEval'));
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if($idEval == null) {
                throw new Exception(ANP);
            }
            if($this->_idRol == ID_ROL_ADMINISTRADOR) {
                $mensajes = $this->m_cons_eval->getMensajesEvaluacionAlguienMas($idEval);
            } else {
                $mensajes = $this->m_cons_eval->getMensajesEvaluacion($idEval, $this->_idUserSess);
            }
            $html = null;
            foreach ($mensajes as $msj) {
                $left  = ($msj->position == 'left') ? null : 'class="chat-left"';
                $color = ($msj->position == 'left') ? 'white' : '#4E58A5';
                $html .=   '<li '.$left.'>
                				<div class="chat">
                					<div class="chat-avatar">
										<img data-toggle="tooltip" data-placement="bottom" data-original-title="'.$msj->nombres_remitente.'" class="img-circle" src="'.$msj->foto.'" alt="">
									</div>
                					<div class="chat-body">
                						'.$msj->msj.'
                						<small>'.$msj->fecha.'</small>
                					</div>
                				</div>
                			</li>';
            }
            $data['error'] = EXIT_SUCCESS;
            $data['mensajes'] = $html;
            $data['notifID'] = 'notif_'.$idEval;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function subirArchivos() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $ruta = null;
        try {
            if(!isset($_FILES["file"])) {
                throw new Exception('Hubo un error al subir su evidencia.');
            }
            $filesize = $_FILES["file"]["size"];
            if($filesize > __getPostMaxFileSize()) {
                throw new Exception('Su archivo es muy pesado. No puede exceder los '.ini_get('post_max_size').'Bs');
            }
            $idEvaluacion = _decodeCI(_post('idEvaluacion'));
            if($idEvaluacion == null) {
                throw new Exception(ANP);
            }
            $idEvaluador = $this->m_utils->getById('sped.evaluacion', 'id_evaluador', 'id_evaluacion', $idEvaluacion);
            if($idEvaluador != $this->_idUserSess) {//NO SE PUEDE SUBIR EVIDENCIAS EN LA EVALUACION DE ALGUIEN MAS
                throw new Exception(ANP);
            }
            $file     = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            if (!is_dir("uploads/modulos/sped/evidencias/")) {
                mkdir("uploads/modulos/sped/evidencias/", 0777);
            }
            $result = 0;
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = $idEvaluacion.'_'.__generateRandomString(7).'_'.date("dmhis");
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
                    "id_evaluacion"   => $idEvaluacion,
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
    
    function setIdSistemaInSession(){
        $idSistema = _decodeCI(_post('id_sis'));
        $idRol     = _decodeCI(_post('rol'));
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