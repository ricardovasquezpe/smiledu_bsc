<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_incidencia extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('mf_rh/m_incidencia');
        $this->load->model('m_utils');
        $this->load->model('mf_usuario/m_usuario');
        _validate_usuario_controlador(ID_PERMISO_INCIDENCIA);
        $this->_idUserSess = _getSesion('nid_persona');
    }

	public function index() {      
	    $data['titleHeader']      = 'Incidencias';
	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
	    $data['rutaSalto']        = 'SI';
	    
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);
	    
    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    
	    //MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
	    $data['font_size'] = _getSesion('font_size');
	    
	    $incidencias = $this->m_incidencia->getAllIncidencias();
	    $data['tb_incidencias'] = $this->createTableIncidencia($incidencias);
	     
	    $data['sedes'] = __buildComboSedes();
	     
	    $areas = $this->m_incidencia->getAllAreasEmpresa();
	    $data['areas'] = $this->comboAreaEmpresa($areas);
	     
	    $tipIncidecias = $this->m_incidencia->getAllTipoIncidencias();
	    $data['tIncidencias'] = $this->comboIncidencias($tipIncidecias);
	    
	    $this->load->view('vf_rh/v_incidencia', $data);
    	
	}
	
	public function createTableIncidencia($data){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_incidencias">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_8 = array('data' => '#'                                                  , 'class' => 'text-left');
	    $head_0 = array('data' => 'Fecha'                                              , 'class' => 'text-center');
	    $head_1 = array('data' => 'Tipo'                                               , 'class' => 'text-left');
	    $head_2 = array('data' => 'Personal'                                           , 'class' => 'text-left');
	    $head_3 = array('data' => 'Sede - &Aacute;rea - &Aacute;rea Espec&iacute;fica' , 'class' => 'text-left');
	    $head_4 = array('data' => 'Persona Reg.'                                       , 'class' => 'text-left');
	    $head_5 = array('data' => 'Fecha Reg.'                                         , 'class' => 'text-center');
	    $head_6 = array('data' => 'Observaci&oacute;n'                                 , 'class' => 'text-left');
	    $head_7 = array('data' => 'Acci&oacute;n'                                      , 'class' => 'text-center');
	    $val = 0;
	    $this->table->set_heading($head_8, $head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
	    foreach($data as $row){
	        $val++;
	        $idIncidencia = _simple_encrypt($row->id_incidencia);
	        $fInci = ($row->fecha_incidencia == null) ? null : date('d/m/Y',strtotime($row->fecha_incidencia));
	        $fReg  = ($row->audi_fec_regi == null) ? null : date('d/m/Y',strtotime($row->audi_fec_regi));
	        
	        $row_col8  = array('data' => $val                      , 'class' => 'text-left '.$row->color);
	        $row_col0  = array('data' => $fInci                    , 'class' => 'text-center '.$row->color);
	        $row_col1  = array('data' => $row->desc_combo          , 'class' => 'text-left '.$row->color);
	        $row_col2  = array('data' => $row->nombres_personal    , 'class' => 'text-left '.$row->color);
	        $row_col3  = array('data' => $row->lugar               , 'class' => 'text-left '.$row->color);
	        $row_col4  = array('data' => $row->audi_pers_regi      , 'class' => 'text-left '.$row->color);
	        $row_col5  = array('data' => $fReg                     , 'class' => 'text-center '.$row->color);
	        
	        $desc6 = "";
	        if($row->valor == INC_CLIMA_LABORAL){
	            $desc6 = "<button class='mdl-button mdl-js-button mdl-button--icon' onclick='abrirModalCambiarEstado(0, \"".$idIncidencia."\"  )'><i class='mdi mdi-edit'></i></button>";
	            if($row->flg_checkbox == '1'){
	                $desc6 = "<button class='mdl-button mdl-js-button mdl-button--icon' onclick='abrirModalCambiarEstado(1, \"".$idIncidencia."\"  )'><i class='mdi mdi-edit'></i></button>";
	            }
	        }else if($row->valor == INC_DESCANSO_MEDICO){
	            $desc6 = "<button class='mdl-button mdl-js-button mdl-button--icon' onclick='abrirModalCambiarEstado(2, \"".$idIncidencia."\"  )'><i class='mdi mdi-edit'></i></button>";
	            if($row->flg_checkbox == '1'){
	                $desc6 = "<button class='mdl-button mdl-js-button mdl-button--icon' onclick='abrirModalCambiarEstado(3, \"".$idIncidencia."\"  )'><i class='mdi mdi-edit'></i></button>";
	            }
	        }
	        
	        $row_col6  = array('data' => "<button class='mdl-button mdl-js-button mdl-button--icon' onclick='verObservacion(\"".$row->desc_incidencia."\"  )'><i class='mdi mdi-remove_red_eye'></i></button>" , 'class' => $row->color.' text-center');
	        $row_col7  = array('data' => $desc6 , 'class' => $row->color.' text-center');
	        
	        $this->table->add_row($row_col8, $row_col0, $row_col1, $row_col2, $row_col3, $row_col4, $row_col5, $row_col6, $row_col7);
	   }
	   
	   $tabla = $this->table->generate();
	    
	   return $tabla;
	}
	
	function comboAreasEspecificas(){
	    $idArea = _simple_decrypt(_post('idArea'));
	    $areasEsp = $this->m_incidencia->getAllAreasEspecificasEmpresa($idArea);
	    
	    $res = $this->comboAreaEmpresa($areasEsp);
	    echo $res;
	}
	
	public function getPersonasByNombre() {
	    $data['tabla'] = $this->tablaPersonas($this->m_usuario->getAllPersonasByNombre(_post('nombre')));
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function tablaPersonas($data) {
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-page-size="5"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_personal">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => 'Nombres'        , 'class' => 'text-left');
	    $head_1 = array('data' => 'Nro. Doc.'      , 'class' => 'text-right');
	    $head_2 = array('data' => 'Acci&oacute;n'  , 'class' => 'text-center');
	    
	    $this->table->set_heading($head_0, $head_1, $head_2);
	    foreach($data as $row) {
	        $idPersona = _simple_encrypt($row->nid_persona);
	        $row_col0  = array('data' => $row->apellidos.', '.$row->nom_persona, 'class' => 'text-left');
	        $row_col1  = array('data' => $row->nro_documento, 'class' => 'text-right');
	        $row_col2  = array('data' => '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="elegirPersonal(\''.$idPersona.'\',\''.$row->nombrecompleto.'\')"><i class="mdi mdi-check"></i></button>', 'class' => 'text-center');
	         
	        $this->table->add_row($row_col0, $row_col1, $row_col2);
	    }
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	function comboAreaEmpresa($data){
	    $opcion = '';
	    foreach ($data as $row){
	        $idArea  = _simple_encrypt($row->id_area);
	        $opcion .= '<option value="'.$idArea.'">'.$row->desc_area.'</option>';
	    }
	    return $opcion;
	}
	
	function comboIncidencias($data){
	    $opcion = '';
	    foreach ($data as $row){
	        $idIncidencia = _simple_encrypt($row->valor);
	        $opcion .= '<option value="'.$idIncidencia.'">'.$row->desc_combo.'</option>';
	    }
	    return $opcion;
	}
	
	function evalComboTipo(){
	    $idIncidencia = _simple_decrypt(_post('idTipoIncidencia'));
	    
	    $opc = "0";
	    $text = "";
	    if($idIncidencia == INC_CLIMA_LABORAL){
	        $opc = "1";
	        $text = "�Resuelto?";
	    }else if($idIncidencia == INC_DESCANSO_M�DICO){
	        $opc = "1";
	        $text = "Recuperaci�n oportuna de pago";
	    }
	    
	    $data['opc'] = $opc;
	    $data['text'] = $text;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function insertIncidencia(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
	    try{
	        $personal =	_simple_decrypt(_post('selectPersonal'));
	        $fecha    =	_post('fecha');
	        $sede     = _decodeCI(_post('selectSede'));
	        $area     =	_simple_decrypt(_post('selectArea'));
	        $areaEsp  =	_simple_decrypt(_post('selectAreaEsp'));
	        $tIncidencia = _simple_decrypt(_post('selectTincidencia'));
	        $descripcion = utf8_decode(_post('descripcion'));
	        $check    = _post('checkBox');
	        $year       = date('Y',strtotime($fecha));
	        if($year < _getYear()){
	            throw new Exception('El a�o debe ser el actual');
	        }
	        $descSede = $this->m_utils->getById("sede", "desc_sede", "nid_sede", $sede);
	        $fechaActual = date('Y-m-d H:i:s');
	        $personaSist = _getSesion('nombre_completo');
	        $idPersonaSist = _getSesion('nid_persona');
	        $nombrePersonaInc = $this->m_usuario->getNombreCompletoPersona($personal);
	        $descArea = $this->m_utils->getById("area", "desc_area", "id_area", $area);
	        $descAreaEsp  = $this->m_utils->getById("area", "desc_area", "id_area", $areaEsp);
	        
	        $arrayInsert = array('tipo_incidencia'  => $tIncidencia,
                	             'desc_incidencia'  => utf8_encode($descripcion),
                	             'fecha_incidencia' => $fecha,
                	             '__id_sede' 		=> $sede,
                	             'desc_sede'        => $descSede,
                	             '__id_area'        => $area,
	                             'desc_area'        => $descArea,
	                             '__id_area_especifica' => $areaEsp,
	                             'desc_area_especifica' => $descAreaEsp,
                	             '__id_personal'    => $personal,
                	             'audi_fec_regi'    => $fechaActual,
                	             'audi_pers_regi'   => $personaSist,
                	             'audi_usua_regi'   => $idPersonaSist,
                	             'nombres_personal' => $nombrePersonaInc,
                	             'flg_checkbox'     => $check
	        );
	         
	        $data = $this->m_incidencia->insertIncidencia($arrayInsert);
	        if($data['error'] == EXIT_SUCCESS){
	            $incidencias = $this->m_incidencia->getAllIncidencias();
	    	    $data['tb_incidencias'] = $this->createTableIncidencia($incidencias);
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function cambiarEstado(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
	    try{
	        $incidencia =	_simple_decrypt(_post('idTipoIncidencia'));
	        $val        =	_post('valEstado');
	        $fecha      =	(_post('fecha') != '') ? _post('fecha') : null;
	         
	        $arrayUpdate = array('flg_checkbox'   => $val,
	                             'fecha_resuelto' => $fecha
	        );
	         
	        $data = $this->m_incidencia->cambiarEstado($arrayUpdate, $incidencia);
	        if($data['error'] == EXIT_SUCCESS){
	            $incidencias = $this->m_incidencia->getAllIncidencias();
	            $data['tb_incidencias'] = $this->createTableIncidencia($incidencias);
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	
	function logOut(){
	    $logedUser = _getSesion('usuario');
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}
	
	function enviarFeedBack(){
	    $nombre  = _getSesion('nombre_completo');
	    $mensaje = utf8_decode(_post('feedbackMsj'));
	    $url     = _post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}
	
}