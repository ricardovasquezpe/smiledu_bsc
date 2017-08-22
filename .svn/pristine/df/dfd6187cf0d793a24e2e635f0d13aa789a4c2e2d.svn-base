<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_indicador_categoria extends MX_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('mf_usuario/m_usuario');
        $this->load->model('mf_lineaEstrat/m_lineaEstrat');
        $this->load->model('mf_indicador/m_indicador');
        $this->load->library('table');
        
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(BSC_ROL_SESS);
    }
   
	public function index(){
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_BSC, BSC_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_BSC, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['main'] = true;
	    $data['ruta_logo']        = MENU_LOGO_BSC;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_BSC;
	    $data['nombre_logo']      = NAME_MODULO_BSC;
	    $data['titleHeader']      = 'Categor&iacute;as';
	    $data['rutaSalto']        = 'SI';
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $idObjetivoEnc = _getSesion('id_objetivo');
	    $idObjetivo    = _simpleDecryptInt($idObjetivoEnc);
	     
	    $indicadores = $this->m_indicador->getAllIndicadoresWithCategorias();
	    $data['tableIndicadorCategoria'] = $this->createTableIndicadorCategoria($indicadores);
	    $this->load->view('vf_indicador/v_indicador_categoria',$data);
	}
	
	public function createTableIndicadorCategoria($data){
	    $cantidad = count($data);
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" 
			                                   data-pagination="true" data-page-size="15" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50,100,'.$cantidad.']"
			                                   data-search="true" id="tb_indicador_categoria">',
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    
	    $head_0 = array('data' => '#'          , 'class' => 'text-left');
	    $head_1 = array('data' => 'Indicador'  , 'class' => 'text-left');
	    $head_2 = array('data' => 'Categorías' , 'class' => 'text-left');
	    $head_3 = array('data' => 'Acción'     , 'class' => 'text-center');
	    $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
	    
	    $val = 1;
	    foreach($data as $row){
	        $idCryptIndicador = _simple_encrypt($row->_id_indicador);
	        $row_col0         = array('data' => $val,                 'class' => 'text-left');
	        $row_col1         = array('data' => $row->desc_indicador, 'class' => 'text-left');
	        $row_col2         = array('data' => $row->categorias,     'class' => 'text-left');
	        $row_col3         = array('data' => '<button type="button" onclick="abrirModalCategorias(\''.$idCryptIndicador.'\');" class="mdl-button mdl-js-button mdl-button--icon" data-toggle="tooltip" data-placement="top" data-original-title="Delete row"><i class="mdi mdi-edit"></i></button>', 'class' => 'text-center');
	        $val++;
	        $this->table->add_row($row_col0, $row_col1, $row_col2, $row_col3);
	       
	    }
	    
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	public function createTableCategorias($data, $idIndicador){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-page-size="5"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_categorias">',
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	     
	    $head_0 = array('data' => '#'          , 'class' => 'text-left');
	    $head_1 = array('data' => 'Categoría'  , 'class' => 'text-left');
	    $head_2 = array('data' => 'Asignar'    , 'class' => 'text-center');
	    $this->table->set_heading($head_0, $head_1, $head_2);
	     
	    $val = 1;
	    foreach($data as $row){
	        $idCategoriaEnc = _simple_encrypt($row->id_categoria);
	        $idObjetivoEnc  = _simple_encrypt($row->__id_objetivo);
	        $checkCate  = ($row->check == '1') ? 'checked' : null;
	        $row_col0         = array('data' => $val, 'class' => 'text-left');
	        $row_col1         = array('data' => $row->desc_categoria, 'class' => 'text-left');
	        $check = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="categoria'.$val.'">
                          <input type="checkbox" id="categoria'.$val.'" attr-bd="'.$checkCate.'" attr-idindicador="'.$idIndicador.'" class="mdl-checkbox__input"
                                 attr-cambio="false" attr-idcategoria="'.$idCategoriaEnc.'" attr-idobjetivo="'.$idObjetivoEnc.'" onchange="cambioCheckCategoria(this);" '.$checkCate.'>
                          <span class="mdl-checkbox__label"></span>
                        </label>';
	        $row_col2         = array('data' => $check, 'class' => 'text-center');
	        $val++;
	        $this->table->add_row($row_col0, $row_col1, $row_col2);
	    }
	     
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	public function getCategoriasByIndicador(){
	    $idIndicador = _simpleDecryptInt(_post('idIndicador'));
	    $categorias = $this->m_indicador->getCategoriasByIndicador($idIndicador);
	    $tabla = $this->createTableCategorias($categorias, _post('idIndicador'));
	    
	    $data['tabla'] = $tabla; 
	    echo json_encode(array_map('utf8_encode',$data));
	}
	
	public function grabarCategoriaIndicador(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $myPostData = json_decode(_post('categorias'), TRUE);
	        $arrayGeneral = array();
	        foreach($myPostData['categoria'] as $key => $categoria){
	            $idCategoria = _simpleDecryptInt($categoria['idCategoria']);
	            $idIndicador = _simpleDecryptInt($categoria['idIndicador']);
	            $idObjetivo  = _simpleDecryptInt($categoria['idObjetivo']);
	            if($idCategoria == null) {
	                throw new Exception(ANP);
	            }
	            
	            $condicion = $this->m_indicador->evaluaInsertUpdateCatInd($idCategoria, $idIndicador);
	            $arrayDatos = array();
	            $arrayDatos = array("__id_categoria" => $idCategoria,
                	                "__id_indicador" => $idIndicador,
                	                "__id_objetivo"  => $idObjetivo,
                	                "condicion" => $condicion
	            );
	            
	            array_push($arrayGeneral, $arrayDatos);
	        }
	        
	        $data = $this->m_indicador->updateInsertCategoriaIndicador($arrayGeneral);
	        if($data['error'] == EXIT_SUCCESS){
	            $indicadores = $this->m_indicador->getAllIndicadoresWithCategorias();
	            $data['tableCategoriaIndicador'] =  $this->createTableIndicadorCategoria($indicadores);
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode',$data));
	}
	
	function enviarFeedBack(){
	    $nombre = $this->session->userdata('nombre_usuario');
	    $mensaje = $this->input->post('feedbackMsj');
	    $url = $this->input->post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
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
	    $idRol = _simpleDecryptInt($idRolEnc);
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
	
	    $dataUser = array("id_rol"     => $idRol,
	        "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	
	    $idRol     = $this->session->userdata('nombre_rol');
	
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
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