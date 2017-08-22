<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_horario extends MX_Controller {
    
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
        $this->load->model('mf_mantenimiento/m_horario');
        _validate_usuario_controlador(ID_PERMISO_HORARIO);
    }

	public function index(){   
	    $data['titleHeader'] = 'Horario Docente';     
    	$data['tbHorario']  = $this->buildTablaHorario_HTML(null, null);
    	$data['comboProfe'] = $this->lib_utils->buildComboDocentes();
    	$data['comboCursos'] = __buildComboCursos();
    	$data['comboAulas'] = __buildComboAllAulas();
    	
    	$data['arbolPermisosMantenimiento'] = $this->lib_utils->buildArbolPermisos();
    	
    	$idRol     = _getSesion('id_rol');
    	$rolSistemas   = $this->m_utils->getSistemasByRol($idRol);
    	$data['apps']  = $this->lib_utils->modalCreateSistemasByrol($rolSistemas);
    	
    	//MENU
    	$menu = $this->load->view('v_menu_v2', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
    	
        $this->load->view('vf_mantenimiento/v_horario', $data);
	}
	
	function buildTablaHorario_HTML($flgIdHorario, $idHorario) {
	    $listaHorario = ($flgIdHorario == null) ? array() : $this->m_horario->getHorarios($flgIdHorario, $idHorario);
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-show-columns="true" data-search="true" data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_horario">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_1 = array('data' => '#');
	    //$head_2 = array('data' => 'Id Horario', 'style' => 'text-align:left', 'data-visible' => 'false');
	    $head_3 = array('data' => 'Docente');
	    $head_4 = array('data' => 'Aula');
	    $head_5 = array('data' => 'Curso');
	    $head_6 = array('data' => 'Acci&oacute;n');
	    $val = 0;
	    $this->table->set_heading($head_1/*, $head_2*/, $head_3, $head_4, $head_5, $head_6);
	    foreach($listaHorario as $row) {
	        $idHorario = _encodeCI($row->id_horario);
	        $val++;
	        $row_cell_1  = array('data' => $val);
	        //$row_cell_2  = array('data' => $row->id_horario);
	        $row_cell_3  = array('data' => ucwords(strtolower($row->docente)));
	        $row_cell_4  = array('data' => $row->aula);
	        $row_cell_5  = array('data' => $row->curso);
	        $btnBorrar = null;
	        $countEva = $this->m_horario->getCountHorarioInEvaluacion($row->id_horario);
	        if($countEva == 0) {
	            $btnBorrar = '<button type="button" onclick="deleteHorarioConfirma(\''.$idHorario.'\');" 
	                                  class="btn ink-reaction btn-icon-toggle" data-toggle="tooltip" data-placement="top" 
	                                  data-original-title="Delete row"><i class="mdi mdi-delete"></i></button>';;
	        }
	        $row_cell_6  = array('data' => $btnBorrar);
	        $this->table->add_row($row_cell_1/*, $row_cell_2*/, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6);
	    }
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	function cargarHorarios() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $data['tbHorario'] = $this->buildTablaHorario_HTML('TODO', null);
	        $data['error'] = EXIT_SUCCESS;
	        $data['msj']   = 'Se cargaron los datos';
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function checkIfHorarioExiste() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idProfe = _decodeCI(_post('idProfe'));
	        $idAula  = _decodeCI(_post('idAula'));
	        $idCurso = _decodeCI(_post('idCurso'));
	        if($idProfe == null || $idAula == null || $idCurso == null) {
	            $data['error'] = EXIT_WARM;
	            $data['cantHorario'] = null;
	            throw new Exception(null);
	        }
	        $data['cantHorario'] = $this->m_horario->getCountHorario($idProfe, $idCurso, $idAula);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function insertarHorario() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idProfe = _decodeCI(_post('idProfe'));
	        $idAula  = _decodeCI(_post('idAula'));
	        $idCurso = _decodeCI(_post('idCurso'));
	        if($idProfe == null || $idAula == null || $idCurso == null) {
	            throw new Exception('Seleccione el docente, aula y curso');
	        }
	        $existeHorario = $this->m_horario->getCountHorario($idProfe, $idCurso, $idAula);
	        if($existeHorario != 0) {
	            throw new Exception('El horario ya existe');
	        }
	        $data = $this->m_horario->registrarHorario($idProfe, $idAula, $idCurso);
	        if($data['error'] == EXIT_SUCCESS) {
	            $data['tbHorario']  = $this->buildTablaHorario_HTML('BY_ID', $data['inserted_id']);
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function borrarHorario() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idHorario = _decodeCI(_post('idHorario'));
	        if($idHorario == null) {
	            throw new Exception('Seleccione el horario');
	        }
	        $existeHorarioInEva = $this->m_horario->getCountHorarioInEvaluacion($idHorario);
	        if($existeHorarioInEva != 0) {
	            throw new Exception('El horario no puede ser eliminado');
	        }
	        $data = $this->m_horario->borrarHorario($idHorario);
	        if($data['error'] == EXIT_SUCCESS) {
	            $data['tbHorario']  = $this->buildTablaHorario_HTML('TODO', null);
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
    
    function getTAbleHorarios(){
        $data['tbHorario']  = $this->buildTablaHorario_HTML('TODO', null);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function logOut(){
        $logedUser = _getSesion('usuario');
        $this->session->sess_destroy();
        redirect('','refresh');
    }
}