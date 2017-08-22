<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_main extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct(){
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('mf_indicador/m_responsable_indicador');
        $this->load->model('mf_indicador/m_indicador');
        $this->load->model('mf_lineaEstrat/m_lineaEstrat');
        $this->load->model('mf_grafico/m_grafico');
        $this->load->model('m_main');
        $this->load->library('table');
        
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(BSC_ROL_SESS);
        if(!isset($_COOKIE[$this->config->item('sess_cookie_name')])) {
            $this->session->sess_destroy();
            Redirect(RUTA_SMILEDU, 'refresh');
        }
        if($this->_idUserSess == null || $this->_idRol == null) {
            $this->session->sess_destroy();
            Redirect(RUTA_SMILEDU, 'refresh');
        }
    }

	public function index(){
	    $data = _searchInputHTML('Busca tus indicadores', 'onkeyup="buscarIndicador()" microphone="1"');
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_BSC, BSC_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_BSC, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['main'] = true;
	    $data['ruta_logo']        = MENU_LOGO_BSC;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_BSC;
	    $data['nombre_logo']      = NAME_MODULO_BSC;
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	     
	    //$this->getRolesByUsuario();
	    if($this->_idRol == ID_ROL_MEDICION){
	        $indicadoresRespMedicion = $this->m_indicador->getIndicadoresAsigRespMedicion($this->_idUserSess, 1,0);
	        $data['tbIndicadores'] = _createTableIndicadores($indicadoresRespMedicion);
	        redirect('bsc/cf_indicador/c_indicador','refresh');
	    /*} else if($this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_DIRECTOR) {
	        redirect('bsc/cf_indicador/c_indicador','refresh');
	    } else if($this->_idRol == ID_ROL_DIRECTOR_CALIDAD) {
	        redirect('bsc/cf_indicador/c_indicador','refresh');*/
	    } else {
	        $data['font_size'] = _getSesion('font_size');
	    }
	    
	    $this->load->view('v_main', $data);
	}
	
	function getIndicadores(){
	    $desc = _post("desc");
	    $indicadores = $this->m_indicador->getIndicadoresByDesc($desc);
	    $data['count'] = count($indicadores);
	    $data['indicadores'] = $this->createVistaRapida_indicadores($indicadores);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	public function createVistaRapida_indicadores($data){
	    $vista    = null;
	    $var      = 0;
	    $arrayInd = Array();
	    foreach ($data as $row){
	        $idIndicador = _simple_encrypt($row->_id_indicador);
	        $descripcion = _getDescReduce($row->desc_registro, 113);
	
	        $array = $this->object_to_array($row);
	        $opciones = $this->getOpcionesGauge($array);
	        $vista .= '   <div class="mdl-card mdl-indicator">
                                <div class="mdl-card__title backCirc'.$var.'">
                                    <div class="mdl-graphic container-gauge linEst'.$var.'" id="cont'.$var.'" attr-posicion ="'.$var.'"
                                        data-porcentaje="'.$opciones['porcentaje'].'"
                                        data-porcent1="'.$opciones['porcentajeAmarillo'].'" data-porcent2="'.$opciones['porcentajeVerde'].'"
                                     data-cBack="'.$opciones['color'].'" data-inicioG="'.$opciones['inicioG'].'" data-finG="'.$opciones['finG'].'" data-tipo="'.$row->tipo_gauge.'"
                                     data-colorVerde="'.$opciones['colorVerde'].'" data-colorRojo="'.$opciones['colorRojo'].'"
                                     data-idColor="'.$opciones['idColor'].'" data-dorado="'.$array['dorado'].'" ></div>
                                </div>
                                <div class="mdl-card__supporting-text br-b percent_est_prom" >
                                    <h2>C&oacute;d. '.$row->cod_indi.'</h2>
                                    <h4 class="desc_linea" data-toggle="tooltip" title="'.$descripcion.'" data-placement="bottom">'.$descripcion.'</h4>
                                </div>
                                <div class="mdl-card__actions text-center respons" id="respons'.$var.'">
                                      '.$this->getRepsonsableByIndicador($row->_id_indicador).'
                                </div>
                                <div class="mdl-card__actions ingresar text-center m-b-5">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" disabled>Ingresar</button>
                                </div>
                                <div class="mdl-card__menu">
                                    <button id="menu-'.$var.'" class="mdl-button mdl-js-button mdl-button--icon" disabled>
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
                                </div>
                                <div id="barra'.$var.'" class="mdl-bar_state barraEstado"></div>
                            </div>';
	        array_push($arrayInd, $row->_id_indicador);
	
	        $var++;
	    }
	     
	    $dataUser = array("array_indi" => $arrayInd);
	    $this->session->set_userdata($dataUser);
	     
	    return $vista;
	}
	
    function getRepsonsableByIndicador($idIndicador) {
	    $personas = $this->m_responsable_indicador->getInfoResponsableByIndicador($idIndicador);
	    $infoIndicador = $this->m_indicador->getInfoIndicador($idIndicador);
	    $max  = count($personas);
	    $diff = $max - 1;
	    $textRespo = null;
	    if($diff > 1) {
	        $textRespo = $diff." responsables";
	    } else if($diff <= 1) {
	        $textRespo = $diff." responsable";
	    }
	    if(count($personas) >= 2) {
	        $max = 1;
	    }
	    $i = 1;
	    $result = null;
	    $idPersonaFirst = null;
	    foreach ($personas as $var) {
	        $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$var->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$var->foto_persona : RUTA_IMG_PROFILE."nouser.svg");
            $idPersona = _simple_encrypt($var->nid_persona);
            $foto = $var->google_foto;
	        if($foto == null){
	            $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$var->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$var->foto_persona : RUTA_IMG_PROFILE."nouser.svg");
	        }
	        $result .= '<a data-toggle="tooltip" style="cursor:pointer;display:inline-block;margin-right:10px" data-placement="bottom" data-original-title="'.$var->nombrecompleto.'" onclick="verImagenResponsable(\''.$foto.'\',\''.$var->nombrecompleto.'\',\''.$var->telf_pers.'\',\''.$var->correo_pers.'\',\''.$idPersona.'\')"><img src="'.$foto.'" class="img-circle width-1" alt="foto" width="40" height="40"></a>';
	        if($i == $max) {
                break;
            }
            $i++;
	    }
	    if(count($personas) >= 2){
	       $result .= '<a onclick="showResponsables(\''._simple_encrypt($idIndicador).'\')" style="cursor:pointer;display:inline-block;margin-right:10px;vertical-align:middle;text-decoration:none;color:#757575;" data-toggle="tooltip" data-placement="bottom" data-original-title="y '.$textRespo.' más"><div style="width:38px;height:38px;text-align:center;border:3px solid #E5E5E5;border-radius:1000px"><p style="margin-top:4px">+'.$diff.'</p></div></a>';
	    }
	    if(_getSesion($this->_idRol) == ID_ROL_PROMOTOR){
	       $result .= '<a data-toggle="tooltip" onclick="openModalNewResponsableMedicion();" style="cursor:pointer;display:inline-block;margin-right:10px;vertical-align:middle;text-decoration:none;" data-placement="bottom" data-original-title="Agregar Responsable"><div><i class="mdi mdi-person_add" style="font-size:23px"></i></div></a>';
	    }
	    
	    if(count($personas) == 0){
	        $result = '<a data-toggle="tooltip" style="cursor:pointer;display:inline-block;margin-right:10px" data-placement="bottom"><img src="'.(RUTA_IMG."no_responsable.jpeg").'" class="img-circle width-1 no_responsable" alt="foto" width="40" height="40"></a>';
	    }
	    
	    $result .= '<a data-toggle="tooltip" style="cursor:pointer;display:inline-block;margin-right:10px;vertical-align:middle;text-decoration:none" data-placement="bottom" data-original-title="'.$infoIndicador['desc_linea_estrategica'].' / '.$infoIndicador['desc_objetivo'].'"><div><i class="mdi mdi-info" style="font-size:23px"></i></div></a>';
	    return $result;
	}
	
	function getOpcionesGauge($indicadores){
	    $data['colorVerde'] = "#E2574C";
	    $data['colorRojo']  = "#43AC6D";
	    $data['inicioG']    = 0;
	    $data['finG']       = 100;
	    $data['porcentaje'] = $indicadores['valor_actual_porcentaje'];
	     
	    $data['porcentajeVerde']    = $indicadores['valor_meta'];
	    $data['porcentajeAmarillo'] = $indicadores['flg_amarillo'];
	    $data['dorado'] = 0;
	     
	    $colores = $this->getBackroundColor($indicadores['valor_meta'],$indicadores['flg_amarillo'],$indicadores['dorado'],$indicadores['valor_actual_porcentaje'],$indicadores['tipo_gauge']);
	     
	    if($indicadores['tipo_gauge'] == GAUGE_RATIO){
	        $data['inicioG']    = 0;
	        $data['finG']       = 1;
	    }
	     
	    else if($indicadores['tipo_gauge'] == GAUGE_PUESTO){
	        $data['colorVerde']         = "#43AC6D";
	        $data['colorRojo']          = "#E2574C";
	        $data['inicioG']            = 1;
	        $data['porcentajeVerde']    = $indicadores['flg_amarillo'];
	        $data['porcentajeAmarillo'] = $indicadores['valor_meta'];
	        $data['finG']               = $indicadores['valor_actual_porcentaje'] + $indicadores['flg_amarillo'];
	        if($indicadores['valor_actual_porcentaje'] == 0){
	            $data['finG']       = $indicadores['valor_meta'] + $indicadores['flg_amarillo'];
	            $data['porcentaje'] = 100;
	            $data['color']      = "#E2574C";
	        }
	         
	        $colores = $this->getBackroundColor($indicadores['valor_meta'],$indicadores['flg_amarillo'],$indicadores['dorado'],$indicadores['valor_actual_porcentaje'],$indicadores['tipo_gauge']);
	    }
	     
	    else if($indicadores['tipo_gauge'] == GAUGE_MAXIMO){
	        $data['colorVerde'] = "#E2574C";
	        $data['colorRojo']  = "#43AC6D";
	        $data['inicioG']    = 0;
	        $data['finG']       = $indicadores['valor_actual_porcentaje'] + $indicadores['valor_meta'];
	        if($indicadores['valor_actual_porcentaje'] == 0){
	            $data['finG']       = $indicadores['valor_meta'] + $indicadores['flg_amarillo'];
	            $data['porcentaje'] = 0;
	            $data['color']      = "#E2574C";
	        }
	         
	        $colores = $this->getBackroundColor($indicadores['flg_amarillo'],$indicadores['valor_meta'],$indicadores['dorado'],$indicadores['valor_actual_porcentaje'],$indicadores['tipo_gauge']);
	    }
	     
	    else if($indicadores['tipo_gauge'] == GAUGE_CERO){
	        $data['colorVerde'] = "#43AC6D";
	        $data['colorRojo']  = "#E2574C";
	        $data['inicioG']    = 0;
	        $data['finG']       = 100;
	        $data['porcentajeVerde']    = $indicadores['flg_amarillo'];
	        $data['porcentajeAmarillo'] = $indicadores['valor_meta'];
	         
	        $colores = $this->getBackroundColor($indicadores['valor_meta'],$indicadores['flg_amarillo'],$indicadores['dorado'],$indicadores['valor_actual_porcentaje'],$indicadores['tipo_gauge']);
	    }
	     
	    else if($indicadores['tipo_gauge'] == GAUGE_REDUCCION){
	        $data['colorVerde'] = "#E2574C";
	        $data['colorRojo']  = "#43AC6D";
	        $data['inicioG']    = $indicadores['valor_actual_porcentaje'] - 10;
	        $data['finG']       = $indicadores['valor_actual_porcentaje'] + 3*$indicadores['valor_meta'];
	        $data['porcentajeVerde']    = $indicadores['valor_meta'];
	        $data['porcentajeAmarillo'] = $indicadores['flg_amarillo'];
	
	        $colores = $this->getBackroundColor($indicadores['valor_meta'],$indicadores['flg_amarillo'],$indicadores['dorado'],$indicadores['valor_actual_porcentaje'],$indicadores['tipo_gauge']);
	    }
	
	    $data['color']   = $colores['color'];
	    $data['idColor'] = $colores['idColor'];
	     
	    return $data;
	}
	
	function object_to_array($object) {
	    return (array) $object;
	}
	
	function getBackroundColor($verde, $amarillo, $dorado, $porcentaje, $tipo){
	    $color = null;
	    $idColor = null;
	    if($porcentaje <= $amarillo){
	        $color = "mdl-state__red"; //rojo
	        $idColor = 2;
	         
	        if($tipo == GAUGE_PUESTO){
	            $color = "mdl-state__cyan"; //verde
	            $idColor = 0;
	        }else if($tipo == GAUGE_MAXIMO){
	            $color = "mdl-state__red"; //rojo
	            $idColor = 2;
	        }
	        else if($tipo == GAUGE_CERO){
	            $color = "mdl-state__cyan";//verde
	            $idColor = 0;
	        }
	    }
	     
	    else if($porcentaje < $verde && $porcentaje >= $amarillo){
	        $color = "mdl-state__yellow";//amarillo
	        $idColor = 1;
	    }
	     
	    else if($porcentaje >= $verde){
	        $color = "mdl-state__cyan"; //verde
	        $idColor = 0;
	         
	        if($tipo == GAUGE_PUESTO){
	            $color = "mdl-state__red"; //rojo
	            $idColor = 2;
	        }
	        else if($tipo == GAUGE_MAXIMO){
	            $color = "mdl-state__cyan";//verde
	            $idColor = 0;
	        }
	        else if($tipo == GAUGE_CERO){
	            $color = "mdl-state__red"; //rojo
	            $idColor = 2;
	        }
	    }
	    //PARA LOS INDICADORES QUE PASAN SUS COMPARATIVAS Y METAS
	    if($dorado == 1){
	        $color = "#E1A032";
	        $idColor = 3;
	    }
	     
	    $data['idColor'] = $idColor;//PARA FILTRAR
	    $data['color'] = $color;
	     
	    return $data;
	}
	
	function goToIndicadorDetalle(){
	    $idIndicadorEncr = _post('id_indicador');
	    $idIndicador     = _simpleDecryptInt($idIndicadorEncr);
	     
	    $nIndicador = $this->m_utils->getById('bsc.indicador','desc_indicador','_id_indicador',$idIndicador);
	    $nombreIndicador = _getDescReduce($nIndicador,40);
	    $dataUser = array("nombre_indicador"       => $nombreIndicador,
	        "nombre_indicador_large" => $nIndicador
	    );
	    $this->session->set_userdata($dataUser);
	     
	    $url = '';
	    if($idIndicador != null){
	        $tipo =  $this->m_utils->getById("bsc.indicador","_id_tipo_estructura","_id_indicador",$idIndicador);
	        $dataUser = array("id_indicador" => $idIndicadorEncr, "tipo_estructura_indicador" => $tipo);
	        $this->session->set_userdata($dataUser);
	        $url = 'cf_indicador/c_detalle_indicador';
	    }
	    echo $url;
	}
	
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE['smiledu']);
        $cookie_name2 = "smiledu";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
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
	
    function enviarFeedBack(){
        $nombre = _getSesion('nombre_usuario');
        $mensaje = _post('feedbackMsj');
        $url = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
    
    function migrarMongoToPostgres() {
        $start = microtime(true);
        $data = $this->m_grafico->getDataHistorica()['retval'];
        $arrayInsert = array();
        for ($j = 0; $j < count($data); $j++) {
            $indicador = $data[$j];
            $sedes   = isset($indicador['sedes']) ? $indicador['sedes'] : array();
            $niveles = isset($indicador['niveles']) ? $indicador['niveles'] : array();
            $grados  = isset($indicador['grados']) ? $indicador['grados'] : array();
            $aulas   = isset($indicador['aulas']) ? $indicador['aulas'] : array();
            $comparativas   = isset($indicador['comparativas']) ? $indicador['comparativas'] : array();
            
            $idIndicador = $indicador['__id_indicador'];
            $year        = $indicador['year'];
            $nroMedicion = isset($indicador['nro_medicion'])?$indicador['nro_medicion']:0;
            $valorMeta   = $indicador['valor_meta'];
            $valorActual = $indicador['valor_actual_porcentaje'];
            $fecMedi     = $indicador['fecha_medicion'];
            
            $strSedes = '[';
            for ($i = 0; $i < count($sedes); $i++) {
                $strSedes .= '{';
                $strSedes .= '"id_sede" : '.$sedes[$i]['id_sede'].",";
                $strSedes .= '"valor_actual_porcentaje" : '.(isset($sedes[$i]['valor_actual_porcentaje'])?$sedes[$i]['valor_actual_porcentaje']:0).",";
                $strSedes .= '"id_indicador_detalle" : '.(isset($sedes[$i]['id_indicador_detalle'])?$sedes[$i]['id_indicador_detalle']:0);
                $strSedes .= '},';
            }
            $strSedes = rtrim($strSedes, ",");
            $strSedes .= ']';
            
            $strNiveles = '[';
            for ($i = 0; $i < count($niveles); $i++) {
                $strNiveles .= '{';
                $strNiveles .= '"id_sede" : '.$niveles[$i]['id_sede'].",";
                $strNiveles .= '"id_nivel" : '.$niveles[$i]['id_nivel'].",";
                $strNiveles .= '"id_disciplina" : '.(isset($niveles[$i]['id_disciplina'])?$grados[$i]['id_disciplina']:0).",";
                $strNiveles .= '"valor_actual_porcentaje" : '.(isset($niveles[$i]['valor_actual_porcentaje'])?$niveles[$i]['valor_actual_porcentaje']:0).",";
                $strNiveles .= '"id_indicador_detalle" : '.(isset($niveles[$i]['id_indicador_detalle'])?$niveles[$i]['id_indicador_detalle']:0);
                $strNiveles .= '},';
            }
            $strNiveles = rtrim($strNiveles, ",");
            $strNiveles .= ']';
            
            $strGrados = '[';
            for ($i = 0; $i < count($grados); $i++) {
                $strGrados .= '{';
                $strGrados .= '"id_sede" : '.$grados[$i]['id_sede'].",";
                $strGrados .= '"id_nivel" : '.$grados[$i]['id_nivel'].",";
                $strGrados .= '"id_grado" : '.$grados[$i]['id_grado'].",";
                $strGrados .= '"valor_actual_porcentaje" : '.(isset($grados[$i]['valor_actual_porcentaje'])?$grados[$i]['valor_actual_porcentaje']:0).",";
                $strGrados .= '"id_indicador_detalle" : '.(isset($grados[$i]['id_indicador_detalle'])?$grados[$i]['id_indicador_detalle']:0);
                $strGrados .= '},';
            }
            $strGrados = rtrim($strGrados, ",");
            $strGrados .= ']';
            
            $strAulas = '[';
            for ($i = 0; $i < count($aulas); $i++) {
                $strAulas .= '{';
                $strAulas .= '"id_aula" : '.(isset($aulas[$i]['id_aula'])?$aulas[$i]['id_aula']:0).",";
                $strAulas .= '"desc_aula" : '.(isset($aulas[$i]['desc_aula'])?'"'.utf8_decode($aulas[$i]['desc_aula']).'"':'""')."";
                //$strAulas .= '"valor_actual_porcentaje" : '.$aulas[$i]['valor_actual_porcentaje'].",";
                //$strAulas .= '"id_indicador_detalle" : '.$aulas[$i]['id_indicador_detalle'];
                $strAulas .= '},';
            }
            $strAulas = rtrim($strAulas, ",");
            $strAulas .= ']';
            
            $strComparativas = '[';
            for ($i = 0; $i < count($comparativas); $i++) {
                $strComparativas .= '{';
                $strComparativas .= '"id_comparativa" : '.(isset($comparativas[$i]['id_comparativa'])?$comparativas[$i]['id_comparativa']:0).",";
                $strComparativas .= '"valor_comparativa" : '.(isset($comparativas[$i]['valor_comparativa'])?$comparativas[$i]['valor_comparativa']:0).",";
                $strComparativas .= '"desc_comparativa" : '.(isset($comparativas[$i]['desc_comparativa'])?'"'.utf8_decode($comparativas[$i]['desc_comparativa']).'"':"");
                $strComparativas .= '},';
            }
            $strComparativas = rtrim($strComparativas, ",");
            $strComparativas .= ']';
            
            $array = array("_id_indicador"           => $idIndicador,
                "year"                    => $year,
                "nro_medicion"            => $nroMedicion,
                "valor_meta"              => $valorMeta,
                "valor_actual_porcentaje" => $valorActual,
                "fecha_medicion"          => $fecMedi,
                "sedes"                   => $strSedes,
                "niveles"                 => $strNiveles,
                "grados"                  => $strGrados,
                "aulas"                   => $strAulas,
                "comparativas"            => $strComparativas
            );
            array_push($arrayInsert, $array);
        }
        $this->m_grafico->insertDataHistorica($arrayInsert);
        $time_elapsed_secs = microtime(true) - $start;
        $unidMedida = 'segundo(s)';
        if($time_elapsed_secs >= 60) {
            $time_elapsed_secs = $time_elapsed_secs / 60;
            $unidMedida = 'minuto(s)';
        }
        echo 'FINALIZO OK en '.(round($time_elapsed_secs, 2)).' '.$unidMedida;
    }
    
    function updatearIndicador(){
        $data = $this->m_grafico->getDataHistoricaAulas()['retval'];
        for ($j = 0; $j < count($data); $j++) {
            $indicador = $data[$j];
            $aulas   = isset($indicador['aulas']) ? $indicador['aulas'] : array();
            $idIndicador = $indicador['__id_indicador'];
            $year        = $indicador['year'];
            $nroMedicion = isset($indicador['nro_medicion'])?$indicador['nro_medicion']:0;
            
            $strAulas = '[';
            for ($i = 0; $i < count($aulas); $i++) {
                $strAulas .= '{';
                $strAulas .= '"id_aula" : '.(isset($aulas[$i]['id_aula'])?$aulas[$i]['id_aula']:0).",";
                $strAulas .= '"desc_aula" : '.(isset($aulas[$i]['desc_aula'])?'"'.utf8_decode($aulas[$i]['desc_aula']).'"':'""').",";
                $strAulas .= '"valor_actual_porcentaje" : '.(isset($aulas[$i]['valor_actual_porcentaje'])?$aulas[$i]['valor_actual_porcentaje']:0).",";
                $strAulas .= '"valor_meta" : '.(isset($aulas[$i]['valor_meta'])?$aulas[$i]['valor_meta']:0)."";
                $strAulas .= '},';
            }
            $strAulas = rtrim($strAulas, ",");
            $strAulas .= ']';
            
            $arrayUpdate = array("aulas" => $strAulas);
            $this->m_grafico->updateIndicadorHistorico($idIndicador, $year, $nroMedicion, $arrayUpdate);
        }
    }
    
    function getResponsablesIndicador(){
        $idIndicador   = _simpleDecryptInt(_post('indicador'));
        $result        = $this->m_responsable_indicador->getInfoResponsableByIndicador($idIndicador);
        $data['table'] = $this->buildTableResponsables($result);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTableResponsables($result){
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_responsables">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0  = array('data' => '#'           ,'style' => 'text-align:left');
        $head_1  = array('data' => 'Responsable' ,'style' => 'text-align:left');
        $this->table->set_heading($head_0,$head_1);
        $val = 1;
        foreach($result as $row){
            $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg");
            $foto = $row->google_foto;
            if($foto == null){
                $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg");
            }
            $img = '<div style="display:flex"><img src="'.$foto.'" class="img-circle width-1" alt="foto" width="40" height="40">'.$row->nombrecompleto.'</div>';
            $row_col0  = array('data' => $val                 , 'class' => 'text-left');
            $row_col1  = array('data' => $img , 'class' => 'text-left');
            $this->table->add_row($row_col0,$row_col1);
            $val++;
        }
        return $this->table->generate();
    }
}