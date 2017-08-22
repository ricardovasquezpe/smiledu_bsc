<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_config_ptje extends MX_Controller {
    
    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('m_utils');
        $this->load->model('mf_mantenimiento/m_config_ptje');
        $this->load->library('table');
        $this->load->library('lib_utils');
        $this->load->helper('html');
        if(!isset($_COOKIE['smiledu'])){
            $this->session->sess_destroy();
            redirect('/c_login','refresh');
        }
    }

	public function index() {
	    $logedUser = $this->session->userdata('usuario');
	    if($logedUser != null) {	        
	        $data['tablaConfigPtje'] = $this->buildTablaConfigPtjeHTML();
	        $data['optUniv'] = __buildComboUniversidades();
	        $roles = $this->session->userdata('roles');
	    	if($roles != null) {
	    	    $final 	= array();
	    	    foreach($roles as $rol){
	    	        array_push($final, $rol->nid_rol);
	    	    }
	    	    $data['arbolPermisosMantenimiento'] = $this->lib_utils->buildArbolPermisos($final);
	    	}
	    	
	    	$idRol     = _getSesion('id_rol');
	    	$rolSistemas   = $this->m_utils->getSistemasByRol($idRol);
    	   $data['apps']  = $this->lib_utils->modalCreateSistemasByrol($rolSistemas);
	    	
	    	//MENU Y CABECERA
	    	$menu     = $this->load->view('v_menu_v2', $data, true);
	    	$cabecera = $this->load->view('v_cabecera', '', true);
	    	$data['cabecera'] = $cabecera;
	    	$data['menu']     = $menu;
	    	$data['font_size'] = $this->session->userdata('font_size');
	    	
	        $this->load->view('vf_mantenimiento/v_config_ptje', $data);
	    }else{
	        $this->session->sess_destroy();
	        redirect('','refresh');
	    }
	}
		
	function buildTablaConfigPtjeHTML(){	  
	    $listaConfigPtje = $this->m_config_ptje->getConfigPtje();
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_configPtje">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_1 = array('data' => '#');
	    $head_2 = array('data' => 'Universidad');
        $head_3 = array('data' => 'Puntaje', 'class' => 'text-right');
	    
	    $val = 0;
	    $this->table->set_heading($head_1, $head_2, $head_3);
	    foreach($listaConfigPtje as $row){;	    
	        $val++;
	        $puntaje     = $row->valor_numerico;
	        $idConfig    = $this->encrypt->encode($row->id_config);
	        $row_cell_1  = array('data' => $val);
	        $row_cell_2  = array('data' => $row->desc_config);
	        $row_cell_3  = array('data' => $this->getSpan("classPtje",$idConfig).intval($puntaje).'</span>');
	        $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3);
	    }
	    $tabla = $this->table->generate();
	    $tabla .= '<div id="custom-toolbar">
                    <p style="font-size:22px">Configuraci&oacute;n de Puntaje</p>
                </div>';
	    return $tabla;	   
	}
	
	function getSpan($clase, $id){
	    return '<span class="'.$clase.' editable editable-click" data-pk="'.$id.'">';
	}
	
	//MODAL POPUP INICIO
	function grabarUnivPuntajesPopup(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idUniv  = $this->encrypt->decode($this->input->post('idUniv'));
	        $puntaje = $this->input->post('puntaje');
	        if($idUniv == null || $puntaje == null) {
	            throw new Exception(ANP);
	        }
            $siglaUniv = $this->m_utils->getById('universidad','siglas', 'id_universidad',$idUniv);       
            /* Busca si existe el tipo de examen, universidad y a�o ingresado
               si lo encuentra Trae 1 sino 0*/
            $existConfig = $this->m_config_ptje->getCountUnivPtjeConfig($idUniv, date("Y"));
            if($existConfig > 0) {
                throw new Exception('�sta universidad ya se encuentra configurada');
            }	            
            $arrayDatos = array("id_univ"        => $idUniv,
                                "tipo_examen"    => SIMULACRO,
        	                    "year_config"    => date("Y"),
        	                    "valor_numerico" => $puntaje,
                                "desc_config"    => 'Simulacro puntaje '.$siglaUniv);
	        $data = $this->m_config_ptje->insertaPuntajeConfigPtjePopup($arrayDatos);
	        if($data['error'] == EXIT_SUCCESS) {
	            $data['table'] = $this->buildTablaConfigPtjeHTML();
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode',$data));
	}
	
	//FIN DEL MODAL POPUP
	
	function editarPuntaje() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $pk = $this->encrypt->decode($this->input->post('pk'));
	        if($pk == null){
	            throw new Exception(ANP);
	        }
	        $pkEncry     = $this->input->post('pk');
	        $valor       = trim(utf8_decode($this->input->post('value')) );
	        $columna     = $this->input->post('name');//columna
	        if($columna != 'valor_numerico') {
	            throw new Exception(ANP);
	        }
	        if($valor == null) {
	            throw new Exception("Ingrese un puntaje");
	        }
	        if(strlen($valor) > 6) {
	            throw new Exception('El puntaje no debe exceder 6 caracteres');
	        }
	        if(!ctype_digit((string) $valor)) {
	            throw new Exception("El puntaje debe tener n�meros enteros");
	        }
	        if($valor <= 0) {
	            throw new Exception("El puntaje debe ser mayor que cero");
	        }
	        $data = $this->m_config_ptje->editPuntajeConfig($pk, $columna, $valor);
	        if($data['error'] == EXIT_SUCCESS){
	            $data['pk']  = $pkEncry;
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	        header("HTTP/1.0 666 ".$data['msj'], TRUE, NULL);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function logOut(){
	    $logedUser = $this->session->userdata('usuario');
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}
	
    function enviarFeedBack(){
        $nombre = $this->session->userdata('nombre_completo');
        $mensaje = $this->input->post('feedbackMsj');
        $url = $this->input->post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}