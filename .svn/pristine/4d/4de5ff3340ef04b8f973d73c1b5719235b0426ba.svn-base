<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_excel_correos extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('m_main');
        $this->load->model('mf_mantenimiento/m_admision');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->library('Classes/PHPExcel.php');
        _validate_usuario_controlador(ID_PERMISO_EXCEL_CORREOS);
        $this->_idUserSess = _getSesion('nid_persona');
    }

	public function index() {
	    $data['titleHeader']      = 'Correos';
	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
	    $data['rutaSalto']        = 'SI';
	    $data['optSede'] = __buildComboSedes();
    	$data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);

    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
    	
    	//MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
        $this->load->view('vf_mantenimiento/v_excel_correos', $data);
	}
	
	function getComboGradosNivelBySede() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede = _decodeCI($this->input->post('idSede'));
	        if($idSede == null) {
	            $data['error'] = EXIT_WARM;
	            throw new Exception(null);
	        }
	        $data = $this->getTabla($idSede, null, null, null);
	        $data['optGradoNivel'] = __buildComboGradoNivelBySede($idSede);
	        
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getComboAulasByGradoNivel() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede = _decodeCI($this->input->post('idSede'));
	        $idgradoNivel = _decodeCI($this->input->post('idgradoNivel'));
	        if($idSede == null) {
	            $data['error'] = EXIT_WARM;
	            $data['optAulas'] = null;
	            throw new Exception(null);
	        }
	        if($idgradoNivel == null) {
	            $data['error'] = EXIT_WARM;
	            $data['optAulas'] = null;
	            throw new Exception(null);
	        }
	        $gradoNivel = explode('_', $idgradoNivel);
	        $data = $this->getTabla($idSede, $gradoNivel[1], $gradoNivel[0], null);
	        //Opcional se puede validar si el grado y nivel existen antes de hacer un query
	        $data['optAulas'] = __buildComboAulas($gradoNivel[0], $idSede);
	        //$this->lib_utils->buildComboAulas2($idSede, $gradoNivel[1], $gradoNivel[0]);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getCorreosBySede() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idAula = _decodeCI($this->input->post('idAula'));
	        $idSede = _decodeCI($this->input->post('idSede'));
	        $idgradoNivel = _decodeCI($this->input->post('idgradoNivel'));
	        ///////
	        if($idSede == null) {
	            $data['error'] = EXIT_WARM;
	            $data['optAulas'] = null;
	            throw new Exception(null);
	        }
	        if($idgradoNivel == null) {
	            $data['error'] = EXIT_WARM;
	            $data['optAulas'] = null;
	            throw new Exception(null);
	        }
	        if($idAula == null) {
	            $data['error'] = EXIT_WARM;
	            throw new Exception(null);
	        }
	        $gradoNivel = explode('_', $idgradoNivel);
	        $data = $this->getTabla($idSede, $gradoNivel[1], $gradoNivel[0], $idAula);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getTabla($idSede, $idNivel, $idGrado, $idAula) {
	    $correos = $this->m_utils->getCorreosPadresByAula($idSede, $idNivel, $idGrado, $idAula);
	    $correosArray = array();
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_correos">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_1 = array('data' => '#', 'class' => 'text-center');
	    $head_2 = array('data' => 'Correo', 'style' => 'text-align:left', 'data-sortable' => 'true');
	    $val = 0;
	    $this->table->set_heading($head_1, $head_2);
	    foreach($correos as $row) {
	        $val++;
	        $row_cell_1  = array('data' => '<label for="ingre_'.$val.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
								 			    <input type="checkbox" class="mdl-checkbox__input" id="ingre_'.$val.'" checked onclick="quitarPonerCorreos($(this));" data-correo="'.$row->email.'">
								 			    <span class="mdl-checkbox__label"></span>
								 	        </label>',
	                             'class' => 'text-center');
	        $row_cell_2  = array('data' => $row->email);
	        array_push($correosArray, array("correo" => $row->email));
	    
	        $this->table->add_row($row_cell_1, $row_cell_2);
	    }
	    $data['tablaCorreos'] = $this->table->generate();
	    $data['correosArray'] = json_encode($correosArray);
	    return $data;
	}
	
	function generarExcel() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $data['objPHPExcel'] = new PHPExcel();
    	    $jason  = _post('json');
    	    $jason = json_decode($jason);
    	    //$idAula = _decodeCI(_post('idAula'));
    	    $correosArray = $jason->param;
    	    /*if($idAula == null) {
    	        throw new Exception(ANP);
    	    }*/
    	    if(!isset($correosArray)) {
    	        throw new Exception(ANP);
    	    }
    	    if(!is_array($correosArray)) {
    	        throw new Exception(ANP);
    	    }
    	    if(count($correosArray) <= 0) {
    	        throw new Exception(ANP);
    	    }
    	    $data['correos'] = $correosArray;
    	    $data['nombreArchivo'] = 'correos_padres';
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	        $data['nombreArchivo'] = 'Archivo_con_error';
	    }
	    $this->load->view('v_download_excel_correos', $data);
	}
}