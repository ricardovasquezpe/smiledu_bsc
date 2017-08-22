<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_pregunta extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('mf_categoria/m_categoria');
        $this->load->model('mf_pregunta/m_pregunta');
        $this->load->model('mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, ID_PERMISO_PREGUNTAS, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
    }
    
    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SENC, SENC_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader']      = 'Preguntas';
        $data['ruta_logo']        = MENU_LOGO_SENC;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SENC;
        $data['nombre_logo']      = NAME_MODULO_SENC;
        //MENU
        $rolSistemas         = $this->m_utils->getSistemasByRol(ID_SISTEMA_SENC, $this->_idUserSess);
        $data['barraSec']    = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                  <a href="#encuestas" class="mdl-layout__tab is-active">Preguntas</a>
                                </div>';
        $data['apps']        = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']        = $this->load->view('v_menu', $data, true);
        //NECESARIO
        $encuestas           = null;
        $preguntas           = $this->m_pregunta->getAllPreguntasServicios();
	    $data['tbPreguntas'] = $this->buildTablePreguntas($preguntas);
        ///////////
	    $this->load->view('v_pregunta',$data);
	}
	
	function buildTablePreguntas($data){
	    $servicios = $this->m_pregunta->getAllServicios();
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-custom-search="$.noop"
			                                   data-pagination="true"
			                                   data-show-columns="true" data-search="true" id="tb_preguntas">',
	        'heading_row_start'     => '<tr class="filters">',
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => '#'          , 'class' => 'text-left');
	    $head_1 = array('data' => 'Pregunta'   , 'class' => 'text-left');
	    $head_2 = array('data' => ''           , 'class' => 'text-right');
	    $head_3 = array('data' => 'Servicios'  , 'class' => 'text-left'      , 'data-searchable' => 'false');
	    $head_4 = array('data' => 'Indicadores', 'class' => 'text-center'    , 'data-searchable' => 'false');
	    $cont = 0;
	    $this->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4);
	    foreach($data as $row){
	        $cont++;
	        $idPregEnc = _simple_encrypt($row->id_pregunta);
	        $btnEditDesc = '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_1" style="opacity:0.5">
                                <i class="mdi mdi-lock"></i>
	                        </a>';
	        if($row->count == 0){
	            $btnEditDesc = '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_1" onclick="abrirModalEditDescPregunta(\''.$idPregEnc.'\', this)">
                                    <i class="mdi mdi-edit"></i>
                                </a>';
	        }
	        
	        $row_0   = array('data' => $cont, 'class' => 'text-left');
	        $row_1   = array('data' => $row->desc_pregunta, 'class' => 'text-left');
	        $row_2   = array('data' => $btnEditDesc, 'class' => 'text-right');
	        $options = $this->buildOptionsAllServiciosByPregunta($row->_id_servicio, $servicios);
	        $row_3   = array('data' => '<select onchange="selectServicio(\''.$idPregEnc.'\', '.$cont.')" data-live-search="true" data-container="body" id="selectServicio'.$cont.'" name="selectServicio'.$cont.'" class="form-control pickerButn s-overflow">'.$options.'</select>', 'class' => 'text-left');
	        $row_4   = array('data' => '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalIndicadores(\''.$idPregEnc.'\')"><i class="mdi mdi-view_headline"></i></button>', 'class' => 'text-center');
	        $this->table->add_row($row_0,$row_1,$row_2,$row_3,$row_4);
	    }
	    return $this->table->generate();
	}
	
	function buildOptionsAllServiciosByPregunta($idServicio, $servicios){
	    $opt = '<option value="0">Seleccione un Servicio</option>';
	    foreach($servicios as $row){
	        $selected = null;
	        if($idServicio == $row->id_servicio){
	            $selected = 'selected';
	        }
	        $idServicioCrypt = _simple_encrypt($row->id_servicio);
	        $opt .= '<option value="'.$idServicioCrypt.'" '.$selected.'>'.$row->desc_servicio.'</option>';
	    }
	    return $opt;
	}
	
	function getIndicadoresPregunta(){
	    $idPregunta = _simpleDecryptInt(_post("pregunta"));
	    $indicadoresPregunta = $this->m_pregunta->getArrayJsonIndicadoByPregunta($idPregunta);
	    $indicadoresPregunta = ($indicadoresPregunta != null) ? json_decode($indicadoresPregunta) : array();
	    $indicadores = $this->m_pregunta->getAllIndicadores();
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-custom-search="$.noop"
			                                   data-pagination="true" id="tb_indicadores">',
	        'heading_row_start'     => '<tr class="filters">',
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => '#'          , 'class' => 'text-left');
	    $head_1 = array('data' => 'Indicador'  , 'class' => 'text-left');
	    $head_2 = array('data' => 'Elegir'     , 'class' => 'text-center');
	    $cont = 0;
	    $this->table->set_heading($head_0,$head_1,$head_2);
	    foreach($indicadores as $row){
	        $cont++;
	        $idIndicador = _simple_encrypt($row->_id_indicador);
	        $row_0   = array('data' => $cont               , 'class' => 'text-left');
	        $row_1   = array('data' => $row->desc_indicador, 'class' => 'text-left');
	        $check = null;
	        if(in_array($row->_id_indicador, $indicadoresPregunta)){
	            $check = "checked";
	        }
	        $row_2 = array('data' => '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="indicador_'.$cont.'">
                                          <input type="checkbox" id="indicador_'.$cont.'" onchange="elegirIndicador(\''._post("pregunta").'\', \''.$idIndicador.'\', this)" class="mdl-checkbox__input" '.$check.'>
                                          <span class="mdl-checkbox__label"></span>
                                      </label>', 'class' => 'text-center');
	        $this->table->add_row($row_0,$row_1,$row_2);
	    }
	    $data['tabla'] = $this->table->generate();
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function saveServicioPregunta(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $pregunta = _simpleDecryptInt($this->input->post("pregunta"));
	        $servicio = _simpleDecryptInt($this->input->post("servicio"));
	        if($pregunta == null){
	            throw new Exception(ANP);
	        }
	        
	        $data = $this->m_pregunta->editServicioPregunta($pregunta, $servicio);
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function saveIndicadoresPregunta(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $pregunta  = _simpleDecryptInt($this->input->post("pregunta"));
	        $indicador = _simpleDecryptInt($this->input->post("indicador"));
	        if($pregunta == null || $indicador == null){
	            throw new Exception(ANP);
	        }
	        $indicadoresPregunta = $this->m_pregunta->getArrayJsonIndicadoByPregunta($pregunta);
	        $indicadoresPregunta = ($indicadoresPregunta != null) ? json_decode($indicadoresPregunta) : array();
	        $arrayInd = array();
	        foreach($indicadoresPregunta as $ind){
	            if(_post("check") == 0 && $ind == $indicador){
	                
	            }else{
	                array_push($arrayInd, $ind);
	            }
	        }
	        if(_post("check") == 1){
	                array_push($arrayInd, $indicador);
	        }
	        $data = $this->m_pregunta->editIndicadorPregunta($pregunta, str_replace(']', '}', str_replace('[',  '{',json_encode($arrayInd))));
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getDetallePregunta(){
	    $pregunta = _simpleDecryptInt($this->input->post("pregunta"));
	    $data['descPregunta'] = $this->m_utils->getById("senc.preguntas", "desc_pregunta", "id_pregunta", $pregunta, "senc");
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function editPregunta(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $pregunta = _simpleDecryptInt($this->input->post("pregunta"));
	        $descripcion = $this->input->post("descripcion");
	        if($pregunta == null || $descripcion == null || (strlen(trim($descripcion)) == 0 && strlen(trim($descripcion)) > 200)){
	            throw new Exception(ANP);
	        }
	        $descripcion = _ucfirst(str_replace("\"", "'", utf8_decode($descripcion) ));
	        $data = $this->m_pregunta->editPregunta($pregunta, $descripcion);
	        $data['newDesc'] = $descripcion;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function mostrarRolesSistema(){
	    $idSistema = _decodeCI($this->input->post('sistema'));
	    $roles = $this->m_usuario->getRolesOnlySistem($this->session->userdata('id_persona'),$idSistema);
	    $result = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''.$this->input->post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;
	     
	    echo json_encode(array_map('utf8_encode', $data));
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
}