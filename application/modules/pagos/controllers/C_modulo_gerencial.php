<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_modulo_gerencial extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $GLOBALS['er'] = 'error';
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->model('m_reportes');
        $this->load->library('table');
        $this->load->helper('download');
        $this->load->helper('file');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_CUADRO_MANDO, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
    
    public function index() {
        
        $tabSess   = _getSesion('tab_active_config');
        $tabSess   = (($tabSess == null && $this->_idRol == ID_ROL_RESP_COBRANZAS) || $tabSess == 'tab-1' && $this->_idRol == ID_ROL_RESP_COBRANZAS) ? 'tab-1' : ((($tabSess == null && $this->_idRol == ID_ROL_CONTABILIDAD) ? 'tab-2' : $tabSess));
        $data['tabActivo']                  = $tabSess;
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader']      = 'Cuadro de Mando';
        $data['ruta_logo']        = MENU_LOGO_PAGOS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
        $data['nombre_logo']      = NAME_MODULO_PAGOS;
        //MENU
        $rolSistemas         = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
        $data['apps']          = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']        = $this->load->view('v_menu', $data, true);
        //NECESARIO
        $data['optSede']  = __buildComboSedes();
        $data['optYears'] = __buildComboYearsAcademicos();
        $data['dataG1']   = $this->buildSeriesGraficoBarraPie($this->m_reportes->getDatosGraficoMontoMoraTotal1(null,null));
        $data['dataG2']   = $this->buildSeriesLineaGrafico2($this->m_reportes->getDatosGraficoMesesMontos2(null,null));
        $data['dataG3']   = $this->buildSeriesComparacion3($this->m_reportes->getDatosGraficoComparacion3(null,null));
        $data['dataG4']   = $this->buildSeriesPolarChart4($this->m_reportes->getDatosPolarGrafico(null,null));
        $data['dataG5']   = $this->buildSeriesChart5($this->m_reportes->getDataConceptosGrafico());
        ///////////
        $this->session->set_userdata(array('tab_active_movi' => null));
        $this->session->set_userdata(array('tab_active_config' => null));
        $this->load->view('v_modulo_gerencial', $data);
    }
    
    function logout() {
       $this->session->set_userdata(array("logout" => true));
       unset($_COOKIE[__getCookieName()]);
       $cookie_name2 = __getCookieName();
       $cookie_value2 = "";
       setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
       Redirect(RUTA_SMILEDU, true);
    }
	
	function cambioRol() {
	    $idRol = _simple_decrypt(_post('id_rol'));
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
	    $dataUser = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = _getSesion('id_persona');
	    $idRol     = _getSesion('id_rol');
	    $roles  = $this->m_usuario->getRolesByUsuario($idPersona,$idRol);
	    $return = null;
	    foreach ($roles as $var){
	        $check = null;
	        $class = null;
	        if($var->check == 1){
	            $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
	            $class = 'active';
	        }
	        $idRol = _simple_encrypt($var->nid_rol);
	        $return  .= "<li class='".$class."'>";
	        $return .= '<a href="javascript:void(0)" onclick="cambioRol(\''.$idRol.'\')"><span class="title">'.$var->desc_rol.$check.'</span></a>';
	        $return .= "</li>";
	    }
	    $dataUser = array("roles_menu" => $return);
	    $this->session->set_userdata($dataUser);
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
	    $nombre = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    __enviarFeedBack($mensaje,$url,$nombre);
	}
	
	function mostrarRolesSistema(){
	    $idSistema = _decodeCI(_post('sistema'));
	    $roles = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'),$idSistema);
	    $result = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildSeriesGraficoBarraPie($result){
	    $data = null;
	    $arrayCategorias = array();
	    $arrayMora       = array();
	    $arrayTotal      = array();
	    $arrayCobrado    = array();
	    $arrayRestante   = array();
	    $sumaMora        = null;
	    $sumaRestante    = null;
	    $sumaCobrado     = null;
	    $flg_entra       = 0;
	    $arrayPie        = array();
	    foreach($result as $row){
	        array_push($arrayMora       , floatval($row->mora));
	        array_push($arrayTotal      , floatval($row->monto_total));
	        array_push($arrayCobrado    , floatval($row->monto_pagado));
	        array_push($arrayCategorias , utf8_encode($row->desc_sede));
	        array_push($arrayRestante   , floatval($row->monto_restante));
	        $sumaCobrado  += $row->monto_pagado;
	        $sumaRestante += $row->monto_restante;
	        $sumaMora     += $row->mora;
	        if($row->monto_total != 0 || $row->mora != 0 || $row->monto_pagado != 0){
	            $flg_entra       = 1;
	        }
	    }
	    if(count($result) > 1){
	        $arrayPie = array(
	            array('name'  => 'Mora',
	                'y'     => floatval($sumaMora),
	                'color' => 'red'
	            ) ,
	            array('name'  => 'Restante',
	                'y'     => floatval($sumaRestante),
	                'color' => 'orange'
	            ) ,
	            array('name'  => 'Cobrado',
	                'y'     => floatval($sumaCobrado),
	                'color' => 'green'
	            )
	        );
	    }
	    $data['arrayCategoriasG1'] = json_encode($arrayCategorias);
	    $data['arrayMoraG1']       = json_encode($arrayMora);
	    $data['arrayTotalG1']      = json_encode($arrayTotal);
	    $data['arrayCobradoG1']    = json_encode($arrayCobrado);
	    $data['arrayRestanteG1']   = json_encode($arrayRestante);
	    $data['arrayPieG1']        = json_encode($arrayPie);
	    $data['flg_entra']         = $flg_entra;
	    return json_encode($data);
	}
	
	function buildSeriesLineaGrafico2($result){
	    $data       = null;
	    $limite     = count($result)/2;
	    $contador   = 1;
	    $flg_push   = 0;
	    $arrayNames = array();
	    $arrayMonto = array();
	    $arrayCate  = array();
	    $arrayGen   = array(array(),array());
	    $flg_entra       = 0;
	    foreach($result as $row){
	        if($flg_push == 0){
	            $desc    = explode('-', $row->detalle);
	            $desc[0] = utf8_encode(__mesesTexto($desc[0]));
	            array_push($arrayCate, implode('/', $desc));
	        }
	        array_push($arrayGen[0], floatval($row->monto_pendiente));
	        array_push($arrayGen[1], floatval($row->monto_pagado));
	        $contador++;
	        $flg_entra       = 1;
	    }
	    $arrayNames[0] = 'Pendiente';
	    $arrayNames[1] = 'Pagado';
	    $data['arrayCateG2']   = json_encode($arrayCate);
	    $data['arraySeriesG2'] = json_encode($arrayGen);
	    $data['arrayNamesG2']  = json_encode($arrayNames);
	    $data['arrayColorG2']  = json_encode(array('orange','green'));
	    $data['flg_entra']     = $flg_entra;
	    return json_encode($data);
	}
	
	function buildSeriesComparacion3($result){
	    $arrayCate    = array();
	    $arrayPagados = array();
	    $arrayPendi   = array();
	    $data         = null;
	    $flg_entra       = 0;
	    foreach($result as $row){
	        array_push($arrayPagados , floatval($row->pagado));
	        array_push($arrayPendi   , floatval($row->pendiente));
	        array_push($arrayCate    , utf8_encode($row->desc_sede));
	        if($row->pagado != 0 || $row->pendiente != 0){
	            $flg_entra       = 1;
	        }
	    }
	    $data['arrayCateG3']      = json_encode($arrayCate);
	    $data['arrayPagadosG3']   = json_encode($arrayPagados);
	    $data['arrayPedientesG3'] = json_encode($arrayPendi);
	    $data['flg_entra']        = $flg_entra;
	    return json_encode($data);
	}
	
	function buildSeriesPolarChart4($result){
	    $arrayCate      = array();
	    $arrayPuntuales = array();
	    $arrayNormal    = array();
	    $arrayVencidos  = array();
	    $flg_entra       = 0;
	    foreach($result as $row){
	        array_push($arrayCate      , utf8_encode($row->desc_sede));
	        array_push($arrayPuntuales , floatval($row->puntual));
	        array_push($arrayNormal    , floatval($row->normal));
	        array_push($arrayVencidos  , floatval($row->vencido));
	        if($row->puntual != 0 || $row->normal != 0 || $row->vencido != 0){
	            $flg_entra       = 1;
	        }
	    }
	    $data['arrayCateG4']     = json_encode($arrayCate);
	    $data['arrayVencidosG4'] = json_encode($arrayVencidos);
	    $data['arrayPuntualG4']  = json_encode($arrayPuntuales);
	    $data['arrayNormalG4']   = json_encode($arrayNormal);
	    $data['flg_entra']       = $flg_entra;
	    return json_encode($data);
	}
	
	function getGraficosByFiltroFechas(){
	    $data['error'] = EXIT_ERROR;
	    try{
	        $fecInicio      = implode("-", array_reverse(explode("/", _post('fecInicio'))));
	        $fecFin         = implode("-", array_reverse(explode("/", _post('fecFin'))));
	        if($fecInicio > $fecFin){ 
	            throw new Exception('La fecha inicio debe ser menor a la fecha fin');
	        }
	        if($fecFin > date('Y-m-d') || $fecInicio > date('Y-m-d')){
	            throw new Exception('Las fechas no pueden ser mayores a la actual');
	        }
	        $data           = null;
	        $data['dataG1'] = $this->buildSeriesGraficoBarraPie($this->m_reportes->getDatosGraficoMontoMoraTotal1($fecInicio,$fecFin));
	        $data['dataG2'] = $this->buildSeriesLineaGrafico2($this->m_reportes->getDatosGraficoMesesMontos2($fecInicio,$fecFin));
	        $data['dataG3'] = $this->buildSeriesComparacion3($this->m_reportes->getDatosGraficoComparacion3($fecInicio,$fecFin));
	        $data['dataG4'] = $this->buildSeriesPolarChart4($this->m_reportes->getDatosPolarGrafico($fecInicio,$fecFin));
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildSeriesChart5($result){
	    $arrayCate   = array();
	    $arrayMontos = array();
	    foreach($result as $row){
	        array_push($arrayCate   , utf8_encode($row->concepto));
	        array_push($arrayMontos , floatval($row->monto));
	    }
	    $data['arrayCateG5']  = json_encode($arrayCate);
	    $data['arrayMontoG5'] = json_encode($arrayMontos);
	    return json_encode($data);
	}
}