<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_indicador_rapido extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');;
        $this->load->model('mf_lineaEstrat/m_lineaEstrat');
        $this->load->model('mf_indicador/m_indicador');
        $this->load->model('mf_indicador/m_responsable_indicador');
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
   
	public function index() {	    
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_BSC, BSC_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_BSC, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['main'] = true;
	    $data['ruta_logo']        = MENU_LOGO_BSC;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_BSC;
	    $data['nombre_logo']      = NAME_MODULO_BSC;
        $data['titleHeader']      = 'Indicadores';
        $data['return']           = '';

        $data['rutaSalto']        = 'SI';
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    if($this->_idRol == ID_ROL_MEDICION) {
	        $indicadores = $this->m_indicador->getIndicadoresAsigRespMedicion($this->_idUserSess, 0, 0);
	    } else if($this->_idRol == ID_ROL_DIRECTOR_CALIDAD && _getSesion('es_director_calidad') == null) {
	        $indicadores = $this->m_indicador->getIndicadoresPlanEstrategico();
	    } else if($this->_idRol == ID_ROL_DIRECTOR_CALIDAD && _getSesion('es_director_calidad') == true) {
	        $indicadores = $this->m_indicador->getIndicadoresByCategoria(_simpleDecryptInt(_getSesion('id_categoria')), 0, 0);
	    } else {
	        //if($this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_DIRECTOR) {
	            if(_getSesion('es_director_direc_promot_ver_cate') == null) {
	                $indicadores = $this->m_indicador->getIndicadoresPlanEstrategico();
	            } else {
	                $indicadores = $this->m_indicador->getIndicadoresByCategoria(_simpleDecryptInt(_getSesion('id_categoria')), 0, 0);
	            }
	        //}
	    }
	    $data['countIndicadores']        = count($indicadores);
	    $data['indicadores_vistaRapida'] = $this->createVistaRapida_indicadores($indicadores);
	    
	    $this->load->view('vf_indicador/v_indicador_rapido',$data);
	}
	
	public function createVistaRapida_indicadores($data) {
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
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="goToIndicadorDetalle(\''.$idIndicador.'\')">Ingresar</button>  
                                </div>
                                <div class="mdl-card__menu">
                                    <button id="menu-'.$var.'" class="mdl-button mdl-js-button mdl-button--icon">
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
                                    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-'.$var.'">
                                        <li class="mdl-menu__item" onclick="openModalEditar(\''.$idIndicador.'\' , \'cont'.$var.'\' , \''.$var.'\')"><i class="mdi mdi-edit"></i>Editar valor</li>
                                    </ul>
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
	
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE['smiledu']);
        $cookie_name2 = "smiledu";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
	
	function cambioRol(){
	    $idRolEnc = $this->input->post('id_rol');
	    $idRol = $this->lib_utils->simple_decrypt($idRolEnc,CLAVE_ENCRYPT);
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
	     
	    $dataUser = array("id_rol"     => $idRol,
	        "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	     
	    $idRol     = _getSesion('nombre_rol');
	     
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function pintarDetalle() {
	    $html = null;
	    if(_getSesion('id_rol') == ID_ROL_MEDICION) {
	        $html  .= '<button class="btn ink-reaction btn-flat btn-primary" onclick="actualizarActual();">Actualizar Valor Actual</button><br>';
	    }
	    $modis = $this->m_indicador->getUltimaModif_ActualFromIndicador($this->lib_utils->simple_decrypt(_getSesion('id_indicador'), CLAVE_ENCRYPT));
	    if($modis != null) {
	        $fecha = ($modis['audi_ult_modi_actual'] == null) ? null : date('d/m/Y h:i:s A',strtotime($modis['audi_ult_modi_actual']));
	        $html .= '<strong>Última Modificación:</strong> '.$fecha;
	        $html .= ', <strong>por:</strong> '.$modis['audi_ult_pers_modi_actual'];
	    }
	    $valorAmarillo = $this->m_indicador->getValorAmarilloByIndicador($this->lib_utils->simple_decrypt(_getSesion('id_indicador'), CLAVE_ENCRYPT));
	    return $html;
	}
	
	function getValorAmarillo() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idIndicadorCrypt = _post('idIndicador');
	        $dataUser = array("id_indicador" => $idIndicadorCrypt);
	        $idIndicador = _simpleDecryptInt($idIndicadorCrypt);
    	    $this->session->set_userdata($dataUser);
    	    $idCont = $this->input->post('idCont');
    	    $pos    = $this->input->post('pos');
	        if($idIndicador == null) {
	            throw new Exception('El id de indicador no es válido');
	        }
	        $ppu = $this->m_indicador->getCampoByIndicador($idIndicador);
	        $valorAmarillo = $this->m_indicador->getValorAmarilloByIndicador($idIndicador);
	        $data['valorAmarillo'] = $valorAmarillo['flg_amarillo'];
	        $data['valorMeta']     = $valorAmarillo['valor_meta'];
	        $data['error']         = EXIT_SUCCESS;

	        $dataUser = array(
	            'idCont'   => $idCont,
	            'id_ppu'   => $ppu['aux'],
	            'posicion' => $pos);
	        $this->session->set_userdata($dataUser);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function editValorAmarillo(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idIndicador   = _simpleDecryptInt(_getSesion('id_indicador'));
	        $valorAmarillo = _post('valor');
	        $idObjetivoEnc = _getSesion('id_objetivo');
	        $idObjetivo    = _simpleDecryptInt($idObjetivoEnc);
	        $idUsuario     = _getSesion('id_persona');
	        $idRol         = _getSesion('id_rol');
	        $posicion      = _getSesion('posicion');
	        $idCont        = _getSesion('idCont');
	        $idPpu         = _getSesion('id_ppu');
	        
            $data = _updateValorAmarilloByIndicador($idIndicador,$valorAmarillo,$idObjetivo,$idUsuario,$idRol,$posicion,$idCont);
            if($idRol == ID_ROL_MEDICION){
                $indicadores = $this->m_indicador->getIndicadoresAsigRespMedicion($idUsuario,0,$idIndicador);
            }else{
                $indicadores = $this->m_indicador->getIndicadoresByObjetivo($idObjetivo,0,$idIndicador);
            }
            $data['contGauge'] = $this->buildContenedorGaugeHTML($indicadores,$posicion);
            $data['idCont']    = $idCont;
            $data['posicion']  = $posicion;

	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildContenedorGaugeHTML($indicadores,$posicion){   
	    $opciones = $this->getOpcionesGauge($indicadores);
	    $cont = '<div class="mdl-graphic container-gauge cont_linea linEst'.$posicion.'" id="cont'.$posicion.'" attr-posicion ="'.$posicion.'" data-porcentaje="'.$opciones['porcentaje'].'" data-porcent1="'.$opciones['porcentajeAmarillo'].'" data-porcent2="'.$opciones['porcentajeVerde'].'" 
                        data-cBack="'.$opciones['color'].'" data-inicioG="'.$opciones['inicioG'].'" data-finG="'.$opciones['finG'].'" data-tipo="'.$indicadores['tipo_gauge'].'"
                        data-colorVerde="'.$opciones['colorVerde'].'" data-colorRojo="'.$opciones['colorRojo'].'"
                        data-idColor="'.$opciones['idColor'].'" data-dorado="'.$indicadores['dorado'].'" style="text-align:center"></div>';
	    return $cont;
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
	
	function setIdSistemaInSession(){
	    $idSistema = $this->encrypt->decode($this->input->post('id_sis'));
	    $idRol     = $this->encrypt->decode($this->input->post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getRepsonsableByIndicador($idIndicador) {
	    $personas = $this->m_responsable_indicador->getInfoResponsableByIndicador($idIndicador);
	    //$infoIndicador = $this->m_indicador->getInfoIndicador($idIndicador);
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
	    foreach ($personas as $var) {
	        $nombre = $var->nombrecompleto;
	        $telf = $var->telf_pers;
	        $idPersona = _simple_encrypt($var->nid_persona);
	        $correo = $var->correo_pers;
	        $foto = $var->google_foto;
	        if($foto == null){
	            $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$var->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$var->foto_persona : RUTA_IMG_PROFILE."nouser.svg");
	        }
	        $result .= '<a data-toggle="tooltip" style="cursor:pointer;display:inline-block;margin-right:10px" data-placement="bottom" data-placement="bottom" data-original-title="'.$var->nombrecompleto.'" onclick="verImagenResponsable(\''.$foto.'\',\''.$var->nombrecompleto.'\',\''.$var->telf_pers.'\',\''.$var->correo_pers.'\',\''.$idPersona.'\')"><img src="'.$foto.'" class="img-circle width-1" alt="foto" width="40" height="40"></a>';
	        if($i == $max) {
	            break;
	        }
	        $i++;
	    }
	    if(count($personas) >= 2){
	        $result .= '<a data-toggle="tooltip" style="cursor:pointer;display:inline-block;margin-right:10px" data-placement="bottom">
	                        <div style="width:38px;height:38px;text-align:center;border:3px solid #E5E5E5;border-radius:1000px"><p style="margin-top:4px">+'.$diff.'</p></div>
	                    </a>';
	    }
	    //CUANDO NO TIENE RESPONSABLES DE MEDICION
	    if(count($personas) == 0){
	        $result = '<a data-toggle="tooltip" style="cursor:pointer;display:inline-block;margin-right:10px" data-placement="bottom"><img src="'.(RUTA_IMG."no_responsable.jpeg").'" class="img-circle width-1 no_responsable" alt="foto" width="40" height="40"></a>';
	    }
	    return $result;
	}
	
	function reponsablesIndicadores(){
	    $indicadores = _getSesion('array_indi');
	    
	    $array_responsables = Array();
	    foreach($indicadores as $ind){
	        $res = $this->getRepsonsableByIndicador($ind);
	        array_push($array_responsables, $res);
	    }
	    $data['responsables'] = json_encode($array_responsables);
	    
	    echo json_encode(array_map('utf8_encode', $data));
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
	        $url = 'c_detalle_indicador';
	    }
	    echo $url;
	}
	
	function enviarFeedBack(){
	    $nombre = _getSesion('nombre_usuario');
	    $mensaje = $this->input->post('feedbackMsj');
	    $url = $this->input->post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}
}