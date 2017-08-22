<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_alumno_eai extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('mf_mantenimiento/m_alumno_eai');
        $this->load->model('mf_mantenimiento/m_config_medida_nota');
        $this->load->library('table');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_ALUMNO_EAI);
        $this->_idUserSess = _getSesion('nid_persona');
    }
    
	public function index(){
  	    $data['titleHeader']      = 'Alumno EAI';
  	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
  	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
  	    $data['rutaSalto']        = 'SI';
        $data['optSede'] = __buildComboSedes();
        $data['tablaAlumnosEai'] = $this->buildTablaAlumnoEaiHTML(null);
    	$data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);
    	
    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
    	
    	//MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
    	
        $this->load->view('vf_mantenimiento/v_alumno_eai', $data);
	}
		
	function buildTablaAlumnoEaiHTML($idAula){
	    if( $idAula == 0) {
	        $listaAlumnosEai = array();
	    } else {
	         $listaAlumnosEai = $this->m_alumno_eai->getAllAlumnosEai($idAula);
	    }	    
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_alumno_eai">',
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_1 = array('data' => '#');
	    $head_2 = array('data' => 'Alumno');
	    $head_3 = array('data' => 'Matem&aacute;tica', 'class' => 'text-center');
	    $head_4 = array('data' => 'Comunicaci&oacute;n', 'class' => 'text-center');
	    $head_5 = array('data' => 'Ciencia', 'class' => 'text-center');
	    $head_6 = array('data' => 'Inform&aacute;tica', 'class' => 'text-center');
	    $val = 0;
	    $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
	    foreach($listaAlumnosEai as $row){
	        $idAlumnoCrypt = _encodeCI($row->__id_persona);
	        $val++;
	        $matematica   = $row->medida_rash_eai_mate;
	        $comunicacion = $row->medida_rash_eai_comu;
	        $ciencia      = $row->medida_rash_eai_ciencia;
	        $informatica  = $row->medida_rash_eai_infor;
	        $row_cell_1 = array('data' => $val);
	        $row_cell_2 = array('data' => $row->nombrecompleto);
	        $row_cell_3 = array('data' => $this->getSpan("classMatematica",$idAlumnoCrypt).intval($matematica).'</span>', 'class' => 'text-center');
	        $row_cell_4 = array('data' => $this->getSpan("classComunicacion",$idAlumnoCrypt).intval($comunicacion).'</span>', 'class' => 'text-center');
	        $row_cell_5 = array('data' => $this->getSpan("classCiencia",$idAlumnoCrypt).intval($ciencia).'</span>', 'class' => 'text-center');
	        $row_cell_6 = array('data' => $this->getSpan("classInformatica",$idAlumnoCrypt).intval($informatica).'</span>', 'class' => 'text-center');
	    
	        $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4 , $row_cell_5, $row_cell_6);
	    }
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	function comboSedesNivel() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede = _decodeCI(_post('idSede'));
	        if($idSede == null) {
	           $data['error']    = EXIT_WARM;
	           $data['optNivel'] = null;
	           $data['tablaAlumnosEai'] = $this->buildTablaAlumnoEaiHTML(0);
	           throw new Exception(null);
	        }
	        $data['optNivel'] = __buildComboNivelesBySede($idSede);
	        $data['tablaAlumnosEai'] = $this->buildTablaAlumnoEaiHTML(0);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getComboGradoByNivel_Ctrl() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede  = _decodeCI(_post('idSede'));
	        $idNivel = _decodeCI(_post('idNivel'));
	        if($idNivel == null || $idSede == null) {
	           $data['error']    = EXIT_WARM;
	           $data['optGrado'] = null;
	           $data['tablaAlumnosEai'] = $this->buildTablaAlumnoEaiHTML(0);
	           throw new Exception(null);
	        }
        $data['optGrado'] = __buildComboGradosByNivel($idNivel, $idSede);
        $data['tablaAlumnosEai'] = $this->buildTablaAlumnoEaiHTML(0);
        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboAulasByGradoUtils() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idGrado = _decodeCI(_post('idGrado'));
	        $idSede  = _decodeCI(_post('idSede'));
	        if($idGrado == null || $idSede == null) {
	           $data['error']    = EXIT_WARM;
	           $data['optAula']  = null;
	           $data['tablaAlumnosEai'] = $this->buildTablaAlumnoEaiHTML(0);
	           throw new Exception(null);
	        }
        $data['optAula'] = __buildComboAulas($idGrado, $idSede);
        $data['tablaAlumnosEai'] = $this->buildTablaAlumnoEaiHTML(0);
        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAlumnosFromAula() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idAula   = _decodeCI(_post('idAula'));
	        $dataAula = array('idAula' => $idAula);
	        $this->session->set_userdata($dataAula);
	        if($idAula == null) {
               $data['error']    = EXIT_WARM;
               $data['optAula']  = null;
               $data['tablaAlumnosEai'] = $this->buildTablaAlumnoEaiHTML(0);
               throw new Exception(null);
	        }
        $data['tablaAlumnosEai'] = $this->buildTablaAlumnoEaiHTML($idAula);
        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function editarPuntajeAlumnosEai() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $nuevoValor    = 0;
	    $columnaRash   = "";
	    $idRash        = 0;
	    try{
	        $pk = _decodeCI(_post('pk'));
	        if($pk == null){
	            throw new Exception(ANP);
	        }
	        $pkEncry   = _post('pk');
	        $valor     = trim(utf8_decode(_post('value')));
	        $columna   = _post('name');
	        $idAula    = _getSesion('idAula');
      
	        if($columna != 'medida_rash_eai_mate' && $columna != 'medida_rash_eai_comu' 
	           && $columna != 'medida_rash_eai_ciencia' && $columna != 'medida_rash_eai_infor'){
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
	        $valorInicio  = $this->m_config_medida_nota->getExisteMedidaRash(EAI_EVALUACION, EAI_INICIO);
	        $valorproceso = $this->m_config_medida_nota->getExisteMedidaRash(EAI_EVALUACION, EAI_PROCESO);
	        
	        if($valorInicio == null || $valorproceso == null) {
	            throw new Exception('No se han configurado los valores. Comun�quese con la persona a cargo.');
	        }
	        
	        if($valor <= $valorInicio){
                $nuevoValor = $valor;
                $idRash = EAI_INICIO;
	        } else if ($valor > $valorInicio && $valor <= $valorproceso) {
                $nuevoValor = $valor;
                $idRash = EAI_PROCESO;
	        } else if ($valor > $valorproceso) {
                $nuevoValor = $valor;
                $idRash = EAI_SATISF;
	        }
	        $nombreColumnaEAI = null;
	        if($columna == 'medida_rash_eai_mate') {
	            $nombreColumnaEAI = "ind_logro_eai_mate";
	        } else if ($columna == 'medida_rash_eai_comu') {
	            $nombreColumnaEAI = "ind_logro_eai_comu";
	        } else if ($columna == 'medida_rash_eai_ciencia') {
	            $nombreColumnaEAI = "ind_logro_eai_ciencia";
	        } else {
	            $nombreColumnaEAI = "ind_logro_eai_infor";
	        }
	        $arrayDatos = array("__id_persona"    => $pk,
                	            "__id_aula"       => $idAula,
                	            $columna          => $nuevoValor,
                	            $nombreColumnaEAI => $idRash,
                	            "year_academico"  => _getYear());
	        $data = $this->m_alumno_eai->editCamposEai($arrayDatos);
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
	    $logedUser = _getSesion('usuario');
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}
	
	function getSpan($clase, $id){
	    return '<span class="'.$clase.' editable editable-click" data-pk="'.$id.'">';
	}
	
    function enviarFeedBack(){
        $nombre = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}