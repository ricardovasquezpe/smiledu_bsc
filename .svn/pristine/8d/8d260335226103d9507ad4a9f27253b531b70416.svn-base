<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_capacitacion extends MX_Controller {
    
    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->library('lib_utils');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('mf_rh/m_capacitacion');
        $this->load->model('mf_rh/m_incidencia');
        $this->load->model('m_utils');
        _validate_usuario_controlador(ID_PERMISO_CAPACITACION);
    }

	public function index(){    
  	    $data['titleHeader'] = 'Capacitaciones';  
	    $data['arbolPermisosMantenimiento'] = $this->lib_utils->buildArbolPermisos();
    	$data['calendarData']  = $this->m_capacitacion->getCapacitaciones();
    	
    	$data['sedes'] = $this->comboSedes();
    	
    	$areas = $this->m_incidencia->getAllAreasEmpresa();
    	$data['areas'] = $this->comboAreaEmpresa($areas);
    	
    	$idRol     = _getSesion('id_rol');
    	$rolSistemas   = $this->m_utils->getSistemasByRol($idRol);
    	$data['apps']  = $this->lib_utils->modalCreateSistemasByrol($rolSistemas);
    	
    	//MENU
    	$menu = $this->load->view('v_menu_v2', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
        $this->load->view('vf_rh/v_capacitacion', $data);
	}
	
	function agregarCapacitacion() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $actualYear    = date('Y');
	    $variable = true;
	    try {
	        $fecha    = _post('fecha');
	        $yearCapacitacion = date('Y',strtotime($fecha));
	        if($actualYear == $yearCapacitacion){
    	        $descr    = utf8_decode(_post('descripcion'));
                $detal    = utf8_decode(_post('detalle'));
    	        
    	        $sede     = _simple_decrypt(_post('selectSede'));
    	        $area     =	_simple_decrypt(_post('selectArea'));
    	        $areaEsp  =	_simple_decrypt(_post('selectAreaEsp'));
    	        
    	        $descSede      = $this->m_utils->getById("sede", "desc_sede", "nid_sede", $sede);
    	        $descArea      = $this->m_utils->getById("area", "desc_area", "id_area", $area);
    	        $descAreaEsp   = $this->m_utils->getById("area", "desc_area", "id_area", $areaEsp);
    	        $personaSist   = _getSesion('nombre_completo');
    	        $idPersonaSist = _getSesion('nid_persona');
    	        
    	        $arrayInsert = array("desc_capacitacion"    => $descr,
                                        "fec_programada"       => $fecha,
                                        "observaciones"        => $detal,
                                        "estado"               => CAPACITACION_PROGRAMADA,
                                        "audi_usua_regi"       => $idPersonaSist,
                                        "audi_pers_regi"       => $personaSist,
                                        "id_area"              => $area,
                                        "desc_area"            => $descArea,
                                        "id_sede"              => $sede,
                                        "desc_sede"            => $descSede,
                                        "id_area_especifica"   => $areaEsp,
                                        "desc_area_especifica" => $descAreaEsp);
    	        
    	        $data = $this->m_capacitacion->crearCapacitacion($arrayInsert);
	       }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboAreasEspecificas(){
	    $idArea = _simple_decrypt(_post('idArea'));
	    $areasEsp = $this->m_incidencia->getAllAreasEspecificasEmpresa($idArea);
	     
	    $res = $this->comboAreaEmpresa($areasEsp);
	    echo $res;
	}
	
	function comboSedes(){
	    $sedes = $this->m_utils->getSedes();
	    $opcion = '';
	    foreach ($sedes as $sed){
	        $idSede = _simple_encrypt($sed->nid_sede);
	        $opcion .= '<option value="'.$idSede.'">'.strtoupper($sed->desc_sede).'</option>';
	    }
	    return $opcion;
	}
	
	function editarCapacitacion() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $eventId     = _post('id');
	        $descripcion = utf8_decode(_post('descripcionEdit'));
	        $detalle     = utf8_decode(_post('detalleEdit'));
	        $fecha       = _post('fechaEdit');
	        $sede     = _simple_decrypt(_post('selectSedeEdit'));
	        $area     =	_simple_decrypt(_post('selectAreaEdit'));
	        $areaEsp  =	_simple_decrypt(_post('selectAreaEspEdit'));
	         
	        $descSede      = $this->m_utils->getById("sede", "desc_sede", "nid_sede", $sede);
	        $descArea      = $this->m_utils->getById("area", "desc_area", "id_area", $area);
	        $descAreaEsp   = $this->m_utils->getById("area", "desc_area", "id_area", $areaEsp);
	        $personaSist   = _getSesion('nombre_completo');
	        $idPersonaSist = _getSesion('nid_persona');
	        
	        $arrayUpdate = array("desc_capacitacion"   => $descripcion,
                	            "fec_realizada"        => $fecha,
                	            "observaciones"        => $detalle,
                	            "estado"               => CAPACITACION_REALIZADA,
                	            "audi_usua_regi"       => $idPersonaSist,
                	            "audi_pers_regi"       => $personaSist,
                	            "id_area"              => $area,
                	            "desc_area"            => $descArea,
                	            "id_sede"              => $sede,
                	            "desc_sede"            => $descSede,
                	            "id_area_especifica"   => $areaEsp,
                	            "desc_area_especifica" => $descAreaEsp);
	        
	        $data = $this->m_capacitacion->editarCapacitacion($arrayUpdate, $eventId);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboAreaEmpresa($data){
	    $opcion = '';
	    foreach ($data as $row){
	        $idArea = _simple_encrypt($row->id_area);
	        $opcion .= '<option value="'.$idArea.'">'.$row->desc_area.'</option>';
	    }
	    return $opcion;
	}
	
	function getComboCapEdit(){
	    $idCapacitacion = _post('idCap');
	    $combos         = $this->m_capacitacion->getCombosByCapacitacion($idCapacitacion);
	    
	    $areasEsp = $this->m_incidencia->getAllAreasEspecificasEmpresa($combos['id_area']);
	    $res = $this->comboAreaEmpresa($areasEsp);

	    $data['area']    = _simple_encrypt($combos['id_area']);
	    $data['sede']    = _simple_encrypt($combos['id_sede']);
	    $data['areaEsp'] = _simple_encrypt($combos['id_area_especifica']);
	    $data['areasEsps'] = $res;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function logOut(){
	    $logedUser = _getSesion('usuario');
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}
	
	function enviarFeedBack(){
	    $nombre = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}
}