<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_linea_estrategica extends CI_Controller {

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
        $this->load->model('../m_utils');
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
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_BSC, BSC_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_BSC, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['main'] = true;
	    $data['ruta_logo']        = MENU_LOGO_BSC;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_BSC;
	    $data['nombre_logo']      = NAME_MODULO_BSC;
        $data['titleHeader']      = 'Cuadro de Mando';
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	     
	    //$this->getRolesByUsuario();
	    if($this->_idRol == ID_ROL_MEDICION){
	        $indicadoresRespMedicion = $this->m_indicador->getIndicadoresAsigRespMedicion($this->_idUserSess, 1,0);
	        $data['tbIndicadores'] = _createTableIndicadores($indicadoresRespMedicion);
	        redirect('bsc/cf_indicador/c_indicador','refresh');
	    } else if($this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_DIRECTOR || $this->_idRol == ID_ROL_SUBDIRECTOR || $this->_idRol == ID_ROL_DIRECTOR_CALIDAD) {
	        $lineasEstrat = $this->m_lineaEstrat->getLineasEstrategicas();
	        $data['lineasEstrat'] = $this->getLineasEstrategicasGauge($lineasEstrat);
	    } else {
	        $data['font_size'] = _getSesion('font_size');
	    }
	    
	    $this->load->view('v_linea_estrategica', $data);
	}
		
	function is_url_exist($url){
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_NOBODY, true);
	    curl_exec($ch);
	    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	    if($code == 200){
	        $status = true;
	    }else{
	        $status = false;
	    }
	    curl_close($ch);
	    return $status;
	}
	
	function responsableMedicionControl($idRol,$idUsuario,$data){
	    $this->getRolesByUsuario();
	    if($idRol == ID_ROL_MEDICION){
	        $indicadoresRespMedicion = $this->m_indicador->getIndicadoresAsigRespMedicion($idUsuario, 1,0);
	        $data['tbIndicadores'] = $this->lib_utils->createTableIndicadores($indicadoresRespMedicion);
	        redirect('cf_indicador/c_indicador','refresh');
	    } else if($idRol == ID_ROL_PROMOTOR || $idRol == ID_ROL_DIRECTOR || $this->_idRol == ID_ROL_DIRECTOR_CALIDAD) {
	        $lineasEstrat = $this->m_lineaEstrat->getLineasEstrategicas();
	        $data['lineasEstrat'] = $this->getLineasEstrategicasGauge($lineasEstrat);
	        $data['arbolPermisosMantenimiento'] = $this->lib_utils->buildArbolPermisos($idRol);
	        
	        //MENU Y CABECERA
	        
	        $menu     = $this->load->view('v_menu', $data, true);
	        $data['font_size'] = _getSesion('font_size');
	        $data['menu']     = $menu;
	        
	        $this->load->view('v_linea_estrategica', $data);
	    } else {
	        $data['arbolPermisosMantenimiento'] = $this->lib_utils->buildArbolPermisos($idRol);
	        
	        //MENU Y CABECERA
	        
	        $menu     = $this->load->view('v_menu', $data, true);
	        $data['menu']     = $menu;
	        $data['font_size'] = _getSesion('font_size');
	        
	        $this->load->view('v_linea_estrategica', $data);
	    }
	}

	function getLineasEstrategicasGauge($data){
	    $result = null;
	    $var = 0;
	    foreach($data as $row){    
	        $idLineaEstrat = _simple_encrypt($row->_id_linea_estrategica);
	        
	        $result .= '   <div class="mdl-card mdl-indicator" >
                                <div class="mdl-card__title backCirc'.$var.'" >
                                    <div class="mdl-graphic container-gauge linEst'.$var.'"  id="cont'.$var.'" attr-posicion ="'.$var.'" 
                                        data-toggle="tooltip" data-title="Zona de riesgo: '.$row->flg_amarillo.' / Valor Meta: '.$row->flg_verde.'" data-placement="top" ></div>
                                </div>
                                <div class="mdl-card__supporting-text">
                                    <h2>'.$row->desc_linea_estrategica.'</h2>
                                    <h4>'.$row->info_linea_estrategica.'</h4>
                                </div>
                                <div class="mdl-card__menu">
                                    <button id="menu-'.$var.'" class="mdl-button mdl-js-button mdl-button--icon">
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
                                    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-'.$var.'">
                                        <li class="mdl-menu__item" onclick="openEditarValorAmarilloLinea(\''.$idLineaEstrat.'\' , \'cont'.$var.'\' , \''.$var.'\')" ><i class="mdi mdi-edit"></i>Editar valor</li>
                                    </ul>
                                </div>
                                <div class="mdl-card__actions">
                                    <ul class="mdl-list-numbers">
                                        <li data-toggle="tooltip" data-title="Copa" data-placement="top"><i class="mdi mdi-cup"></i> '.$row->dorados.'</li>
                                        <li data-toggle="tooltip" data-title="Objetivos" data-placement="top"><i class="mdi mdi-filter_center_focus"></i> '.$row->nro_objetivos.'</li>
                                        <li data-toggle="tooltip" data-original-title="Categorias" data-placement="top"><i class="mdi mdi-local_offer"></i> '.$row->nro_categorias.'</li>
                                        <li data-toggle="tooltip" data-title="Indicadores" data-placement="top"><i class="mdi mdi-assistant_photo"></i> '.$row->nro_indicadores.'</li>
                                    </ul>
                                </div>
                                <div class="mdl-card__actions ingresar text-center m-b-5">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="getObjetivosByLinea(\''.$idLineaEstrat.'\')">Ingresar</button>  
                                </div>
                                <div id="barra'.$var.'" class="mdl-bar_state barraEstado"></div>
                            </div>';
	        
	        $var++;
	    }
	    return $result;
	}
	
	function getGraficosLineasEstrat(){
	    $data = $this->m_lineaEstrat->getPorcentajeByLineaEstrat(0);
	    $list = array();
	    $arrayNombresLineas = array();
	    $arrayPorcentajeLineas = array();
	    foreach($data as $row){
	        $dorado = 0;
	        if($row->dorados == $row->tod){
	            $dorado = 1;
	        }
	        
	        $color = $this->getColoresGraficos($row->flg_amarillo, $row->flg_verde, ($row->pas/$row->tod)*100);
	        array_push($list, array("porcentaje" => ($row->pas/$row->tod)*100,
	                                "p_amarillo" => $row->flg_amarillo,
	                                "p_verde"    => $row->flg_verde,
	                                "color"      => $color,
	                                "dorado"     =>$dorado
	        ));
	        array_push($arrayNombresLineas, utf8_encode($row->desc_linea_estrategica));
	        array_push($arrayPorcentajeLineas, round(($row->pas/$row->tod)*100));
	    }
	    
	    $dorado = 0;
	    $dataGeneral = $this->m_lineaEstrat->getGaugesGenerales();
	    if($dataGeneral['porcentajegeneral'] == 100){
	        $dorado = 1;
	    }
	    
	    $result['porcent_lineas_num'] = json_encode($list);
	    
	    $result['porcent_general'] = $dataGeneral['porcentajegeneral'];
	    $result['porcent_general_amarillo'] = $dataGeneral['valor_amarillo'];
	    $result['porcent_general_verde'] = $dataGeneral['valor_meta'];
	    $result['dorado'] = $dorado;
	    $result['dorados'] = $dataGeneral['dorados'];
	    
	    if($dataGeneral['porcentajegeneral'] <= $dataGeneral['valor_amarillo']){
	        $result['backgroundAvantgard'] = "fondoRojo";
	    }else if($dataGeneral['porcentajegeneral'] <= $dataGeneral['valor_meta'] && $dataGeneral['porcentajegeneral'] > $dataGeneral['valor_amarillo']){
	        $result['backgroundAvantgard'] = "fondoAmarillo";
	    }else if($dataGeneral['porcentajegeneral'] > $dataGeneral['valor_meta'] && $dataGeneral['porcentajegeneral'] < 100){
	        $result['backgroundAvantgard'] = "fondoVerde";
	    }else if($dataGeneral['porcentajegeneral'] == 100){
	        $result['backgroundAvantgard'] = "fondoDorado";
	    }
	    $result['arrayNombreLineas']     = json_encode($arrayNombresLineas);
	    $result['arrayPorcentajeLineas'] = json_encode($arrayPorcentajeLineas);
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE['smiledu']);
        $cookie_name2 = "smiledu";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
	
	function getOBjetivosByLineaEstrat(){
	    $idLineaEstrat = _simpleDecryptInt(_post('id_lineaEstrat'));
	    
	    $nombreLineaEstrat = $this->m_utils->getById('bsc.linea_estrategica','desc_linea_estrategica','_id_linea_estrategica',$idLineaEstrat);

	    $dataUser = array("nombre_lineaEstrat" => $nombreLineaEstrat);
	    $this->session->set_userdata($dataUser);
	    
	    $data = $this->m_lineaEstrat->getPorcentajeByObjetivos($idLineaEstrat,0);
	    $var = 10;
	    $gaugeObjetivos = '';
	    $list = array();
	    foreach($data as $row){
	        $dorado = 0;
	        if($row->dorados == $row->tod){
	            $dorado = 1;
	        }
	        
	        $idObjetivo = _simple_encrypt($row->_id_objetivo);
	        $cantCat    = $this->m_lineaEstrat->countCategoriasInObj($row->_id_objetivo);
	        
	        $gaugeObjetivos .= '   <div class="mdl-card mdl-indicator">
                                        <div class="mdl-card__title backCirc'.$var.'">
                                            <div class="mdl-graphic container-gauge linEst'.$var.'"  id="cont'.$var.'" attr-posicion ="'.$var.'"></div>
                                        </div>
                                        <div class="mdl-card__supporting-text">
                                            <h2>C&oacute;d.'.$row->cod_obje.'</h2>
                                            <h4>'.$row->desc_objetivo.'</h4>
                                        </div>
                                        <div class="mdl-card__actions">
                                            <ul class="mdl-list-numbers">
                                                <li data-toggle="tooltip" data-title="Copa" data-placement="top"><i class="mdi mdi-cup"></i> '.$row->dorados.'</li>
                                                <li data-toggle="tooltip" data-original-title="Categorias" data-placement="top"><i class="mdi mdi-local_offer"></i> '.$cantCat.'</li>
                                                <li data-toggle="tooltip" data-title="Indicadores" data-placement="top"><i class="mdi mdi-assistant_photo"></i> '.$row->tod.'</li>
                                             </ul>                                         
                                        </div>   
                                        <div class="mdl-card__actions ingresar text-center m-b-5">
                                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="goToCategorias(\''.$idObjetivo.'\')">Ingresar</button>  
                                        </div>                                                                                                
                                        <div class="mdl-card__menu">
                                            <button id="menu-'.$var.'" class="mdl-button mdl-js-button mdl-button--icon">
                                                <i class="mdi mdi-more_vert"></i>
                                            </button>
                                            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-'.$var.'">
                                                <li class="mdl-menu__item" onclick="openEditarValorAmarilloObjetivo(\''._post('id_lineaEstrat').'\' , \''.$idObjetivo.'\' , \'cont'.$var.'\' , \''.$var.'\')" ><i class="mdi mdi-edit"></i>Editar valor</li>
                                            </ul>
                                        </div>
                                        <div id="barra'.$var.'" class="mdl-bar_state barraEstado"></div>
                                   </div>';
	       	        
	        
	        $listaColores = $this->getColoresGraficos($row->flg_amarillo, $row->flg_verde, $row->porcentaje);
	        array_push( $list, array("porcentaje" => $row->porcentaje,
	                                 "p_amarillo" => $row->flg_amarillo,
	                                 "p_verde"    => $row->flg_verde,
	                                 "color"      => $listaColores,
	                                 "dorado"     => $dorado));
	        $var++;
	    }
	     
	    $result['porcent_objetivos_num'] = json_encode($list);
	    $result['porcent_objetivos']     = $gaugeObjetivos;
	    
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function gotoCategorias(){
	    $idObjetivo    = _simpleDecryptInt(_post('id_objetivo'));
	    
	    $nombreObjetivo = _getDescReduce($this->m_utils->getById('bsc.objetivo','desc_objetivo','_id_objetivo',$idObjetivo), 25);
	    
	    $dataUser = array("nombre_objetivo" => $nombreObjetivo);
	    $this->session->set_userdata($dataUser);
	    
	    $url = '';
	    if($idObjetivo != null){
	        $dataUser = array("id_objetivo" => _post('id_objetivo'));
	        $this->session->set_userdata($dataUser);
	        $url = 'cf_indicador/c_categoria';
	    }
	     
	    echo $url;
	}
	
	function getColoresGraficos($amarillo, $verde, $porcentaje) {
	    $color = null;
	    if($porcentaje < $amarillo) {
	        $color = "mdl-state__red";
	    } else if($porcentaje < $verde && $porcentaje >= $amarillo) {
	        $color = "mdl-state__yellow";
	    } else if($porcentaje >= $verde && $porcentaje <= 100) {
	        $color = "mdl-state__cyan";
	    } else {
	        $color = "#E1A032";
	    }
	    return $color;
	}
	
	function getRolesByUsuario(){
	    $idPersona = _getSesion('id_persona');
	    $idRol     = _getSesion('id_rol');
	    
	    $roles  = $this->m_usuario->getRolesByUsuario($idPersona,$idRol);
	    
	    $return = null;
	    foreach ($roles as $var){
	        $check = null;
	        $class = null;
	        if($var->check == 1){
	           $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>'; 
	           $class = 'active rolActive';
	        }
	        $idRol = $this->lib_utils->simple_encrypt($var->nid_rol,CLAVE_ENCRYPT);
	        $return  .= "<li class='".$class."'>";
	        $return .= '<a href="javascript:void(0)" style="background-color:rgba(246, 245, 247, 0)" onclick="cambioRol(\''.$idRol.'\')" class="textC"><span>'.$var->desc_rol.$check.'</span></a>';
	        $return .= "</li>";
	    }
	    $dataUser = array("roles_menu" => $return);
	    $this->session->set_userdata($dataUser);
	}
	
	function cambioRol(){
	    $idRolEnc = _post('id_rol');
	    $idRol = _simpleDecryptInt($idRolEnc);
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
	    
	    $dataUser = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    
	    $idRol     = _getSesion('nombre_rol');
	    
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getValorAmarilloLinea() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idLineaCrypt = _post('idLinea');
	        $dataUser = array();
	        $idLinea  = _simpleDecryptInt($idLineaCrypt);
	        $this->session->set_userdata($dataUser);
	        $idCont = _post('idCont');
	        $pos    = _post('pos');
	        if($idLinea == null) {
	            throw new Exception(ANP);
	        }
	        $datosLE = $this->m_utils->getCamposById('bsc.linea_estrategica', array('flg_amarillo', 'flg_verde'), '_id_linea_estrategica', $idLinea);
	        $data['valorAmarillo'] = $datosLE['flg_amarillo'];
	        $data['meta_LE']       = $datosLE['flg_verde'];
	        $data['error']         = EXIT_SUCCESS;
	        $dataUser = array(
	            "idCont"    => $idCont,
	            "id_Linea"  => $idLineaCrypt,
	            "posicion"  => $pos);
	        $this->session->set_userdata($dataUser);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function editValorAmarilloLinea() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idLinea       = _simpleDecryptInt(_getSesion('id_Linea'));
	        $valorAmarillo = _post('valor');
	        $valorMeta     = _post('meta');
	        if(strlen($valorAmarillo) == 0 || strlen($valorMeta) == 0) {
	            throw new Exception('Ingrese los valores');
	        }
	        if (!is_float($valorAmarillo) && !is_numeric($valorAmarillo) ) {
	            throw new Exception('La zona de riesgo debe ser un n&uacute;mero');
	        }
	        if (!is_float($valorMeta) && !is_numeric($valorMeta) ) {
	            throw new Exception('La meta debe ser un n&uacute;mero');
	        }
	        if($valorAmarillo >= $valorMeta) {
	            throw new Exception('La zona de riesgo tiene que ser menor a la meta');
	        }
	        if($valorMeta > 100) {
	            throw new Exception('error', 'La meta no puede ser mayor a 100%');
	        }
	        $posicion = _getSesion('posicion');
	        $idCont   = _getSesion('idCont');
	        $update   = array('_id_linea_estrategica' => $idLinea,
                              'flg_amarillo'          => $valorAmarillo,
                              'flg_verde'             => $valorMeta);
	        $data = $this->m_lineaEstrat->updateFlgAmarillo($update,'linea_estrategica','_id_linea_estrategica');
	        $data = $this->buildGaugeLineaHTML(_getSesion('id_Linea'), $posicion, $data);
	        $data['idCont']    = $idCont;
	        $data['posicion']  = $posicion;
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildGaugeLineaHTML($idLineaCrypt,$var,$result){	  
	    $idLinea       = _simpleDecryptInt($idLineaCrypt);
	    $data = $this->m_lineaEstrat->getPorcentajeByLineaEstrat($idLinea);
	    $porcentaje = ($data['tod'] == 0 ) ? 0 : ($data['pas']/$data['tod'])*100;
	    $color = $this->getColoresGraficos($data['flg_amarillo'], $data['flg_verde'], $porcentaje);
	    $list =  array('porcentaje' => $porcentaje,
        	           'p_verde'    => $data['flg_verde'],
	                   'p_amarillo' => $data['flg_amarillo'],
        	           'color'      => $color);
	    $result['porcent_lineas_num'] = json_encode($list);
	    $result['cont'] = '<div class="cont_linea container-gauge linEst'.$var.'"  id="cont'.$var.'" attr-posicion ="'.$var.'" onclick="getObjetivosByLinea(\''.$idLineaCrypt.'\')"></div>';
	    
	    $gaugeGeneral = $this->m_lineaEstrat->getGaugesGenerales();
	     
	    $result['porcent_lineas_num'] = json_encode($list);
	    $dataGeneral = $this->m_lineaEstrat->getGaugesGenerales();
	    $result['porcent_general'] = $dataGeneral['porcentajegeneral'];
	    $result['porcent_general_amarillo'] = $dataGeneral['valor_amarillo'];
	    $result['porcent_general_verde'] = $dataGeneral['valor_meta'];
	     
	    if($dataGeneral['porcentajegeneral'] <= $dataGeneral['valor_amarillo']){
	        $result['backgroundAvantgard'] = "fondoRojo";
	    }else if($dataGeneral['porcentajegeneral'] <= $dataGeneral['valor_meta'] && $dataGeneral['porcentajegeneral'] > $dataGeneral['valor_amarillo']){
	        $result['backgroundAvantgard'] = "fondoAmarillo";
	    }else if($dataGeneral['porcentajegeneral'] > $dataGeneral['valor_meta']){
	        $result['backgroundAvantgard'] = "fondoVerde";
	    }else if($dataGeneral['porcentajegeneral'] == 100){
	        $result['backgroundAvantgard'] = "fondoDorado";
	    }
	    return $result;
	}
	
	function getValorAmarilloObjetivo() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idLineaCrypt = _post('idLinea');
	        $idObjetivoCrypt = _post('idObjetivo');
	        //$dataUser = array();
	        $idObjetivo  = _simpleDecryptInt($idObjetivoCrypt);
	        //$this->session->set_userdata($dataUser);
	        $idCont = _post('idCont');
	        $pos    = _post('pos');
	        $datosObj = $this->m_utils->getCamposById('bsc.objetivo', array('flg_amarillo', 'flg_verde'), '_id_objetivo', $idObjetivo);
            $data['valorAmarillo'] = $datosObj['flg_amarillo'];
            $data['valorVerde']    = $datosObj['flg_verde'];
            $data['error']         = EXIT_SUCCESS;
            $dataUser = array("idCont"      => $idCont,
                              "id_LineaO"   => $idLineaCrypt,  
            	              "id_Objetivo" => $idObjetivoCrypt,
            	              "posicion"    => $pos);
            $this->session->set_userdata($dataUser);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function editValorAmarilloObjetivo() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idLinea         = _simpleDecryptInt(_getSesion('id_LineaO'));
	        $idObjetivo      = _simpleDecryptInt(_getSesion('id_Objetivo'));
	        $valorAmarillo   = _post('valor');
	        $valorMeta       = _post('meta');
	        $posicion        = _getSesion('posicion');
	        $idCont          = _getSesion('idCont');
	        
	        if(strlen($valorAmarillo) == 0 || strlen($valorMeta) == 0) {
	            throw new Exception('Ingrese los valores');
	        }
	        if (!is_float($valorAmarillo) && !is_numeric($valorAmarillo) ) {
	            throw new Exception('La zona de riesgo debe ser un n&uacute;mero');
	        }
	        if (!is_float($valorMeta) && !is_numeric($valorMeta) ) {
	            throw new Exception('La meta debe ser un n&uacute;mero');
	        }
	        if($valorAmarillo >= $valorMeta) {
	            throw new Exception('La zona de riesgo tiene que ser menor a la meta');
	        }
	        if($valorMeta > 100) {
	            throw new Exception('error', 'La meta no puede ser mayor a 100%');
	        }
	        $update = array('_id_objetivo' => $idObjetivo,
	                        'flg_amarillo' => $valorAmarillo,
	                        'flg_verde'    => $valorMeta);
	        $data = $this->m_lineaEstrat->updateFlgAmarillo($update, 'objetivo', '_id_objetivo');
            $data = $this->buildGaugeObjetivoHTML($idLinea, _getSesion('id_Objetivo'), $posicion, $data);
            $data['idCont']    = $idCont;
            $data['posicion']  = $posicion;
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildGaugeObjetivoHTML($idLinea,$idObjetivoCrypt,$var,$result) {
	    $idObjetivo = _simpleDecryptInt($idObjetivoCrypt);
	    $data = $this->m_lineaEstrat->getPorcentajeByObjetivos($idLinea,$idObjetivo);
	    $color = $this->getColoresGraficos($data['flg_amarillo'], $data['flg_verde'], $data['porcentaje']);
	    $list =  array('porcentaje' => $data['porcentaje'],
            	       'p_verde'    => $data['flg_verde'],
            	       'p_amarillo' => $data['flg_amarillo'],
            	       'color'      => $color);
	    $result['porcent_objetivos_num'] = json_encode($list);
	    $result['cont'] = '<div class="cont_obj container-gauge linEst'.$var.'" id="cont'.$var.'" attr-posicion ="'.$var.'" onclick="goToCategorias(\''.$idObjetivoCrypt.'\')"></div';
	    return $result;
	}
	
	function getValorAmarilloGeneral(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $valorAmarillo = $this->m_utils->getById("bsc.config", "valor_numerico1", "id_config", CONFIG_GENERAL);
	        $valorVerde    = $this->m_utils->getById("bsc.config", "valor_numerico2", "id_config", CONFIG_GENERAL);
	        $data['valorAmarillo'] = $valorAmarillo;
	        $data['valorVerde']    = $valorVerde;
	        $data['error']         = EXIT_SUCCESS;
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function editValorAmarilloGeneral(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $valorAmarillo   = _post('valorAmarilloGeneral');
	        $update          = array("id_config"       => CONFIG_GENERAL,
	                                 "valor_numerico1" => $valorAmarillo);
	        $valorVerde = $this->m_utils->getById("bsc.config", "valor_numerico2", "id_config", CONFIG_GENERAL);
	        if($valorAmarillo >= $valorVerde){
	            $data['error'] = EXIT_ERROR;
	            $data['msj']   = "El valor zona de riesgo debe ser menor al meta";
	        } else{
	            $data = $this->m_lineaEstrat->updateFlgAmarillo($update,'config','id_config');
	        }
	        $data = $this->buildGaugeGeneralHTML($data);
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildGaugeGeneralHTML($result){
	    $dataGeneral = $this->m_lineaEstrat->getGaugesGenerales();
	    $result['porcent_general'] = $dataGeneral['porcentajegeneral'];
	    $result['porcent_general_amarillo'] = $dataGeneral['valor_amarillo'];
	    $result['porcent_general_verde'] = $dataGeneral['valor_meta'];
	    
	    if($dataGeneral['porcentajegeneral'] <= $dataGeneral['valor_amarillo']){
	        $result['backgroundAvantgard'] = "fondoRojo";
	    }else if($dataGeneral['porcentajegeneral'] <= $dataGeneral['valor_meta'] && $dataGeneral['porcentajegeneral'] > $dataGeneral['valor_amarillo']){
	        $result['backgroundAvantgard'] = "fondoAmarillo";
	    }else if($dataGeneral['porcentajegeneral'] > $dataGeneral['valor_meta']){
	        $result['backgroundAvantgard'] = "fondoVerde";
	    }else if($dataGeneral['porcentajegeneral'] == 100){
	        $result['backgroundAvantgard'] = "fondoDorado";
	    }
	    return $result;
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
    
    function goToAllIndis() {
        redirect('bsc/cf_indicador/c_indicador','refresh');
    }
}